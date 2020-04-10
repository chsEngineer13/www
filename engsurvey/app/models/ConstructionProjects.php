<?php
declare(strict_types=1);

namespace Engsurvey\Models;

/**
 * Проекты.
 */
class ConstructionProjects extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $constructionTypeId;

    /**
     * @var string
     */
    protected $customerId;
    
    /**
     * @var string
     */
    protected $technicalDirectorId;
    
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
            'code' => 'code',
            'name' => 'name',
            'construction_type_id' => 'constructionTypeId',
            'customer_id' => 'customerId',
            'technical_director_id' => 'technicalDirectorId',
            'report_link' => 'reportLink',
            'map_link' => 'mapLink',
            'comment' => 'comment',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель ConstructionProjects ссылается на таблицу "construction_projects".
    */
    public function getSource()
    {
        return 'construction_projects';
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
            'constructionTypeId',
            __NAMESPACE__ . '\ConstructionTypes',
            'id',
            ['alias' => 'ConstructionTypes']
        );

        $this->belongsTo(
            'customerId',
            __NAMESPACE__ . '\Organizations',
            'id',
            ['alias' => 'Customers']
        );
        
        $this->belongsTo(
            'technicalDirectorId',
            __NAMESPACE__ . '\Employees',
            'id',
            ['alias' => 'TechnicalDirector']
        );

        $this->hasMany(
            'id',
            __NAMESPACE__ . '\ConstructionSites',
            'constructionProjectId',
            ['alias' => 'ConstructionSites']
        );
        
        $this->hasMany(
            'id',
            __NAMESPACE__ . '\SurveyFacilities',
            'constructionProjectId',
            ['alias' => 'SurveyFacilities']
        );
    }


    /**
     * @param string $code
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function setCode(string $code)
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
     * @param string $name
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function setName(string $name): ConstructionProjects
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
     * @param string $constructionTypeId
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function setConstructionTypeId(?string $constructionTypeId): ?ConstructionProjects
    {
        $this->constructionTypeId = $constructionTypeId;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getConstructionTypeId(): ?string
    {
        return $this->constructionTypeId;
    }
    

    /**
     * @return \Engsurvey\Models\ConstructionTypes
     */
    public function getConstructionType($parameters = null)
    {
        return $this->getRelated('ConstructionTypes', $parameters);
    }


    /**
     * @param string $customerId
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function setCustomerId(string $customerId): ConstructionProjects
    {
        $this->customerId = $customerId;

        return $this;
    }


    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }
    
    
    /**
     * @return \Engsurvey\Models\Organizations
     */
    public function getCustomer($parameters = null)
    {
        return $this->getRelated('Customers', $parameters);
    }
    

    /**
     * Устанавливает уникальный идентификатор технического директора.
     *
     * @param string $technicalDirectorId
     *
     * @return Engsurvey\Models\ConstructionProjects
     */
    public function setTechnicalDirectorId(?string $technicalDirectorId): ?ConstructionProjects
    {
        $this->technicalDirectorId = $technicalDirectorId;

        return $this;
    }


    /**
     * Возвращает уникальный идентификатор технического директора.
     *
     * @return string
     */
    public function getTechnicalDirectorId(): ?string
    {
        return $this->technicalDirectorId;
    }
    
    
    /**
     * Возвращает экземпляр модели Employees на основе определенных отношений.
     *
     * @param mixed $parameters
     *
     * @return Engsurvey\Models\Employees
     */
    public function getTechnicalDirector($parameters = null)
    {
        return $this->getRelated('TechnicalDirector', $parameters);
    }
    

    /**
     * @param string $reportLink
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function setReportLink(?string $reportLink): ConstructionProjects
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
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function setMapLink(?string $mapLink): ConstructionProjects
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
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function setComment(?string $comment): ConstructionProjects
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
    public function getConstructionSites($parameters = null)
    {
        return $this->getRelated('ConstructionSites', $parameters);
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
