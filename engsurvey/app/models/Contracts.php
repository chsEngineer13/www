<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

/**
 * Сведения о договорах.
 */
class Contracts extends ModelBase
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
    protected $contractNumber;
    
    /**
     * @var string
     */
    protected $supplementalAgreementNumber;

    /**
     * @var string|null
     */
    protected $signatureDate;

    /**
     * @var string
     */
    protected $subjectOfContract;

    /**
     * @var string
     */
    protected $constructionProjectId;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string|null
     */
    protected $branchId;

    /**
     * @var float|null
     */
    protected $contractCost;

    /**
     * @var string
     */
    protected $contractStatusId;

    /**
     * @var string
     */
    protected $comment;


    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'contract_number' => 'contractNumber',
            'supplemental_agreement_number' => 'supplementalAgreementNumber',
            'signature_date' => 'signatureDate',
            'subject_of_contract' => 'subjectOfContract',
            'construction_project_id' => 'constructionProjectId',
            'customer_id' => 'customerId',
            'branch_id' => 'branchId',
            'contract_cost' => 'contractCost',
            'contract_status_id' => 'contractStatusId',
            'comment' => 'comment',
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
        $this->setSource('contracts');

        $this->belongsTo(
            'constructionProjectId', __NAMESPACE__ . '\ConstructionProjects', 'id',
            ['alias' => 'ConstructionProject']
        );

        $this->belongsTo(
            'customerId', __NAMESPACE__ . '\Organizations', 'id',
            ['alias' => 'Customer']
        );

        $this->belongsTo(
            'branchId', __NAMESPACE__ . '\Branches', 'id',
            ['alias' => 'Branch']
        );

        $this->belongsTo(
            'contractStatusId', __NAMESPACE__ . '\ContractStatuses', 'id',
            ['alias' => 'ContractStatus']
        );

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
     * Устанавливает идентификатор договора.
     *
     * @param string $id Идентификатор договора.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setId(string $id): Contracts
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Возвращает идентификатор договора.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * Устанавливает номер договора.
     *
     * @param string $contractNumber Номер договора.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setContractNumber(string $contractNumber): Contracts
    {
        $this->contractNumber = $contractNumber;

        return $this;
    }


    /**
     * Возвращает номер договора.
     *
     * @return string
     */
    public function getContractNumber(): string
    {
        return $this->contractNumber;
    }
    
    
    /**
     * Устанавливает номер дополнительного соглашения.
     *
     * @param string $supplementalAgreementNumber Номер дополнительного соглашения.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setSupplementalAgreementNumber(string $supplementalAgreementNumber): Contracts
    {
        $this->supplementalAgreementNumber = $supplementalAgreementNumber;

        return $this;
    }


    /**
     * Возвращает номер дополнительного соглашения.
     *
     * @return string
     */
    public function getSupplementalAgreementNumber(): string
    {
        return $this->supplementalAgreementNumber;
    }


    /**
     * Устанавливает дату подписания договора.
     *
     * @param string $signatureDate Дата подписания договора в формате 'YYYY-MM-DD'.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setSignatureDate(?string $signatureDate): Contracts
    {
        $this->signatureDate = $signatureDate;

        return $this;
    }


    /**
     * Возвращает дату подписания договора в формате 'YYYY-MM-DD'.
     *
     * @return string|null
     */
    public function getSignatureDate(): ?string
    {
        return $this->signatureDate;
    }


    /**
     * Возвращает дату подписания договора в соответствии с форматом.
     *
     * @param string $format.
     *
     * @return string|null
     */
    public function getFormattedSignatureDate(string $format): ?string
    {
        $signatureDate = $this->signatureDate;

        if (!is_null($signatureDate)) {
            $dt = \DateTime::createFromFormat('Y-m-d', $signatureDate);
            $signatureDate = $dt->format($format);
        }

        return $signatureDate;
    }


    /**
     * Устанавливает предмет договора.
     *
     * @param string $subjectOfContract Предмет договора.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setSubjectOfContract(string $subjectOfContract): Contracts
    {
        $this->subjectOfContract = trim($subjectOfContract);

        return $this;
    }


    /**
     * Возвращает предмет договора.
     *
     * @return string
     */
    public function getSubjectOfContract(): string
    {
        return $this->subjectOfContract;
    }


    /**
     * Устанавливает уникальный идентификатор объекта строительства (стройки).
     *
     * @param string $constructionProjectId Уникальный идентификатор объекта строительства.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setConstructionProjectId(string $constructionProjectId)
    {
        $this->constructionProjectId = $constructionProjectId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор объекта строительства (стройки).
     *
     * @return string
     */
    public function getConstructionProjectId(): string
    {
        return $this->constructionProjectId;
    }


    /**
     * Устанавливает уникальный идентификатор заказчика.
     *
     * @param string $customerId Уникальный идентификатор заказчика.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setCustomerId(string $customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор заказчика.
     *
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }


    /**
     * Устанавливает уникальный идентификатор ответственного филиала.
     *
     * @param string $branchId Уникальный идентификатор ответственного филиала.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setBranchId(?string $branchId)
    {
        $this->branchId = $branchId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор ответственного филиала.
     *
     * @return string|null
     */
    public function getBranchId(): ?string
    {
        return $this->branchId;
    }


    /**
     * Устанавливает стоимость работ по договору.
     *
     * @param string $contractCost Cтоимость работ по договору.
     *
     * @return \Engsurvey\Models\Contracts
     */
    /*public function setContractCost(?string $contractCost)
    {
        if (is_null($contractCost) || $contractCost === '') {
            $this->contractCost = null;
        } else {
            $contractCost = trim($contractCost);
            $contractCost = str_replace(' ', '', $contractCost);
            $contractCost = str_replace(',', '.', $contractCost);
            $this->contractCost = $contractCost;
        }

        return $this;
    }*/


    /**
     * Возвращает стоимость работ по договору.
     *
     * @return string|nul
     */
    /*public function getContractCost(): ?string
    {
        $contractCost = $this->contractCost;

        if (!is_null($contractCost)) {
             $contractCost = number_format($contractCost, 2, ',', ' ');
        }

        return $contractCost;
    }*/
    
    
    /**
     * Устанавливает стоимость работ по договору.
     *
     * @param string $contractCost Cтоимость работ по договору.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setContractCost(?string $contractCost)
    {
        $this->contractCost = $contractCost;

        return $this;
    }
    
    
    /**
     * Возвращает стоимость работ по договору.
     *
     * @return string|nul
     */
    public function getContractCost(): ?string
    {
        return $this->contractCost;
    }
    
    
    /**
     * Возвращает стоимость работ по договору в соответствии с форматом.
     *
     * @param string $precision Число знаков после запятой
     * @param string $decimalSeparator Pазделитель целой и дробной части
     * @param string $thousandsSeparator Разделитель тысяч
     *
     * @return string
     */
    public function getFormattedContractCost(int $precision, string $decimalSeparator, string $thousandsSeparator): string
    {
        $contractCost = $this->contractCost;

        if (is_null($contractCost)) {
            $contractCost = '';
        } else {
            $contractCost = number_format((float)$contractCost, $precision, $decimalSeparator, $thousandsSeparator);
        }

        return $contractCost;
    }


    /**
     * Устанавливает уникальный идентификатор статуса договора.
     *
     * @param string $contractStatusId Уникальный идентификатор статуса договора.
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setContractStatusId(string $contractStatusId): Contracts
    {
        $this->contractStatusId = $contractStatusId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор статуса договора.
     *
     * @return string
     */
    public function getContractStatusId(): string
    {
        return $this->contractStatusId;
    }


    /**
     * Устанавливает комментарий.
     *
     * @param string $comment Комментарий
     *
     * @return \Engsurvey\Models\Contracts
     */
    public function setComment(string $comment): Contracts
    {
        $this->comment = trim($comment);

        return $this;
    }


    /**
     * Возвращает комментарий.
     *
     * @param string $comment Комментарий
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

}
