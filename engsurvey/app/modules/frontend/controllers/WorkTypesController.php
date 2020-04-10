<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\WorkTypes;
use Engsurvey\Frontend\Forms\WorkTypeForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;

class WorkTypesController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к 'search'.
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'work-types',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск видов работ.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск видов работ и передача их в представление.
        $workTypes = WorkTypes::find();
        $this->view->setVar('workTypes', $workTypes);

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();

        // Шаблон, используемый для создания представления.
        $this->view->pick('work-types/search');
    }


    /**
     * Отображает форму создания нового вида работ.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('WorkTypes', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('work-types');
        }
        
        // Форма, используемая в представлении.
        $this->view->form = new WorkTypeForm();

        // Шаблон, используемый для создания представления.
        $this->view->pick('work-types/new');
    }


    /**
     * Отображает форму редактирования существующего вида работ.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('WorkTypes', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('work-types');
        }
        
        if (!$this->request->isPost()) {
            // Получение и валидация обязательного параметра 'id' переданного методом GET.
            $id = $this->request->getQuery('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }

            // Поиск вида работ.
            $workType = WorkTypes::findFirstById($id);
            if (!$workType) {
                throw new EngsurveyException('Вид работ не найден.');
            }

            // Найденные данные связываются с формой,
            // передавая модель первым параметром.
            $this->view->form = new WorkTypeForm($workType);
        } else {
            // Форма, используемая в представлении.
            $this->view->form = new WorkTypeForm();
        }

        // Шаблон представления.
        $this->view->pick('work-types/edit');
    }


    /**
     * Создает новый вид работ на основе данных,
     * введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('WorkTypes', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('work-types');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы создания нового вида работ
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Получение данных.
        $surveyTypeId = $this->request->getPost('surveyTypeId', 'trim');
        $name = $this->request->getPost('name', 'trim');
        $shortName = $this->request->getPost('shortName', 'trim');
        $unitId = $this->request->getPost('unitId', 'trim');
        $productionRate = $this->request->getPost('productionRate', 'trim');
        $numderOfEngineers = $this->request->getPost('numderOfEngineers', 'trim');
        $numderOfWorkers = $this->request->getPost('numderOfWorkers', 'trim');
        $numderOfDrivers = $this->request->getPost('numderOfDrivers', 'trim');
        $numderOfDrillers = $this->request->getPost('numderOfDrillers', 'trim');
        $zfTaiga = $this->request->getPost('zfTaiga', 'trim');
        $zfForestTundra = $this->request->getPost('zfForestTundra', 'trim');
        $zfTundra = $this->request->getPost('zfTundra', 'trim');
        $zfForestSteppe = $this->request->getPost('zfForestSteppe', 'trim');
        $sfSummer = $this->request->getPost('sfSummer', 'trim');
        $sfAutumnSpring = $this->request->getPost('sfAutumnSpring', 'trim');
        $sfWinter = $this->request->getPost('sfWinter', 'trim');
        $comment = $this->request->getPost('comment', 'trim');

        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой записи.
        $workType = new WorkTypes();
        $workType->setId($id);
        $workType->setSurveyTypeId($surveyTypeId);
        $workType->setName($name);
        $workType->setShortName($shortName);
        $workType->setUnitId($unitId);
        $workType->setProductionRate($this->stringToFloat($productionRate));
        $workType->setNumderOfEngineers((int)$numderOfEngineers);
        $workType->setNumderOfWorkers((int)$numderOfWorkers);
        $workType->setNumderOfDrivers((int)$numderOfDrivers);
        $workType->setNumderOfDrillers((int)$numderOfDrillers);
        $workType->setZfTaiga($this->stringToFloat($zfTaiga));
        $workType->setZfForestTundra($this->stringToFloat($zfForestTundra));
        $workType->setZfTundra($this->stringToFloat($zfTundra));
        $workType->setZfForestSteppe($this->stringToFloat($zfForestSteppe));
        $workType->setSfSummer($this->stringToFloat($sfSummer));
        $workType->setSfAutumnSpring($this->stringToFloat($sfAutumnSpring));
        $workType->setSfWinter($this->stringToFloat($sfWinter));
        $workType->setComment($comment);

        if ($workType->create() === false) {
            $this->flashSession->error('Ошибка создания нового вида работ.');

            return $this->dispatcher->forward(['action' => 'new']);
        }

        // HTTP редирект.
        return $this->response->redirect('work-types');
    }


    /**
     * Изменяет запись о виде работ на основе данных,
     * введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('WorkTypes', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('work-types');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Получение и валидация ID вида работ.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID вида работ.');
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы редактирования вида работ
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // Поиск вида работ.
        $workType = WorkTypes::findFirstById($id);
        if (!$workType) {
            throw new EngsurveyException('Вид работ не найден.');
        }

        // Получение данных.
        $surveyTypeId = $this->request->getPost('surveyTypeId', 'trim');
        $name = $this->request->getPost('name', 'trim');
        $shortName = $this->request->getPost('shortName', 'trim');
        $unitId = $this->request->getPost('unitId', 'trim');
        $productionRate = $this->request->getPost('productionRate', 'trim');
        $numderOfEngineers = $this->request->getPost('numderOfEngineers', 'trim');
        $numderOfWorkers = $this->request->getPost('numderOfWorkers', 'trim');
        $numderOfDrivers = $this->request->getPost('numderOfDrivers', 'trim');
        $numderOfDrillers = $this->request->getPost('numderOfDrillers', 'trim');
        $zfTaiga = $this->request->getPost('zfTaiga', 'trim');
        $zfForestTundra = $this->request->getPost('zfForestTundra', 'trim');
        $zfTundra = $this->request->getPost('zfTundra', 'trim');
        $zfForestSteppe = $this->request->getPost('zfForestSteppe', 'trim');
        $sfSummer = $this->request->getPost('sfSummer', 'trim');
        $sfAutumnSpring = $this->request->getPost('sfAutumnSpring', 'trim');
        $sfWinter = $this->request->getPost('sfWinter', 'trim');
        $comment = $this->request->getPost('comment', 'trim');

        // Обновление вида работ.
        $workType->setSurveyTypeId($surveyTypeId);
        $workType->setName($name);
        $workType->setShortName($shortName);
        $workType->setUnitId($unitId);
        $workType->setProductionRate($this->stringToFloat($productionRate));
        $workType->setNumderOfEngineers((int)$numderOfEngineers);
        $workType->setNumderOfWorkers((int)$numderOfWorkers);
        $workType->setNumderOfDrivers((int)$numderOfDrivers);
        $workType->setNumderOfDrillers((int)$numderOfDrillers);
        $workType->setZfTaiga($this->stringToFloat($zfTaiga));
        $workType->setZfForestTundra($this->stringToFloat($zfForestTundra));
        $workType->setZfTundra($this->stringToFloat($zfTundra));
        $workType->setZfForestSteppe($this->stringToFloat($zfForestSteppe));
        $workType->setSfSummer($this->stringToFloat($sfSummer));
        $workType->setSfAutumnSpring($this->stringToFloat($sfAutumnSpring));
        $workType->setSfWinter($this->stringToFloat($sfWinter));
        $workType->setComment($comment);

        if ($workType->update() === false) {
            throw new EngsurveyException('Не удалось обновить вид работ.');
        }

        // HTTP редирект.
        return $this->response->redirect('work-types');
    }


    /**
     * Удаляет вид работ.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('WorkTypes', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('work-types');
        }
        
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        // Поиск вида работ.
        $workType = WorkTypes::findFirstById($id);
        if (!$workType) {
            throw new EngsurveyException('Вид работ не найден.');
        }

        $name = $workType->getName();

        // Удаление вида работ.
        if ($workType->delete() === false) {
            $messages = $workType->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }
        } else {
            $message = 'Вид работ «' . $name . '» успешно удален.';
            $this->flashSession->success($message);
        }

        // HTTP редирект.
        return $this->response->redirect('work-types');
    }


    /**
     * Выполняет валидацию данных полученных из формы
     * и формирует сообщения об ошибках с передачей их в представление.
     * Возвращают булево значение, показывающее, прошла валидация
     * успешно, либо нет.
     *
     * @param  array $data Данные полученные из формы.
     * @return boolean
     */
    protected function validate(array $data)
    {
        $form = new WorkTypeForm();

        if (!$form->isValid($data)) {

            // Сообщения для элемента 'surveyTypeId'.
            $messages = $form->getMessagesFor('surveyTypeId');
            if (count($messages)) {
                $surveyTypeMessages = '';

                foreach ($messages as $message) {
                    $surveyTypeMessages .= $message . '<br>';
                }

                $surveyTypeMessages = rtrim($surveyTypeMessages, '<br>');

                $this->view->setVar('surveyTypeMessages', $surveyTypeMessages);
            }

            // Сообщения для элемента 'name'.
            $messages = $form->getMessagesFor('name');
            if (count($messages)) {
                $nameMessages = '';

                foreach ($messages as $message) {
                    $nameMessages .= $message . '<br>';
                }

                $nameMessages = rtrim($nameMessages, '<br>');

                $this->view->setVar('nameMessages', $nameMessages);
            }

            // Сообщения для элемента 'shortName'.
            $messages = $form->getMessagesFor('shortName');
            if (count($messages)) {
                $shortNameMessages = '';

                foreach ($messages as $message) {
                    $shortNameMessages .= $message . '<br>';
                }

                $shortNameMessages = rtrim($shortNameMessages, '<br>');

                $this->view->setVar('shortNameMessages', $shortNameMessages);
            }

            // Сообщения для элемента 'unitId'.
            $messages = $form->getMessagesFor('unitId');
            if (count($messages)) {
                $unitMessages = '';

                foreach ($messages as $message) {
                    $unitMessages .= $message . '<br>';
                }

                $unitMessages = rtrim($unitMessages, '<br>');

                $this->view->setVar('unitMessages', $unitMessages);
            }

            // Сообщения для элемента 'productionRate'.
            $messages = $form->getMessagesFor('productionRate');
            if (count($messages)) {
                $productionRateMessages = '';

                foreach ($messages as $message) {
                    $productionRateMessages .= $message . '<br>';
                }

                $productionRateMessages = rtrim($productionRateMessages, '<br>');

                $this->view->setVar('productionRateMessages', $productionRateMessages);
            }

            // Сообщения для элемента 'numderOfEngineers'.
            $messages = $form->getMessagesFor('numderOfEngineers');
            if (count($messages)) {
                $numderOfEngineersMessages = '';

                foreach ($messages as $message) {
                    $numderOfEngineersMessages .= $message . '<br>';
                }

                $numderOfEngineersMessages = rtrim($numderOfEngineersMessages, '<br>');

                $this->view->setVar('numderOfEngineersMessages', $numderOfEngineersMessages);
            }

            // Сообщения для элемента 'numderOfWorkers'.
            $messages = $form->getMessagesFor('numderOfWorkers');
            if (count($messages)) {
                $numderOfWorkersMessages = '';

                foreach ($messages as $message) {
                    $numderOfWorkersMessages .= $message . '<br>';
                }

                $numderOfWorkersMessages = rtrim($numderOfWorkersMessages, '<br>');

                $this->view->setVar('numderOfWorkersMessages', $numderOfWorkersMessages);
            }

            // Сообщения для элемента 'numderOfDrivers'.
            $messages = $form->getMessagesFor('numderOfDrivers');
            if (count($messages)) {
                $numderOfDriversMessages = '';

                foreach ($messages as $message) {
                    $numderOfDriversMessages .= $message . '<br>';
                }

                $numderOfDriversMessages = rtrim($numderOfDriversMessages, '<br>');

                $this->view->setVar('numderOfDriversMessages', $numderOfDriversMessages);
            }

            // Сообщения для элемента 'numderOfDrillers'.
            $messages = $form->getMessagesFor('numderOfDrillers');
            if (count($messages)) {
                $numderOfDrillersMessages = '';

                foreach ($messages as $message) {
                    $numderOfDrillersMessages .= $message . '<br>';
                }

                $numderOfDrillersMessages = rtrim($numderOfDrillersMessages, '<br>');

                $this->view->setVar('numderOfDrillersMessages', $numderOfDrillersMessages);
            }

            // Сообщения для элемента 'zfTaiga'.
            $messages = $form->getMessagesFor('zfTaiga');
            if (count($messages)) {
                $zfTaigaMessages = '';

                foreach ($messages as $message) {
                    $zfTaigaMessages .= $message . '<br>';
                }

                $zfTaigaMessages = rtrim($zfTaigaMessages, '<br>');

                $this->view->setVar('zfTaigaMessages', $zfTaigaMessages);
            }

            // Сообщения для элемента 'zfForestTundra'.
            $messages = $form->getMessagesFor('zfForestTundra');
            if (count($messages)) {
                $zfForestTundraMessages = '';

                foreach ($messages as $message) {
                    $zfForestTundraMessages .= $message . '<br>';
                }

                $zfForestTundraMessages = rtrim($zfForestTundraMessages, '<br>');

                $this->view->setVar('zfForestTundraMessages', $zfForestTundraMessages);
            }

            // Сообщения для элемента 'zfTundra'.
            $messages = $form->getMessagesFor('zfTundra');
            if (count($messages)) {
                $zfTundraMessages = '';

                foreach ($messages as $message) {
                    $zfTundraMessages .= $message . '<br>';
                }

                $zfTundraMessages = rtrim($zfTundraMessages, '<br>');

                $this->view->setVar('zfTundraMessages', $zfTundraMessages);
            }

            // Сообщения для элемента 'zfForestSteppe'.
            $messages = $form->getMessagesFor('zfForestSteppe');
            if (count($messages)) {
                $zfForestSteppeMessages = '';

                foreach ($messages as $message) {
                    $zfForestSteppeMessages .= $message . '<br>';
                }

                $zfForestSteppeMessages = rtrim($zfForestSteppeMessages, '<br>');

                $this->view->setVar('zfForestSteppeMessages', $zfForestSteppeMessages);
            }

            // Сообщения для элемента 'sfSummer'.
            $messages = $form->getMessagesFor('sfSummer');
            if (count($messages)) {
                $sfSummerMessages = '';

                foreach ($messages as $message) {
                    $sfSummerMessages .= $message . '<br>';
                }

                $sfSummerMessages = rtrim($sfSummerMessages, '<br>');

                $this->view->setVar('sfSummerMessages', $sfSummerMessages);
            }

            // Сообщения для элемента 'sfAutumnSpring'.
            $messages = $form->getMessagesFor('sfAutumnSpring');
            if (count($messages)) {
                $sfAutumnSpringMessages = '';

                foreach ($messages as $message) {
                    $sfAutumnSpringMessages .= $message . '<br>';
                }

                $sfAutumnSpringMessages = rtrim($sfAutumnSpringMessages, '<br>');

                $this->view->setVar('sfAutumnSpringMessages', $sfAutumnSpringMessages);
            }

            // Сообщения для элемента 'sfWinter'.
            $messages = $form->getMessagesFor('sfWinter');
            if (count($messages)) {
                $sfWinterMessages = '';

                foreach ($messages as $message) {
                    $sfWinterMessages .= $message . '<br>';
                }

                $sfWinterMessages = rtrim($sfWinterMessages, '<br>');

                $this->view->setVar('sfWinterMessages', $sfWinterMessages);
            }

            return false;
        }

        return true;
    }
    
    
    /**
     * Преобразование строки в число с плавающей точкой.
     * Строка должна содержать число, допускается форматированное число вида "12 345,67".
     *
     * @param  string  $str Строка содержащей форматированное число.
     *
     * @return  float|false  Число с плавающей точкой или FALSE, если преобразование не удалось осуществить.
     */
    protected function stringToFloat($str)
    {
        // Удаление обычных пробелов.
        $str = str_replace(' ', '', $str);
        // Удаление неразрывных пробелов.
        $str = str_replace("\xC2\xA0", '', $str);
        // Замена запятыx на точки
        $str = str_replace(',', '.', $str);

        if (is_numeric($str)) {
            return (float)$str;
        } else {
            return false;
        }
    }

}
