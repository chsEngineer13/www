<?php
declare(strict_types=1);

namespace Engsurvey\Models\Behavior;

use Engsurvey\Models\Users;
use Engsurvey\Exception as EngsurveyException;
use Phalcon\Security\Random;

trait TimestampableOld
{
    /**
     * @var string (timestamp)
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $createdUserId;

    /**
     * @var string (timestamp)
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $updatedUserId;
    
    
    /**
     * Выполняется до проверки поля на не нулевую/пустую строку
     * или на внешние ключи при выполнении операции вставки.
     *
     * @return void
     */
    public function beforeValidationOnCreate()
    {
        // Получение идентификатора текущего пользователя.
        $currentUserId = $this->getCurrentUser()->getId();

        // Установка даты и времени создания строки.
        $dt = new \DateTime();
        $this->setCreatedAt($dt->format('Y-m-d\TH:i:s.u'));

        // Установка идентификатора пользователя, создавшего строку.
        $this->setCreatedUserId($currentUserId);

        // Установка даты и времени последнего обновления строки.
        $dt = new \DateTime();
        $this->setUpdatedAt($dt->format('Y-m-d\TH:i:s.u'));

        // Установка идентификатора пользователя, последним обновивший строку.
        $this->setUpdatedUserId($currentUserId);
    }


    /**
     * Выполняется до проверки поля на не нулевую/пустую строку
     * или на внешние ключи при выполнении операции обновления.
     *
     * @return void
     */
    protected function beforeValidationOnUpdate()
    {
        // Получение идентификатора текущего пользователя.
        $currentUserId = $this->getCurrentUser()->getId();

        // Установка даты и времени последнего обновления строки.
        $dt = new \DateTime();
        $this->setUpdatedAt($dt->format('Y-m-d\TH:i:s.u'));

        // Установка идентификатора пользователя, последним обновивший строку.
        $this->setUpdatedUserId($currentUserId);
    }


    /**
     * Устанавливает дату и время создания строки.
     *
     * @param string $createdAt Дата и время в формате 'YYYY-DD-MM hh:mm:ss'.
     *
     * @return TODO: Текущий класс модели.
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * Возвращает дату и время создания строки
     * в формате 'DD.MM.YYYY hh:mm:ss'.
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        $createdAt = $this->createdAt;

        if (!empty($createdAt)) {
            $dt = new \DateTime($createdAt);
            $createdAt = $dt->format('d.m.Y H:i:s');
        }

        return $createdAt;
    }


    /**
     * Возвращает отформатированную дату и время создания строки.
     *
     * @param string $format Формат возвращаемых дати и время.
     *
     * @return string
     */
    public function getFormattedCreatedAt(string $format): string
    {
        $createdAt = $this->createdAt;

        if (!is_null($createdAt)) {
            $dt = new \DateTime($createdAt);
            $createdAt = $dt->format($format);
        }

        return $createdAt;
    }
    


    /**
     * Устанавливает идентификатор пользователя, создавшего строку.
     *
     * @param string $userId Идентификатор пользователя
     *
     * @return TODO: Текущий класс модели.
     */
    public function setCreatedUserId(string $userId)
    {
        $this->createdUserId = $userId;
        
        return $this;
    }


    /**
     * Возвращает идентификатор пользователя, создавшего строку.
     *
     * @return string
     */
    public function getCreatedUserId(): string
    {
        return $this->createdUserId;
    }


    /**
     * Устанавливает дату и время последнего обновления строки.
     *
     * @param string $updatedAt Дата и время в формате 'YYYY-DD-MM hh:mm:ss'.
     *
     * @return TODO: Текущий класс модели.
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * Возвращает дату и время последнего обновления строки.
     * в формате 'DD.MM.YYYY hh:mm:ss'.
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        $updatedAt = $this->updatedAt;

        /*if (!empty($updatedAt)) {
            $dt = new \DateTime($updatedAt);
            $updatedAt = $dt->format('d.m.Y H:i:s');
        }*/

        return $updatedAt;
    }
    
    
    /**
     * Возвращает отформатированную дату и время обновления строки.
     *
     * @param string $format Формат возвращаемых дати и время.
     *
     * @return string
     */
    public function getFormattedUpdatedAt(string $format): string
    {
        $updatedAt = $this->updatedAt;

        if (!is_null($updatedAt)) {
            $dt = new \DateTime($updatedAt);
            $updatedAt = $dt->format($format);
        }

        return $updatedAt;
    }


    /**
     * Устанавливает идентификатор пользователя, последним обновивший строку.
     *
     * @param string $userId Идентификатор пользователя
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setUpdatedUserId(string $userId)
    {
        $this->updatedUserId = $userId;
        
        return $this;
    }


    /**
     * Возвращает идентификатор пользователя, последним обновивший строку.
     *
     * @return string
     */
    public function getUpdatedUserId(): string
    {
        return $this->updatedUserId;
    }
    
    
    /**
     * TODO: Рассмотреть вариант с использованием Model Relationships (Phalcon).
     *
     * Возвращает пользователя, последним обновивший строку.
     *
     * @return Users
     */
    public function getUpdatedUser(): Users
    {
        $userId = $this->updatedUserId;
        
        $user = Users::findFirst("id = '$userId'");
        if ($user === false) {
            throw new EngsurveyException('Пользователь, последним обновивший строку, не найден.');
        }

        return $user;
    }
    
    

}
