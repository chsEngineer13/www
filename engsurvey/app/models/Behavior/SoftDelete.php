<?php
declare(strict_types=1);

namespace Engsurvey\Models\Behavior;

trait SoftDelete
{
    /**
     * @var boolean
     */
    protected $deletedFlag;


    /**
     * Устанавливает признак логического удаления строки.
     *
     * @param bool $flag Признак удаления
     *
     * @return TODO: Текущий класс модели.
     */
    public function setDeletedFlag(bool $flag)
    {
        $this->deletedFlag = $flag;

        return $this;
    }


    /**
     * Возвращает признак логического удаления строки.
     *
     * @return bool
     */
    public function getDeletedFlag(): bool
    {
        return $this->deletedFlag;
    }


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
            $parameters = 'deletedFlag = FALSE';
        }

        if (is_array($parameters) === false && strpos($parameters, 'deletedFlag') === false) {
            $parameters .= ' AND deletedFlag = FALSE';
        }

        if (is_array($parameters) === true) {
            if (isset($parameters[0]) === true && strpos($parameters[0], 'deletedFlag') === false) {
                $parameters[0] .= ' AND deletedFlag = FALSE';
            } elseif (isset($parameters['conditions']) === true && strpos($parameters['conditions'], 'deletedFlag') === false) {
                $parameters['conditions'] .= ' AND deletedFlag = FALSE';
            } else {
                $parameters['conditions'] = 'deletedFlag = FALSE';
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
