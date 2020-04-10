<?php
declare(strict_types=1);

namespace Engsurvey\Models;

/**
 * Участки работ.
 */
class ConstructionSites extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $constructionProjectId;

    /**
     * @var string
     */
    protected $siteNumber;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $chiefProjectEngineerId;

    /**
     * @var string|null
     */
    protected $reportLink;

    /**
     * @var string|null
     */
    protected $mapLink;

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
            'construction_project_id' => 'constructionProjectId',
            'site_number' => 'siteNumber',
            'name' => 'name',
            'chief_project_engineer_id' => 'chiefProjectEngineerId',
            'report_link' => 'reportLink',
            'map_link' => 'mapLink',
            'comment' => 'comment',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель ConstructionSites ссылается на таблицу "construction_sites".
    */
    public function getSource()
    {
        return 'construction_sites';
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
            'constructionProjectId',
            __NAMESPACE__ . '\ConstructionProjects',
            'id',
            ['alias' => 'ConstructionProjects']
        );

        $this->belongsTo(
            'chiefProjectEngineerId',
            __NAMESPACE__ . '\Employees',
            'id',
            ['alias' => 'ChiefProjectEngineer']
        );

        $this->hasMany(
            'id',
            __NAMESPACE__ . '\SurveyFacilities',
            'constructionSiteId',
            ['alias' => 'SurveyFacilities']
        );
    }


    /**
     * @param string $constructionProjectId
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function setConstructionProjectId(string $constructionProjectId): ConstructionSites
    {
        $this->constructionProjectId = $constructionProjectId;

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
    public function getConstructionProject($parameters = null)
    {
        return $this->getRelated('ConstructionProjects', $parameters);
    }


    /**
     * @param string $siteNumber
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function setSiteNumber(string $siteNumber): ConstructionSites
    {
        $this->siteNumber = $siteNumber;

        return $this;
    }


    /**
     * @return string
     */
    public function getSiteNumber(): string
    {
        return $this->siteNumber;
    }


    /**
     * @param string $name
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function setName(string $name): ConstructionSites
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    

    /**
     * Устанавливает уникальный идентификатор главного инженера проекта.
     *
     * @param string $chiefProjectEngineerId
     *
     * @return Engsurvey\Models\ConstructionSites
     */
    public function setChiefProjectEngineerId(?string $chiefProjectEngineerId): ?ConstructionSites
    {
        $this->chiefProjectEngineerId = $chiefProjectEngineerId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор главного инженера проекта.
     *
     * @return string
     */
    public function getChiefProjectEngineerId(): ?string
    {
        return $this->chiefProjectEngineerId;
    }
    
    
    /**
     * Возвращает экземпляр модели Employees на основе определенных отношений.
     *
     * @param mixed $parameters
     *
     * @return Engsurvey\Models\Employees
     */
    public function getChiefProjectEngineer($parameters = null)
    {
        return $this->getRelated('ChiefProjectEngineer', $parameters);
    }
    

    /**
     * @param string|null $reportLink
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function setReportLink(?string $reportLink): ConstructionSites
    {
        $this->reportLink = $reportLink;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getReportLink(): ?string
    {
        return $this->reportLink;
    }
    
    
    /**
     * @param string $mapLink
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function setMapLink(?string $mapLink): ConstructionSites
    {
        $this->mapLink = $mapLink;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getMapLink(): ?string
    {
        return $this->mapLink;
    }


    /**
     * @param string $comment
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function setComment(?string $comment): ConstructionSites
    {
        $this->comment = $comment;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    
    /**
     * @param string $parameters Параметры запроса
     * @return \Phalcon\Mvc\Model\Resultset\Simple
     */
    public function getSurveyFacilities($parameters = null)
    {
        return $this->getRelated('SurveyFacilities', $parameters);
    }

}
