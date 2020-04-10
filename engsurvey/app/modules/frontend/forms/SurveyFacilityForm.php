<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;


class SurveyFacilityForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор объекта изысканий.
        $this->add(new Hidden("id"));

        // Идентификатор стройки.
        $this->add(new Hidden("constructionProjectId"));

        // Идентификатор участка работ.
        $this->add(new Hidden("constructionSiteId"));

        // Порядковый номер объекта изысканий.
        $sequenceNumber = new Text(
            'sequenceNumber',
            [
                'class' => 'form-control form-control-sm',
                'title' => 'Порядковый номер объекта изысканий в рамках участка работ',
            ]
        );
        $sequenceNumber->setLabel('Порядковый №');
        $sequenceNumber->setFilters('trim');
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

        // Наименование объекта изысканий.
        $facilityName = new TextArea(
            'facilityName',
            [
                'class' => 'form-control form-control-xl',
                'rows' => '3',
                'title' => 'Наименование объекта изысканий',
            ]
        );
        $facilityName->setLabel('Наименование объекта&nbsp;*');
        $facilityName->setFilters('trim');
        $facilityName->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Наименование объекта изысканий обязательно.',
                    ]
                )
            ]
        );
        $this->add($facilityName);

        // Обозначение объекта изысканий.
        $facilityDesignation = new Text(
            'facilityDesignation',
            [
                'class' => 'form-control form-control-md',
                'title' => 'Обозначение объекта изысканий',
            ]
        );
        $facilityDesignation->setLabel('Обозначение объекта');
        $facilityDesignation->setFilters('trim');
        $this->add($facilityDesignation);

        // Номер объекта изысканий.
        $facilityNumber = new Text(
            'facilityNumber',
            [
                'class' => 'form-control form-control-sm',
                'title' => 'Номер объекта изысканий',
            ]
        );
        $facilityNumber->setLabel('Номер объекта');
        $facilityNumber->setFilters('trim');
        $this->add($facilityNumber);

        // Этап работ.
        $stageOfWorks = new Text(
            'stageOfWorks',
            [
                'class' => 'form-control form-control-sm',
                'title' => 'Этап работ',
            ]
        );
        $stageOfWorks->setLabel('Этап работ');
        $stageOfWorks->setFilters('trim');
        $this->add($stageOfWorks);

        // Комментарий.
        $comment = new TextArea(
            'comment',
            [
                'class' => 'form-control form-control-xl',
                'rows' => '5',
                'title' => 'Комментарий',
            ]
        );
        $comment->setLabel('Комментарий');
        $comment->setFilters('trim');
        $this->add($comment);
    }
}