<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\ConstructionProjects;
use Engsurvey\Frontend\Forms\ConstructionProjectForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;


class ConstructionProjectsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'construction-projects',
                'action' => 'search',
            ]
        );
    }


    /**
     * Выполняет поиск строек.
     * Возвращает результаты с пагинацией.
     */
    public function searchAction()
    {
        // Шаблон, используемый для создания представления.
        $this->view->pick('construction-projects/search');

        // Поиск строек и передача их представление.
        $parameters = [
            'order' => 'code',
        ];
        $constructionProjects = ConstructionProjects::find($parameters);
        $this->view->setVar('constructionProjects', $constructionProjects);

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания новой стройки.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionProjects', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-projects');
        }
        
        // Шаблон, используемый при создании представления.
        $this->view->pick('construction-projects/new');

        // Форма, используемая в представлении.
        $this->view->form = new ConstructionProjectForm;
    }


    /**
     * Отображает форму редактирования существующей стройки.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionProjects', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-projects');
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

        // Поиск стройки.
        $project = ConstructionProjects::findFirstById($id);
        if (!$project) {
            throw new EngsurveyException('Стройка не найдена.');
        }

        // Шаблон, используемый для создания представления.
        $this->view->pick('construction-projects/edit');

        // Найденные данные связываются с формой,
        // передавая модель первым параметром.
        $this->view->form = new ConstructionProjectForm($project);
    }


    /**
     * Создает стройку на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionProjects', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-projects');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            throw new EngsurveyException('Не удалось получить данные методом POST.');
        }

        $form = new ConstructionProjectForm();

        $project = new ConstructionProjects();

        // Валидация ввода.
        $data = $this->request->getPost();

        if (!$form->isValid($data, $project)) {

            // Получение сообщений для элемента 'code'.
            $messages = $form->getMessagesFor('code');

            if (count($messages)) {
                $codeMessages = '';

                foreach ($messages as $message) {
                    if (empty($codeMessages)) {
                        $codeMessages = $message;
                    } else {
                        $codeMessages += $message . "<br>";
                    }
                }

                $codeMessages = rtrim($codeMessages, "<br>");

                $this->view->setVar('codeMessages', $codeMessages);
            }

            // Получение сообщений для элемента 'name'.
            $messages = $form->getMessagesFor('name');

            if (count($messages)) {
                $nameMessages = '';

                foreach ($messages as $message) {
                    if (empty($nameMessages)) {
                        $nameMessages = $message;
                    } else {
                        $nameMessages += $message . "<br>";
                    }
                }

                $nameMessages = rtrim($nameMessages, "<br>");

                $this->view->setVar('nameMessages', $nameMessages);
            }

            // Получение сообщений для элемента 'customerId'.
            $messages = $form->getMessagesFor('customerId');

            if (count($messages)) {
                $customerIdMessages = '';

                foreach ($messages as $message) {
                    if (empty($customerIdMessages)) {
                        $customerIdMessages = $message;
                    } else {
                        $customerIdMessages += $message . "<br>";
                    }
                }

                $customerIdMessages = rtrim($customerIdMessages, "<br>");

                $this->view->setVar('customerIdMessages', $customerIdMessages);
            }

            // Повторный вывод формы создания новой стройки
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'new',
                ]
            );
        }

        // Получение данных методом POST.
        $code = $this->request->getPost('code', 'trim');
        $name = $this->request->getPost('name', 'trim');
        $constructionTypeId = $this->request->getPost('constructionTypeId');
        $customerId = $this->request->getPost('customerId');
        $technicalDirectorId = $this->request->getPost('technicalDirectorId');
        $reportLink = $this->request->getPost('reportLink', 'trim');
        $mapLink = $this->request->getPost('mapLink', 'trim');
        $comment = $this->request->getPost('comment', 'trim');
        
        if (!Uuid::validate($technicalDirectorId)) {
            $technicalDirectorId = null;
        }

        // Генерация ID для новой стройки.
        $id = Uuid::generate();

        // Создание новой стройки.
        $project->setId($id);
        $project->setCode($code);
        $project->setName($name);
        $project->setConstructionTypeId($constructionTypeId);
        $project->setCustomerId($customerId);
        $project->setTechnicalDirectorId($technicalDirectorId);
        $project->setReportLink($reportLink);
        $project->setMapLink($mapLink);
        $project->setComment($comment);

        if ($project->create() === false) {
            $msg = 'Не удалось создать новую стройку: <br>';
            foreach ($project->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('construction-projects');
    }


    /**
     * Изменяет стройку на основе данных, введенных в действии "edit".
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionProjects', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-projects');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            throw new EngsurveyException('Не удалось получить данные методом POST.');
        }

        $id = $this->request->getPost('id');

        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID стройки.');
        }

        // Поиск стройки.
        $project = ConstructionProjects::findFirstById($id);
        if (!$project) {
            throw new EngsurveyException('Стройка не найдена.');
        }

        // Форма.
        $form = new ConstructionProjectForm();

        //Получение данных методом POST.
        $data = $this->request->getPost();

        // Валидация ввода.
        if (!$form->isValid($data, $project)) {

            // Получение сообщений для элемента 'code'.
            $messages = $form->getMessagesFor('code');
            if (count($messages)) {
                $codeMessages = '';

                foreach ($messages as $message) {
                    if (empty($codeMessages)) {
                        $codeMessages = $message;
                    } else {
                        $codeMessages += $message . "<br>";
                    }
                }

                $codeMessages = rtrim($codeMessages, "<br>");

                $this->view->setVar('codeMessages', $codeMessages);
            }

            // Получение сообщений для элемента 'name'.
            $messages = $form->getMessagesFor('name');
            if (count($messages)) {
                $nameMessages = '';

                foreach ($messages as $message) {
                    if (empty($nameMessages)) {
                        $nameMessages = $message;
                    } else {
                        $nameMessages += $message . "<br>";
                    }
                }

                $nameMessages = rtrim($nameMessages, "<br>");

                $this->view->setVar('nameMessages', $nameMessages);
            }

            // Получение сообщений для элемента 'customerId'.
            $messages = $form->getMessagesFor('customerId');
            if (count($messages)) {
                $customerIdMessages = '';

                foreach ($messages as $message) {
                    if (empty($customerIdMessages)) {
                        $customerIdMessages = $message;
                    } else {
                        $customerIdMessages += $message . "<br>";
                    }
                }

                $customerIdMessages = rtrim($customerIdMessages, "<br>");

                $this->view->setVar('customerIdMessages', $customerIdMessages);
            }

            // Повторный вывод формы редактирования стройки
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );

        }

        // Получение данных методом POST.
        $code = $this->request->getPost('code', 'trim');
        $name = $this->request->getPost('name', 'trim');
        $constructionTypeId = $this->request->getPost('constructionTypeId');
        $customerId = $this->request->getPost('customerId');
        $technicalDirectorId = $this->request->getPost('technicalDirectorId');
        $reportLink = $this->request->getPost('reportLink', 'trim');
        $mapLink = $this->request->getPost('mapLink', 'trim');
        $comment = $this->request->getPost('comment', 'trim');
        
        if (!Uuid::validate($technicalDirectorId)) {
            $technicalDirectorId = null;
        }

        // Обновление данных по стройки.
        $project->setCode($code);
        $project->setName($name);
        $project->setConstructionTypeId($constructionTypeId);
        $project->setCustomerId($customerId);
        $project->setTechnicalDirectorId($technicalDirectorId);
        $project->setReportLink($reportLink);
        $project->setMapLink($mapLink);
        $project->setComment($comment);

        if ($project->update() === false) {
            $msg = 'Не удалось сохранить изменения стройки: <br>';
            foreach ($project->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('construction-projects');
    }


    /**
     * Удаляет стройку.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionProjects', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-projects');
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

        // Поиск стройки.
        $project = ConstructionProjects::findFirstById($id);
        if (!$project) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        $name = $project->getName();

        // Удаление стройки.
        if ($project->delete() === false) {
            $msg = 'Не удалось удалить стройку: <br>';
            foreach ($project->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }
        
        $msg = "Стройка «${name}» успешно удалена.";
        $this->flashSession->success($msg);

        // HTTP редирект.
        return $this->response->redirect('construction-projects');
    }
    
    
    /**
     * Определяет, в зависимости от полученных парметров,
     * какая функция будет выполнять запрос.
     */
    public function getJsonAction()
    {
        // Получение параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');
        
        if (!is_null($id)) {
            if (Uuid::validate($id)) {
                $this->getJsonById($id);
            } else {
                throw new EngsurveyException('Некорректный параметр URL.');
            }
        } else {
            $this->getJson();
        }
        
        // Отключение компонента представлений.
        $this->view->disable();
    }  


    /**
     * Выполняет поиск строек.
     * Возвращает результат поиска в формате JSON.
     */
    protected function getJson()
    {
        // Поиск строек.
        $projects = ConstructionProjects::find();

        if (count($projects) === 0) {
            $jsonContent = ['status' => 'not_found'];
        } else {
            $construction_projects = [];
            foreach ($projects as $project) {
                $construction_projects[] = [
                    'id' => $project->getId(),
                    'code' => $project->getCode(),
                    'name' => $project->getName(),
                    'construction_type_id' => $project->getConstructionTypeId(),
                    'customer_id' => $project->getCustomerId(),
                    'technical_director_id' => $project->getTechnicalDirectorId(),
                    'report_link' => $project->getReportLink(),
                    'map_link' => $project->getMapLink(),
                    'comment' => $project->getComment()
                ];
            }
            
            $jsonContent = [
                'status' => 'found',
                'construction_projects' => $construction_projects
            ];
        }
        
        echo json_encode($jsonContent);
    }    
    
    
    /**
     * Выполняет поиск стройки по ID.
     * Возвращает результат поиска в формате JSON.
     */
    public function getJsonById($id)
    {
        // Поиск стройки.
        $project = ConstructionProjects::findFirstById($id);

        if ($project === false) {
            $jsonContent = ['status' => 'not_found'];
        } else {
            $jsonContent = [
                'status' => 'found',
                'construction_project' => [
                    'id' => $project->getId(),
                    'code' => $project->getCode(),
                    'name' => $project->getName(),
                    'construction_type_id' => $project->getConstructionTypeId(),
                    'customer_id' => $project->getCustomerId(),
                    'technical_director_id' => $project->getTechnicalDirectorId(),
                    'report_link' => $project->getReportLink(),
                    'map_link' => $project->getMapLink(),
                    'comment' => $project->getComment()
                ]
            ];
        }
        
        echo json_encode($jsonContent);
    }

}
