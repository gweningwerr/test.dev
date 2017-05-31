<?php
namespace AppBundle\Handler;

use Monolog\Logger;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthenticationHandler implements LogoutSuccessHandlerInterface
{
    protected $logger = null;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $referer = $request->headers->get('referer');

        if (preg_match('#\?#', $referer)) {
            $referer .= '&';
        } else {
            $referer .= '?';
        }
        $referer .= 'v=' . substr(md5(date('H:m:i')), 0, 3);

        return new RedirectResponse($referer);
    }
}