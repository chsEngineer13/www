<?php
declare(strict_types=1);

namespace Engsurvey\Models;

/**
 * Филиалы.
 */
class Branches extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $organizationId;

    /**
     * @var integer
     */
    protected $sequenceNumber;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $code;


    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        $parentСolumnMap = parent::columnMap();

        $columnMap = [
            'organization_id' => 'organizationId',
            'sequence_number' => 'sequenceNumber',
            'display_name' => 'displayName',
            'code' => 'code',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель Branches ссылается на таблицу "branches".
    */
    public function getSource(): string
    {
        return 'branches';
    }


    /**
     * Инициализация экземпляра модели в приложении.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->belongsTo(
            'organizationId', __NAMESPACE__ . '\Organizations', 'id',
            ['alias' => 'Organizations']
        );
    }


    public function beforeValidation()
    {
        parent::beforeValidation();

        // Если порядковый номер не установлен, его необходимо вычислить и установить.
        $sequenceNumber = $this->sequenceNumber;

        if (empty($sequenceNumber)) {
            $maxSequenceNumber = Branches::maximum(
                [
                    'column' => 'sequenceNumber',
                    'conditions' => "isDeleted = false"
                ]
            );

            // Вычисление и установка следующего  порядкового номера.
            if (!empty($maxSequenceNumber)) {
                $sequenceNumber = $maxSequenceNumber + 1;
            } else {
                $sequenceNumber = 1;
            }

            $this->sequenceNumber = $sequenceNumber;

        }
    }


    /**
     * @param string $organizationId
     * @return \Engsurvey\Models\Branches
     */
    public function setOrganizationId(string $organizationId): Branches
    {
        $this->organizationId = $organizationId;

        return $this;
    }


    /**
     * @return string
     */
    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }


    /**
     * @return \Engsurvey\Models\Organizations
     */
    public function getOrganization($parameters = null)
    {
        return $this->getRelated('Organizations', $parameters);
    }


    /**
     * @param integer $sequenceNumber
     * @return \Engsurvey\Models\Branches
     */
    public function setSequenceNumber(int $sequenceNumber): Branches
    {
        $this->sequenceNumber = $sequenceNumber;

        return $this;
    }


    /**
     * @return integer
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }


    /**
     * @param string $displayName
     * @return \Engsurvey\Models\Branches
     */
    public function setDisplayName(string $displayName): Branches
    {
        $this->displayName = $displayName;

        return $this;
    }


    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }


    /**
     * @param string $code
     * @return \Engsurvey\Models\Branches
     */
    public function setCode(string $code): Branches
    {
        $this->code = $code;

        return $this;
    }


    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }


    /**
     * @return int|null
     */
    public static function getLastSequenceNumber(): ?int
    {
        $maxSequenceNumber = Branches::maximum(
            [
                'column' => 'sequenceNumber'
            ]
        );

        return $maxSequenceNumber;
    }

}
