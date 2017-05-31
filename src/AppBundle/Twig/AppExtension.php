<?php
namespace AppBundle\Twig;

use AppBundle\Helper\TransLitHelper;
use Twig_SimpleFunction;

class AppExtension extends \Twig_Extension
{
    private $transLit;

    public function getName()
    {
        return 'app_extension';
    }

    public function __construct(TransLitHelper $transLit)
    {
        $this->transLit = $transLit;
    }

    public function getFunctions()
    {
        return [
            'userPhoto' => new Twig_SimpleFunction('userPhoto', [$this, 'userPhoto'])
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('transLit',[$this, 'transLit']),
            new \Twig_SimpleFilter('mbStrLen', [$this, 'mbStrLen'])
        ];
    }

    function transLit($str, $code) {
        return $this->transLit->convert($str, $code);
    }

    public function mbStrLen($content){
        return mb_strlen($content,'utf-8');
    }

    function userPhoto ($name) {
        return '/users/' . $name;
    }
}