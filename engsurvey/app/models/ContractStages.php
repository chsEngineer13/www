<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\Timestampable;
use Engsurvey\Models\Behavior\SoftDelete;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;


/**
 * Этапы работ по договорам в соответствии с календарными планами.
 */
class ContractStages extends ModelBase
{
    use Timestampable;
    use SoftDelete;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $contractId;
    
    /**
     * @var string
     */
    protected $sectionNumber;

    /**
     * @var string
     */
    protected $sectionName;    

    /**
     * @var string
     */
    protected $stageNumber;

    /**
     * @var string
     */
    protected $stageName;

    /**
     * @var string
     */
    protected $constructionSiteId;

    /**
     * @var string|null
     */
    protected $startDate;

    /**
     * @var string|null
     */
    protected $endDate;

    /**
     * @var float|null
     */
    protected $costWithoutVat;

    /**
     * @var float|null
     */
    protected $vat;

    /**
     * @var float|null
     */
    protected $costWithVat;

    /**
     * @var string
     */
    protected $comment;


    /**
     * Устанавливает уникальный идентификатор этапа работ.
     *
     * @param string $id Уникальный идентификатор этапа работ.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setId($id): ContractStages
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор этапа работ.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * Устанавливает уникальный идентификатор договора.
     *
     * @param string $contractId Уникальный идентификатор договора.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setContractId(string $contractId): ContractStages
    {
        $this->contractId = $contractId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор договора.
     *
     * @return string
     */
    public function getContractId(): string
    {
        return $this->contractId;
    }
    
    
    /**
     * Устанавливает номер раздела календарного плана.
     *
     * @param string $sectionNumber Номер раздела календарного плана.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setSectionNumber(string $sectionNumber): ContractStages
    {
        $this->sectionNumber = $sectionNumber;

        return $this;
    }


    /**
     * Возвращает номер раздела календарного плана.
     *
     * @return string
     */
    public function getSectionNumber(): string
    {
        return $this->sectionNumber;
    }
    
    
    /**
     * Устанавливает наименование раздела календарного плана.
     *
     * @param string $sectionName Наименование раздела календарного плана.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setSectionName(string $sectionName): ContractStages
    {
        $this->sectionName = $sectionName;

        return $this;
    }


    /**
     * Возвращает наименование раздела календарного плана.
     *
     * @return string
     */
    public function getSectionName(): string
    {
        return $this->sectionName;
    }


    /**
     * Устанавливает номер этапа работ.
     *
     * @param string $stageNumber Номер этапа работ.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setStageNumber(string $stageNumber): ContractStages
    {
        $this->stageNumber = $stageNumber;

        return $this;
    }


    /**
     * Возвращает номер этапа работ.
     *
     * @return string
     */
    public function getStageNumber(): string
    {
        return $this->stageNumber;
    }


    /**
     * Устанавливает наименование этапа работ.
     *
     * @param string $stageName Наименование этапа работ.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setStageName(string $stageName): ContractStages
    {
        $this->stageName = $stageName;

        return $this;
    }


    /**
     * Возвращает наименование этапа работ.
     *
     * @return string
     */
    public function getStageName(): string
    {
        return $this->stageName;
    }


    /**
     * Устанавливает идентификатор участка работ.
     *
     * @param string|null $constructionSiteId Идентификатор участка работ.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setConstructionSiteId(?string $constructionSiteId): ContractStages
    {
        $this->constructionSiteId = $constructionSiteId;

        return $this;
    }


    /**
     * Возвращает идентификатор участка работ.
     *
     * @return string|null
     */
    public function getConstructionSiteId(): ?string
    {
        return $this->constructionSiteId;
    }


    /**
     * Устанавливает дату начала работ.
     *
     * @param string|null $startDate Дата начала работ.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setStartDate(?string $startDate): ContractStages
    {
        $this->startDate = $startDate;

        return $this;
    }


    /**
     * Возвращает дату начала работ.
     *
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }


    /**
     * Устанавливает дату окончания работ.
     *
     * @param string|null $endDate Дата окончания работ.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setEndDate(?string $endDate): ContractStages
    {
        $this->endDate = $endDate;

        return $this;
    }


    /**
     * Возвращает дату окончания работ.
     *
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }


    /**
     * Устанавливает стоимость работ без НДС.
     *
     * @param float|null $costWithoutVat Стоимость работ без НДС.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setCostWithoutVat(?float $costWithoutVat): ContractStages
    {
        $this->costWithoutVat = $costWithoutVat;

        return $this;
    }


    /**
     * Возвращает стоимость работ без НДС.
     *
     * @return float|null
     */
    public function getCostWithoutVat(): ?float
    {
        $costWithoutVat = $this->costWithoutVat;

        if (is_null($costWithoutVat)) {
            return null;
        }

        return (float)$costWithoutVat;
    }


    /**
     * Устанавливает величину НДС.
     *
     * @param float|null $vat НДС.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setVat(?float $vat): ContractStages
    {
        $this->vat = $vat;

        return $this;
    }


    /**
     * Возвращает величину НДС.
     *
     * @return float|null
     */
    public function getVat(): ?float
    {
        $vat = $this->vat;

        if (is_null($vat)) {
            return null;
        }

        return (float)$vat;
    }


