<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Crews;
use Engsurvey\Frontend\Forms\CrewForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;

class CrewsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'crews',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск бригад.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск бригад и передача их в представление.
        $parameters = [
            'order' => 'crewName',
        ];
        $crews = Crews::find($parameters);
        $this->view->setVar('crews', $crews);
        
        // Шаблон, используемый для создания представления.
        $this->view->pick('crews/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания новой бригады.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Crews', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crews');
        }
        
        // Форма, используемая в представлении.
        $this->view->form = new CrewForm;
        
        // Шаблон, используемый для создания представления.
        $this->view->pick('crews/new');
    }
    
    
    /**
     * Отображает форму редактирования существующей бригады.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Crews', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crews');
        }
        
        if (!$this->request->isPost()) {
            // Получение и валидация обязательного параметра 'id' переданного методом GET.
            $id = $this->request->getQuery('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }
        
            // Поиск бригады.
            $crew = Crews::findFirstById($id);
            if (!$crew) {
                throw new EngsurveyException('Бригада не найдена.');
            }

            // Найденные данные связываются с формой,
            // передавая модель первым параметром.
            $this->view->form = new CrewForm($crew);
        }
        
        // Шаблон представления.
        $this->view->pick('crews/edit');
    }


    /**
     * Создает новую бригаду на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Crews', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crews');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        $form = new CrewForm();
        $crew = new Crews();

        // Валидация ввода.
        $postData = $this->request->getPost();

        if (!$form->isValid($postData, $crew)) {

            // Сообщения для элемента 'branchId'.
            $messages = $form->getMessagesFor('branchId');
            if (count($messages)) {
                $branchMessages = '';
                
                foreach ($messages as $message) {
                    $branchMessages .= $message . '<br>';
                }
                
                $branchMessages = rtrim($branchMessages, '<br>');
                
                $this->view->setVar('branchMessages', $branchMessages);
            }
            
            // Сообщения для элемента 'crewTypeId'.
            $messages = $form->getMessagesFor('crewTypeId');
            if (count($messages)) {
                $crewTypeMessages = '';
                
                foreach ($messages as $message) {
                    $crewTypeMessages .= $message . '<br>';
                }
                
                $crewTypeMessages = rtrim($crewTypeMessages, '<br>');
                
                $this->view->setVar('crewTypeMessages', $crewTypeMessages);
            }
            
            // Сообщения для элемента 'crewName'.
            $messages = $form->getMessagesFor('crewName');
            if (count($messages)) {
                $crewNameMessages = '';
                
                foreach ($messages as $message) {
                    $crewNameMessages .= $message . '<br>';
                }
                
                $crewNameMessages = rtrim($crewNameMessages, '<br>');
                
                $this->view->setVar('crewNameMessages', $crewNameMessages);
            }

            // Сообщения для элемента 'numberOfCrew'.
            $messages = $form->getMessagesFor('numberOfCrew');
            if (count($messages)) {
                $numberOfCrewMessages = '';
                
                foreach ($messages as $message) {
                    $numberOfCrewMessages .= $message . '<br>';
                }
                
                $numberOfCrewMessages = rtrim($numberOfCrewMessages, '<br>');
                
                $this->view->setVar('numberOfCrewMessages', $numberOfCrewMessages);
            }
            
            // Повторный вывод формы создания новой бригады
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Получение, фильтрация и форматирование данных.
        $branchId = $this->request->getPost('branchId');
        $crewTypeId = $this->request->getPost('crewTypeId');
        $crewName = $this->request->getPost('crewName', 'trim');
        
        $headLastName = $this->request->getPost('headLastName', 'trim');
        if (mb_strlen($headLastName) === 0) {
            $headLastName = null;
        }

        $headFirstName = $this->request->getPost('headFirstName', 'trim');
        if (mb_strlen($headFirstName) === 0) {
            $headFirstName = null;
        }
        
        $headMiddleName = $this->request->getPost('headMiddleName', 'trim');
        if (mb_strlen($headMiddleName) === 0) {
            $headMiddleName = null;
        }

        $phone = $this->request->getPost('phone', 'trim');
        if (mb_strlen($phone) === 0) {
            $phone = null;
        }

        $email = $this->request->getPost('email', 'trim');
        if (mb_strlen($email) === 0) {
            $email = null;
        }

        $numberOfCrew = $this->request->getPost('numberOfCrew', 'trim');
        if (mb_strlen($numberOfCrew) === 0) {
            $numberOfCrew = null;
        } else {
            $numberOfCrew = (int)$numberOfCrew;
        }

        $reportLink = $this->request->getPost('reportLink', 'trim');
        if (mb_strlen($reportLink) === 0) {
            $reportLink = null;
        }

        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой бригады.
        $crew->setId($id);
        $crew->setBranchId($branchId);
        $crew->setCrewTypeId($crewTypeId);
        $crew->setCrewName($crewName);
        $crew->setHeadLastName($headLastName);
        $crew->setHeadFirstName($headFirstName);
        $crew->setHeadMiddleName($headMiddleName);
        $crew->setPhone($phone);
        $crew->setEmail($email);
        $crew->setNumberOfCrew($numberOfCrew);
        $crew->setReportLink($reportLink);

        if ($crew->create() === false) {
            $this->flashSession->error('Ошибка создания новой бригады.');
            
            return $this->dispatcher->forward(
                [
                    'action' => 'new',
                ]
            );
        }

        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('crews');
    }


    /**
     * Изменяет запись о бригаде на основе данных, введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Crews', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crews');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }
        
        // Получение и валидация ID партии.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID партии.');
        }
        
        // Поиск партии.
        $crew = Crews::findFirstById($id);
        if (!$crew) {
            throw new EngsurveyException('Бригада не найдена.');
        }

        $form = new CrewForm();
        $this->view->form = $form;

        // Валидация данных полученных из формы.
        if (!$form->isValid($_POST, $crew)) {
            
            // Сообщения для элемента 'branchId'.
            $messages = $form->getMessagesFor('branchId');
            if (count($messages)) {
                $branchMessages = '';
                
                foreach ($messages as $message) {
                    $branchMessages .= $message . '<br>';
                }
                
                $branchMessages = rtrim($branchMessages, '<br>');
                
                $this->view->setVar('branchMessages', $branchMessages);
            }
            
            // Сообщения для элемента 'crewTypeId'.
            $messages = $form->getMessagesFor('crewTypeId');
            if (count($messages)) {
                $crewTypeMessages = '';
                
                foreach ($messages as $message) {
                    $crewTypeMessages .= $message . '<br>';
                }
                
                $crewTypeMessages = rtrim($crewTypeMessages, '<br>');
                
                $this->view->setVar('crewTypeMessages', $crewTypeMessages);
            }
            
            // Сообщения для элемента 'crewName'.
            $messages = $form->getMessagesFor('crewName');
            if (count($messages)) {
                $crewNameMessages = '';
                
                foreach ($messages as $message) {
                    $crewNameMessages .= $message . '<br>';
                }
                
                $crewNameMessages = rtrim($crewNameMessages, '<br>');
                
                $this->view->setVar('crewNameMessages', $crewNameMessages);
            }
            
            // Сообщения для элемента 'numberOfCrew'.
            $messages = $form->getMessagesFor('numberOfCrew');
            if (count($messages)) {
                $numberOfCrewMessages = '';
                
                foreach ($messages as $message) {
                    $numberOfCrewMessages .= $message . '<br>';
                }
                
                $numberOfCrewMessages = rtrim($numberOfCrewMessages, '<br>');
                
                $this->view->setVar('numberOfCrewMessages', $numberOfCrewMessages);
            }

            // Повторный вывод формы редактирования данных бригады
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // Получение, фильтрация и форматирование данных.
        $branchId = $this->request->getPost('branchId');
        $crewTypeId = $this->request->getPost('crewTypeId');
        $crewName = $this->request->getPost('crewName', 'trim');
        
        $headLastName = $this->request->getPost('headLastName', 'trim');
        if (mb_strlen($headLastName) === 0) {
            $headLastName = null;
        }

        $headFirstName = $this->request->getPost('headFirstName', 'trim');
        if (mb_strlen($headFirstName) === 0) {
            $headFirstName = null;
        }
        
        $headMiddleName = $this->request->getPost('headMiddleName', 'trim');
        if (mb_strlen($headMiddleName) === 0) {
            $headMiddleName = null;
        }

        $phone = $this->request->getPost('phone', 'trim');
        if (mb_strlen($phone) === 0) {
            $phone = null;
        }

        $email = $this->request->getPost('email', 'trim');
        if (mb_strlen($email) === 0) {
            $email = null;
        }
        
        $numberOfCrew = $this->request->getPost('numberOfCrew', 'trim');
        if (mb_strlen($numberOfCrew) === 0) {
            $numberOfCrew = null;
        } else {
            $numberOfCrew = (int)$numberOfCrew;
        }
        
        $reportLink = $this->request->getPost('reportLink', 'trim');
        if (mb_strlen($reportLink) === 0) {
            $reportLink = null;
        }
        
        // Обновление данных о бригаде.
        $crew->setBranchId($branchId);
        $crew->setCrewTypeId($crewTypeId);
        $crew->setCrewName($crewName);
        $crew->setHeadLastName($headLastName);
        $crew->setHeadFirstName($headFirstName);
        $crew->setHeadMiddleName($headMiddleName);
        $crew->setPhone($phone);
        $crew->setEmail($email);
        $crew->setNumberOfCrew($numberOfCrew);
        $crew->setReportLink($reportLink);

        if ($crew->update() === false) {
            $msg = 'Не удалось сохранить изменения бригады: <br>';
            foreach ($crew->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }
        
        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('crews');
    }


    /**
     * Удаляет бригаду.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Crews', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crews');
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
        
        // Поиск бригады.
        $crew = Crews::findFirstById($id);
        if (!$crew) {
            throw new EngsurveyException('Бригада не найдена.');
        }


        $crewName = $crew->getCrewName();

        // Удаление участка работ.
        if ($crew->delete() === false) {
            $msg = 'Не удалось удалить бригаду "' . $crewName. '": <br>';
            foreach ($crew->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);            
        }
        
        $msg = 'Бригада «' . $crewName . '» успешно удалена.';
        $this->flashSession->success($msg);

        // HTTP редирект.
        return $this->response->redirect('crews');
    }
    
    
    /**
     * Определяет, в зависимости от полученных парметров,
     * какая функция будет выполнять JSON-запрос.
     */
    public function getJsonAction()
    {
        // Получение параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');
        
        // Получение параметра 'branch_id' переданного методом GET.
        $branchId = $this->request->getQuery('branch_id');
        
        if (!is_null($id)) {
            if (Uuid::validate($id)) {
                $this->getJsonById($id);
            } else {
                throw new EngsurveyException('Некорректный параметр URL.');
            }
        } elseif (!is_null($branchId)) {
            if (Uuid::validate($branchId)) {
                $this->getJsonByBranchId($branchId);
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
     * Выполняет поиск бригад.
     * Возвращает результат поиска в формате JSON.
     */
    protected function getJson()
    {
        $obCrews = Crews::find(
            [
                'order' => 'crewName',
            ]
        );

        if (count($obCrews) === 0) {
            $data = ['status' => 'not_found'];
        } else {
            $arCrews = [];
            foreach ($obCrews as $obCrew) {
                $arCrews[] = [
                    'id' => $obCrew->getId(),
                    'branch_id' => $obCrew->getBranchId(),
                    'crew_type_id' => $obCrew->getCrewType(),
                    'crew_name' => $obCrew->getCrewName(),
                    'head_last_name' => $obCrew->getHeadLastName(),
                    'head_first_name' => $obCrew->getHeadFirstName(),
                    'head_middle_name' => $obCrew->getHeadMiddleName(),
                    'head_initials' => $obCrew->getHeadInitials(),
                    'phone' => $obCrew->getPhone(),
                    'email' => $obCrew->getEmail(),
                    'number_of_crew' => $obCrew->getNumberOfCrew(),
                    'report_link' => $obCrew->getReportLink(),
                ];
            }
            
            $data = [
                'status' => 'found',
                'crews' => $arCrews,
            ];
        }
        
        echo json_encode($data);
    } 

    
    /**
     * Выполняет поиск бригады по ID.
     * Возвращает результат поиска в формате JSON.
     */
    protected function getJsonById(string $id)
    {
        $crew = Crews::findFirstById($id);

        if ($crew === false) {
            $data = ['status' => 'not_found'];
        } else {
            $data = [
                'status' => 'found',
                'crew' => [
                    'id' => $crew->getId(),
                    'branch_id' => $crew->getBranchId(),
                    'crew_type_id' => $crew->getCrewType(),
                    'crew_name' => $crew->getCrewName(),
                    'head_last_name' => $crew->getHeadLastName(),
                    'head_first_name' => $crew->getHeadFirstName(),
                    'head_middle_name' => $crew->getHeadMiddleName(),
                    'head_initials' => $crew->getHeadInitials(),
                    'phone' => $crew->getPhone(),
                    'email' => $crew->getEmail(),
                    'number_of_crew' => $crew->getNumberOfCrew(),
                    'report_link' => $crew->getReportLink(),
                ]
            ];
        }

        echo json_encode($data);
    }
    
    
    /**
     * Выполняет поиск бригад относящихся к филиалу.
     * Возвращает результат поиска в формате JSON.
     */
    protected function getJsonByBranchId($branchId)
    {
        $obCrews = Crews::find(
            [
                "branchId = '$branchId'",
                'order' => 'crewName',
            ]
        );

        if (count($obCrews) === 0) {
            $data = ['status' => 'not_found'];
        } else {
            $arCrews = [];
            foreach ($obCrews as $obCrew) {
                $arCrews[] = [
                    'id' => $obCrew->getId(),
                    'branch_id' => $obCrew->getBranchId(),
                    'crew_type_id' => $obCrew->getCrewType(),
                    'crew_name' => $obCrew->getCrewName(),
                    'head_last_name' => $obCrew->getHeadLastName(),
                    'head_first_name' => $obCrew->getHeadFirstName(),
                    'head_middle_name' => $obCrew->getHeadMiddleName(),
                    'head_initials' => $obCrew->getHeadInitials(),
                    'phone' => $obCrew->getPhone(),
                    'email' => $obCrew->getEmail(),
                    'number_of_crew' => $obCrew->getNumberOfCrew(),
                    'report_link' => $obCrew->getReportLink(),
                ];
            }
            
            $data = [
                'status' => 'found',
                'crews' => $arCrews,
            ];
        }
        
        echo json_encode($data);
    } 

}
