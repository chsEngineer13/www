<?php
namespace Engsurvey\Models;

/**
 * Единицы измерения.
 */
class Units extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $symbol;


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
            'symbol' => 'symbol',
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель Units ссылается на таблицу "units".
    */
    public function getSource()
    {
        return 'units';
    }


    /**
     * @param string $name
     * @return \Engsurvey\Models\Units
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
     * @param string $symbol
     * @return \Engsurvey\Models\Units
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }


    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

}
