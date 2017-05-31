<?php
namespace AppBundle\Helper;

defined('FILTER_FLAG_NO_ENCODE') or define ('FILTER_FLAG_NO_ENCODE',!FILTER_FLAG_ENCODE_LOW);

use Symfony\Component\HttpFoundation\Request;

class RequestHelper
{

	/** @var Request */
	private static $syRequest;

	private static $aVars = [];

	/**
	 * Проверяем на AJAX запрос
	 * @return bool
	 */
	public static function isAjax(){
		if (static::getString('asJson')) {
			return true;
		}
		if (static::syRequest()->isXmlHttpRequest()) {
			return true;
		}
		return false;
	}

	/**
	 * Получаем значение токена
	 * @return array|string
	 */
	public static function getToken(){
		return static::syRequest()->headers->get('X-CSRFToken');
	}

	/**
	 * - Получаем булево значение
	 * @param $name
	 * @param int $default
	 * @return bool
	 */
	public static function getBool($name, $default = 0){
		$tmp = self::get($name, $default, FILTER_SANITIZE_NUMBER_INT);

		return $tmp ? true : false;
	}

	/**
	 * - фильтр по числу с плавающей точкой
	 * - вырезает все, кроме чисел
	 * @param $name
	 * @param int $default
	 * @return mixed|null
	 */
	public static function getInt($name, $default = 0){
		return self::get($name, $default, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_NO_ENCODE);
	}

	/**
	 * - фильтр по числу с плавающей точкой
	 * @param $name
	 * @param float $default
	 * @return mixed|null
	 */
	public static function getFloat($name,$default=0.0){
		return self::get($name, $default, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_SCIENTIFIC|FILTER_FLAG_ALLOW_FRACTION);
	}

	/**
	 * - Удаляет все символы, с ASCII-кодом < 32.
	 * - все HTML сушности
	 *
	 * @param $name
	 * @param string $default
	 * @return mixed|null
	 */
	public static function getWord($name, $default = ''){
		return self::get($name, $default, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	}

	/**
	 * - Кодирует все символы, с ASCII-кодом < 32.
	 * - кодирует html теги
	 *
	 * @param $name
	 * @param string $default
	 * @return mixed|null
	 */
	public static function getString($name, $default = ''){
		return self::get($name, $default, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
	}

	/**
	 * - Экранирует HTML-символы '"<>& и символы с ASCII-кодом < 32,
	 * - преобразовать специальные символы в HTML-сущности
	 * - при необходимости удаляет или кодирует остальные специальные символы.
	 *
	 * @param $name
	 * @param null $default
	 * @return mixed|null
	 */
	public static function getVar($name, $default = null){
		return self::get($name, $default, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_LOW );
	}

	/**
	 * - Получаем файл из запроса
	 * todo тут еще надо будет доработать по проверке валидности файла.
	 * @param $name
	 * @param int $filter
	 * @param int $flags
	 * @return mixed
	 */
	public static function getFiles( $name, $filter = FILTER_SANITIZE_STRING, $flags = FILTER_FLAG_STRIP_LOW){
		$files = static::syRequest()->files->get($name);
		return  self::filter($files, $filter, $flags);
	}

	/**
	 * - проверяет значение, как e-mail адрес
	 * @param $name
	 * @param string $default
	 * @return mixed|null
	 */
	public static function getEmail($name, $default = ''){
		return self::get($name, $default, FILTER_VALIDATE_EMAIL, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	}


	/**
	 * Получаем экземпляр класса "Request"
	 * на основе текущих значений глобальных переменных PHP
	 * @return Request
	 */
	public static function syRequest() {
		if (!static::$syRequest instanceof Request) {
			static::$syRequest = Request::createFromGlobals();
		}
		return static::$syRequest;
	}

	private static function get($name, $default = null, $filter = FILTER_UNSAFE_RAW, $flags = FILTER_FLAG_NO_ENCODE){
		if (!static::$aVars) {

			if (!empty(static::syRequest()->attributes->all())) {
				static::$aVars = array_merge(static::$aVars, static::syRequest()->attributes->all());
			}
			if (!empty(static::syRequest()->query->all())) {
				static::$aVars = array_merge(static::$aVars, static::syRequest()->query->all());
			}
			if (!empty(static::syRequest()->request->all())) {
				static::$aVars = array_merge(static::$aVars, static::syRequest()->request->all());
			}
		}
		$result = ArrHelper::get(static::$aVars, $name);

		//$result = static::syRequest()->get($name);

		if ($result) {
			return static::filter($result, $filter, $flags);
		}
		// todo добавить лог обычный, не критический
		return $default;

	}

	private static function filter($var, $filter, $flags, $array=false){
		if($array or is_array($var)){
			return filter_var_array($var, $filter);
		}
		else {
			return filter_var($var, $filter, $flags);
		}
	}

}

