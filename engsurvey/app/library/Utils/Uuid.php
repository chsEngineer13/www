<?php
namespace Engsurvey\Utils;

/**
 * Класс, который генерирует и проверяет UUID.
 *
 * @author Andrew Moore (http://www.php.net/manual/en/function.uniqid.php#94959)
 * @author Oleg Chernyakov (ChernyakovOV@gmail.com)
 */
class Uuid
{
    /**
     * Генерирует UUID версии 4 (случайный).
     *
     * @return string
     */
    public static function generate()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }


    /*
     * Проверяет, является ли переменная UUID.
     * Возвращает TRUE, если переменная является UUID, или FALSE в противном случае.
     *
     * @param  mixed $var Проверяемая переменная.
     * @return bool
     */
    public static function validate($var)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
            '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $var) === 1;
    }

}
