<?php
namespace AppBundle\Helper;

use AppBundle\AppBundle;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\VarDumper\VarDumper;

class AppHelper
{
    private static $oContainer;
    private static $oDoctrine;
    private static $oEntityManager;

	/**
	 * Хелпер Дамепра от Symfony, передавать можно любое количество парметров
	 * @param $params
	 */
	public static function dump(...$params)
	{
		//if (!RequestHelper::isAjax()) {
			VarDumper::dump($params);
		//}
	}

	/**
	 * Хелпер Дамепра от Symfony, передавать можно любое количество парметров
	 * Оборнут в exit , для остановки дальнейшего вывода кода
	 * @param $params
	 */
	public static function dumpExit(...$params)
	{
		//if (!RequestHelper::isAjax()) {
			exit(VarDumper::dump($params));
		//}

	}

	/**
	 * @param $params
	 */
	public static function debug(...$params)
	{
		echo '<pre>';
		print_r($params);
		echo '</pre>';
	}

	/**
	 * @param $params
	 */
	public static function debugExit(...$params)
	{
		echo '<pre>';
		print_r($params);
		exit('</pre>');
	}

    /**
     * @return ContainerInterface|Container
     */
    public static function getContainer()
    {
        if (!static::$oContainer) {
            static::$oContainer = AppBundle::getContainer();
        }

        return static::$oContainer;
    }

    /**
     * @return Registry | mixed
     */
    public static function getDoctrine()
    {
        if (!static::$oDoctrine) {
            if (!static::getContainer()->has('doctrine')) {
                throw new \LogicException('The DoctrineBundle is not registered in your application.');
            }
            static::$oDoctrine = static::getContainer()->get('doctrine');
        }

        return static::$oDoctrine;
    }

    /**
     * @return EntityManager
     */
    public static function em()
    {
        if (!static::$oEntityManager) {
            static::$oEntityManager = static::getDoctrine()->getManager();
        }

        return static::$oEntityManager;
    }

    /**
     * получить юзера из его токена
     * @return User|mixed|null
     */
    public static function getUser()
    {
        if (!self::getContainer()->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }
        if ($securiToken = self::getContainer()->get('security.token_storage')->getToken()) {
            $user = $securiToken->getUser();
            if ($user and ($user instanceof User)) {
                return $user;
            }
        }

        return NULL;
    }
}

