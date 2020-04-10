<?php
namespace Engsurvey\Models;

/**
 * Организации.
 */
class Organizations extends ModelBaseOld
{
    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var string|null
     */
    protected $fullName;

    /**
     * @var string|null
     */
    protected $additionalInfo;


    /**
     * @param string $displayName
     * @return \Engsurvey\Models\Organizations
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }


    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }


    /**
     * @param string $shortName
     * @return \Engsurvey\Models\Organizations
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
     * @param string $fullName
     * @return \Engsurvey\Models\Organizations
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getFullName()
    {
        return $this->fullName;
    }


    /**
     * @param string $additionalInfo
     * @return \Engsurvey\Models\Organizations
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
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
            'display_name' => 'displayName',
            'short_name' => 'shortName',
            'full_name' => 'fullName',
            'additional_info' => 'additionalInfo'
        ];

        return array_merge ($parentСolumnMap, $columnMap);
    }


    /**
    * Модель Organizations ссылается на таблицу "organizations".
    */
    public function getSource()
    {
        return 'organizations';
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
