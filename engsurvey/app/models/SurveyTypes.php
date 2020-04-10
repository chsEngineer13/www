<?php
declare(strict_types=1);

namespace Engsurvey\Models;

/**
 * Виды бригад.
 */
class SurveyTypes extends ModelBaseOld
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
    * Модель SurveyTypes ссылается на таблицу 'survey_types'.
    */
    public function getSource()
    {
        return 'survey_types';
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
    

    /**
     * @param int $sequenceNumber
     * @return \Engsurvey\Models\SurveyTypes
     */
    public function setSequenceNumber(int $sequenceNumber): SurveyTypes
    {
        $this->sequenceNumber = $sequenceNumber;
        
        return $this;
    }
    

    /**
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }
    
    
    /**
     * @param string $name
     * @return \Engsurvey\Models\SurveyTypes
     */
    public function setName(string $name): SurveyTypes
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
    public function getName()//: string
    {
        return $this->name;
    }

    
    /**
     * @param string $shortName
     * @return \Engsurvey\Models\SurveyTypes
     */
    public function setShortName($shortName): SurveyTypes
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

}
