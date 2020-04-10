<?php
declare(strict_types=1);

namespace Engsurvey\Models\Behavior;

use Engsurvey\Models\Users;

trait Timestampable
{
    /**
     * @var string
     */
    protected $createdDate;

    /**
     * @var string
     */
    protected $createdUserId;

    /**
     * @var string
     */
    protected $updatedDate;

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
        $this->setCreatedDate(date('Y-m-d H:i:s'));

        // Установка идентификатора пользователя, создавшего строку.
        $this->setCreatedUserId($currentUserId);

        // Установка даты и времени последнего обновления строки.
        $this->setUpdatedDate(date('Y-m-d H:i:s'));

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
        $this->setUpdatedDate(date('Y-m-d H:i:s'));

        // Установка идентификатора пользователя, последним обновивший строку.
        $this->setUpdatedUserId($currentUserId);
    }


    /**
     * Устанавливает дату и время создания строки.
     *
     * @param string $createdDate Дата и время в формате 'YYYY-DD-MM hh:mm:ss'.
     *
     * @return TODO: Текущий класс модели.
     */
    public function setCreatedDate(string $createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }


    /**
     * Возвращает дату и время создания строки
     * в формате 'YYYY-MM-DD hh:mm:ss'.
     *
     * @return string
     */
    public function getCreatedDate(): string
    {
        return $this->createdDate;
    }


    /**
     * Возвращает отформатированную дату и время создания строки.
     *
     * @param string $format Формат возвращаемых дати и время.
     *
     * @return string
     */
    public function getFormattedCreatedDate(string $format): string
    {
        $createdDate = $this->createdDate;
        
        $dt = new \DateTime($createdDate);
        $createdDate = $dt->format($format);

        return $createdDate;
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
     * TODO: Рассмотреть вариант с использованием Model Relationships (Phalcon).
     *
     * Возвращает пользователя, создавшим строку.
     *
     * @return Users
     */
    public function getCreatedUser(): Users
    {
        $createdUserId = $this->createdUserId;
        
        $createdUser = Users::findFirst("id = '$createdUserId'");
        if ($createdUser === false) {
            throw new \Exception('Пользователь, создавший    строку, не найден.');
        }

        return $createdUser;
    }


    /**
     * Устанавливает дату и время последнего обновления строки.
     *
     * @param string $updatedDate Дата и время в формате 'YYYY-DD-MM hh:mm:ss'.
     *
     * @return TODO: Текущий класс модели.
     */
    public function setUpdatedDate(string $updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }


    /**
     * Возвращает дату и время последнего обновления строки.
     * в формате 'YYYY-MM-DD hh:mm:ss'.
     *
     * @return string
     */
    public function getUpdatedDate(): string
    {
        return $this->updatedDate;
    }
    
    
    /**
     * Возвращает отформатированную дату и время обновления строки.
     *
     * @param string $format Формат возвращаемых даты и времени.
     *
     * @return string
     */
    public function getFormattedUpdatedDate(string $format): string
    {
        $updatedDate = $this->updatedDate;
        
        $dt = new \DateTime($updatedDate);
        $updatedDate = $dt->format($format);

        return $updatedDate;
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
        $updatedUserId = $this->updatedUserId;
        
        $updatedUser = Users::findFirst("id = '$updatedUserId'");
        if ($updatedUser === false) {
            throw new \Exception('Пользователь, последним обновивший строку, не найден.');
        }

        return $updatedUser;
    }

}
