<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Helper\AppHelper;
use AppBundle\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var TaskRepository $taskRepo */
        $taskRepo = $em->getRepository('AppBundle:Task');
        /** @var Task $tasks */
        $tasks = $taskRepo->getTasks();

        return $this->render('home/index.html.twig', ['tasks' => $tasks]);
    }
}