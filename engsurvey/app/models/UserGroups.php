<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

class UserGroups extends ModelBase
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
    protected $systemName;
    
    /**
     * @var string
     */
    protected $name;
    
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
            'system_name' => 'systemName',
            'name' => 'name',
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
        $this->setSource('user_groups');
        
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
     * Устанавливает идентификатор группы пользователей.
     *
     * @param string $id Идентификатор группы пользователей.
     *
     * @return \Engsurvey\Models\UserGroups
     */
    public function setId(string $id): UserGroups
    {
        $this->id = $id;
        
        return $this;
    }


    /**
     * Возвращает идентификатор группы пользователей.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * Устанавливает системное имя группы пользователей.
     *
     * @param string $systemName Системное имя группы пользователей.
     *
     * @return \Engsurvey\Models\UserGroups
     */
    public function setSystemName(string $systemName): UserGroups
    {
        $this->systemName = mb_strtolower(trim($systemName));

        return $this;
    }


    /**
     * Возвращает системное имя группы пользователей.
     *
     * @return string
     */
    public function getSystemName(): string
    {
        return $this->systemName;
    }
    
    
    /**
     * Устанавливает наименование группы пользователей.
     *
     * @param string $name Наименование группы пользователей.
     *
     * @return \Engsurvey\Models\UserGroups
     */
    public function setName(string $name): UserGroups
    {
        $this->name = trim($name);

        return $this;
    }


    /**
     * Возвращает наименование группы пользователей.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    
    /**
     * Устанавливает описание группы пользователей.
     *
     * @param string $description Описание группы пользователей.
     *
     * @return \Engsurvey\Models\UserGroups
     */
    public function setDescription(string $description): UserGroups
    {
        $this->description = trim($description);

        return $this;
    }


    /**
     * Возвращает описание группы пользователей.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

}
