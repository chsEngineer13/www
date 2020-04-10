<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Units;
use Engsurvey\Frontend\Forms\UnitForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;

class UnitsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'units',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск единиц измерения.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск единиц измерения и передача их в представление.
        $units = Units::find(['order' => 'name']);
        $this->view->setVar('units', $units);

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();

        // Шаблон, используемый для создания представления.
        $this->view->pick('units/search');
    }


    /**
     * Отображает форму создания новой единицы измерения.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Units', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('units');
        }
        
        // Форма, используемая в представлении.
        $this->view->form = new UnitForm();

        // Шаблон, используемый для создания представления.
        $this->view->pick('units/new');
    }


    /**
     * Отображает форму редактирования существующей единицы измерения.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Units', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('units');
        }
        
        if (!$this->request->isPost()) {
            // Получение и валидация обязательного параметра 'id' переданного методом GET.
            $id = $this->request->getQuery('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }

            // Поиск единицы измерения.
            $unit = Units::findFirstById($id);
            if (!$unit) {
                throw new EngsurveyException('Единица измерения не найдена.');
            }

            // Найденные данные связываются с формой,
            // передавая модель первым параметром.
            $this->view->form = new UnitForm($unit);
        } else {
            // Форма, используемая в представлении.
            $this->view->form = new UnitForm();
        }

        // Шаблон представления.
        $this->view->pick('units/edit');
    }


    /**
     * Создает новую единицу измерения на основе данных,
     * введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Units', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('units');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы создания новой единицы измерения
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Получение данных.
        $name = $this->request->getPost('name', 'trim');
        $symbol = $this->request->getPost('symbol', 'trim');

        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой записи.
        $unit = new Units();
        $unit->setId($id);
        $unit->setName($name);
        $unit->setSymbol($symbol);

        if ($unit->create() === false) {
            $this->flashSession->error('Ошибка создания новой единицы измерения.');

            return $this->dispatcher->forward(['action' => 'new']);
        }

        // HTTP редирект.
        return $this->response->redirect('units');
    }


    /**
     * Изменяет запись о единицы измерения на основе данных,
     * введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Units', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('units');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Получение и валидация ID единицы измерения.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID единицы измерения.');
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы редактирования единицы измерения
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // Поиск единицы измерения.
        $unit = Units::findFirstById($id);
        if (!$unit) {
            throw new EngsurveyException('Единица измерения не найдена.');
        }

        // Получение данных.
        $name = $this->request->getPost('name', 'trim');
        $symbol = $this->request->getPost('symbol', 'trim');

        // Обновление единицы измерения.
        $unit->setName($name);
        $unit->setSymbol($symbol);

        if ($unit->update() === false) {
            throw new EngsurveyException('Не удалось обновить единицу измерения.');
        }

        // HTTP редирект.
        return $this->response->redirect('units');
    }


    /**
     * Удаляет единицу измерения.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Units', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('units');
        }
        
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        // Поиск единицы измерения.
        $unit = Units::findFirstById($id);
        if (!$unit) {
            throw new EngsurveyException('Единица измерения не найдена.');
        }

        $name = $unit->getName();

        // Удаление единицы измерения.
        if ($unit->delete() === false) {
            $messages = $unit->getMessages();
            
            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }
        } else {
            $message = 'Единица измерения «' . $name . '» успешно удалена.';
            $this->flashSession->success($message);
        }

        // HTTP редирект.
        return $this->response->redirect('units');
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
        $form = new UnitForm();

        if (!$form->isValid($data)) {

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

            // Сообщения для элемента 'symbol'.
            $messages = $form->getMessagesFor('symbol');
            if (count($messages)) {
                $symbolMessages = '';

                foreach ($messages as $message) {
                    $symbolMessages .= $message . '<br>';
                }

                $symbolMessages = rtrim($symbolMessages, '<br>');

                $this->view->setVar('symbolMessages', $symbolMessages);
            }

            return false;
        }

        return true;
    }

}
