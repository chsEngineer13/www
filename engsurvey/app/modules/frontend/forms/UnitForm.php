<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;

class UnitForm extends Form
{
    /**
     * Инициализация формы.
     *
     * @param $entity
     * @param array $options
     */
    public function initialize($entity = null, $options = null)
    {
        // Идентификатор.
        $this->add(new Hidden("id"));
        
        // Наименование единицы измерения.
        $name = new Text(
            'name',
            ['title' => 'Наименование единицы измерения']
        );
        $name->setLabel('Наименование');
        $name->setFilters('trim');
        $name->addValidators([
            new PresenceOf([
                'message' => 'Наименование единицы измерения обязательно.',
            ])
        ]);
        $this->add($name);
        
        // Условное обозначение.
        $symbol = new Text(
            'symbol',
            ['title' => 'Условное обозначение']
        );
        $symbol->setLabel('Усл. обозначение');
        $symbol->setFilters('trim');
        $symbol->addValidators([
            new PresenceOf([
                'message' => 'Условное обозначение обязательно.',
            ])
        ]);
        $this->add($symbol);
    }
}
