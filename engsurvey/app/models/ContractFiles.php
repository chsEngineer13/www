<?php
//declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

/**
 * Файлы договорной документации.ContractFiles
 */
class ContractFiles extends ModelBase
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
    protected $contractId;

    /**
     * @var integer
     */
    protected $seqNumber;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var integer
     */
    protected $size;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $description;


    /**
     * Устанавливает уникальный идентификатор файла.
     *
     * @param string $id Уникальный идентификатор файла.
     *
     * @return \Engsurvey\Models\ContractFiles
     */
    public function setId($id): ContractFiles
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор файла.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Устанавливает уникальный идентификатор договора.
     *
     * @param string $contractId Уникальный идентификатор договора.
     *
     * @return \Engsurvey\Models\ContractFiles
     */
    public function setContractId(string $contractId): ContractFiles
    {
        $this->contractId = $contractId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор статуса договора.
     *
     * @return string
     */
    public function getContractId(): string
    {
        return $this->contractId;
    }


    /**
     * Устанавливает порядковый номер.
     *
     * @param int $seqNumber Порядковый номер.
     *
     * @return \Engsurvey\Models\ContractFiles
     */
    public function setSeqNumber(int $seqNumber): ContractFiles
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
     * Устанавливает оригинальное имя файла.
     *
     * @param string $filename Имя файла.
     *
     * @return \Engsurvey\Models\ContractFiles
     */
    public function setFilename(string $filename): ContractFiles
    {
        $this->filename = $filename;

        return $this;
    }


    /**
     * Возвращает оригинальное имя файла.
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }


    /**
     * Устанавливает размер файла в байтах.
     *
     * @param int $size Размер файла в байтах.
     *
     * @return \Engsurvey\Models\ContractFiles
     */
    public function setSize(int $size): ContractFiles
    {
        $this->size = $size;

        return $this;
    }


    /**
     * Возвращает размер файла в байтах.
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }


    /**
     * Устанавливает MIME-тип файла.
     *
     * @param string $mimeType MIME-тип файла.
     *
     * @return \Engsurvey\Models\ContractFiles
     */
    public function setMimeType(string $mimeType): ContractFiles
    {
        $this->mimeType = trim($mimeType);

        return $this;
    }


    /**
     * Возвращает MIME-тип файла.
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }


    /**
     * Устанавливает описание файла.
     *
     * @param string $description Описание файла.
     *
     * @return \Engsurvey\Models\ContractFiles
     */
    public function setDescription(string $description): ContractFiles
    {
        $this->description = trim($description);

        return $this;
    }


    /**
     * Возвращает описание файла.
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
            'contract_id' => 'contractId',
            'seq_number' => 'seqNumber',
            'filename' => 'filename',
            'size' => 'size',
            'mime_type' => 'mimeType',
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
        $this->setSource('contract_files');
        
        $this->belongsTo('contractId', __NAMESPACE__ . '\Contracts', 'id');

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
     * Возвращает экземпляр модели Contracts на основе определенных отношений.
     *
     * @param mixed $parameters Параметр запроса.
     *
     * @return \Engsurvey\Models\Contracts
     */
    /*public function getContract($parameters = null): Contracts
    {
        return $this->getRelated(__NAMESPACE__ . '\Contracts', $parameters);
    }*/
    
    
    /**
     * Возвращает последний присвоенный порядковый в рамках договора.
     *
     * @param string $contractId Идентификатор договора.
     *
     * @return int|null
     */
    public static function getLastSeqNumber(string $contractId): ?int
    {
        $lastSeqNumber = ContractFiles::maximum(
            [
                "contractId='$contractId'",
                'column' => 'seqNumber'
            ]
        );

        return $lastSeqNumber;
    }


    /**
     * Возвращает следующий порядковый номер в рамках договора.
     *
     * @param string $contractId Идентификатор договора.
     *
     * @return int|null
     */
    public static function getNextSeqNumber(string $contractId): int
    {
        $nextSeqNumber = ContractFiles::getLastSeqNumber($contractId);

        if (is_null($nextSeqNumber)) {
            $nextSeqNumber = 1;
        } else {
            $nextSeqNumber++;
        }

        return $nextSeqNumber;
    }


    /**
     * Сдвигает порядковые номера записей, освобождая место для
     * порядкового номера новой или обновляемой записи в рамках договора.
     * Возвращает TRUE в случае успешного завершения
     * или FALSE в случае возникновения ошибки.
     *
     * @param string $contractId Идентификатор договора.
     * @param int $seqNumber Порядковый номер новой или обновляемой записи.
     *
     * @return bool
     */
    public static function shiftSeqNumbers(string $contractId, int $seqNumber): bool
    {
        // Отбор записей с порядковым номером равным или большим полученного,
        // с сортировкой по убыванию порядкового номера.
        $records = ContractFiles::find(
            [
                "contractId='$contractId' AND seqNumber >= $seqNumber",
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
     * Перенумеровывает порядковые номера записей в рамках договора.
     * Возвращает TRUE в случае успешного завершения
     * или FALSE в случае возникновения ошибки.
     *
     * @param string $contractId Идентификатор договора.
     *
     * @return bool
     */
    public static function renumberSeqNumbers(string $contractId): bool
    {
        // Получение записей с сортировкой по порядковому номеру.
        $records = ContractFiles::find(
            [
                "contractId='$contractId'",
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
    
    
    /**
     * Возвращает отформатированный размер файла.
     *
     * @return string
     */
    public function getFormattedSize(int $precision, string $decimalSeparator, string $thousandsSeparator): string
    {
        $size = $this->size;

        $formattedSize = '';
        
        // Варианты размера файла.
        $formats = array('байт', 'КБ', 'МБ', 'ГБ', 'ТБ');
        // Начальный формат размера в байтах.
        $format = 0;

        if ($size >= 1024) {

            while ($size >= 1024 && count($formats) != ++$format) {
                $size = round($size / 1024, $precision);
            }

            // Если число превышает максимальное значение,
            // необходимо добавить последний возможный
            // размер файла в массив еще раз.
            $formats[] = 'ТБ';

            $formattedSize = number_format($size , $precision, $decimalSeparator , $thousandsSeparator);
            $formattedSize = $formattedSize . ' ' . $formats[$format];

        } else {
            $formattedSize = (string)$size . ' байт';
        }

        return $formattedSize;
    }

}
