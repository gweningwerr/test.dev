<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\TaskType;
use AppBundle\Helper\AppHelper;
use AppBundle\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class TaskController extends Controller
{
    /**
     * Список просьб пользователя
     *
     * @Route("/tasks", name="tasks")
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        /** @var User $user */
        $user = $this->getUser();

        /** @var TaskRepository $taskRepo */
        $taskRepo = $em->getRepository('AppBundle:Task');

        $tasksToMe = $taskRepo->getTasks([
            'performer' => $user->getId()
        ]);

        $tasksMy = $taskRepo->getTasks([
            'author' => $user->getId()
        ]);

        return $this->render('task/index.html.twig', [
            'tasksToMe' => $tasksToMe,
            'tasksMy' => $tasksMy
        ]);
    }

    /**
     * Подробная информация по задаче
     *
     * @Route("/task/show/{id}", name="task")
     * @param $id
     * @return Response
     */
    function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        /** @var TaskRepository $taskRepo */
        $taskRepo = $em->getRepository('AppBundle:Task');
        /** @var Task $task */
        $task = $taskRepo->getTask($id);

        $response = $this->render(
            'task/show.html.twig',
            [
                'task' => $task,
                'statusRow' => $this->rowStatusTask($task)
            ]
        );

        return $response;
    }

    /**
     * Создание / редактирование просьбы
     *
     * @Route("/task/edit/{id}", defaults={"id" = null}, name="task_edit")
     *
     * @param Request $request
     * @param int|null $id код задачи
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction (Request $request, $id = null) {

        $em = $this->getDoctrine()->getManager();
        $taskForm = $this->createForm(TaskType::class);

        if ($id) {
            /** @var TaskRepository $recipeRepo */
            $taskRepo = $em->getRepository('AppBundle:Task');
            $data = $taskRepo->findOneBy(['id' => $id]);

            if ($data) {
                $taskForm->setData($data);
            }
        }

        $taskForm->handleRequest($request);

        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $data = $taskForm->getData();

            $data->setDateCreate();
            $data->setAuthor(AppHelper::em()->getReference('AppBundle\Entity\User',$this->getUser()->getId()));
            $data->setStatus(AppHelper::em()->getReference('AppBundle\Entity\ListStatus',1));

            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('task/edit.html.twig', [
            'taskForm' =>  $taskForm->createView()
        ]);
    }

    /**
     * Смена статуса просьбы
     *
     * @Route("/status/{id}/{status}", name="status_change")
     *
     * @param $id
     * @param $status
     * @return JsonResponse
     */
    public function changeStatusAction ($id, $status) {

        $em = $this->getDoctrine()->getManager();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        /** @var TaskRepository $taskRepo */
        $taskRepo = $em->getRepository('AppBundle:Task');

        /** @var Task $task */
        $task = $taskRepo->getTask($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача не найдена');
        }

        if (!in_array($this->getUser()->getId(), [$task->getAuthor()->getId(), $task->getPerformer()->getId()])) {
            throw $this->createAccessDeniedException();
        }

        $statusId = $task->getStatus()->getId();

        if ($status == $statusId) {
            return new JsonResponse([
                'res' => 0,
                'msg' => 'Просьба уже имеет необходимый статус'
            ]);
        }

        if ($status == 4 && $this->getUser()->getId() != $task->getAuthor()->getId()) {
            return new JsonResponse([
                'res' => 0,
                'msg' => 'Просьбу может закрыть только её автор'
            ]);
        } elseif ($status == 3 && $this->getUser()->getId() != $task->getPerformer()->getId()) {
            return new JsonResponse([
                'res' => 0,
                'msg' => 'Просьбу может уведомить только исполнитель'
            ]);
        }

        $task->setStatus(AppHelper::em()->getReference('AppBundle\Entity\ListStatus',$status));
        $em->persist($task);
        $em->flush();

        return new JsonResponse([
            'res' => 1,
            'html' => $this->rowStatusTask($task)
        ]);
    }

    /**
     * Генерирует страку управления задачей
     *
     * @param Task $task
     * @return string
     */
    public function rowStatusTask($task) {

        if ($this->getUser()->getId() != $task->getAuthor()->getId()) {
            $author = false;
        } else {
            $author = true;
        }

        if ($this->getUser()->getId() != $task->getPerformer()->getId()) {
            $performer = false;
        } else {
            $performer = true;
        }

        return $this->render('task/status-row.html.twig', [
            'task' => $task,
            'author' => $author,
            'performer' => $performer
        ])->getContent();
    }
}
