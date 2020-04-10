<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\ConstructionSites;
use Engsurvey\Models\ConstructionProjects;
use Engsurvey\Frontend\Forms\ConstructionSiteForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;


class ConstructionSitesController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'construction-sites',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск участков работ.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Получение и валидация обязательного параметра 'construction-project-id' переданного методом GET.
        $constructionProjectId = $this->request->getQuery('construction-project-id');
        if (!Uuid::validate($constructionProjectId)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        // Поиск стройки и передача ее в представление.
        $constructionProject = ConstructionProjects::findFirstById($constructionProjectId);
        $this->view->setVar('constructionProject', $constructionProject);

        // Поиск участков работ и передача их представление.
        $constructionSites = ConstructionSites::find(
            [
                "constructionProjectId = '$constructionProjectId'",
                'order' => 'siteNumber',
            ]
        );
        $this->view->setVar('constructionSites', $constructionSites);
        
        // Шаблон, используемый для создания представления.
        $this->view->pick('construction-sites/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания нового участка работ.
     */
    public function newAction()
    {
        // Получение и валидация обязательного параметра 'construction-project-id' переданного методом GET.
        $constructionProjectId = $this->request->getQuery('construction-project-id');
        
        if (!Uuid::validate($constructionProjectId)) {
            // Попытка получение параметра 'constructionProjectId' из внутреннего перенаправления (forward).
            $constructionProjectId = $this->dispatcher->getParam('construction-project-id');
            if (!Uuid::validate($constructionProjectId)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }
        }
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionSites', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
        }

        // Поиск стройки и передача ее в представление.
        $constructionProject = ConstructionProjects::findFirstById($constructionProjectId);
        if ($constructionProject === false) {
            throw new EngsurveyException('Стройка не найдена.');
        }
        $this->view->setVar('constructionProject', $constructionProject);
        
        // Шаблон, используемый при создании представления.
        $this->view->pick('construction-sites/new');

        // Форма, используемая в представлении и значение формы по умолнанию.
        $this->view->form = new ConstructionSiteForm();
        $this->tag->setDefault('constructionProjectId', $constructionProjectId);
    }


    /**
     * Отображает форму редактирования существующего участка работ.
     */
    public function editAction()
    {
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');

        if (!Uuid::validate($id)) {
            // Попытка получение параметра 'id' из внутреннего перенаправления (forward).
            $id = $this->dispatcher->getParam('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }
        }

        // Поиск участка работ.
        $site = ConstructionSites::findFirstById($id);
        if (!$site) {
            throw new EngsurveyException('Участок работ не найден.');
        }
        
        // Получение стройки в представление.
        $constructionProject = $site->getConstructionProject();
        $constructionProjectId = $site->getConstructionProjectId();
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionSites', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
        }
        
        // Передача стройки в представление.
        $this->view->setVar('constructionProject', $constructionProject);

        // Шаблон, используемый для создания представления.
        $this->view->pick('construction-sites/edit');

        // Найденные данные связываются с формой,
        // передавая модель первым параметром.
        $this->view->form = new ConstructionSiteForm($site);
    }


    /**
     * Создает участок работ на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            throw new EngsurveyException('Не удалось получить данные методом POST.');
        }

        $form = new ConstructionSiteForm();
        $site = new ConstructionSites();

        // Валидация ввода.
        $data = $this->request->getPost();

        if (!$form->isValid($data, $site)) {
            // Получение сообщений для элемента 'siteNumber'.
            $messages = $form->getMessagesFor('siteNumber');
            if (count($messages)) {
                $siteNumberMessages = '';

                foreach ($messages as $message) {
                    if (empty($siteNumberMessages)) {
                        $siteNumberMessages = $message;
                    } else {
                        $siteNumberMessages += $message . "<br>";
                    }
                }

                $siteNumberMessages = rtrim($siteNumberMessages, "<br>");

                $this->view->setVar('siteNumberMessages', $siteNumberMessages);
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

            // Повторный вывод формы создания нового участка работ
            // с сообщениями об ошибках.
            $constructionProjectId = $this->request->getPost('constructionProjectId');
            return $this->dispatcher->forward(
                [
                    'action' => 'new',
                    'params' => ['construction-project-id' => $constructionProjectId],
                ]
            );
        }

        // Получение данных методом POST.
        $constructionProjectId = $this->request->getPost('constructionProjectId');
        $siteNumber = $this->request->getPost('siteNumber', 'trim');
        $name = $this->request->getPost('name', 'trim');
        $chiefProjectEngineerId = $this->request->getPost('chiefProjectEngineerId');
        $reportLink = $this->request->getPost('reportLink', 'trim');
        $mapLink = $this->request->getPost('mapLink', 'trim');
        $comment = $this->request->getPost('comment', 'trim');
        
        if (!Uuid::validate($chiefProjectEngineerId)) {
            $chiefProjectEngineerId = null;
        }
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionSites', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
        }

        // Генерация ID для нового участка работ.
        $id = Uuid::generate();

        // Создание нового участка работ.
        $site->setId($id);
        $site->setConstructionProjectId($constructionProjectId);
        $site->setSiteNumber($siteNumber);
        $site->setName($name);
        $site->setChiefProjectEngineerId($chiefProjectEngineerId);
        $site->setReportLink($reportLink);
        $site->setMapLink($mapLink);
        $site->setComment($comment);

        if ($site->create() === false) {
            $msg = 'Не удалось создать новый участкок работ: <br>';
            foreach ($site->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
    }


    /**
     * Изменяет участок работ на основе данных, введенных в действии "edit".
     */
    public function updateAction()
    {
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            throw new EngsurveyException('Не удалось получить данные методом POST.');
        }
        
        $id = $this->request->getPost('id');
        
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID участка работ.');
        }

        // Поиск участка работ.
        $site = ConstructionSites::findFirstById($id);
        if (!$site) {
            throw new EngsurveyException('Участок работ не найден.');
        }
        
        // Форма.
        $form = new ConstructionSiteForm();

        // Получение данных методом POST.
        $data = $this->request->getPost();

        // Валидация ввода.
        if (!$form->isValid($data, $site)) {
            // Получение сообщений для элемента 'siteNumber'.
            $messages = $form->getMessagesFor('siteNumber');
            if (count($messages)) {
                $siteNumberMessages = '';

                foreach ($messages as $message) {
                    if (empty($siteNumberMessages)) {
                        $siteNumberMessages = $message;
                    } else {
                        $siteNumberMessages += $message . "<br>";
                    }
                }

                $siteNumberMessages = rtrim($siteNumberMessages, "<br>");

                $this->view->setVar('siteNumberMessages', $siteNumberMessages);
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

            // Повторный вывод формы редактирования участка работ
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }
        
        // Получение данных методом POST.
        $constructionProjectId = $this->request->getPost('constructionProjectId');
        $siteNumber = $this->request->getPost('siteNumber', 'trim');
        $name = $this->request->getPost('name', 'trim');
        $chiefProjectEngineerId = $this->request->getPost('chiefProjectEngineerId');
        $reportLink = $this->request->getPost('reportLink', 'trim');
        $mapLink = $this->request->getPost('mapLink', 'trim');
        $comment = $this->request->getPost('comment', 'trim');
        
        if (!Uuid::validate($chiefProjectEngineerId)) {
            $chiefProjectEngineerId = null;
        }
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionSites', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
        }

        // Обновление данных по участку работ.
        $site->setSiteNumber($siteNumber);
        $site->setName($name);
        $site->setChiefProjectEngineerId($chiefProjectEngineerId);
        $site->setReportLink($reportLink);
        $site->setMapLink($mapLink);
        $site->setComment($comment);

        if ($site->update() === false) {
            $msg = 'Не удалось сохранить изменения участка работ: <br>';
            foreach ($site->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }
        
        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
    }


    /**
     * Удаляет участок работ.
     */
    public function deleteAction()
    {
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');

        if (!Uuid::validate($id)) {
            // Попытка получение параметра 'id' из внутреннего перенаправления (forward).
            $id = $this->dispatcher->getParam('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }
        }
        
        // Поиск участка работ.
        $site = ConstructionSites::findFirstById($id);
        if (!$site) {
            throw new EngsurveyException('Участок работ не найден.');
        }

        $constructionProjectId = $site->getConstructionProjectId();
        $name = $site->getName();
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ConstructionSites', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
        }

        // Удаление участка работ.
        if ($site->delete() === false) {
            $msg = 'Не удалось удалить участок работ "' . $name. '": <br>';
            foreach ($site->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);            
        }
        
        $msg = "Участок работ «${name}» успешно удален.";
        $this->flashSession->success($msg);

        // HTTP редирект.
        return $this->response->redirect('construction-sites?construction-project-id=' . $constructionProjectId);
    }
    
    
    /**
     * Определяет, в зависимости от полученных парметров,
     * какая функция будет выполнять JSON-запрос.
     */
    public function getJsonAction()
    {
        // Получение параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');
        
        // Получение параметра 'construction_project_id' переданного методом GET.
        $constructionProjectId = $this->request->getQuery('construction_project_id');
        
        if (!is_null($id)) {
            if (Uuid::validate($id)) {
                $this->getJsonById($id);
            } else {
                throw new EngsurveyException('Некорректный параметр URL.');
            }
        } elseif (!is_null($constructionProjectId)) {
            if (Uuid::validate($constructionProjectId)) {
                $this->getJsonByConstructionProjectId($constructionProjectId);
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
     * Выполняет поиск участков работ.
     * Возвращает результат поиска в формате JSON.
     */
    protected function getJson()
    {
        $sites = ConstructionSites::find(
            [
                'order' => 'siteNumber',
            ]
        );

        if (count($sites) === 0) {
            $data = ['status' => 'not_found'];
        } else {
            $construction_sites = [];
            foreach ($sites as $site) {
                $construction_projects[] = [
                    'id' => $site->getId(),
                    'construction_project_id' => $site->getConstructionProjectId(),
                    'site_number' => $site->getSiteNumber(),
                    'name' => $site->getName(),
                    'chief_project_engineer_id' => $site->getChiefProjectEngineerId(),
                    'report_link' => $site->getReportLink(),
                    'map_link' => $site->getMapLink(),
                    'comment' => $site->getComment(),
                ];
            }
            
            $data = [
                'status' => 'found',
                'construction_sites' => $construction_sites,
            ];
        }
        
        echo json_encode($data);
    } 

    
    /**
     * Выполняет поиск участка работ по ID.
     * Возвращает результат поиска в формате JSON.
     */
    protected function getJsonById(string $id)
    {
        $site = ConstructionSites::findFirstById($id);

        if ($site === false) {
            $data = ['status' => 'not_found'];
        } else {
            $data = [
                'status' => 'found',
                'construction_site' => [
                    'id' => $site->getId(),
                    'construction_project_id' => $site->getConstructionProjectId(),
                    'site_number' => $site->getSiteNumber(),
                    'name' => $site->getName(),
                    'chief_project_engineer_id' => $site->getChiefProjectEngineerId(),
                    'report_link' => $site->getReportLink(),
                    'map_link' => $site->getMapLink(),
                    'comment' => $site->getComment(),
                ]
            ];
        }

        echo json_encode($data);
    }
    
    
    /**
     * Выполняет поиск участков работ относящихся к стройке.
     * Возвращает результат поиска в формате JSON.
     */
    protected function getJsonByConstructionProjectId($constructionProjectId)
    {
        $sites = ConstructionSites::find(
            [
                "constructionProjectId = '$constructionProjectId'",
                'order' => 'siteNumber',
            ]
        );

        if (count($sites) === 0) {
            $data = ['status' => 'not_found'];
        } else {
            $construction_sites = [];
            foreach ($sites as $site) {
                $construction_sites[] = [
                    'id' => $site->getId(),
                    'construction_project_id' => $site->getConstructionProjectId(),
                    'site_number' => $site->getSiteNumber(),
                    'name' => $site->getName(),
                    'chief_project_engineer_id' => $site->getChiefProjectEngineerId(),
                    'report_link' => $site->getReportLink(),
                    'map_link' => $site->getMapLink(),
                    'comment' => $site->getComment(),
                ];
            }
            
            $data = [
                'status' => 'found',
                'construction_sites' => $construction_sites,
            ];
        }
        
        echo json_encode($data);
    } 

}
