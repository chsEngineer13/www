<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

class EmployeeGroupMemberships extends ModelBase
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
    protected $employeeGroupId;

    /**
     * @var string
     */
    protected $employeeId;


    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'employee_group_id' => 'employeeGroupId',
            'employee_id' => 'employeeId',
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
        $this->setSchema('human_resources');
        $this->setSource('employee_group_memberships');
        
        $this->belongsTo('employeeGroupId', __NAMESPACE__ . '\EmployeeGroups', 'id');
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
     * Устанавливает идентификатор строки.
     *
     * @param string $id
     *
     * @return \Engsurvey\Models\EmployeeGroupMemberships
     */
    public function setId($id): EmployeeGroupMemberships
    {
        $this->id = $id;
        
        return $this;
    }


    /**
     * Возвращает идентификатор строки.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Устанавливает идентификатор группы сотрудников.
     *
     * @param string $employeeGroupId
     *
     * @return \Engsurvey\Models\EmployeeGroupMemberships
     */
    public function setEmployeeGroupId($employeeGroupId): EmployeeGroupMemberships
    {
        $this->employeeGroupId = $employeeGroupId;
        
        return $this;
    }


    /**
     * Возвращает идентификатор группы сотрудников.
     *
     * @return string
     */
    public function getEmployeeGroupId()
    {
        return $this->employeeGroupId;
    }
    
    
    /**
     * Возвращает экземпляр модели EmployeeGroups на основе определенных отношений.
     *
     * @param mixed $parameters
     *
     * @return \Engsurvey\Models\EmployeeGroups
     */
    public function getEmployeeGroup($parameters = null): EmployeeGroups
    {
        return $this->getRelated(__NAMESPACE__ . '\EmployeeGroups', $parameters);
    }
    
    
    /**
     * Устанавливает идентификатор сотрудника.
     *
     * @param string $employeeId
     *
     * @return \Engsurvey\Models\EmployeeGroupMemberships
     */
    public function setEmployeeId($employeeId): EmployeeGroupMemberships
    {
        $this->employeeId = $employeeId;
        
        return $this;
    }


    /**
     * Возвращает идентификатор сотрудника.
     *
     * @return string
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }
    
    
    /**
     * Возвращает экземпляр модели Employees на основе определенных отношений.
     *
     * @param mixed $parameters
     *
     * @return \Engsurvey\Models\Employees
     */
    public function getEmployee($parameters = null): Employees
    {
        return $this->getRelated(__NAMESPACE__ . '\Employees', $parameters);
    }

}