    /**
     * Устанавливает стоимость работ с учетом НДС.
     *
     * @param float|null $costWithVat Стоимость работ с учетом НДС.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setCostWithVat(?float $costWithVat): ContractStages
    {
        $this->costWithVat = $costWithVat;

        return $this;
    }


    /**
     * Возвращает стоимость работ с учетом НДС.
     *
     * @return float|null
     */
    public function getCostWithVat(): ?float
    {
        $costWithVat = $this->costWithVat;

        if (is_null($costWithVat)) {
            return null;
        }

        return (float)$costWithVat;
    }


    /**
     * Устанавливает комментарий.
     *
     * @param string $comment Комментарий.
     *
     * @return \Engsurvey\Models\ContractStages
     */
    public function setComment(string $comment): ContractStages
    {
        $this->comment = $comment;

        return $this;
    }


    /**
     * Возвращает комментарий.
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }


    /**
     * Инициализация экземпляра модели в приложении.
     *
     * @return void
     */
    public function initialize()
    {
        $this->setSchema('engsurvey');
        $this->setSource('contract_stages');

        $this->belongsTo(
            'contractId',
            __NAMESPACE__ . '\Contracts',
            'id',
            [
                'alias' => 'Contract'
            ]
        );

        $this->belongsTo(
            'constructionSiteId',
             __NAMESPACE__ . '\ConstructionSites',
            'id',
            [
                'alias' => 'ConstructionSite',
                'foreignKey' => [
                    'allowNulls' => true,
                    'message' => 'constructionSiteId нет в модели ConstructionSites',
                ]
            ]
        );

        // TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
        $this->addBehavior(
            new PhalconSoftDelete(
                [
                    "field" => "deletedFlag",
                    "value" => true,
                ]
            )
        );
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
            'section_number' => 'sectionNumber',
            'section_name' => 'sectionName',
            'stage_number' => 'stageNumber',
            'stage_name' => 'stageName',
            'construction_site_id' => 'constructionSiteId',
            'start_date' => 'startDate',
            'end_date' => 'endDate',
            'cost_without_vat' => 'costWithoutVat',
            'vat' => 'vat',
            'cost_with_vat' => 'costWithVat',
            'comment' => 'comment',
            // Timestampable
            'created_date' => 'createdDate',
            'created_user_id' => 'createdUserId',
            'updated_date' => 'updatedDate',
            'updated_user_id' => 'updatedUserId',
            // SoftDelete
            'deleted_flag' => 'deletedFlag',
        ];
    }


    /**
     * Возвращает дату начала работ в соответствии с форматом.
     *
     * @param string $format.
     *
     * @return string
     */
    public function getFormattedStartDate(string $format): string
    {
        $startDate = $this->startDate;

        if (is_null($startDate)) {
            $startDate = '';
        } else {
            $dt = \DateTime::createFromFormat('Y-m-d', $startDate);
            $startDate = $dt->format($format);
        }

        return $startDate;
    }


    /**
     * Возвращает дату окончания работ в соответствии с форматом.
     *
     * @param string $format.
     *
     * @return string
     */
    public function getFormattedEndDate(string $format): string
    {
        $endDate = $this->endDate;

        if (is_null($endDate)) {
            $endDate = '';
        } else {
            $dt = \DateTime::createFromFormat('Y-m-d', $endDate);
            $endDate = $dt->format($format);
        }

        return $endDate;
    }


    /**
     * Возвращает стоимость работ без НДС в соответствии с форматом.
     *
     * @param string $precision Число знаков после запятой
     * @param string $decimalSeparator Pазделитель целой и дробной части
     * @param string $thousandsSeparator Разделитель тысяч
     *
     * @return string
     */
    public function getFormattedCostWithoutVat(int $precision, string $decimalSeparator, string $thousandsSeparator): string
    {
        $costWithoutVat = $this->costWithoutVat;

        if (is_null($costWithoutVat)) {
            $costWithoutVat = '';
        } else {
            $costWithoutVat = number_format((float)$costWithoutVat, $precision, $decimalSeparator, $thousandsSeparator);
        }

        return $costWithoutVat;
    }


    /**
     * Возвращает НДС в соответствии с форматом.
     *
     * @param string $precision Число знаков после запятой
     * @param string $decimalSeparator Pазделитель целой и дробной части
     * @param string $thousandsSeparator Разделитель тысяч
     *
     * @return string
     */
    public function getFormattedVat(int $precision, string $decimalSeparator, string $thousandsSeparator): string
    {
        $vat = $this->vat;

        if (is_null($vat)) {
            $vat = '';
        } else {
            $vat = number_format((float)$vat, $precision, $decimalSeparator, $thousandsSeparator);
        }

        return $vat;
    }


    /**
     * Возвращает стоимость работ с учетом НДС в соответствии с форматом.
     *
     * @param string $precision Число знаков после запятой
     * @param string $decimalSeparator Pазделитель целой и дробной части
     * @param string $thousandsSeparator Разделитель тысяч
     *
     * @return string
     */
    public function getFormattedCostWithVat(int $precision, string $decimalSeparator, string $thousandsSeparator): string
    {
        $costWithVat = $this->costWithVat;

        if (is_null($costWithVat)) {
            $costWithVat = '';
        } else {
            $costWithVat = number_format((float)$costWithVat, $precision, $decimalSeparator, $thousandsSeparator);
        }

        return $costWithVat;
    }

}
