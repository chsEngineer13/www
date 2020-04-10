<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Engsurvey\Models\Employees;
use Engsurvey\Models\EmployeeGroups;
use Engsurvey\Models\EmployeeGroupMemberships;

class ConstructionSiteForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор участка работ.
        $this->add(new Hidden("id"));
        
        // Идентификатор стройки.
        $this->add(new Hidden("constructionProjectId"));

        // Номер участка работ.
        $siteNumber = new Text(
            'siteNumber',
            ['title' => 'Номер участка работ',]
        );
        $siteNumber->setLabel('Номер&nbsp;*');
        $siteNumber->setFilters('trim');
        $siteNumber->addValidators([
            new PresenceOf([
                'message' => 'Номер участка работ обязателен.',
            ])
        ]);
        $this->add($siteNumber);

        // Наименование участка работ.
        $name = new TextArea('name',
            [
                'title' => 'Наименование участка работ',
                'rows' => '3',
            ]
        );
        $name->setLabel('Наименование&nbsp;*');
        $name->setFilters('trim');
        $name->addValidators([
            new PresenceOf([
                'message' => 'Наименование участка работ обязательно.',
            ])
        ]);
        $this->add($name);
        
        // Получение массива сотрудников входящих в группу "ГИПы".
        $chiefProjectEngineers = $this->getChiefProjectEngineers();

        // Главный инженер проекта (ГИП).
        $chiefProjectEngineer = new Select(
            'chiefProjectEngineerId',
            $chiefProjectEngineers,
            [
                'data-live-search' => 'true',
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $chiefProjectEngineer->setLabel('ГИП');
        $this->add($chiefProjectEngineer);
        
        // Ссылка на отчет.
        $reportLink = new Text(
            'reportLink',
            ['title' => 'Внешняя ссылка на отчет.',]
        );
        $reportLink->setLabel('Отчет (ссылка)');
        $reportLink->setFilters('trim');
        $this->add($reportLink);
        
        // Ссылка на объект на карте.
        $mapLink = new Text(
            'mapLink',
            ['title' => 'Внешняя ссылка на объект на карте.',]
        );
        $mapLink->setLabel('Объект на карте (ссылка)');
        $mapLink->setFilters('trim');
        $this->add($mapLink);

        // Комментарий.
        $comment = new TextArea('comment',
            [
                'title' => 'Комментарий',
                'rows' => '5',
            ]
        );
        $comment->setLabel('Комментарий');
        $comment->setFilters('trim');
        $this->add($comment);
        
    }
    
    
    /**
     * Возвращает массива сотрудников входящих в группу "ГИПы".
     *
     * @return array
     */
    public function getChiefProjectEngineers(): array
    {
        $chiefProjectEngineers = [];
        
        // Поиск группы "ГИПы".
        $employeeGroup = EmployeeGroups::findFirst("systemName = 'chief_project_engineers'");
        if ($employeeGroup === false) {
            // TODO: Стоит ли бросать исключение?
            return $chiefProjectEngineers;
        }
        
        $employeeGroupId = $employeeGroup->getId();

        $employeeGroupMembers = EmployeeGroupMemberships::find("employeeGroupId = '$employeeGroupId'");
        
        foreach ($employeeGroupMembers as $member) {
            $employee = $member->getEmployee();
            $employeeId = $employee->getId();
            $employeeFullName = $employee->getFullName();
            
            $chiefProjectEngineers[$employeeId] = $employeeFullName;
        }
        
        natsort($chiefProjectEngineers);

        return $chiefProjectEngineers;
    }
    
}
