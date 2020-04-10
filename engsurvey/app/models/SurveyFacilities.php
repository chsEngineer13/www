<?php
namespace Engsurvey\Models;

use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;

/**
 * Объекты изысканий.
 */
class SurveyFacilities extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $constructionProjectId;

    /**
     * @var string
     */
    protected $constructionSiteId;

    /**
     * @var integer
     */
    protected $sequenceNumber;

    /**
     * @var string
     */
    protected $facilityName;

    /**
     * @var string
     */
    protected $facilityDesignation;

    /**
     * @var string
     */
    protected $facilityNumber;

    /**
     * @var string
     */
    protected $stageOfWorks;

    /**
     * @var string
     */
    protected $comment;


    /**
     * @param string $constructionProjectId
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setConstructionProjectId($constructionProjectId)
    {
        $this->constructionProjectId = $constructionProjectId;

        return $this;
    }


    /**
     * @return string
     */
    public function getConstructionProjectId()
    {
        return $this->constructionProjectId;
    }


    /**
     * @param string $constructionSiteId
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setConstructionSiteId($constructionSiteId)
    {
        $this->constructionSiteId = $constructionSiteId;

        return $this;
    }


    /**
     * @return string
     */
    public function getConstructionSiteId()
    {
        return $this->constructionSiteId;
    }


    /**
     * @param integer $sequenceNumber
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;

        return $this;
    }


    /**
     * @return integer
     */
    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }


    /**
     * @param string $facilityName
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setFacilityName($facilityName)
    {
        $this->facilityName = $facilityName;

        return $this;
    }


    /**
     * @return string
     */
    public function getFacilityName()
    {
        return $this->facilityName;
    }


    /**
     * @param string $facilityDesignation
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setFacilityDesignation($facilityDesignation)
    {
        $this->facilityDesignation = $facilityDesignation;

        return $this;
    }


    /**
     * @return string
     */
    public function getFacilityDesignation()
    {
        return $this->facilityDesignation;
    }


    /**
     * @param string $facilityNumber
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setFacilityNumber($facilityNumber)
    {
        $this->facilityNumber = $facilityNumber;

        return $this;
    }


    /**
     * @return string
     */
    public function getFacilityNumber()
    {
        return $this->facilityNumber;
    }


    /**
     * @param string $stageOfWorks
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setStageOfWorks($stageOfWorks)
    {
        $this->stageOfWorks = $stageOfWorks;

        return $this;
    }


    /**
     * @return string
     */
    public function getStageOfWorks()
    {
        return $this->stageOfWorks;
    }


    /**
     * @param string $comment
     * @return \Engsurvey\Models\SurveyFacilities
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }


    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


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
            'construction_site_id' => 'constructionSiteId',
            'sequence_number' => 'sequenceNumber',
            'facility_name' => 'facilityName',
            'facility_designation' => 'facilityDesignation',
            'facility_number' => 'facilityNumber',
            'stage_of_works' => 'stageOfWorks',
            'comment' => 'comment',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель SurveyFacilities ссылается на таблицу "survey_facilities".
    */
    public function getSource()
    {
        return 'survey_facilities';
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
            'constructionSiteId',
            __NAMESPACE__ . '\ConstructionSites',
            'id',
            ['alias' => 'ConstructionSites']
        );
    }


    /**
     * @return \Engsurvey\Models\ConstructionProjects
     */
    public function getConstructionProject($parameters = null)
    {
        return $this->getRelated('ConstructionProjects', $parameters);
    }


    /**
     * @return \Engsurvey\Models\ConstructionSites
     */
    public function getConstructionSite($parameters = null)
    {
        return $this->getRelated('ConstructionSites', $parameters);
    }


    /**
     * Возвращает последний порядковый номер объектов изысканий
     * в пределах участка работ.
     *
     * @param integer $constructionSiteId
     * @return integer
     */
    public static function getLastSequenceNumber($constructionSiteId)
    {
        if (!Uuid::validate($constructionSiteId)) {
            throw new EngsurveyException('Недопустимое значение аргумента функции. Ожидается UUID.');
        }

        $lastSequenceNumber = SurveyFacilities::count(
            [
                'column' => 'sequenceNumber',
                'conditions' => "constructionSiteId = '$constructionSiteId'",
            ]
        );

        return $lastSequenceNumber;
    }

}
