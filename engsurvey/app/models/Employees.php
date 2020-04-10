<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Phalcon\Mvc\Model as PhalconModel;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Security\Random;

/**
 * Сведения о сотрудниках.
 */
class Employees extends PhalconModel
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $middleName;

    /**
     * @var string
     */
    protected $initials;

    /**
     * @var string
     */
    protected $branchId;

    /**
     * @var string
     */
    protected $jobTitle;

    /**
     * @var string
     */
    protected $department;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $phoneWork;

    /**
     * @var string
     */
    protected $phoneGas;

    /**
     * @var string
     */
    protected $phoneMobile;

    /**
     * @var string
     */
    protected $email;

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
     * @var boolean
     */
    protected $isDeleted;


    /**
     * Карта сопоставления столбцов.
     *
     * @return array
     */
    public function columnMap(): array
    {
        return [
            'id' => 'id',
            'last_name' => 'lastName',
            'first_name' => 'firstName',
            'middle_name' => 'middleName',
            'initials' => 'initials',
            'branch_id' => 'branchId',
            'job_title' => 'jobTitle',
            'department' => 'department',
            'location' => 'location',
            'phone_work' => 'phoneWork',
            'phone_gas' => 'phoneGas',
            'phone_mobile' => 'phoneMobile',
            'email' => 'email',
            'created_at' => 'createdAt',
            'created_user_id' => 'createdUserId',
            'updated_at' => 'updatedAt',
            'updated_user_id' => 'updatedUserId',
            'is_deleted' => 'isDeleted',
        ];
    }


    /**
    * Модель Employees ссылается на таблицу 'employees'.
    */
    public function getSource()
    {
        return 'employees';
    }


    /**
     * Инициализация экземпляра модели в приложении.
     *
     * @return void
     */
    public function initialize()
    {
        $this->setSchema('human_resources');
        
        $this->belongsTo('branchId', __NAMESPACE__ . '\Branches', 'id');

        // Записи в БД физически не удаляются,
        // а помечаются как удаленные.
        $this->addBehavior(new SoftDelete(
            [
                'field' => 'isDeleted',
                'value' => true
            ]
        ));
    }
    
    
    /**
     * Выполняется до проверки поля на не нулевую/пустую строку
     * или на внешние ключи при выполнении операции вставки.
     *
     * @return void
     */
    public function beforeValidationOnCreate()
    {
        // Получение текущего пользователя.
        $currentUserId = $this->getCurrentUserId();

        // Установка идентификатора сотрудника.
        $random = new Random();
        $this->setId($random->uuid());

        // Установка даты и времени создания строки.
        $this->setCreatedAt(date('Y-m-d H:i:s'));

        // Установка идентификатора пользователя, создавшего строку.
        $this->setCreatedUserId($currentUserId);

        // Установка даты и времени последнего обновления строки.
        $this->setUpdatedAt(date('Y-m-d H:i:s'));

        // Установка идентификатора пользователя, последним обновивший строку.
        $this->setUpdatedUserId($currentUserId);
        
        // Формирование и установка инициалов.
        $firstName = $this->getFirstName();
        $middleName = $this->getMiddleName();
        
        $initials = $this->createInitials($firstName, $middleName);
        $this->setInitials($initials);

    }



    /**
     * Выполняется до проверки поля на не нулевую/пустую строку
     * или на внешние ключи при выполнении операции обновления.
     *
     * @return void
     */
    protected function beforeValidationOnUpdate()
    {
        // Получение текущего пользователя.
        $currentUserId = $this->getCurrentUserId();

        // Установка даты и времени последнего обновления строки.
        $this->setUpdatedAt(date('Y-m-d H:i:s'));

        // Установка идентификатора пользователя, последним обновивший строку.
        $this->setUpdatedUserId($currentUserId);
        
        // Формирование и установка инициалов.
        $firstName = $this->getFirstName();
        $middleName = $this->getMiddleName();
        
        $initials = $this->createInitials($firstName, $middleName);
        $this->setInitials($initials);
    }


    /**
     * Устанавливает идентификатор сотрудника.
     *
     * @param  string $id
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setId($id): Employees
    {
        $this->id = $id;
        
        return $this;
    }


    /**
     * Возвращает идентификатор сотрудника.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Устанавливает фамилию сотрудника.
     *
     * @param string $lastName
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setLastName(string $lastName): Employees
    {
        $this->lastName = trim($lastName);

        return $this;
    }


    /**
     * Возвращает фамилию сотрудника.
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }


    /**
     * Устанавливает имя сотрудника.
     *
     * @param string $firstName
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setFirstName(string $firstName): Employees
    {
        $this->firstName = trim($firstName);

        return $this;
    }


    /**
     * Возвращает имя сотрудника.
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }


    /**
     * Устанавливает отчество сотрудника.
     *
     * @param string $middleName
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setMiddleName(string $middleName): Employees
    {
        $this->middleName = trim($middleName);

        return $this;
    }


    /**
     * Возвращает отчество сотрудника.
     *
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }


    /**
     * Устанавливает инициалы сотрудника.
     *
     * @param string $initials
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setInitials(string $initials): Employees
    {
        $this->initials = trim($initials);

        return $this;
    }


    /**
     * Возвращает инициалы сотрудника.
     *
     * @return string
     */
    public function getInitials(): string
    {
        return $this->initials;
    }
    
    
    /**
     * Возвращает полное имя сотрудника (Фамилия Имя Отчество).
     *
     * @return string
     */
    public function getFullName(): string
    {
        $lastName = $this->lastName;
        $firstName = $this->firstName;
        $middleName = $this->middleName;
        
        $fullName = $lastName . ' ' . $firstName . ' ' . $middleName;

        return $fullName;
    }
    
    
    /**
     * Возвращает сокращенное имя сотрудника (Фамилия И. О.).
     *
     * @return string
     */
    public function getShortName(): string
    {
        $lastName = $this->lastName;
        $initials = $this->initials;
        
        $shortName = $lastName . ' ' . $initials;

        return $shortName;
    }


    /**
     * Устанавливает идентификатор филиала.
     *
     * @param string $branchId
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setBranchId(string $branchId): Employees
    {
        $this->branchId = $branchId;

        return $this;
    }


    /**
     * Возвращает идентификатор филиала.
     *
     * @return string
     */
    public function getBranchId(): string
    {
        return $this->branchId;
    }


    /**
     * Возвращает экземпляр модели Branches на основе определенных отношений.
     *
     * @param mixed $parameters
     *
     * @return \Engsurvey\Models\Branches
     */
    public function getBranch($parameters = null): Branches
    {
        return $this->getRelated(__NAMESPACE__ . '\Branches', $parameters);
    }


    /**
     * Устанавливает должность сотрудника.
     *
     * @param string $jobTitle
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setJobTitle(string $jobTitle): Employees
    {
        $this->jobTitle = trim($jobTitle);

        return $this;
    }


    /**
     * Возвращает должность сотрудника.
     *
     * @return string
     */
    public function getJobTitle(): string
    {
        return $this->jobTitle;
    }


    /**
     * Устанавливает подразделение в котором работает сотрудника.
     *
     * @param string $department
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setDepartment(string $department): Employees
    {
        $this->department = trim($department);

        return $this;
    }


    /**
     * Возвращает подразделение в котором работает сотрудника.
     *
     * @return string
     */
    public function getDepartment(): string
    {
        return $this->department;
    }


    /**
     * Устанавливает местонахождение сотрудника.
     *
     * @param string $location
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setLocation(string $location): Employees
    {
        $this->location = trim($location);

        return $this;
    }


    /**
     * Возвращает местонахождение сотрудника.
     *
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }


    /**
     * Устанавливает рабочий телефон.
     *
     * @param string $phoneWork
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setPhoneWork(string $phoneWork): Employees
    {
        $this->phoneWork = trim($phoneWork);

        return $this;
    }


    /**
     * Возвращает рабочий телефон.
     *
     * @return string
     */
    public function getPhoneWork(): string
    {
        return $this->phoneWork;
    }


    /**
     * Устанавливает телефон газ-связи.
     *
     * @param string $phoneGas
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setPhoneGas(string $phoneGas): Employees
    {
        $this->phoneGas = trim($phoneGas);

        return $this;
    }


    /**
     * Возвращает телефон газ-связи.
     *
     * @return string
     */
    public function getPhoneGas(): string
    {
        return $this->phoneGas;
    }


    /**
     * Устанавливает мобильный телефон.
     *
     * @param string $phoneMobile
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setPhoneMobile(string $phoneMobile): Employees
    {
        $this->phoneMobile = trim($phoneMobile);

        return $this;
    }


    /**
     * Возвращает мобильный телефон.
     *
     * @return string
     */
    public function getPhoneMobile(): string
    {
        return $this->phoneMobile;
    }


    /**
     * Устанавливает адрес электронной почты.
     *
     * @param string $email
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setEmail(string $email): Employees
    {
        $this->email = trim($email);

        return $this;
    }


    /**
     * Возвращает адрес электронной почты.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * Устанавливает дату и время создания строки.
     *
     * @param string $createdAt Дата и время в формате 'YYYY-DD-MM hh:mm:ss'.
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setCreatedAt(string $createdAt): Employees
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
     * Устанавливает идентификатор пользователя, создавшего строку.
     *
     * @param string $userId Идентификатор пользователя
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setCreatedUserId(string $userId): Employees
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
     * @return \Engsurvey\Models\Employees
     */
    public function setUpdatedAt(string $updatedAt): Employees
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

        if (!empty($updatedAt)) {
            $dt = new \DateTime($updatedAt);
            $updatedAt = $dt->format('d.m.Y H:i:s');
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
    public function setUpdatedUserId(string $userId): Employees
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
     * Устанавливает статус логического удаления строки.
     *
     * @param bool $status Статус удаления
     *
     * @return \Engsurvey\Models\Employees
     */
    public function setIsDeleted(bool $status): Employees
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
     * Возвращает идентификатор текущего пользователя
     *
     * @return string
     */
    protected function getCurrentUserId(): string
    {
        $session = $this->getDI()->getShared('session');
        $currentUser = $session->get('currentUser');
        $currentUserId = $currentUser->getId();

        return $currentUserId;
    }

    
    /**
     * Формирует инициалы из имени и отчества.
     *
     * @param string $firstName Имя
     * @param string $middleName Отчество
     *
     * @return string
     */
    protected function createInitials(string $firstName, string $middleName): string
    {
        mb_internal_encoding("UTF-8");
        
        $initials = '';
        
        if (mb_strlen($firstName) > 0) {
            $str = mb_substr($firstName, 0, 1);
            $initials = $str . '.';
        }
        
        if (mb_strlen($middleName) > 0) {
            $str = mb_substr($middleName, 0, 1);
            $initials .= ' ' . $str . '.';
        }
            
        return $initials;
    }


    //--------------------------------------------------------------------------
    // Функции поддержки 'мягкого' удаления записей.
    //--------------------------------------------------------------------------

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

}
