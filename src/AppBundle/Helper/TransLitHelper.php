<?php

namespace AppBundle\Helper;


class TransLitHelper
{
    /**
     * @param string $text конвертируемый текст
     * @param string $code в какой вид конвертировать
     * @return mixed
     */
    static function convert($text, $code = 'ru')
    {
        $en_table = [
            ' and ',
            'A',
            'a',
            'B',
            'b',
            'V',
            'v',
            'G',
            'g',
            'D',
            'd',
            'E',
            'e',
            'JO',
            'jo',
            'ZH',
            'zh',
            'Z',
            'z',
            'I',
            'i',
            'J',
            'j',
            'K',
            'k',
            'L',
            'l',
            'M',
            'm',
            'N',
            'n',
            'O',
            'o',
            'P',
            'p',
            'R',
            'r',
            'S',
            's',
            'T',
            't',
            'U',
            'u',
            'F',
            'f',
            'X',
            'x',
            'C',
            'c',
            'CH',
            'ch',
            'SH',
            'sh',
            'SHH',
            'shh',
            '',
            'Y',
            'y',
            '',
            'E',
            'e',
            'YU',
            'yu',
            'YA',
            'ya'
        ];

        $ru_table = [
            ' и ',
            'А',
            'a',
            'Б',
            'б',
            'В',
            'в',
            'Г',
            'г',
            'Д',
            'д',
            'Е',
            'е',
            'Ё',
            'ё',
            'Ж',
            'ж',
            'З',
            'з',
            'И',
            'и',
            'Й',
            'й',
            'К',
            'к',
            'Л',
            'л',
            'М',
            'м',
            'Н',
            'н',
            'О',
            'о',
            'П',
            'п',
            'Р',
            'р',
            'С',
            'с',
            'Т',
            'т',
            'У',
            'у',
            'Ф',
            'ф',
            'Х',
            'х',
            'Ц',
            'ц',
            'Ч',
            'ч',
            'Ш',
            'ш',
            'Щ',
            'щ',
            'ъ',
            'Ы',
            'ы',
            'ь',
            'Э',
            'э',
            'Ю',
            'ю',
            'Я',
            'я'
        ];

        switch ($code) {
            case 'en':
                return str_replace($ru_table, $en_table, $text);
                break;
            case 'ru':
                return str_replace($en_table, $ru_table, $text);
                break;
            default :
                return $text;
        }
    }
}