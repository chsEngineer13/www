<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Branches;
use Engsurvey\Frontend\Forms\BranchForm;
use Engsurvey\Utils\Uuid;
//use Engsurvey\Exception as EngsurveyException;

use Engsurvey\Models\Test;

class BranchesController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к 'search'.
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'branches',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск филиалов.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск филиалов и передача их представление.
        $parameters = [
            'order' => 'sequenceNumber',
        ];
        $branches = Branches::find($parameters);
        
        $this->view->setVar('branches', $branches);
        
        // Шаблон, используемый для создания представления.
        $this->view->pick('branches/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }
    
    
    /**
     * Отображает форму создания нового филиала.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Branches', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('branches');
        }

        // Форма, используемая в представлении.
        $this->view->form = new BranchForm;
        
        // Шаблон, используемый для создания представления.
        $this->view->pick('branches/new');
    }
    
    
    /**
     * Отображает форму редактирования филиала.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Branches', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('branches');
        }
        
        if (!$this->request->isPost()) {
            // Получение и валидация обязательного параметра 'id' переданного методом GET.
            $id = $this->request->getQuery('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }

            // Поиск филиала.
            $branch = Branches::findFirstById($id);
            if (!$branch) {
                throw new EngsurveyException('Филиал не найден.');
            }

            // Найденные данные связываются с формой,
            // передавая модель первым параметром.
            $this->view->form = new BranchForm($branch);
        } else {
            // Форма, используемая в представлении.
            $this->view->form = new BranchForm();
        }

        // Шаблон представления.
        $this->view->pick('branches/edit');
    }
    
    
    /**
     * Создает новый филиал на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Branches', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('branches');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Валидация данных полученных из формы.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы создания нового филиала
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Сохранение данных полученных из формы в переменных.
        $sequenceNumber = $this->request->getPost('sequenceNumber', 'trim');
        $organizationId = $this->request->getPost('organizationId');
        $displayName = $this->request->getPost('displayName', 'trim');
        $code = $this->request->getPost('code', 'trim');
        
        // Если значение порядкового номера не указано, будет присвоено
        // следующее по порядку значение.
        if ($sequenceNumber === '') {
            $sequenceNumber = (int)Branches::getLastSequenceNumber() + 1;
        }

        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой записи.
        $branch = new Branches();
        $branch->setId($id);
        $branch->setSequenceNumber($sequenceNumber);
        $branch->setOrganizationId($organizationId);
        $branch->setDisplayName($displayName);
        $branch->setCode($code);

        if ($branch->create() === false) {
            $this->flashSession->error('Ошибка создания нового филиала.');
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // HTTP редирект.
        return $this->response->redirect('branches');
    }
    
    
    /**
     * Изменяет запись о филиале на основе данных, введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Branches', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('branches');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Получение и валидация ID филиала.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID филиала.');
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы редактирования данных о филиале
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // Сохранение данных полученных из формы в переменных.
        $sequenceNumber = $this->request->getPost('sequenceNumber', 'trim');
        $organizationId = $this->request->getPost('organizationId');
        $displayName = $this->request->getPost('displayName', 'trim');
        $code = $this->request->getPost('code', 'trim');
        
        // Если значение порядкового номера не указано, будет присвоено
        // следующее по порядку значение.
        if ($sequenceNumber === '') {
            $sequenceNumber = (int)Branches::getLastSequenceNumber() + 1;
        }

        // Поиск филиала.
        $branch = Branches::findFirstById($id);
        if (!$branch) {
            throw new EngsurveyException('Филиал не найден.');
        }

        // Обновление записи.
        $branch->setSequenceNumber((int)$sequenceNumber);
        $branch->setOrganizationId($organizationId);
        $branch->setDisplayName($displayName);
        $branch->setCode($code);

        if ($branch->update() === false) {
            throw new EngsurveyException('Не удалось обновить филиал.');
        }

        // HTTP редирект.
        return $this->response->redirect('branches');
    }
    
    
    /**
     * Удаляет филиал.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Branches', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('branches');
        }
        
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');

        if (!Uuid::validate($id)) {
            // Попытка получение параметра 'id' из внутреннего перенаправления (forward).
            $id = $this->dispatcher->getParam('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }
        }

        // Поиск филиала.
        $branch = Branches::findFirstById($id);
        if (!$branch) {
            throw new EngsurveyException('Филиал не найдено.');
        }

        $displayName = $branch->getDisplayName();

        // Удаление филиала.
        if ($branch->delete() === false) {
            $msg = 'Не удалось удалить филиал "' . $displayName. '".';
            foreach ($branch->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $msg = 'Филиал «' . $displayName . '» успешно удален.';
        $this->flashSession->success($msg);

        // HTTP редирект.
        return $this->response->redirect('branches');
    }
    
    
    /**
     * Выполняет валидацию данных полученных из формы
     * и формирует сообщения об ошибках с передачей их в представление.
     * Возвращают булево значение, показывающее, прошла валидация
     * успешно, либо нет.
     *
     * @param  array $data Данные полученные из формы.
     * @return bool
     */
    protected function validate(array $data): bool
    {
        $form = new BranchForm();

        if ($form->isValid($data) === false) {

            // Сообщения для элемента формы 'sequenceNumber'.
            $messages = $form->getMessagesFor('sequenceNumber');
            if (count($messages)) {
                $sequenceNumberMessages = '';

                foreach ($messages as $message) {
                    $sequenceNumberMessages .= $message . '<br>';
                }

                $sequenceNumberMessages = rtrim($sequenceNumberMessages, '<br>');

                $this->view->setVar('sequenceNumberMessages', $sequenceNumberMessages);
            }

            // Сообщения для элемента формы 'organizationId'.
            $messages = $form->getMessagesFor('organizationId');
            if (count($messages)) {
                $organizationMessages = '';

                foreach ($messages as $message) {
                    $organizationMessages .= $message . '<br>';
                }

                $organizationMessages = rtrim($organizationMessages, '<br>');

                $this->view->setVar('organizationMessages', $organizationMessages);
            }

            // Сообщения для элемента формы 'displayName'.
            $messages = $form->getMessagesFor('displayName');
            if (count($messages)) {
                $displayNameMessages = '';

                foreach ($messages as $message) {
                    $displayNameMessages .= $message . '<br>';
                }

                $displayNameMessages = rtrim($displayNameMessages, '<br>');

                $this->view->setVar('displayNameMessages', $displayNameMessages);
            }

            // Сообщения для элемента формы 'code'.
            $messages = $form->getMessagesFor('code');
            if (count($messages)) {
                $codeMessages = '';

                foreach ($messages as $message) {
                    $codeMessages .= $message . '<br>';
                }

                $codeMessages = rtrim($codeMessages, '<br>');

                $this->view->setVar('codeMessages', $codeMessages);
            }

            return false;
        }

        return true;
    }

}
