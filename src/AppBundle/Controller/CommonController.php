<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommonController extends Controller
{
    public function topAction()
    {
        return $this->render('Common/top.html.twig', []);
    }

    public function footerAction()
    {
        return $this->render('common/footer.html.twig', []);
    }

}
