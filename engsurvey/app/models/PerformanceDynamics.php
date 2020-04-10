<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Models\Behavior\TimestampableOld;
use Engsurvey\Models\Behavior\SoftDeleteOld;
// TODO: В дальнейшем отказаться от использования PhalconSoftDelete.
use Phalcon\Mvc\Model\Behavior\SoftDelete as PhalconSoftDelete;

class PerformanceDynamics extends ModelBase
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
    protected $seqNumber;

    /**
     * @var string
     */
    protected $organizationId;

    /**
     * @var string
     */
    protected $initialDataReportLink;

    /**
     * @var string
     */
    protected $engineeringSurveyReportLink;

    /**
     * @var string
     */
    protected $laboratoryReportLink;

    /**
     * @var string
     */
    protected $territoryPlanningReportLink;


    /**
     * Устанавливает уникальный идентификатор строки.
     *
     * @param string $id Идентификатор пользователя.
     *
     * @return \Engsurvey\Models\PerformanceDynamics
     */
    public function setId(string $id): PerformanceDynamics
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор строки.
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
     * @param int $seqNumber Порядковый номер
     *
     * @return \Engsurvey\Models\PerformanceDynamics
     */
    public function setSeqNumber(int $seqNumber): PerformanceDynamics
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
     * Устанавливает уникальный идентификатор организации.
     *
     * @param string $organizationId Уникальный идентификатор организации.
     *
     * @return \Engsurvey\Models\PerformanceDynamics
     */
    public function setOrganizationId(string $organizationId): PerformanceDynamics
    {
        $this->organizationId = $organizationId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор организации.
     *
     * @return string
     */
    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }


    /**
     * Устанавливает ссылка на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @param string $reportLink Cсылка на отчет.
     *
     * @return \Engsurvey\Models\PerformanceDynamics
     */
    public function setInitialDataReportLink(string $reportLink = ''): PerformanceDynamics
    {
        $this->initialDataReportLink = $reportLink;

        return $this;
    }


    /**
     * Возвращает ссылку на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @return string
     */
    public function getInitialDataReportLink(): string
    {
        return $this->initialDataReportLink;
    }


    /**
     * Устанавливает ссылка на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @param string $reportLink Cсылка на отчет.
     *
     * @return \Engsurvey\Models\PerformanceDynamics
     */
    public function setEngineeringSurveyReportLink(string $reportLink = ''): PerformanceDynamics
    {
        $this->engineeringSurveyReportLink = $reportLink;

        return $this;
    }


    /**
     * Возвращает ссылку на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @return string
     */
    public function getEngineeringSurveyReportLink(): string
    {
        return $this->engineeringSurveyReportLink;
    }


    /**
     * Устанавливает ссылка на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @param string $reportLink Cсылка на отчет.
     *
     * @return \Engsurvey\Models\PerformanceDynamics
     */
    public function setLaboratoryReportLink(string $reportLink = ''): PerformanceDynamics
    {
        $this->laboratoryReportLink = $reportLink;

        return $this;
    }


    /**
     * Возвращает ссылку на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @return string
     */
    public function getLaboratoryReportLink(): string
    {
        return $this->laboratoryReportLink;
    }


    /**
     * Устанавливает ссылка на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @param string $reportLink Cсылка на отчет.
     *
     * @return \Engsurvey\Models\PerformanceDynamics
     */
    public function setTerritoryPlanningReportLink(string $reportLink = ''): PerformanceDynamics
    {
        $this->territoryPlanningReportLink = $reportLink;

        return $this;
    }


    /**
     * Возвращает ссылку на отчет о ходе выполнения работ по сбору исходных данных (СИД).
     *
     * @return string
     */
    public function getTerritoryPlanningReportLink(): string
    {
        return $this->territoryPlanningReportLink;
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
            'organization_id' => 'organizationId',
            'initial_data_report_link' => 'initialDataReportLink',
            'engineering_survey_report_link' => 'engineeringSurveyReportLink',
            'laboratory_report_link' => 'laboratoryReportLink',
            'territory_planning_report_link' => 'territoryPlanningReportLink',
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
        $this->setSource('performance_dynamics');

        $this->belongsTo('organizationId', __NAMESPACE__ . '\Organizations', 'id');

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
     * Возвращает экземпляр модели Organizations на основе определенных отношений.
     *
     * @param mixed $parameters Параметр запроса.
     *
     * @return \Engsurvey\Models\Organizations
     */
    public function getOrganization($parameters = null): Organizations
    {
        return $this->getRelated(__NAMESPACE__ . '\Organizations', $parameters);
    }


    /**
     * Возвращает последний порядковый номер.
     *
     * @return int|null
     */
    public static function getLastSeqNumber(): ?int
    {
        $lastSeqNumber = PerformanceDynamics::maximum(
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
        $nextSeqNumber = PerformanceDynamics::getLastSeqNumber();

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
        $records = PerformanceDynamics::find(
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
        $records = PerformanceDynamics::find(
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
