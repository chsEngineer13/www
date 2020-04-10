<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Engsurvey\Models\Branches;
use Engsurvey\Models\CrewTypes;

class CrewForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор бригады.
        $this->add(new Hidden("id"));

        // Филиал.
        $branch = new Select(
            'branchId',
            Branches::find(['order' => 'displayName']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'displayName'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $branch->setLabel('Филиал&nbsp;*');
        $branch->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать филиал.',
            ])
        ]);
        $this->add($branch);

        // Вид бригады.
        $crewType = new Select(
            'crewTypeId',
            CrewTypes::find(['order' => 'sequenceNumber']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'name'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $crewType->setLabel('Вид бригады&nbsp;*');
        $crewType->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать вид бригады.',
            ])
        ]);
        $this->add($crewType);

        // Наименование бригады.
        $crewName = new Text(
            'crewName',
            ['title' => 'Наименование бригады']
        );
        $crewName->setLabel('Наименование бригады&nbsp;*');
        $crewName->setFilters('trim');
        $crewName->addValidators([
            new PresenceOf([
                'message' => 'Наименование бригады обязательно.',
            ])
        ]);
        $this->add($crewName);

        // Фамилия бригадира.
        $headLastName = new Text(
            'headLastName',
            ['title' => 'Фамилия руководителя бригады']
        );
        $headLastName->setLabel('Фамилия бригадира');
        $headLastName->setFilters('trim');
        $this->add($headLastName);

        // Имя бригадира.
        $headFirstName = new Text(
            'headFirstName',
            ['title' => 'Имя руководителя бригады']
        );
        $headFirstName->setLabel('Имя бригадира');
        $headFirstName->setFilters('trim');
        $this->add($headFirstName);

        // Отчество бригадира.
        $headMiddleName = new Text(
            'headMiddleName',
            ['title' => 'Отчество руководителя бригады']
        );
        $headMiddleName->setLabel('Отчество бригадира');
        $headMiddleName->setFilters('trim');
        $this->add($headMiddleName);

        // Телефон.
        $phone = new Text(
            'phone',
            ['title' => 'Телефон']
        );
        $phone->setLabel('Телефон');
        $phone->setFilters('trim');
        $this->add($phone);

        // Эл. почта.
        $email = new Text(
            'email',
            ['title' => 'Электронная почта']
        );
        $email->setLabel('Эл. почта');
        $email->setFilters('trim');
        $this->add($email);

        // Численность бригады.
        $numberOfCrew = new Text(
            'numberOfCrew',
            ['title' => 'Численность бригады',]
        );
        $numberOfCrew->setLabel('Численность бригады');
        $numberOfCrew->setFilters('trim');
        $numberOfCrew->addValidators(
            [
                new Regex(
                    [
                        // Положительное целое число > 0.
                        'pattern' => '/^([1-9]\d*)$|^$/',
                        'message' => 'Введите положительное целое число больше 0 или оставьте поле пустым.'
                    ]
                )
            ]
        );
        $this->add($numberOfCrew);
        
        // Ссылка на отчет.
        $reportLink = new Text(
            'reportLink',
            ['title' => 'Ссылка на отчет']
        );
        $reportLink->setLabel('Отчет (ссылка)');
        $reportLink->setFilters('trim');
        $this->add($reportLink);
    }
}
