<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class EmployeeGroupForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор группы сотрудников.
        $this->add(new Hidden("id"));

        // Системное имя группы сотрудников.
        $systemName = new Text(
            'systemName',
            ['title' => 'Системное имя группы сотрудников']
        );
        $systemName->setLabel('Системное имя&nbsp;*');
        $systemName->setFilters(['trim']);
        $systemName->addValidators(
            [
                new PresenceOf(
                    ['message' => 'Системное имя группы сотрудников обязательно.']
                ),
                new Regex(
                    [
                        'pattern' => '/^[a-z0-9_]+$/',
                        'message' => 'В системном имени допустимы только латинские символы в нижнем регистре, цифры и знаки подчеркивания (_).'
                    ]
                ),
            ]
        );
        $this->add($systemName);

        // Наименование группы сотрудников.
        $name = new Text(
            'name',
            ['title' => 'Наименование группы сотрудников']
        );
        $name->setLabel('Наименование&nbsp;*');
        $name->setFilters(['trim']);
        $name->addValidators([
            new PresenceOf(
                ['message' => 'Наименование группы сотрудников обязательно.']
            )
        ]);
        $this->add($name);

        // Описание группы сотрудников.
        $description = new TextArea(
            'description', 
            [
                'class' => 'form-control',
                'title' => 'Описание группы сотрудников',
                'rows' => '3'
            ]
        );
        $description->setLabel('Описание');
        $this->add($description);

    }
}
