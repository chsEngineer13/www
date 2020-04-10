<?php
namespace Engsurvey\Models;

/**
 * Виды бригад.
 */
class CrewTypes extends ModelBaseOld
{
    /**
     * @var integer
     */
    protected $sequenceNumber;

    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $shortName;
    

    /**
     * @param integer $sequenceNumber
     * @return \Engsurvey\Models\CrewTypes
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
     * @param string $name
     * @return \Engsurvey\Models\CrewTypes
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    
    /**
     * @param string $shortName
     * @return \Engsurvey\Models\CrewTypes
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }


    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
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
            'sequence_number' => 'sequenceNumber',
            'name' => 'name',
            'short_name' => 'shortName',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель CrewTypes ссылается на таблицу "crew_types".
    */
    public function getSource()
    {
        return 'crew_types';
    }


    /**
     * Инициализация экземпляра модели в приложении.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
    }

}
