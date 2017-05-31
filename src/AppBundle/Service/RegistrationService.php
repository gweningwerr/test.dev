<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Helper\AppHelper;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Swift_Message;
use Swift_Mime_Message;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class RegistrationService
{
    private $container;

    private $registrationForm;

    private $user;

    private $logger;

    private $em;

    public function __construct(logger $logger, Container $container, EntityManager $em)
    {
        $this->logger = $logger;
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * Создает нового пользователя, возвращает true если создался и false в противном случае
     *
     * @param Request $request
     * @return bool
     */
    public function register(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');


        /** @var User $user */
        $user = $userManager->createUser();
        $user->setEnabled(true);

        $this->registrationForm = $formFactory->createForm();
        $this->registrationForm->setData($user);
        $this->registrationForm->handleRequest($request);

        if ($this->registrationForm->isValid()) {

            $data = $this->registrationForm->getData();
            if ($user->getUsername() == null) {
                $username = str_replace('@', '_', $data->getEmail());
                $user->setUsername($username);
            }

            // Создание пользователя
            $userManager->updateUser($user);
            $this->user = $user;

            $emailFrom = $this->container->getParameter('mailer.from');
            $title = 'Регистрация пользователя на TEST.DEV';

            $mailerClient = $this->container->get('mailer');
            /** @var Swift_Mime_Message $messageClient */
            $messageClient = Swift_Message::newInstance()
                ->setSubject($title)
                ->setFrom($emailFrom)
                ->setTo($data->getEmail())
                ->setBody(
                    $this->container->get('templating')->render(
                        'user/email.html.twig',
                        ['pass' => $data->getPlainPassword()]
                    ),
                    'text/html'
                );
            $mailerClient->send($messageClient);

            return $this->user;
        } else {
            return null;
        }
    }

    /**
     * Обновляет данные пользователя
     *
     * @param Request $request
     * @return bool
     */
    public function update(Request $request)
    {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.profile.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');


        /** @var User $user */
        $user = $userManager->createUser();
        $user->setEnabled(true);

        $this->registrationForm = $formFactory->createForm();
        $this->registrationForm->setData($user);
        $this->registrationForm->handleRequest($request);

        if ($this->registrationForm->isValid()) {
            $userManager->updateUser($user);
            $this->user = $user;
            return $this->user;
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Возвращает форму для регистрации
     *
     * @return mixed
     */
    public function getRegistrationForm()
    {
        return $this->registrationForm;
    }
}