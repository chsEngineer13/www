<?php
namespace Engsurvey\Models;

/**
 * Техника (вездеходы, буровые установки и т.д.),
 * используемая при производстве инженерных изысканий.
 */
class Vehicles extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $manufactureYear;

    /**
     * @var string
     */
    protected $vehicleTypeId;

    /**
     * @var string
     */
    protected $vehicleConditionId;

    /**
     * @var string
     */
    protected $branchId;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $comment;


    /**
     * @param string $name
     * @return \Engsurvey\Models\Vehicles
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
     * @param string $manufactureYear
     * @return \Engsurvey\Models\Vehicles
     */
    public function setManufactureYear($manufactureYear)
    {
        $this->manufactureYear = $manufactureYear;

        return $this;
    }


    /**
     * @return string
     */
    public function getManufactureYear()
    {
        return $this->manufactureYear;
    }


    /**
     * @param string $vehicleTypeId
     * @return \Engsurvey\Models\Vehicles
     */
    public function setVehicleTypeId($vehicleTypeId)
    {
        $this->vehicleTypeId = $vehicleTypeId;

        return $this;
    }


    /**
     * @return string
     */
    public function getVehicleTypeId()
    {
        return $this->vehicleTypeId;
    }


    /**
     * @param string $vehicleConditionId
     * @return \Engsurvey\Models\Vehicles
     */
    public function setVehicleConditionId($vehicleConditionId)
    {
        $this->vehicleConditionId = $vehicleConditionId;

        return $this;
    }


    /**
     * @return string
     */
    public function getVehicleConditionId()
    {
        return $this->vehicleConditionId;
    }


    /**
     * @param string $branchId
     * @return \Engsurvey\Models\Vehicles
     */
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;

        return $this;
    }


    /**
     * @return string
     */
    public function getBranchId()
    {
        return $this->branchId;
    }


    /**
     * @param string $location
     * @return \Engsurvey\Models\Vehicles
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }


    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }


    /**
     * @param string $comment
     * @return \Engsurvey\Models\Vehicles
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
            'name' => 'name',
            'manufacture_year' => 'manufactureYear',
            'vehicle_type_id' => 'vehicleTypeId',
            'vehicle_condition_id' => 'vehicleConditionId',
            'branch_id' => 'branchId',
            'location' => 'location',
            'comment' => 'comment',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель Vehicles ссылается на таблицу "vehicles".
    */
    public function getSource()
    {
        return 'vehicles';
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
            'vehicleTypeId',
            __NAMESPACE__ . '\VehicleTypes',
            'id',
            ['alias' => 'VehicleTypes']
        );

        $this->belongsTo(
            'vehicleConditionId',
            __NAMESPACE__ . '\VehicleConditions',
            'id',
            ['alias' => 'VehicleConditions']
        );

        $this->belongsTo(
            'branchId',
            __NAMESPACE__ . '\Branches',
            'id',
            ['alias' => 'Branches']
        );
    }


    /**
     * @return \Engsurvey\Models\VehicleConditions
     */
    public function getVehicleType($parameters = null)
    {
        return $this->getRelated('VehicleTypes', $parameters);
    }


    /**
     * @return \Engsurvey\Models\VehicleConditions
     */
    public function getVehicleCondition($parameters = null)
    {
        return $this->getRelated('VehicleConditions', $parameters);
    }


    /**
     * @return \Engsurvey\Models\Branchs
     */
    public function getBranch($parameters = null)
    {
        return $this->getRelated('Branchs', $parameters);
    }

}
