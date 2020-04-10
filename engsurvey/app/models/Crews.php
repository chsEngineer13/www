<?php
declare(strict_types=1);

namespace Engsurvey\Models;

/**
 * Бригады.
 */
class Crews extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $branchId;

    /**
     * @var string
     */
    protected $crewTypeId;

    /**
     * @var string
     */
    protected $crewName;

    /**
     * @var string|null
     */
    protected $headLastName;

    /**
     * @var string|null
     */
    protected $headFirstName;

    /**
     * @var string|null
     */
    protected $headMiddleName;

    /**
     * @var string|null
     */
    protected $headInitials;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var integer|null
     */
    protected $numberOfCrew;
    
    /**
     * @var string|null
     */
    protected $reportLink;


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
            'crew_type_id' => 'crewTypeId',
            'crew_name' => 'crewName',
            'head_last_name' => 'headLastName',
            'head_first_name' => 'headFirstName',
            'head_middle_name' => 'headMiddleName',
            'head_initials' => 'headInitials',
            'phone' => 'phone',
            'email' => 'email',
            'number_of_crew' => 'numberOfCrew',
            'report_link' => 'reportLink',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель Crews ссылается на таблицу "crews".
    */
    public function getSource()
    {
        return 'crews';
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
            'crewTypeId',
            __NAMESPACE__ . '\CrewTypes',
            'id',
            ['alias' => 'CrewTypes']
        );
    }


    /**
     * @param string $branchId
     * @return \Engsurvey\Models\Crews
     */
    public function setBranchId(string $branchId): Crews
    {
        $this->branchId = $branchId;

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
    public function getBranch($parameters = null)
    {
        return $this->getRelated('Branches', $parameters);
    }


    /**
     * @param string $crewTypeId
     * @return \Engsurvey\Models\Crews
     */
    public function setCrewTypeId(string $crewTypeId): Crews
    {
        $this->crewTypeId = $crewTypeId;

        return $this;
    }


    /**
     * @return string
     */
    public function getCrewTypeId(): string
    {
        return $this->crewTypeId;
    }


    /**
     * @return \Engsurvey\Models\CrewTypes
     */
    public function getCrewType($parameters = null)
    {
        return $this->getRelated('CrewTypes', $parameters);
    }


    /**
     * @param string $crewName
     * @return \Engsurvey\Models\Crews
     */
    public function setCrewName(string $crewName): Crews
    {
        $this->crewName = $crewName;

        return $this;
    }


    /**
     * @return string
     */
    public function getCrewName(): string
    {
        return $this->crewName;
    }


    /**
     * @param string|null $headLastName
     * @return \Engsurvey\Models\Crews
     */
    public function setHeadLastName(?string $headLastName): Crews
    {
        $this->headLastName = $headLastName;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getHeadLastName(): ?string
    {
        return $this->headLastName;
    }


    /**
     * @param string|null $headFirstName
     * @return \Engsurvey\Models\Crews
     */
    public function setHeadFirstName(?string $headFirstName): Crews
    {
        $this->headFirstName = $headFirstName;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getHeadFirstName(): ?string
    {
        return $this->headFirstName;
    }


    /**
     * @param string|null $headMiddleName
     * @return \Engsurvey\Models\Crews
     */
    public function setHeadMiddleName(?string $headMiddleName): Crews
    {
        $this->headMiddleName = $headMiddleName;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getHeadMiddleName(): ?string
    {
        return $this->headMiddleName;
    }


    /**
     * @param string|null $headInitials
     * @return \Engsurvey\Models\Crews
     */
    public function setHeadInitials(?string $headInitials): Crews
    {
        $this->headInitials = $headInitials;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getHeadInitials(): ?string
    {
        return $this->headInitials;
    }


    /**
     * @return string|null
     */
    public function getHeadFullName(): ?string
    {
        $headLastName = (string)$this->headLastName;
        $headFirstName = (string)$this->headFirstName;
        $headMiddleName = (string)$this->headMiddleName;

        $headFullName = trim($headLastName . " " . $headFirstName . " " . $headMiddleName);
        
        if (strlen ($headFullName) === 0) {
            $headFullName = null;
        }

        return $headFullName;
    }


    /**
     * @return string|null
     */
    public function getHeadShortName(): ?string
    {
        $headLastName = (string)$this->headLastName;
        $headInitials = (string)$this->headInitials;

        $headShortName = trim($headLastName . " " . $headInitials);
        if (strlen ($headShortName) === 0) {
            $headShortName = null;
        }

        return $headShortName;
    }


    /**
     * @param string|null $phone
     * @return \Engsurvey\Models\Crews
     */
    public function setPhone(?string $phone): Crews
    {
        $this->phone = $phone;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }


    /**
     * @param string|null $email
     * @return \Engsurvey\Models\Crews
     */
    public function setEmail(?string $email): Crews
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * @param int|null $numberOfCrew
     * @return \Engsurvey\Models\Crews
     */
     // TODO: При валидации данных из формы с установленным типом параметра бросается исключение.
    public function setNumberOfCrew(/*?int */$numberOfCrew): Crews
    {
        $this->numberOfCrew = $numberOfCrew;

        return $this;
    }


    /**
     * @return int
     */
     // TODO: Если указан возвращаемый тип, при валидации данных из формы возвращает '' 
     // и в результате бросается исключение.
    public function getNumberOfCrew()/*: ?int*/
    {
        return $this->numberOfCrew;
    }
    
    
    /**
     * @param string $reportLink
     * @return \Engsurvey\Models\Crews
     */
    public function setReportLink(?string $reportLink): Crews
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

}
