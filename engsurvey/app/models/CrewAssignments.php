<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Validators\DateTimeValidator;
use Engsurvey\Utils\Uuid;

/**
 * Распределение бригад по объектам.
 */
class CrewAssignments extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $branchId;

    /**
     * @var string
     */
    protected $crewId;

    /**
     * @var string
     */
    protected $constructionProjectId;

    /**
     * @var string
     */
    protected $constructionSiteId;

    /**
     * @var string
     */
    protected $startDate;

    /**
     * @var string
     */
    protected $endDate;

    /**
     * @var string|null
     */
    protected $comment;


    /**
     * Карта сопоставления колонок.
     *
     * @return array
     */
    public function columnMap()
    {
        $parentСolumnMap = parent::columnMap();

        $columnMap = [
            'branch_id' => 'branchId',
            'crew_id' => 'crewId',
            'construction_project_id' => 'constructionProjectId',
            'construction_site_id' => 'constructionSiteId',
            'start_date' => 'startDate',
            'end_date' => 'endDate',
            'comment' => 'comment',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель CrewAssignments ссылается на таблицу 'crew_assignments'.
    */
    public function getSource()
    {
        return 'crew_assignments';
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
            'branchId',
            __NAMESPACE__ . '\Branches',
            'id',
            ['alias' => 'Branches']
        );

        $this->belongsTo(
            'crewId',
            __NAMESPACE__ . '\Crews',
            'id',
            ['alias' => 'Crews']
        );

        $this->belongsTo(
            'constructionProjectId',
            __NAMESPACE__ . '\ConstructionProjects',
            'id',
            ['alias' => 'ConstructionProjects']
        );

        $this->belongsTo(
            'constructionSiteId',
            __NAMESPACE__ . '\ConstructionSites',
            'id',
            ['alias' => 'ConstructionSites']
        );
    }


    /**
     * @param  string $branchId
     * @return \Engsurvey\Models\CrewAssignments
     */
    public function setBranchId(string $branchId): CrewAssignments
    {
        if (Uuid::validate($branchId)) {
            $this->branchId = $branchId;
        } else {
            throw new \InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getBranchId(): string
    {
        return $this->branchId;
    }


    /**
     * @return \Engsurvey\Models\Branches
     */
    public function getBranch($parameters = null): Branches
    {
        return $this->getRelated('Branches', $parameters);
    }


    /**
     * @param  string $crewId
     * @return \Engsurvey\Models\CrewAssignments
     */
    public function setCrewId(string $crewId): CrewAssignments
    {
        if (Uuid::validate($crewId)) {
            $this->crewId = $crewId;
        } else {
            throw new \InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getCrewId(): string
    {
        return $this->crewId;
    }


    /**
     * @return \Engsurvey\Models\Crews
     */
    public function getCrew($parameters = null): Crews
    {
        return $this->getRelated('Crews', $parameters);
    }


    /**
     * @param  string $constructionProjectId
     * @return \Engsurvey\Models\CrewAssignments
     */
    public function setConstructionProjectId(string $constructionProjectId): CrewAssignments
    {
        if (Uuid::validate($constructionProjectId)) {
            $this->constructionProjectId = $constructionProjectId;
        } else {
            throw new \InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getConstructionProjectId(): string
    {
        return $this->constructionProjectId;
    }


    /**
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function getConstructionProject($parameters = null): ConstructionProjects
    {
        return $this->getRelated('ConstructionProjects', $parameters);
    }


    /**
     * @param  string $constructionSiteId
     * @return \Engsurvey\Models\CrewAssignments
     */
    public function setConstructionSiteId(string $constructionSiteId): CrewAssignments
    {
        if (Uuid::validate($constructionSiteId)) {
            $this->constructionSiteId = $constructionSiteId;
        } else {
            throw new \InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getConstructionSiteId(): string
    {
        return $this->constructionSiteId;
    }


    /**
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function getConstructionSite($parameters = null): ConstructionSites
    {
        return $this->getRelated('ConstructionSites', $parameters);
    }


    /**
     * @param  string $startDate Дата начала работ на объекте. Формат даты YYYY-MM-DD.
     * @return \Engsurvey\Models\CrewAssignments
     */
    public function setStartDate($startDate): CrewAssignments
    {
        if (DateTimeValidator::validate($startDate, 'Y-m-d')) {
            $this->startDate = $startDate;
        } else {
            throw new \InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string Дата в формате DD.YY.YYYY.
     */
    public function getStartDate()
    {
        $startDate = $this->startDate;
        
        $dt = \DateTime::createFromFormat('Y-m-d', $startDate);
        $startDate = $dt->format('d.m.Y');
        
        return $startDate;
    }


    /**
     * @param  string $format Формат даты, принимаемый функцией date().
     * @return string
     *
     * @link http://php.net/manual/ru/function.date.php
     */
    public function getFormattedStartDate(string $format): string
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $this->startDate);
        $formattedStartDate = $dateTime->format($format);

        return $formattedStartDate;
    }


    /**
     * @param string $endDate Дата завершения работ на объекте. Формат даты YYYY-MM-DD.
     * @return \Engsurvey\Models\CrewAssignments
     */
    public function setEndDate($endDate): CrewAssignments
    {
        if (DateTimeValidator::validate($endDate, 'Y-m-d')) {
            $this->endDate = $endDate;
        } else {
            throw new \InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getEndDate()
    {
        $endDate = $this->endDate;
        
        $dt = \DateTime::createFromFormat('Y-m-d', $endDate);
        $endDate = $dt->format('d.m.Y');
        
        return $endDate;
    }


    /**
     * @param  string $format Формат даты, принимаемый функцией date().
     * @return string
     *
     * @link http://php.net/manual/ru/function.date.php
     */
    public function getFormattedEndDate(string $format): string
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $this->endDate);
        $formattedEndDate = $dateTime->format($format);

        return $formattedEndDate;
    }


    /**
     * @param string|null $comment
     * @return \Engsurvey\Models\CrewAssignments
     */
    public function setComment(?string $comment): CrewAssignments
    {
        if (strlen(trim($comment)) > 0) {
            $this->comment = trim($comment);
        } else {
            $this->comment = null;
        }

        return $this;
    }


    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

}
