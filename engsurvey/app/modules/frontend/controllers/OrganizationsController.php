<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Organizations;
use Engsurvey\Frontend\Forms\OrganizationForm;
use Engsurvey\Utils\Uuid;

class OrganizationsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'organizations',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск организаций.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Шаблон, используемый для создания представления.
        $this->view->pick('organizations/search');

        // Поиск организаций и передача их представление.
        $parameters = [
            'order' => 'displayName',
        ];
        $organizations = Organizations::find($parameters);
        $this->view->setVar('organizations', $organizations);

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания новой организации.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Organizations', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('organizations');
        }
        
        // Шаблон, используемый для создания представления.
        $this->view->pick('organizations/new');

        // Форма, используемая в представлении.
        $this->view->form = new OrganizationForm;
    }


    /**
     * Отображает форму редактирования существующей организации.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Organizations', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('organizations');
        }
        
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');

        if (!Uuid::validate($id)) {
            // Попытка получение параметра 'id' из внутреннего перенаправления (forward).
            $id = $this->dispatcher->getParam('id');
            if (!Uuid::validate($id)) {
                $this->flashSession->error('Отсутствует или некорректный параметр URL.');
                return $this->response->redirect('organizations');
            }
        }

        // Поиск организации.
        $organization = Organizations::findFirstById($id);

        if (!$organization) {
            $this->flashSession->error('Организация не найдена.');
            return $this->response->redirect('organizations');
        }

        // Шаблон, используемый для создания представления.
        $this->view->pick('organizations/edit');

        // Найденные данные связываются с формой,
        // передавая модель первым параметром.
        $this->view->form = new OrganizationForm($organization);
    }


    /**
     * Создает организацию на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Organizations', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('organizations');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    'action' => 'index',
                ]
            );
        }

        $form = new OrganizationForm();

        $organization = new Organizations();

        // Валидация ввода.
        $data = $this->request->getPost();

        if (!$form->isValid($data, $organization)) {

            // Получение сообщений для элемента 'displayName'.
            $messages = $form->getMessagesFor('displayName');

            if (count($messages)) {
                $displayNameMessages = '';

                foreach ($messages as $message) {
                    if (empty($displayNameMessages)) {
                        $displayNameMessages = $message;
                    } else {
                        $displayNameMessages += $message . "<br>";
                    }
                }

                $displayNameMessages = rtrim($displayNameMessages, "<br>");

                $this->view->setVar('displayNameMessages', $displayNameMessages);
            }

            // Получение сообщений для элемента 'shortName'.
            $messages = $form->getMessagesFor('shortName');

            if (count($messages)) {
                $shortNameMessages = '';

                foreach ($messages as $message) {
                    if (empty($shortNameMessages)) {
                        $shortNameMessages = $message;
                    } else {
                        $shortNameMessages += $message . "<br>";
                    }
                }

                $shortNameMessages = rtrim($shortNameMessages, "<br>");

                $this->view->setVar('shortNameMessages', $shortNameMessages);
            }

            // Повторный вывод формы создания новой организации
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'new',
                ]
            );

        }

        // Фильтрация данных полученных методом POST.
        $displayName = $this->request->getPost('displayName', 'trim');
        $shortName = $this->request->getPost('shortName', 'trim');
        $fullName = $this->request->getPost('fullName', 'trim');
        $additionalInfo = $this->request->getPost('additionalInfo', 'trim');

        // Генерация ID для новой организации.
        $id = Uuid::generate();

        // Создание новой организации.
        $organization->setId($id);
        $organization->setDisplayName($displayName);
        $organization->setShortName($shortName);
        $organization->setFullName($fullName);
        $organization->setAdditionalInfo($additionalInfo);

        if ($organization->create() === false) {
            $this->flashSession->error('Ошибка создания новой организации.');
            
            return $this->dispatcher->forward(
                [
                    'action' => 'new',
                ]
            );
        }
        
        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('organizations');
    }


    /**
     * Изменяет организацию на основе данных, введенных в действии "edit".
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Organizations', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('organizations');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            $this->flashSession->error('Ошибка получения данных методом POST.');
            return $this->response->redirect('organizations');
        }
        
        $id = $this->request->getPost('id');
        
        if (!Uuid::validate($id)) {
            $this->flashSession->error('Некорректный ID организации.');
            return $this->response->redirect('organizations');
        }
        
        // Поиск организации.
        $organization = Organizations::findFirstById($id);

        if (!$organization) {
            $this->flashSession->error('Организация не найдена.');
            return $this->response->redirect('organizations');
        }
        
        $form = new OrganizationForm();

        $organization = new Organizations();

        // Валидация ввода.
        $data = $this->request->getPost();

        if (!$form->isValid($data, $organization)) {

            // Получение сообщений для элемента 'displayName'.
            $messages = $form->getMessagesFor('displayName');

            if (count($messages)) {
                $displayNameMessages = '';

                foreach ($messages as $message) {
                    if (empty($displayNameMessages)) {
                        $displayNameMessages = $message;
                    } else {
                        $displayNameMessages += $message . "<br>";
                    }
                }

                $displayNameMessages = rtrim($displayNameMessages, "<br>");

                $this->view->setVar('displayNameMessages', $displayNameMessages);
            }

            // Получение сообщений для элемента 'shortName'.
            $messages = $form->getMessagesFor('shortName');

            if (count($messages)) {
                $shortNameMessages = '';

                foreach ($messages as $message) {
                    if (empty($shortNameMessages)) {
                        $shortNameMessages = $message;
                    } else {
                        $shortNameMessages += $message . "<br>";
                    }
                }

                $shortNameMessages = rtrim($shortNameMessages, "<br>");

                $this->view->setVar('shortNameMessages', $shortNameMessages);
            }

            // Повторный вывод формы редактирования организации
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );

        }
        
        // Фильтрация данных полученных методом POST.
        $displayName = $this->request->getPost('displayName', 'trim');
        $shortName = $this->request->getPost('shortName', 'trim');
        $fullName = $this->request->getPost('fullName', 'trim');
        $additionalInfo = $this->request->getPost('additionalInfo', 'trim');
        
        // Обновление данных по организации.
        $organization->setDisplayName($displayName);
        $organization->setShortName($shortName);
        $organization->setFullName($fullName);
        $organization->setAdditionalInfo($additionalInfo);

        if ($organization->update() === false) {
            $this->flashSession->error('Не удалось сохранить изменения организации.');
            
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }
        
        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('organizations');

    }


    /**
     * Удаляет организацию.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Organizations', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('organizations');
        }
        
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');

        if (!Uuid::validate($id)) {
            // Попытка получение параметра 'id' из внутреннего перенаправления (forward).
            $id = $this->dispatcher->getParam('id');
            if (!Uuid::validate($id)) {
                $this->flashSession->error('Отсутствует или некорректный параметр URL.');
                return $this->response->redirect('organizations');
            }
        }
        
        // Поиск организации.
        $organization = Organizations::findFirstById($id);

        if (!$organization) {
            $this->flashSession->error('Организация не найдена.');
            return $this->response->redirect('organizations');
        }
        
        $shortName = $organization->getShortName();

        // Удаление организации.
        if ($organization->delete() === false) {
            $msg = "Не удалось удалить организацию ${shortName}.";
            $this->flashSession->error($msg);
        } else {
            $msg = "Организация ${shortName} успешно удалена.";
            $this->flashSession->success($msg);
        }

        // HTTP редирект.
        return $this->response->redirect('organizations');
    }

}

