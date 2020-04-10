<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

/**
 * Статусы договоров.
 */
class ContractStatuses extends ModelBase
{
    use TimestampableOld;
    use SoftDeleteOld;
    
    // Статусы договоров:
    // Новый
    const CONTRACT_STATUS_NEW = 'new';
    // Действующий
    const CONTRACT_STATUS_ACTIVE = 'active';
    // Завершен
    const CONTRACT_STATUS_COMPLETD = 'completd';
    // Не заключен
    const CONTRACT_STATUS_NOT_SIGNED = 'not_signed';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var integer
     */
    protected $sequenceNumber;

    /**
     * @var string
     */
    protected $systemName;

    /**
     * @var string
     */
    protected $name;


    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'sequence_number' => 'sequenceNumber',
            'system_name' => 'systemName',
            'name' => 'name',
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
        $this->setSource('contract_statuses');

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
     * Устанавливает идентификатор статуса договора.
     *
     * @param string $id Идентификатор группы пользователей.
     *
     * @return \Engsurvey\Models\ContractStatuses
     */
    public function setId(string $id): ContractStatuses
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Возвращает идентификатор статуса договора.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * Устанавливает порядковый номер.
     *
     * @param int $sequenceNumber Порядковый номер.
     *
     * @return \Engsurvey\Models\ContractStatuses
     */
    public function setSequenceNumber(int $sequenceNumber): ContractStatuses
    {
        $this->sequenceNumber = $sequenceNumber;

        return $this;
    }


    /**
     * Возвращает порядковый номер.
     *
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }


    /**
     * Устанавливает системное имя статуса договора.
     *
     * @param string $systemName Системное имя статуса договора.
     *
     * @return \Engsurvey\Models\ContractStatuses
     */
    public function setSystemName(string $systemName): ContractStatuses
    {
        $this->systemName = mb_strtolower(trim($systemName));

        return $this;
    }


    /**
     * Возвращает системное имя статуса договора.
     *
     * @return string
     */
    public function getSystemName(): string
    {
        return $this->systemName;
    }
    
    
    /**
     * Устанавливает наименование статуса договора.
     *
     * @param string $name Наименование статуса договора.
     *
     * @return \Engsurvey\Models\ContractStatuses
     */
    public function setName(string $name): ContractStatuses
    {
        $this->name = trim($name);

        return $this;
    }


    /**
     * Возвращает наименование статуса договора.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}
