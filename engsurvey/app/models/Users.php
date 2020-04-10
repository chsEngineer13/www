<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

class Users extends ModelBase
{
    use TimestampableOld;
    use SoftDeleteOld;
    
    
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $employeeId;

    /**
     * @var string
     */
    protected $passwordHash;

    /**
     * @var bool
     */
    protected $mustChangePassword;

    /**
     * @var bool
     */
    protected $isDisabled;

    /**
     * @var bool
     */
    protected $isLocked;

    /**
     * @var string
     */
    protected $description;


    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'login' => 'login',
            'employee_id' => 'employeeId',
            'password_hash' => 'passwordHash',
            'must_change_password' => 'mustChangePassword',
            'is_disabled' => 'isDisabled',
            'is_locked' => 'isLocked',
            'description' => 'description',
            // TODO: Переместить в файл TimestampableOld.php.
            'created_at' => 'createdAt',
            'created_user_id' => 'createdUserId',
            'updated_at' => 'updatedAt',
            'updated_user_id' => 'updatedUserId',
            // TODO: Переместить в файл SoftDeleteOld.php.
            'is_deleted' => 'isDeleted',
        ];
    }


    /**
     * Инициализация экземпляра модели в приложении.
     *
     * @return void
     */
    public function initialize()
    {
        $this->setSchema('engsurvey');
        $this->setSource('users');

        $this->belongsTo('employeeId', __NAMESPACE__ . '\Employees', 'id');

        // TODO: Переместить в файл SoftDeleteOld.php.
        $this->addBehavior(
            new PhalconSoftDelete(
                [
                    "field" => "isDeleted",
                    "value" => true,
                ]
            )
        );

    }
    
    
    /**
     * Выполняется до проверки поля на не нулевую/пустую строку
     * или на внешние ключи при выполнении операции вставки.
     *
     * @return void
     */
    public function __beforeValidationOnCreate()
    {
        // Установка идентификатора нового пользователя.
        $random = new Random();
        $this->setId($random->uuid());
    }


    /**
     * Устанавливает уникальный идентификатор пользователя.
     *
     * @param string $id Идентификатор пользователя.
     *
     * @return \Engsurvey\Models\Users
     */
    public function setId(string $id): Users
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор пользователя.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * Устанавливает имя учётной записи пользователя (логин).
     *
     * @param string $login Имя учётной записи пользователя (логин).
     *
     * @return \Engsurvey\Models\Users
     */
    public function setLogin(string $login): Users
    {
        $this->login = $login;

        return $this;
    }


    /**
     * Возвращает имя учётной записи пользователя (логин).
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }


    /**
     * Устанавливает уникальный идентификатор сотрудника.
     *
     * @param string|null $employeeId
     *
     * @return \Engsurvey\Models\Users
     */
    public function setEmployeeId(?string $employeeId = null): Users
    {
        $this->employeeId = $employeeId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор сотрудника.
     *
     * @return string|null
     */
    public function getEmployeeId(): ?string
    {
        return $this->employeeId;
    }


    /**
     * Возвращает экземпляр модели Employees на основе определенных отношений.
     *
     * @param mixed $parameters Параметры запроса.
     *
     * @return \Engsurvey\Models\Employees
     */
    public function getEmployee($parameters = null)
    {
        return $this->getRelated(__NAMESPACE__ . '\Employees', $parameters);
    }


    /**
     * Устанавливает xеш пароля.
     *
     * @param string $passwordHash
     *
     * @return \Engsurvey\Models\Users
     */
    public function setPasswordHash(string $passwordHash): Users
    {
        $this->passwordHash = $passwordHash;
        
        return $this;
    }


    /**
     * Возвращает xеш пароля.
     *
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }


    /**
     * Устанавливает значение флага, указывающего на необходимость смены пароля при следующем входе в систему.
     *
     * @param bool $flag
     *
     * @return \Engsurvey\Models\Users
     */
    public function setMustChangePassword(bool $flag = true): Users
    {
        $this->mustChangePassword = $flag;
        
        return $this;
    }


    /**
     * Возвращает значение флага, указывающего на необходимость смены пароля при следующем входе в систему.
     *
     * @return bool
     */
    public function getMustChangePassword(): bool
    {
        return $this->mustChangePassword;
    }


    /**
     * Проверяет необходимость смены пароля при следующем входе в систему.
     *
     * @return bool
     */
    public function mustChangePassword(): bool
    {
        return $this->mustChangePassword;
    }


    /**
     * Устанавливает значение флага отключения учетной записи пользователя.
     *
     * @param bool $flag
     *
     * @return \Engsurvey\Models\Users
     */
    public function setIsDisabled(bool $flag = false): Users
    {
        $this->isDisabled = $flag;
        
        return $this;
    }


    /**
     * Возвращает значение флага отключения учетной записи пользователя.
     *
     * @return bool
     */
    public function getIsDisabled(): bool
    {
        return $this->isDisabled;
    }


    /**
     * Проверяет, является ли учетная запись пользователя отключена.
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }


    /**
     * Устанавливает значение флага блокировки учетной записи пользователя.
     *
     * @param bool $flag
     *
     * @return \Engsurvey\Models\Users
     */
    public function setIsLocked(bool $flag = false): Users
    {
        $this->isLocked = $flag;
        
        return $this;
    }


    /**
     * Возвращает значение флага блокировки учетной записи пользователя.
     *
     * @return bool
     */
    public function getIsLocked(): bool
    {
        return $this->isLocked;
    }


    /**
     * Проверяет, является ли учетная запись пользователя заблокирована.
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->isLocked;
    }


    /**
     * Устанавливает описание учетной записи пользователя.
     *
     * @param string $description
     *
     * @return \Engsurvey\Models\Users
     */
    public function setDescription(string $description): Users
    {
        $this->description = trim($description);
        
        return $this;
    }


    /**
     * Возвращает описание учетной записи пользователя.
     *
     * @param string $description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

}
