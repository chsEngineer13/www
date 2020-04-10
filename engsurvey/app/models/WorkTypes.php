<?php
declare(strict_types=1);

namespace Engsurvey\Models;

use Engsurvey\Utils\Uuid;

/**
 * Виды работ.
 */
class WorkTypes extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $surveyTypeId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var string
     */
    protected $unitId;

    /**
     * @var float
     */
    protected $productionRate;

    /**
     * @var int
     */
    protected $numderOfEngineers;

    /**
     * @var int
     */
    protected $numderOfWorkers;

    /**
     * @var int
     */
    protected $numderOfDrivers;

    /**
     * @var int
     */
    protected $numderOfDrillers;

    /**
     * @var float
     */
    protected $zfTaiga;

    /**
     * @var float
     */
    protected $zfForestTundra;

    /**
     * @var float
     */
    protected $zfTundra;

    /**
     * @var float
     */
    protected $zfForestSteppe;

    /**
     * @var float
     */
    protected $sfSummer;

    /**
     * @var float
     */
    protected $sfAutumnSpring;

    /**
     * @var float
     */
    protected $sfWinter;

    /**
     * @var string
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
            'survey_type_id' => 'surveyTypeId',
            'name' => 'name',
            'short_name' => 'shortName',
            'unit_id' => 'unitId',
            'production_rate' => 'productionRate',
            'numder_of_engineers' => 'numderOfEngineers',
            'numder_of_workers' => 'numderOfWorkers',
            'numder_of_drivers' => 'numderOfDrivers',
            'numder_of_drillers' => 'numderOfDrillers',
            'zf_taiga' => 'zfTaiga',
            'zf_forest_tundra' => 'zfForestTundra',
            'zf_tundra' => 'zfTundra',
            'zf_forest_steppe' => 'zfForestSteppe',
            'sf_summer' => 'sfSummer',
            'sf_autumn_spring' => 'sfAutumnSpring',
            'sf_winter' => 'sfWinter',
            'comment' => 'comment',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }
    

    /**
    * Модель WorkTypes ссылается на таблицу 'work_types'.
    */
    public function getSource()
    {
        return 'work_types';
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
            'surveyTypeId',
            __NAMESPACE__ . '\SurveyTypes',
            'id',
            ['alias' => 'SurveyTypes']
        );

        $this->belongsTo(
            'unitId',
            __NAMESPACE__ . '\Units',
            'id',
            ['alias' => 'Units']
        );
    }


    /**
     * @param  string $surveyTypeId
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setSurveyTypeId(string $surveyTypeId): WorkTypes
    {
        if (Uuid::validate($surveyTypeId)) {
            $this->surveyTypeId = $surveyTypeId;
        } else {
            throw new InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getSurveyTypeId(): string
    {
        return $this->surveyTypeId;
    }
    
    
    /**
     * @return \Engsurvey\Models\SurveyTypes
     */
    public function getSurveyType($parameters = null): SurveyTypes
    {
        return $this->getRelated('SurveyTypes', $parameters);
    }


    /**
     * @param string $name
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setName(string $name): WorkTypes
    {
        if (strlen(trim($name)) > 0) {
            $this->name = trim($name);
        } else {
            throw new InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

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
     * @param string $shortName
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setShortName(string $shortName): WorkTypes
    {
        if (strlen(trim($shortName)) > 0) {
            $this->shortName = trim($shortName);
        } else {
            throw new InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }
    
    
    /**
     * @param  string $unitId
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setUnitId(string $unitId): WorkTypes
    {
        if (Uuid::validate($unitId)) {
            $this->unitId = $unitId;
        } else {
            throw new InvalidArgumentException('Недопустимое значение аргумента функции.');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getUnitId(): string
    {
        return $this->unitId;
    }
    
    
    /**
     * @return \Engsurvey\Models\Units
     */
    public function getUnit($parameters = null): Units
    {
        return $this->getRelated('Units', $parameters);
    }
    
    
    /**
     * @param  float $productionRate
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setProductionRate(float $productionRate): WorkTypes
    {
        $this->productionRate = $productionRate;

        return $this;
    }


    /**
     * @return float
     */
    public function getProductionRate(): float
    {
        return (float)$this->productionRate;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedProductionRate(
        int $decimals = 2, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $productionRate = (float)$this->productionRate;
        $formattedProductionRate = number_format($productionRate, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedProductionRate;
    }
    
    
    /**
     * @param  int $numderOfEngineers
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setNumderOfEngineers(int $numderOfEngineers): WorkTypes
    {
        $this->numderOfEngineers = $numderOfEngineers;

        return $this;
    }


    /**
     * @return float
     */
    public function getNumderOfEngineers(): int
    {
        return $this->numderOfEngineers;
    }


    /**
     * @param  int $numderOfWorkers
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setNumderOfWorkers(int $numderOfWorkers): WorkTypes
    {
        $this->numderOfWorkers = $numderOfWorkers;

        return $this;
    }


    /**
     * @return float
     */
    public function getNumderOfWorkers(): int
    {
        return $this->numderOfWorkers;
    }


    /**
     * @param  int $numderOfDrivers
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setNumderOfDrivers(int $numderOfDrivers): WorkTypes
    {
        $this->numderOfDrivers = $numderOfDrivers;

        return $this;
    }


    /**
     * @return float
     */
    public function getNumderOfDrivers(): int
    {
        return $this->numderOfDrivers;
    }


    /**
     * @param  int $numderOfDrillers
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setNumderOfDrillers(int $numderOfDrillers): WorkTypes
    {
        $this->numderOfDrillers = $numderOfDrillers;

        return $this;
    }


    /**
     * @return float
     */
    public function getNumderOfDrillers(): int
    {
        return $this->numderOfDrillers;
    }
    
    
    /**
     * @param  float $zfTaiga
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setZfTaiga(float $zfTaiga): WorkTypes
    {
        $this->zfTaiga = $zfTaiga;

        return $this;
    }


    /**
     * @return float
     */
    public function getZfTaiga(): float
    {
        return (float)$this->zfTaiga;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedZfTaiga(
        int $decimals = 1, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $zfTaiga = (float)$this->zfTaiga;
        $formattedZfTaiga = number_format($zfTaiga, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedZfTaiga;
    }


    /**
     * @param  float $zfForestTundra
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setZfForestTundra(float $zfForestTundra): WorkTypes
    {
        $this->zfForestTundra = $zfForestTundra;

        return $this;
    }


    /**
     * @return float
     */
    public function getZfForestTundra(): float
    {
        return (float)$this->zfForestTundra;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedZfForestTundra(
        int $decimals = 1, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $zfForestTundra = (float)$this->zfForestTundra;
        $formattedZfForestTundra = number_format($zfForestTundra, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedZfForestTundra;
    }


    /**
     * @param  float $zfTundra
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setZfTundra(float $zfTundra): WorkTypes
    {
        $this->zfTundra = $zfTundra;

        return $this;
    }


    /**
     * @return float
     */
    public function getZfTundra(): float
    {
        return (float)$this->zfTundra;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedZfTundra(
        int $decimals = 1, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $zfTundra = (float)$this->zfTundra;
        $formattedZfTundra = number_format($zfTundra, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedZfTundra;
    }


    /**
     * @param  float $zfForestSteppe
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setZfForestSteppe(float $zfForestSteppe): WorkTypes
    {
        $this->zfForestSteppe = $zfForestSteppe;

        return $this;
    }


    /**
     * @return float
     */
    public function getZfForestSteppe(): float
    {
        return (float)$this->zfForestSteppe;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedZfForestSteppe(
        int $decimals = 1, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $zfForestSteppe = (float)$this->zfForestSteppe;
        $formattedZfForestSteppe = number_format($zfForestSteppe, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedZfForestSteppe;
    }


    /**
     * @param  float $sfSummer
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setSfSummer(float $sfSummer): WorkTypes
    {
        $this->sfSummer = $sfSummer;

        return $this;
    }


    /**
     * @return float
     */
    public function getSfSummer(): float
    {
        return (float)$this->sfSummer;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedSfSummer(
        int $decimals = 1, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $sfSummer = (float)$this->sfSummer;
        $formattedSfSummer = number_format($sfSummer, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedSfSummer;
    }


    /**
     * @param  float $sfAutumnSpring
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setSfAutumnSpring(float $sfAutumnSpring): WorkTypes
    {
        $this->sfAutumnSpring = $sfAutumnSpring;

        return $this;
    }


    /**
     * @return float
     */
    public function getSfAutumnSpring(): float
    {
        return (float)$this->sfAutumnSpring;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedSfAutumnSpring(
        int $decimals = 1, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $sfAutumnSpring = (float)$this->sfAutumnSpring;
        $formattedSfAutumnSpring = number_format($sfAutumnSpring, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedSfAutumnSpring;
    }


    /**
     * @param  float $sfWinter
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setSfWinter(float $sfWinter): WorkTypes
    {
        $this->sfWinter = $sfWinter;

        return $this;
    }


    /**
     * @return float
     */
    public function getSfWinter(): float
    {
        return (float)$this->sfWinter;
    }
    
    
    /**
     * @param  int    $decimals Число знаков после запятой. 
     * @param  string $decimalРoint Разделитель дробной части. 
     * @param  string $thousandsSeparator Разделитель тысяч.
     * @return string
     */
    public function getFormattedSfWinter(
        int $decimals = 1, 
        string $decimalРoint = ',', 
        string $thousandsSeparator = ''): string
    {
        $sfWinter = (float)$this->sfWinter;
        $formattedSfWinter = number_format($sfWinter, $decimals, $decimalРoint, $thousandsSeparator);

        return $formattedSfWinter;
    }


    /**
     * @param string $comment
     * @return \Engsurvey\Models\WorkTypes
     */
    public function setComment(?string $comment): WorkTypes
    {
        if (strlen(trim($comment)) > 0) {
            $this->comment = trim($comment);
        } else {
            $this->comment = null;
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

}
