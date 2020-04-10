<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;

class OrganizationForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор организации.
        $this->add(new Hidden("id"));
        
        
        // Наименование организации используемое в интерфейсе системы.
        $displayName = new Text('displayName',
            [
                'class' => 'form-control form-control-xl',
                'title' => 'Наименование, используемое в интерфейсе системы',
            ]
        );

        $displayName->setLabel('Представление&nbsp;*');
        
        $displayName->setFilters('trim');
        
        $displayName->addValidators([
            new PresenceOf([
                'message' => 'Представление обязательно.',
            ])
        ]);
        
        $this->add($displayName);
        
        
        // Сокращенное наименование организации.
        $shortName = new Text('shortName',
            [
                'class' => 'form-control form-control-xl',
                'title' => 'Сокращенное наименование организации',
            ]
        );

        $shortName->setLabel('Сокращенное наименование&nbsp;*');

        $shortName->setFilters('trim');
        
        $shortName->addValidators([
            new PresenceOf([
                'message' => 'Сокращенное наименование обязательно.',
            ])
        ]);
        
        $this->add($shortName);
        
        
        // Полное наименование организации.
        $fullName = new TextArea('fullName',
            [
                'class' => 'form-control form-control-xl',
                'title' => 'Полное наименование организации с указанием организационно-правовой формы',
                'rows' => '3'
            ]
        );

        $fullName->setLabel('Полное наименование');
        
        $fullName->setFilters('trim');

        $this->add($fullName);
        
        
        // Дополнительная информация.
        $additionalInfo = new TextArea('additionalInfo',
            [
                'class' => 'form-control form-control-xl',
                'title' => 'Дополнительная информация',
                'rows' => '5',
            ]
        );

        $additionalInfo->setLabel('Дополнительная информация');

        $additionalInfo->setFilters('trim');

        $this->add($additionalInfo);
        
    }
}