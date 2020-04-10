<?php
namespace Engsurvey\Models;

/**
 * Виды строительства.
 */
class ConstructionTypes extends ModelBaseOld
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
     * @param integer $sequenceNumber
     * @return \Engsurvey\Models\ConstructionTypes
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
     * @return \Engsurvey\Models\ConstructionTypes
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
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель ConstructionTypes ссылается на таблицу "construction_types".
    */
    public function getSource()
    {
        return 'construction_types';
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
