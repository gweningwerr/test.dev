<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Route;

class Mailer {
    /** @var \Swift_Mailer $mailer */
    private $mailer;

    /** @var Route $router */
    private $router;
    
    /** @var Container $container */
    private $container;


    /**
     * Mailer constructor.
     * @param Mailer $mailer
     * @param Container $container
     */
    public function __construct(Mailer $mailer, Container $container)
    {
        $this->mailer = $mailer;
        $this->router = $container->get('router');
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate(
            'user_resetting_reset',
            ['token' => $user->getConfirmationToken()],
            true
        );

        $rendered = $this->container->get('templating')->renderResponse('user/email.txt.twig', [
            'user' => $user,
            'confirmationUrl' => $url
        ]);

        $this->sendEmailMessage($rendered, $user->getEmail());
    }

    /**
     * @param string $renderedTemplate
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $toEmail)
    {
        $fromEmail = $this->container->getParameter('treto_mailer.emailFrom');

        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);

        $this->mailer->send($message);
    }
}