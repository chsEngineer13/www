<?php
declare(strict_types=1);

namespace Engsurvey\Models\Behavior;

trait SoftDeleteOld
{
    /**
     * @var boolean
     */
    protected $isDeleted;


    /**
     * Устанавливает статус логического удаления строки.
     *
     * @param bool $status Статус удаления
     *
     * @return TODO: Текущий класс модели.
     */
    public function setIsDeleted(bool $status)
    {
        $this->isDeleted = $status;

        return $this;
    }


    /**
     * Возвращает статус логического удаления строки.
     *
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }


    /**
     * Проверяет, удалена ли данная строка.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


    /**
     * Метод выполняется до операции удаления.
     *
     * @return bool
     */
    //public function beforeDelete()
    //{
    //    $this->isDeleted = true;

        //return false;
    //}


    /**
     * Добавляет в параметры запроса условие не выбирать удаленные записи.
     *
     * @param mixed $parameters Параметры запроса
     *
     * @return mixed
     *
     * @link https://github.com/denners777/intranet-phalcon/blob/master/app/shared/models/ModelBase.php
     */
    protected static function softDeleteFetch($parameters = null)
    {
        if (is_int($parameters)) {
            throw new \InvalidArgumentException('Тип аргумента функции integer не поддерживается.');
        }

        if ($parameters === null) {
            $parameters = 'isDeleted = FALSE';
        }

        if (is_array($parameters) === false && strpos($parameters, 'isDeleted') === false) {
            $parameters .= ' AND isDeleted = FALSE';
        }

        if (is_array($parameters) === true) {
            if (isset($parameters[0]) === true && strpos($parameters[0], 'isDeleted') === false) {
                $parameters[0] .= ' AND isDeleted = FALSE';
            } elseif (isset($parameters['conditions']) === true && strpos($parameters['conditions'], 'isDeleted') === false) {
                $parameters['conditions'] .= ' AND isDeleted = FALSE';
            } else {
                $parameters['conditions'] = 'isDeleted = FALSE';
            }
        }

        return $parameters;
    }


    /**
     * Пререопределение функции find().
     * Запрашивает набор записей, соответствующих указанным условиям.
     * Удаленные записи не учитываются.
     *
     * @param mixed $parameters Параметры запроса
     *
     * @return Phalcon\Mvc\Model\Resultset\Simple
     */
    public static function find($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::find($parameters);
    }


    /**
     * Пререопределение функции findFirst().
     * Запрашивает первую запись, соответствующую указанным условиям.
     * Удаленные записи не учитываются.
     *
     * ВНИМАНИЕ! Тип параметра integer не поддерживается.
     *           Например: findFirst(5);
     *
     * @param string|array|null $parameters Параметры запроса
     *
     * @return Phalcon\Mvc\Model
     */
    public static function findFirst($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::findFirst($parameters);
    }



    /**
     * Пререопределение функции count().
     * Подсчитывает, сколько записей соответствует указанным условиям.
     * Удаленные записи не учитываются.
     *
     * @param array|null $parameters Параметры запроса
     *
     * @return mixed
     */
    public static function count($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::count($parameters);
    }


    /**
     * Пререопределение функции sum().
     * Рассчитывает сумму по столбцу,
     * который соответствует указанным условиям.
     * Удаленные записи не учитываются.
     *
     * @param array|null $parameters Параметры запроса
     *
     * @return mixed
     */
    public static function sum($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::sum($parameters);
    }


    /**
     * Пререопределение функции average().
     * Рассчитывает среднее значение для столбца,
     * соответствующего заданным условиям.
     * Удаленные записи не учитываются.
     *
     * @param array|null $parameters Параметры запроса
     *
     * @return mixed
     */
    public static function average($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::average($parameters);
    }


    /**
     * Пререопределение функции maximum().
     * Позволяет получить максимальное значение столбца,
     * соответствующего указанным условиям.
     * Удаленные записи не учитываются.
     *
     * @param array|null $parameters Параметры запроса
     *
     * @return mixed
     */
    public static function maximum($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::maximum($parameters);
    }


    /**
     * Пререопределение функции minimum().
     * Позволяет получить минимальное значение столбца,
     * соответствующего указанным условиям.
     * Удаленные записи не учитываются.
     *
     * @param array|null $parameters Параметры запроса
     *
     * @return mixed
     */
    public static function minimum($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::minimum($parameters);
    }

}
