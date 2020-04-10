<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

use Engsurvey\Models\ConstructionProjects;
use Engsurvey\Models\Organizations;
use Engsurvey\Models\Branches;
use Engsurvey\Models\ContractStatuses;

class ContractForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор договора.
        $this->add(new Hidden('id'));

        // Номер договора.
        $contractNumber = new Text(
            'contractNumber',
            ['title' => 'Номер договора',]
        );
        $contractNumber->setLabel('Номер договора&nbsp;*');
        $contractNumber->setFilters('trim');
        $contractNumber->addValidators([
            new PresenceOf([
                'message' => 'Номер договора обязателен.',
            ])
        ]);
        $this->add($contractNumber);

        // Дата подписания договора.
        $signatureDate = new Text(
            'signatureDate',
            ['title' => 'Дата подписания договора',]
        );
        $signatureDate->setLabel('Дата подписания');
        $signatureDate->setFilters('trim');
        $signatureDate->addValidators(
            [
                new Regex(
                    [
                        // Дата в формате DD.MM.YYYY или пустое значение.
                        'pattern' => '/^(3[0-1]|0[1-9]|[1-2][0-9])\.(0[1-9]|1[0-2])\.([0-9]{4})|^$/',
                        'message' => 'Введите дату в формате ДД.ММ.ГГГГ или оставьте поле незаполненным.'
                    ]
                ),
            ]
        );
        $this->add($signatureDate);

        // Предмет договора.
        $subjectOfContract = new TextArea(
            'subjectOfContract',
            [
                'rows' => '3',
                'title' => 'Предмет договора',
            ]
        );
        $subjectOfContract->setLabel('Предмет договора&nbsp;*');
        $subjectOfContract->setFilters(['trim']);
        $subjectOfContract->addValidators([
            new PresenceOf([
                'message' => 'Предмет договора обязателен.',
            ])
        ]);
        $this->add($subjectOfContract);

        // Стройка.
        $constructionProject = new Select(
            'constructionProjectId',
            ConstructionProjects::find(['order' => 'code']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'code'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $constructionProject->setLabel('Стройка&nbsp;*');
        $constructionProject->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать объект строительства (стройку).',
            ])
        ]);
        $this->add($constructionProject);

        // Заказчик (агент).
        $customer = new Select(
            'customerId',
            Organizations::find(['order' => 'displayName']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'displayName'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $customer->setLabel('Заказчик (агент)&nbsp;*');
        $customer->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать заказчика.',
            ])
        ]);
        $this->add($customer);

        // Ответственный филиал.
        $branch = new Select(
            'branchId',
            Branches::find(['order' => 'sequenceNumber']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'displayName'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $branch->setLabel('Ответственный филиал');
        $this->add($branch);

        // Стоимость работ по договору.
        $contractCost = new Text(
            'contractCost',
            ['title' => 'Стоимость работ по договору']
        );
        $contractCost->setLabel('Стоимость работ');
        $contractCost->setFilters(['trim']);
        $contractCost->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка 
                        // или запятая), с разделителем разрядов (пробел) или без него,
                        // или пустое значение.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$|^$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($contractCost);

        // Статус договора.
        $contractStatus = new Select(
            'contractStatusId',
            ContractStatuses::find(['order' => 'sequenceNumber']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'name'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $contractStatus->setLabel('Статус договора&nbsp;*');
        $contractStatus->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать статус договора.',
            ])
        ]);
        $this->add($contractStatus);

        // Комментарий.
        $comment = new TextArea(
            'comment',
            [
                'rows' => '5',
                'title' => 'Комментарий',
            ]
        );
        $comment->setLabel('Комментарий');
        $comment->setFilters(['trim']);
        $this->add($comment);
        
    }

}
