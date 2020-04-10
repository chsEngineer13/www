<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

class EmployeeGroups extends ModelBase
{
    use TimestampableOld;
    use SoftDeleteOld;
    
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var int
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
     * @var string
     */
    protected $description;


    /**
     * Устанавливает идентификатор группы сотрудников.
     *
     * @param  string $id
     *
     * @return \Engsurvey\Models\EmployeeGroups
     */
    public function setId($id): EmployeeGroups
    {
        $this->id = $id;
        
        return $this;
    }


    /**
     * Возвращает идентификатор группы сотрудников.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Устанавливает порядковый номер.
     *
     * @param int $seqNumber Порядковый номер.
     *
     * @return \Engsurvey\Models\EmployeeGroups
     */
    public function setSeqNumber(int $seqNumber): EmployeeGroups
    {
        $this->seqNumber = $seqNumber;

        return $this;
    }


    /**
     * Возвращает порядковый номер.
     *
     * @return int
     */
    public function getSeqNumber(): int
    {
        return $this->seqNumber;
    }


    /**
     * Устанавливает системное имя группы сотрудников.
     *
     * @param string $systemName Системное имя группы сотрудников
     *
     * @return \Engsurvey\Models\EmployeeGroups
     */
    public function setSystemName(string $systemName): EmployeeGroups
    {
        $this->systemName = mb_strtolower(trim($systemName));

        return $this;
    }


    /**
     * Возвращает системное имя группы сотрудников.
     *
     * @return string
     */
    public function getSystemName(): string
    {
        return $this->systemName;
    }
    
    
    /**
     * Устанавливает наименование группы сотрудников.
     *
     * @param string $name Наименование группы сотрудников
     *
     * @return \Engsurvey\Models\EmployeeGroups
     */
    public function setName(string $name): EmployeeGroups
    {
        $this->name = trim($name);

        return $this;
    }


    /**
     * Возвращает наименование группы сотрудников.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    
    /**
     * Устанавливает описание группы сотрудников.
     *
     * @param string $description Описание группы сотрудников
     *
     * @return \Engsurvey\Models\EmployeeGroups
     */
    public function setDescription(string $description): EmployeeGroups
    {
        $this->description = trim($description);

        return $this;
    }


    /**
     * Возвращает описание группы сотрудников.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    
    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'seq_number' => 'seqNumber',
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
        $this->setSchema('human_resources');
        $this->setSource('employee_groups');
        
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
     * Возвращает последний порядковый номер.
     *
     * @return int|null
     */
    public static function getLastSeqNumber(): ?int
    {
        $lastSeqNumber = EmployeeGroups::maximum(
            [
                'column' => 'seqNumber'
            ]
        );

        return $lastSeqNumber;
    }


    /**
     * Возвращает следующий порядковый номер.
     *
     * @return int|null
     */
    public static function getNextSeqNumber(): int
    {
        $nextSeqNumber = EmployeeGroups::getLastSeqNumber();

        if (is_null($nextSeqNumber)) {
            $nextSeqNumber = 1;
        } else {
            $nextSeqNumber++;
        }

        return $nextSeqNumber;
    }


    /**
     * Сдвигает порядковые номера записей, освобождая место
     * для порядкового номера новой или обновляемой записи.
     * Возвращает TRUE в случае успешного завершения
     * или FALSE в случае возникновения ошибки.
     *
     * @param  int $seqNumber Порядковый номер новой или обновляемой записи.
     *
     * @return bool
     */
    public static function shiftSeqNumbers(int $seqNumber): bool
    {
        // Отбор записей с порядковым номером равным или большим полученного,
        // с сортировкой по убыванию порядкового номера.
        $records = EmployeeGroups::find(
            [
                "seqNumber >= $seqNumber",
                'order' => 'seqNumber DESC',
            ]
        );

        // Увеличение порядкового номера на единицу
        // для отобранных записей.
        foreach ($records as $record) {
            $seqNumberOfRecord = $record->getSeqNumber();
            $record->setSeqNumber($seqNumberOfRecord + 1);

            if ($record->update() === false) {
                return false;
            }
        }

        return true;
    }


    /**
     * Перенумеровывает порядковые номера записей.
     * Возвращает TRUE в случае успешного завершения
     * или FALSE в случае возникновения ошибки.
     *
     * @return bool
     */
    public static function renumberSeqNumbers(): bool
    {
        // Получение записей с сортировкой по порядковому номеру.
        $records = EmployeeGroups::find(
            [
                'order' => 'seqNumber',
            ]
        );

        // Перенумерацию записей, порядковые номера которых
        // распологаются не по порядку.
        $currentSeqNumber = 1;
        foreach ($records as $record) {
            $seqNumber = $record->getSeqNumber();

            if ($seqNumber !== $currentSeqNumber) {
                $record->setSeqNumber($currentSeqNumber);

                if ($record->update() === false) {
                    return false;
                }
            }

            $currentSeqNumber++;
        }

        return true;
    }

}
