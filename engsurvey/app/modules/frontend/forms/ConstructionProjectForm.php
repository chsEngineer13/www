<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Engsurvey\Models\ConstructionTypes;
use Engsurvey\Models\Organizations;
use Engsurvey\Models\Employees;
use Engsurvey\Models\EmployeeGroups;
use Engsurvey\Models\EmployeeGroupMemberships;

class ConstructionProjectForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор стройки.
        $this->add(new Hidden("id"));

        // Шифр стройки.
        $code = new Text(
            'code',
            ['title' => 'Шифр стройки',]
        );
        $code->setLabel('Шифр стройки&nbsp;*');
        $code->setFilters('trim');
        $code->addValidators([
            new PresenceOf([
                'message' => 'Шифр стройки обязателен.',
            ])
        ]);
        $this->add($code);

        // Наименование стройки.
        $name = new TextArea(
            'name',
            [
                'title' => 'Наименование стройки',
                'rows' => '3'
            ]
        );
        $name->setLabel('Наименование стройки&nbsp;*');
        $name->setFilters('trim');
        $name->addValidators([
            new PresenceOf([
                'message' => 'Наименование стройки обязательно.',
            ])
        ]);
        $this->add($name);

        // Вид строительства.
        $constructionTypeId = new Select(
            'constructionTypeId',
            ConstructionTypes::find(['order' => 'sequenceNumber']),
            [
                'class' => 'form-control selectpicker',
                'data-live-search' => 'true',
                'using' => array('id', 'name'),
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $constructionTypeId->setLabel('Вид строительства');
        $this->add($constructionTypeId);

        // Заказчик (агент).
        $customerId = new Select(
            'customerId',
            Organizations::find(['order' => 'displayName']),
            [
                'class' => 'form-control selectpicker',
                'data-live-search' => 'true',
                'using' => array('id', 'displayName'),
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $customerId->setLabel('Заказчик (агент)&nbsp;*');
        $customerId->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать Заказчика.',
            ])
        ]);
        $this->add($customerId);
        
        // Получение массива сотрудников входящих в группу "Технических директоров".
        $technicalDirectors = $this->getTechnicalDirectors();

        // Технический директор.
        $technicalDirector = new Select(
            'technicalDirectorId',
            $technicalDirectors,
            [
                'data-live-search' => 'true',
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $technicalDirector->setLabel('Технический директор');
        $this->add($technicalDirector);
        
        // Ссылка на отчет.
        $reportLink = new Text(
            'reportLink',
            ['title' => 'Ссылка на отчет',]
        );
        $reportLink->setLabel('Отчет (ссылка)');
        $reportLink->setFilters('trim');
        $this->add($reportLink);
        
        // Ссылка на объект на карте.
        $mapLink = new Text(
            'mapLink',
            ['title' => 'Ссылка на объект на карте',]
        );
        $mapLink->setLabel('Объект на карте (ссылка)');
        $mapLink->setFilters('trim');
        $this->add($mapLink);
        
        // Комментарий.
        $comment = new TextArea(
            'comment',
            [
                'rows' => '5',
                'title' => 'Комментарий',
            ]
        );
        $comment->setLabel('Комментарий');
        $comment->setFilters('trim');
        $this->add($comment);
    }
    
    
    /**
     * Возвращает массива сотрудников входящих в группу "Технических директоров".
     *
     * @return array
     */
    public function getTechnicalDirectors(): array
    {
        $technicalDirectors = [];
        
        // Поиск группы "Технических директоров".
        $employeeGroup = EmployeeGroups::findFirst("systemName = 'technical_directors'");
        if ($employeeGroup === false) {
            // TODO: Стоит ли бросать исключение?
            return $technicalDirectors;
        }
        
        $employeeGroupId = $employeeGroup->getId();

        $employeeGroupMembers = EmployeeGroupMemberships::find("employeeGroupId = '$employeeGroupId'");
        
        foreach ($employeeGroupMembers as $member) {
            $employee = $member->getEmployee();
            $employeeId = $employee->getId();
            $employeeFullName = $employee->getFullName();
            
            $technicalDirectors[$employeeId] = $employeeFullName;
        }
        
        natsort($technicalDirectors);

        return $technicalDirectors;
    }
    
    
}
