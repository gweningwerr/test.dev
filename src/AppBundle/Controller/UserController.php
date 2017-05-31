<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Helper\AppHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


/**
 * @property Container container
 */
class UserController extends Controller
{

    /**
     * Авторизация
     *
     * @Route("/login", name="login")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();


        return $this->render(
            'user/login.html.twig',
            [
                'csrf_token' => $csrfToken,
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
    }

    /**
     * Регистрация
     *
     * @Route("/registration", name="registration")
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    function registrationAction(Request $request)
    {
        $registrationService = $this->container->get('app.registration');
        /** @var User $user */
        $user = $registrationService->register($request);

        $error_message = null;
        // Если получилось зарегистрировать пользователя
        if ($user) {
            // То авторизуем его
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main',serialize($token));

            // И редиректим на домашнюю страницу
            $url = $this->generateUrl('home');
            $response = new RedirectResponse($url);
            return $response;
        }

        $response = $this->render(
            'user/registration.html.twig',
            [
                'form' => $registrationService->getRegistrationForm()->createView()
            ]
        );

        return $response;
    }

    /**
     * Отображение данных
     *
     * @Route("/profile/", name="profile")
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    function profileAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $response = $this->render(
            'user/show.html.twig',
            [
                'user' => $this->getUser()
            ]
        );

        return $response;
    }

    /**
     * Редактирование данных
     *
     * @Route("/profile/edit", name="profile_edit")
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    function profileEditAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.profile.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        $form = $formFactory->createForm();
        $form->setData($this->getUser());
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $user->setChangeInfo();
            $userManager->updateUser($user);
            return $this->redirectToRoute('profile');
        }

        $response = $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $this->getUser()
            ]
        );

        return $response;
    }
}
