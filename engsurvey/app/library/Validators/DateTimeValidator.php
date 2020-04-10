<?php
namespace Engsurvey\Validators;

use DateTime;

class DateTimeValidator
{
    /**
     * Проверяет переменную содержащую дату/время на соответствие формату.
     * Возвращает TRUE в случае успеха или FALSE в случае возникновения ошибки.
     *
     * @param  mixed  $var    Проверяемая переменная.
     * @param  string $format Формат даты и времени.
     * @return bool
     *
     * @link http://php.net/manual/ru/function.checkdate.php#113205
     */
    public static function validate($var, $format)
    {
        $dt = DateTime::createFromFormat($format, $var);
        return $dt && $dt->format($format) == $var;
    }
}
