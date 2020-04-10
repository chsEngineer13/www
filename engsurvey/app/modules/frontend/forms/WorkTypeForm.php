<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Engsurvey\Models\SurveyTypes;
use Engsurvey\Models\Units;

class WorkTypeForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор вида работ.
        $this->add(new Hidden("id"));

        // Вид изысканий.
        $surveyType = new Select(
            'surveyTypeId',
            SurveyTypes::find(['order' => 'sequenceNumber']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'name'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $surveyType->setLabel('Вид изысканий&nbsp;*');
        $surveyType->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать вид изысканий.',
            ])
        ]);
        $this->add($surveyType);

        // Наименование вида работ.
        $name = new TextArea(
            'name',
            [
                'rows' => '3',
                'title' => 'Наименование вида работ',
            ]
        );
        $name->setLabel('Наименование&nbsp;*');
        $name->setFilters(['trim']);
        $name->addValidators([
            new PresenceOf([
                'message' => 'Наименование вида работ обязательно.',
            ])
        ]);
        $this->add($name);

        // Сокращенное наименование вида работ.
        $shortName = new TextArea(
            'shortName',
            [
                'rows' => '2',
                'title' => 'Сокращенное наименование вида работ',
            ]
        );
        $shortName->setLabel('Сокращенное наименование&nbsp;*');
        $shortName->setFilters(['trim']);
        $shortName->addValidators([
            new PresenceOf([
                'message' => 'Сокращенное наименование вида работ обязательно.',
            ])
        ]);
        $this->add($shortName);

        // Единица измерения.
        $unit = new Select(
            'unitId',
            Units::find(['order' => 'name']),
            [
                'data-live-search' => 'true',
                'using' => ['id', 'name'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $unit->setLabel('Единица измерения&nbsp;*');
        $unit->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать единицу измерения.',
            ])
        ]);
        $this->add($unit);

        // Норма выработки в день.
        $productionRate = new Text(
            'productionRate',
            ['title' => 'Норма выработки в день']
        );
        $productionRate->setLabel('Норма выработки&nbsp;*');
        $productionRate->setFilters(['trim']);
        $productionRate->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($productionRate);

        // Количество ИТР.
        $numderOfEngineers = new Text(
            'numderOfEngineers',
            ['title' => 'Количество ИТР',]
        );
        $numderOfEngineers->setLabel('ИТР&nbsp;*');
        $numderOfEngineers->setFilters(['trim']);
        $numderOfEngineers->addValidators(
            [
                new Regex(
                    [
                        // Положительное целое число или 0.
                        'pattern' => '/^([1-9]\d*|0)$/',
                        'message' => 'Введите положительное целое число или 0.'
                    ]
                )
            ]
        );
        $this->add($numderOfEngineers);

        // Количество рабочих.
        $numderOfWorkers = new Text(
            'numderOfWorkers',
            ['title' => 'Количество рабочих',]
        );
        $numderOfWorkers->setLabel('рабочие&nbsp;*');
        $numderOfWorkers->setFilters(['trim']);
        $numderOfWorkers->addValidators(
            [
                new Regex(
                    [
                        // Положительное целое число или 0.
                        'pattern' => '/^([1-9]\d*|0)$/',
                        'message' => 'Введите положительное целое число или 0.'
                    ]
                )
            ]
        );
        $this->add($numderOfWorkers);

        // Количество водителей.
        $numderOfDrivers = new Text(
            'numderOfDrivers',
            ['title' => 'Количество водителей',]
        );
        $numderOfDrivers->setLabel('водители&nbsp;*');
        $numderOfDrivers->setFilters(['trim']);
        $numderOfDrivers->addValidators(
            [
                new Regex(
                    [
                        // Положительное целое число или 0.
                        'pattern' => '/^([1-9]\d*|0)$/',
                        'message' => 'Введите положительное целое число или 0.'
                    ]
                )
            ]
        );
        $this->add($numderOfDrivers);

        // Количество бурмастеров.
        $numderOfDrillers = new Text(
            'numderOfDrillers',
            ['title' => 'Количество бурмастеров',]
        );
        $numderOfDrillers->setLabel('бурмастера&nbsp;*');
        $numderOfDrillers->setFilters(['trim']);
        $numderOfDrillers->addValidators(
            [
                new Regex(
                    [
                        // Положительное целое число или 0.
                        'pattern' => '/^([1-9]\d*|0)$/',
                        'message' => 'Введите положительное целое число или 0.'
                    ]
                )
            ]
        );
        $this->add($numderOfDrillers);

        // Зональный коэффициент при работе в тайге.
        $zfTaiga = new Text(
            'zfTaiga',
            ['title' => 'Зональный коэффициент при работе в тайге']
        );
        $zfTaiga->setLabel('тайга&nbsp;*');
        $zfTaiga->setFilters(['trim']);
        $zfTaiga->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($zfTaiga);

        // Зональный коэффициент при работе в лесотундре.
        $zfForestTundra = new Text(
            'zfForestTundra',
            ['title' => 'Зональный коэффициент при работе в лесотундре']
        );
        $zfForestTundra->setLabel('лесотундра&nbsp;*');
        $zfForestTundra->setFilters(['trim']);
        $zfForestTundra->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($zfForestTundra);

        // Зональный коэффициент при работе в тундре.
        $zfTundra = new Text(
            'zfTundra',
            ['title' => 'Зональный коэффициент при работе в тундре']
        );
        $zfTundra->setLabel('тундра&nbsp;*');
        $zfTundra->setFilters(['trim']);
        $zfTundra->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($zfTundra);

        // Зональный коэффициент при работе в лесостепи.
        $zfForestSteppe = new Text(
            'zfForestSteppe',
            ['title' => 'Зональный коэффициент при работе в лесостепи']
        );
        $zfForestSteppe->setLabel('лесостепь&nbsp;*');
        $zfForestSteppe->setFilters(['trim']);
        $zfForestSteppe->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($zfForestSteppe);

        // Сезонный коэффициент при работе в летний период.
        $sfSummer = new Text(
            'sfSummer',
            ['title' => 'Сезонный коэффициент при работе в летний период']
        );
        $sfSummer->setLabel('летний период&nbsp;*');
        $sfSummer->setFilters(['trim']);
        $sfSummer->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($sfSummer);

        // Сезонный коэффициент при работе в осенне-весенний период.
        $sfAutumnSpring = new Text(
            'sfAutumnSpring',
            ['title' => 'Сезонный коэффициент при работе в осенне-весенний период']
        );
        $sfAutumnSpring->setLabel('осенне-весенний период&nbsp;*');
        $sfAutumnSpring->setFilters(['trim']);
        $sfAutumnSpring->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($sfAutumnSpring);

        // Сезонный коэффициент при работе в зимний период.
        $sfWinter = new Text(
            'sfWinter',
            ['title' => 'Сезонный коэффициент при работе в зимний период']
        );
        $sfWinter->setLabel('зимний период&nbsp;*');
        $sfWinter->setFilters(['trim']);
        $sfWinter->addValidators(
            [
                new Regex(
                    [
                        // Положительное дробное число (десятичный разделитель точка
                        // или запятая), с разделителем разрядов (пробел) или без него.
                        'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$/',
                        'message' => 'Недопустимое значение.'
                    ]
                ),
            ]
        );
        $this->add($sfWinter);

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
