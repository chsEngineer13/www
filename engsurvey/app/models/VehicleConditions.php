<?php
namespace Engsurvey\Models;

/**
 * Состояния техники, используемой при производстве инженерных изысканий.
 */
class VehicleConditions extends ModelBaseOld
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
     * @return \Engsurvey\Models\VehicleConditions
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
     * @return \Engsurvey\Models\VehicleConditions
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
    * Модель VehicleConditions ссылается на таблицу "vehicle_conditions".
    */
    public function getSource()
    {
        return 'vehicle_conditions';
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
