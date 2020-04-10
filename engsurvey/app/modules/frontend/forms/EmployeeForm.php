<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Engsurvey\Models\Branches;

class EmployeeForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор сотрудника.
        $this->add(new Hidden('id'));

        // Фамилия.
        $lastName = new Text(
            'lastName',
            ['title' => 'Фамилия сотрудника']
        );
        $lastName->setLabel('Фамилия&nbsp;*');
        $lastName->setFilters(['trim']);
        $lastName->addValidators([
            new PresenceOf([
                'message' => 'Фамилия сотрудника обязательна.',
            ])
        ]);
        $this->add($lastName);

        // Имя.
        $firstName = new Text(
            'firstName',
            ['title' => 'Имя сотрудника']
        );
        $firstName->setLabel('Имя&nbsp;*');
        $firstName->setFilters(['trim']);
        $firstName->addValidators([
            new PresenceOf([
                'message' => 'Имя сотрудника обязательно.',
            ])
        ]);
        $this->add($firstName);

        // Отчество.
        $middleName = new Text(
            'middleName',
            ['title' => 'Отчество сотрудника']
        );
        $middleName->setLabel('Отчество&nbsp;*');
        $middleName->setFilters(['trim']);
        $middleName->addValidators([
            new PresenceOf([
                'message' => 'Отчество сотрудника обязательно.',
            ])
        ]);
        $this->add($middleName);

        // Филиал.
        $branch = new Select(
            'branchId',
            Branches::find(['order' => 'sequenceNumber']),
            [
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

        // Должность.
        $jobTitle = new Text(
            'jobTitle',
            ['title' => 'Должность сотрудника']
        );
        $jobTitle->setLabel('Должность&nbsp;*');
        $jobTitle->setFilters(['trim']);
        $jobTitle->addValidators([
            new PresenceOf([
                'message' => 'Должность сотрудника обязательна.',
            ])
        ]);
        $this->add($jobTitle);

        // Подразделение.
        $department = new Text(
            'department',
            ['title' => 'Подразделение']
        );
        $department->setLabel('Подразделение');
        $department->setFilters(['trim']);
        $this->add($department);

        // Местонахождение сотрудника.
        $location = new Text(
            'location',
            ['title' => 'Местонахождение сотрудника']
        );
        $location->setLabel('Местонахождение');
        $location->setFilters(['trim']);
        $this->add($location);

        // Телефон рабочий.
        $phoneWork = new Text(
            'phoneWork',
            ['title' => 'Телефон рабочий']
        );
        $phoneWork->setLabel('Телефон рабочий');
        $phoneWork->setFilters(['trim']);
        $this->add($phoneWork);

        // Телефон газовый.
        $phoneGas = new Text(
            'phoneGas',
            ['title' => 'Телефон газовый']
        );
        $phoneGas->setLabel('Телефон газовый');
        $phoneGas->setFilters(['trim']);
        $this->add($phoneGas);

        // Телефон мобильный.
        $phoneMobile = new Text(
            'phoneMobile',
            ['title' => 'Телефон мобильный']
        );
        $phoneMobile->setLabel('Телефон мобильный');
        $phoneMobile->setFilters(['trim']);
        $this->add($phoneMobile);

        // Электронная почта.
        $email = new Text(
            'email',
            ['title' => 'Электронная почта']
        );
        $email->setLabel('Электронная почта');
        $email->setFilters(['trim']);
        $this->add($email);
    }

}
