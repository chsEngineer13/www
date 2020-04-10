<?php
namespace Engsurvey\Models;

use Phalcon\Mvc\Model as PhalconModel;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Engsurvey\Utils\Uuid;

class ModelBaseOld extends PhalconModel
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $createdUserId;

    /**
     * @var string
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $updatedUserId;

    /**
     * @var boolean
     */
    protected $isDeleted;


    /**
     * Устанавливает ID строки.
     *
     * @param  string $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * Возвращает ID строки.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Устанавливает дату и время создания строки.
     * в формате DD.MM.YYYY HH:MM:SS.
     *
     * @param  string $createdAt
     * @return void
     */
    public function setCreatedAt($createdAt)
    {
        $datetime = \DateTime::createFromFormat('d.m.Y H:i:s', $createdAt);
        $this->createdAt = $datetime->format('Y-m-d H:i:s');
    }


    /**
     * Возвращает дату и время создания строки.
     * в формате DD.MM.YYYY HH:MM:SS.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        $datetime = new \DateTime($this->createdAt);
        return $datetime->format('d.m.Y H:i:s');
    }


    /**
     * Устанавливает ID пользователя, создавшего строку.
     *
     * @param  string $createdUserId
     * @return void
     */
    public function setCreatedUserId($createdUserId)
    {
        $this->createdUserId = $createdUserId;
    }


    /**
     * Возвращает ID пользователя, создавшего строку.
     *
     * @return string
     */
    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }


    /**
     * Устанавливает дата и время последнего изменения строки.
     * в формате DD.MM.YYYY HH:MM:SS.
     *
     * @param  string $updatedAt
     * @return void
     */
    public function setUpdatedAt($updatedAt)
    {
        $datetime = \DateTime::createFromFormat('d.m.Y H:i:s', $updatedAt);
        $this->updatedAt = $datetime->format('Y-m-d H:i:s');
    }


    /**
     * Возвращает дата и время последнего изменения строки.
     * в формате DD.MM.YYYY HH:MM:SS.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        $datetime = new \DateTime($this->updatedAt);
        return $datetime->format('d.m.Y H:i:s');
    }


    /**
     * Устанавливает ID пользователя, произведшего изменение строки.
     *
     * @param  string $updatedUserId
     * @return void
     */
    public function setUpdatedUserId($updatedUserId)
    {
        $this->updatedUserId = $updatedUserId;
    }


    /**
     * Возвращает ID пользователя, произведшего изменение строки.
     *
     * @return string
     */
    public function getUpdatedUserId()
    {
        return $this->updatedUserId;
    }


    /**
     * Устанавливает признак логического удаления строки.
     *
     * @param boolean $flag
     * @return void
     */
    public function setIsDeleted($flag)
    {
        $this->isDeleted = $flag;
    }


    /**
     * Возвращает признак логического удаления строки.
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }


    /**
     * Проверяет, удалена ли данная строка.
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->isDeleted;
    }


    /**
     * Карта сопоставления столбцов.
     *
     * @return void
     */
    public function columnMap()
    {
        // Ключи - реальные имена в таблице и
        // значения - их имена в приложении.
        return array (
            'id' => 'id',
            'created_at' => 'createdAt',
            'created_user_id' => 'createdUserId',
            'updated_at' => 'updatedAt',
            'updated_user_id' => 'updatedUserId',
            'is_deleted' => 'isDeleted',
        );
    }


    /**
     * Инициализация экземпляра модели в приложении.
     *
     * @return void
     */
    public function initialize()
    {
        $this->skipAttributesOnUpdate(array('created_at', 'created_user_id'));

        // Записи в БД физически не удаляются,
        // а помечаются как удаленные.
        $this->addBehavior(new SoftDelete(
            array(
                'field' => 'isDeleted',
                'value' => true
            )
        ));
    }


    /**
     * Выполняется до проверки поля на не нулевую / пустую строку
     * или на внешние ключи.
     *
     * @return void
     */
    protected function beforeValidation()
    {
        if (empty($this->isDeleted)) {
            $this->isDeleted = false;
        }
    }


    /**
     * Выполняется до проверки поля на не нулевую / пустую строку
     * или на внешние ключи при выполнении операции вставки.
     *
     * @return void
     */
    protected function beforeValidationOnCreate()
    {
        $session = $this->getDI()->getShared('session');
        $currentUser = $session->get('currentUser');
        $currentUserId = $currentUser->getId();

        if (empty($this->id)) {
            $random = new \Phalcon\Security\Random();
            $this->id = $random->uuid();
        }

        if (empty($this->createdAt)) {
            $this->createdAt = date('Y-m-d H:i:s');
        }

        if (empty($this->createdUserId)) {
            $this->createdUserId = $currentUserId;
        }

        if (empty($this->updatedAt)) {
            $this->updatedAt = date('Y-m-d H:i:s');
        }

        if (empty($this->updatedUserId)) {
            $this->updatedUserId = $currentUserId;
        }
    }


    /**
     * Выполняется до проверки поля на не нулевую / пустую строку
     * или на внешние ключи при выполнении операции обновления.
     *
     * @return void
     */
    protected function beforeValidationOnUpdate()
    {
        $session = $this->getDI()->getShared('session');
        $currentUser = $session->get('currentUser');
        $currentUserId = $currentUser->getId();

        if (empty($this->updatedAt)) {
            $this->updatedAt = date('Y-m-d H:i:s');
        }

        if (empty($this->updatedUserId)) {
            $this->updatedUserId = $currentUserId;
        }
    }


    /**
     * @param array|string $parameters Параметры запроса
     * @return Phalcon\Mvc\Model\Resultset\Simple
     */
    public static function find($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::find($parameters);
    }


    /**
     * @param array|string $parameters Параметры запроса
     * @return Phalcon\Mvc\Model
     */
    public static function findFirst($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::findFirst($parameters);
    }


    /**
     * @param string $id ID
     * @return Phalcon\Mvc\Model
     */
    public static function findFirstById($id)
    {
        if (!Uuid::validate($id)) {
            // TODO: Добавить исключение.
        }

        $parameters = "id = '$id' AND isDeleted = FALSE";

        return parent::findFirst($parameters);
    }


    /**
     * @param array|string $parameters Параметры запроса
     * @return mixed
     */
    public static function count($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::count($parameters);
    }
    
    
    /**
     * @param array|string $parameters Параметры запроса
     * @return mixed
     */
    public static function sum($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::sum($parameters);
    }
    
    
    /**
     * @param array|string $parameters Параметры запроса
     * @return mixed
     */
    public static function average($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::average($parameters);
    }
    
    
    /**
     * @param array|string $parameters Параметры запроса
     * @return mixed
     */
    public static function maximum($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::maximum($parameters);
    }
    
    
    /**
     * @param array|string $parameters Параметры запроса
     * @return mixed
     */
    public static function minimum($parameters = null)
    {
        $parameters = self::softDeleteFetch($parameters);

        return parent::minimum($parameters);
    }


    /**
     * @param array|string $parameters Параметры запроса
     * @return mixed
     */
    protected static function softDeleteFetch($parameters = null)
    {
        if ($parameters === null) {
            $parameters = 'isDeleted = false';
        }

        if (is_array($parameters) === false && strpos($parameters, 'isDeleted') === false) {
            $parameters .= ' AND isDeleted = false';
        }

        if (is_array($parameters) === true) {
            if (isset($parameters[0]) === true && strpos($parameters[0], 'isDeleted') === false) {
                $parameters[0] .= ' AND isDeleted = false';
            } elseif (isset($parameters['conditions']) === true && strpos($parameters['conditions'], 'isDeleted') === false) {
                $parameters['conditions'] .= ' AND isDeleted = false';
            } else {
                $parameters['conditions'] = 'isDeleted = false';
            }
        }

        return $parameters;
    }
    
    
    /**
     * Возвращает текущего пользователя, если он осуществил вход в систему.
     * Если текущий пользователя нет, функция возвращает null.
     *
     */
    protected function getCurrentUser()
    {
        $session = $this->getDI()->getShared('session');
        
        if ($session->has('currentUser')) {
            $currentUser = $session->get('currentUser');
            return $currentUser;
        }

        return null;
    }

}
