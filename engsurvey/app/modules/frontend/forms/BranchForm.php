<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Engsurvey\Models\Organizations;

class BranchForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор.
        $this->add(new Hidden("id"));

        // Порядковый номер.
        $sequenceNumber = new Text(
            'sequenceNumber',
            ['title' => 'Порядковый номер']
        );
        $sequenceNumber->setLabel('Порядковый №');
        $sequenceNumber->setFilters(['trim']);
        $sequenceNumber->addValidators(
            [
                new Regex(
                    [
                        // Положительное целое число > 0.
                        'pattern' => '/^([1-9]\d*)$|^$/',
                        'message' => 'Введите положительное целое число больше 0 или оставьте поле пустым для автоматической нумерации.'
                    ]
                )
            ]
        );
        $this->add($sequenceNumber);
        
        // Организация.
        $organization = new Select(
            'organizationId',
            Organizations::find(['order' => 'displayName']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'displayName'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $organization->setLabel('Организация&nbsp;*');
        $organization->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать организацию.',
            ])
        ]);
        $this->add($organization);
        
        // Наименование филиала, используемого в интерфейсе системы.
        $displayName = new Text(
            'displayName',
            ['title' => 'Наименование филиала, используемое в интерфейсе системы']
        );
        $displayName->setLabel('Наименование&nbsp;*');
        $displayName->setFilters(['trim']);
        $displayName->addValidators([
            new PresenceOf([
                'message' => 'Наименование филиала обязательно.',
            ])
        ]);
        $this->add($displayName);
        
        // Код филиала.
        $code = new Text(
            'code',
            ['title' => 'Код филиала',]
        );
        $code->setLabel('Код филиала&nbsp;*');
        $code->setFilters(['trim']);
        $code->addValidators([
            new PresenceOf([
                'message' => 'Код филиала обязателен.',
            ])
        ]);
        $this->add($code);
    }
}
