<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

class UserGroupMemberships extends ModelBase
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
    protected $userGroupId;

    /**
     * @var string
     */
    protected $userId;


    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'user_group_id' => 'userGroupId',
            'user_id' => 'userId',
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
        $this->setSource('user_group_memberships');
        
        $this->belongsTo('userGroupId', __NAMESPACE__ . '\UserGroups', 'id');
        $this->belongsTo('userId', __NAMESPACE__ . '\Users', 'id');

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
     * @param string $id Идентификатор строки.
     *
     * @return \Engsurvey\Models\UserGroupMemberships
     */
    public function setId(string $id): UserGroupMemberships
    {
        $this->id = $id;
        
        return $this;
    }


    /**
     * Возвращает идентификатор строки.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    
    /**
     * Устанавливает идентификатор группы пользователей.
     *
     * @param string $userGroupId
     *
     * @return \Engsurvey\Models\UserGroupMemberships
     */
    public function setUserGroupId(string $userGroupId): UserGroupMemberships
    {
        $this->userGroupId = $userGroupId;
        
        return $this;
    }


    /**
     * Возвращает идентификатор группы пользователей.
     *
     * @return string
     */
    public function getUserGroupId(): string
    {
        return $this->userGroupId;
    }
    
    
    /**
     * Возвращает экземпляр модели UserGroups на основе определенных отношений.
     *
     * @param mixed $parameters Параметры запроса.
     *
     * @return \Engsurvey\Models\UserGroups
     */
    public function getUserGroup($parameters = null): UserGroups
    {
        return $this->getRelated(__NAMESPACE__ . '\UserGroups', $parameters);
    }
    
    
    /**
     * Устанавливает идентификатор пользователя.
     *
     * @param string $userId Идентификатор пользователя.
     *
     * @return \Engsurvey\Models\UserGroupMemberships
     */
    public function setUserId(string $userId): UserGroupMemberships
    {
        $this->userId = $userId;
        
        return $this;
    }


    /**
     * Возвращает идентификатор пользователя.
     *
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
    
    
    /**
     * Возвращает экземпляр модели Users на основе определенных отношений.
     *
     * @param mixed $parameters Параметры запроса.
     *
     * @return \Engsurvey\Models\Users
     */
    public function getUser($parameters = null)
    {
        return $this->getRelated(__NAMESPACE__ . '\Users', $parameters);
    }

}
