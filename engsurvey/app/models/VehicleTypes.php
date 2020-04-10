<?php
namespace Engsurvey\Models;

/**
 * Виды техники, используемой при производстве инженерных изысканий.
 */
class VehicleTypes extends ModelBaseOld
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
     * @return \Engsurvey\Models\VehicleTypes
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
     * @return \Engsurvey\Models\VehicleTypes
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
    * Модель VehicleTypes ссылается на таблицу "vehicle_types".
    */
    public function getSource()
    {
        return 'vehicle_types';
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
