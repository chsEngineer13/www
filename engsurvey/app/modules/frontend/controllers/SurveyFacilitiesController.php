<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\SurveyFacilities;
use Engsurvey\Models\ConstructionSites;
use Engsurvey\Models\ConstructionProjects;
use Engsurvey\Frontend\Forms\SurveyFacilityForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;


class SurveyFacilitiesController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'survey-facilities',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск объектов изысканий относящихся к участку работ.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Получение и валидация обязательного параметра 'construction-site-id' переданного методом GET.
        $constructionSiteId = $this->request->getQuery('construction-site-id');
        if (!Uuid::validate($constructionSiteId)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        // Поиск участка работ и передача его в представление.
        $constructionSite = ConstructionSites::findFirstById($constructionSiteId);
        if (!$constructionSite) {
            throw new EngsurveyException('Участок работ не найден.');
        }
        $this->view->setVar('constructionSite', $constructionSite);

        // Получение и передача в представление стройки.
        $constructionProject = $constructionSite->getConstructionProject();
        $this->view->setVar('constructionProject', $constructionProject);

        // Получение и передача в представление объектов изысканий.
        $surveyFacilities = $constructionSite->getSurveyFacilities();
        $this->view->setVar('surveyFacilities', $surveyFacilities);

        // Шаблон, используемый для создания представления.
        $this->view->pick('survey-facilities/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания нового объекта изысканий.
     */
    public function newAction()
    {
        // Получение и валидация обязательного параметра 'construction-site-id' переданного методом GET.
        $constructionSiteId = $this->request->getQuery('construction-site-id');
        if (!Uuid::validate($constructionSiteId)) {
            // Попытка получение параметра 'constructionSiteId' из внутреннего перенаправления (forward).
            $constructionSiteId = $this->dispatcher->getParam('construction-site-id');
            if (!Uuid::validate($constructionSiteId)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }
        }
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('SurveyFacilities', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
        }

        // Поиск участка работ и передача его в представление.
        $constructionSite = ConstructionSites::findFirstById($constructionSiteId);
        if (!$constructionSite) {
            throw new EngsurveyException('Участок работ не найден.');
        }
        $this->view->setVar('constructionSite', $constructionSite);

        // Получение и передача в представление стройки.
        $constructionProject = $constructionSite->getConstructionProject();
        $this->view->setVar('constructionProject', $constructionProject);

        // Вычисление очередного порядкового номера.
        $sequenceNumber = SurveyFacilities::getLastSequenceNumber($constructionSiteId) + 1;

        // Шаблон, используемый при создании представления.
        $this->view->pick('survey-facilities/new');

        // Форма, используемая в представлении и значение формы по умолнанию.
        $this->view->form = new SurveyFacilityForm();
        $this->tag->setDefault('constructionProjectId', $constructionProject->getId());
        $this->tag->setDefault('constructionSiteId', $constructionSiteId);
        $this->tag->setDefault('sequenceNumber', $sequenceNumber);
    }


    /**
     * Отображает форму редактирования существующего объекта изысканий.
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

        // Поиск объекта изысканий.
        $surveyFacility = SurveyFacilities::findFirstById($id);
        if (!$surveyFacility) {
            throw new EngsurveyException('Объект изысканий не найден.');
        }

        // Получение и передача участка работ в представление.
        $constructionSite = $surveyFacility->getConstructionSite();
        $constructionSiteId = $constructionSite->getId();
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('SurveyFacilities', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
        }
        
        // Передача участка работ в представление.
        $this->view->setVar('constructionSite', $constructionSite);

        // Получение и передача стройки в представление.
        $constructionProject = $constructionSite->getConstructionProject();
        $this->view->setVar('constructionProject', $constructionProject);

        // Шаблон, используемый для создания представления.
        $this->view->pick('survey-facilities/edit');

        // Найденные данные связываются с формой,
        // передавая модель первым параметром.
        $this->view->form = new SurveyFacilityForm($surveyFacility);
    }


    /**
     * Создает объект изысканий на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            throw new EngsurveyException('Не удалось получить данные методом POST.');
        }

        $form = new SurveyFacilityForm();
        $facility = new SurveyFacilities();

        // Валидация ввода.
        $data = $this->request->getPost();

        if (!$form->isValid($data, $facility)) {
            // Получение сообщений для элемента 'sequenceNumber'.
            $messages = $form->getMessagesFor('sequenceNumber');
            if (count($messages)) {
                $sequenceNumberMessages = '';

                foreach ($messages as $message) {
                    if (empty($sequenceNumberMessages)) {
                        $sequenceNumberMessages = $message;
                    } else {
                        $sequenceNumberMessages += $message . "<br>";
                    }
                }

                $sequenceNumberMessages = rtrim($sequenceNumberMessages, "<br>");

                $this->view->setVar('sequenceNumberMessages', $sequenceNumberMessages);
            }

            // Получение сообщений для элемента 'facilityName'.
            $messages = $form->getMessagesFor('facilityName');
            if (count($messages)) {
                $facilityNameMessages = '';

                foreach ($messages as $message) {
                    if (empty($facilityNameMessages)) {
                        $facilityNameMessages = $message;
                    } else {
                        $facilityNameMessages += $message . "<br>";
                    }
                }

                $facilityNameMessages = rtrim($facilityNameMessages, "<br>");

                $this->view->setVar('facilityNameMessages', $facilityNameMessages);
            }

            // Повторный вывод формы создания нового объекта изысканий
            // с сообщениями об ошибках.
            $constructionSiteId = $this->request->getPost('constructionSiteId');
            return $this->dispatcher->forward(
                [
                    'action' => 'new',
                    'params' => ['construction-site-id' => $constructionSiteId],
                ]
            );
        }

        // Получение данных методом POST.
        $constructionProjectId = $this->request->getPost('constructionProjectId');
        $constructionSiteId = $this->request->getPost('constructionSiteId');
        $sequenceNumber = $this->request->getPost('sequenceNumber', 'trim');
        $facilityName = $this->request->getPost('facilityName', 'trim');
        $facilityDesignation = $this->request->getPost('facilityDesignation', 'trim');
        $facilityNumber = $this->request->getPost('facilityNumber', 'trim');
        $stageOfWorks = $this->request->getPost('stageOfWorks', 'trim');
        $comment = $this->request->getPost('comment', 'trim');
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('SurveyFacilities', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
        }

        // Генерация ID для нового объекта изысканий.
        $id = Uuid::generate();

        // Создание нового объекта изысканий.
        $facility->setId($id);
        $facility->setConstructionProjectId($constructionProjectId);
        $facility->setConstructionSiteId($constructionSiteId);
        $facility->setSequenceNumber($sequenceNumber);
        $facility->setFacilityName($facilityName);
        $facility->setFacilityDesignation($facilityDesignation);
        $facility->setFacilityNumber($facilityNumber);
        $facility->setStageOfWorks($stageOfWorks);
        $facility->setComment($comment);

        if ($facility->create() === false) {
            $msg = 'Не удалось создать новый объект изысканий: <br>';
            foreach ($facility->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
    }


    /**
     * Изменяет объект изысканий на основе данных, введенных в действии "edit".
     */
    public function updateAction()
    {
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            throw new EngsurveyException('Не удалось получить данные методом POST.');
        }

        $id = $this->request->getPost('id');

        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID объекта изысканий.');
        }

        // Поиск объекта изысканий.
        $surveyFacility = SurveyFacilities::findFirstById($id);
        if (!$surveyFacility) {
            throw new EngsurveyException('Объект изысканий не найден.');
        }

        // Форма.
        $form = new SurveyFacilityForm();

        // Получение данных методом POST.
        $data = $this->request->getPost();

        // Валидация ввода.
        if (!$form->isValid($data, $surveyFacility)) {
            // Получение сообщений для элемента 'sequenceNumber'.
            $messages = $form->getMessagesFor('sequenceNumber');
            if (count($messages)) {
                $sequenceNumberMessages = '';

                foreach ($messages as $message) {
                    if (empty($sequenceNumberMessages)) {
                        $sequenceNumberMessages = $message;
                    } else {
                        $sequenceNumberMessages += $message . "<br>";
                    }
                }

                $sequenceNumberMessages = rtrim($sequenceNumberMessages, "<br>");

                $this->view->setVar('sequenceNumberMessages', $sequenceNumberMessages);
            }

            // Получение сообщений для элемента 'facilityName'.
            $messages = $form->getMessagesFor('facilityName');
            if (count($messages)) {
                $facilityNameMessages = '';

                foreach ($messages as $message) {
                    if (empty($facilityNameMessages)) {
                        $facilityNameMessages = $message;
                    } else {
                        $facilityNameMessages += $message . "<br>";
                    }
                }

                $facilityNameMessages = rtrim($facilityNameMessages, "<br>");

                $this->view->setVar('facilityNameMessages', $facilityNameMessages);
            }

            // Повторный вывод формы редактирования объекта изысканий
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
        $constructionSiteId = $this->request->getPost('constructionSiteId');
        $sequenceNumber = $this->request->getPost('sequenceNumber', 'trim');
        $facilityName = $this->request->getPost('facilityName', 'trim');
        $facilityDesignation = $this->request->getPost('facilityDesignation', 'trim');
        $facilityNumber = $this->request->getPost('facilityNumber', 'trim');
        $stageOfWorks = $this->request->getPost('stageOfWorks', 'trim');
        $comment = $this->request->getPost('comment', 'trim');
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('SurveyFacilities', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
        }

        // Обновление данных объекта изысканий.
        $surveyFacility->setSequenceNumber($sequenceNumber);
        $surveyFacility->setFacilityName($facilityName);
        $surveyFacility->setFacilityDesignation($facilityDesignation);
        $surveyFacility->setFacilityNumber($facilityNumber);
        $surveyFacility->setStageOfWorks($stageOfWorks);
        $surveyFacility->setComment($comment);

        if ($surveyFacility->update() === false) {
            $msg = 'Не удалось создать новый объект изысканий: <br>';
            foreach ($surveyFacility->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        if ($surveyFacility->update() === false) {
            $msg = 'Не удалось сохранить изменения объекта изысканий: <br>';
            foreach ($surveyFacility->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $form->clear();

        // HTTP редирект.
        return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
    }


    /**
     * Удаляет объект изысканий.
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

        // Поиск объекта изысканий.
        $surveyFacility = SurveyFacilities::findFirstById($id);
        if (!$surveyFacility) {
            throw new EngsurveyException('Объект изысканий не найден.');
        }

        $constructionSiteId = $surveyFacility->getConstructionSiteId();
        $facilityName = $surveyFacility->getFacilityName();
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('SurveyFacilities', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
        }

        // Удаление объекта изысканий.
        if ($surveyFacility->delete() === false) {
            $msg = 'Не удалось удалить объект изысканий "' . $facilityName. '": <br>';
            foreach ($surveyFacility->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $msg = "Объект изысканий «{$facilityName}» успешно удален.";
        $this->flashSession->success($msg);

        // HTTP редирект.
        return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
    }
    
    
    public function importObjectsXlsxAction()
    {
        // Получение и валидация обязательного параметра "construction-project-id" переданного методом GET.
        $constructionProjectId = $this->request->getQuery("construction-project-id");
        if (!Uuid::validate($constructionProjectId)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }
        
        // Получение и валидация обязательного параметра "construction-site-id" переданного методом GET.
        $constructionSiteId = $this->request->getQuery("construction-site-id");
        if (!Uuid::validate($constructionSiteId)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('SurveyFacilities', 'importObjectsXlsx') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
        }

        // Проверка загрузки файл.
        if ($this->request->hasFiles()) {
            foreach ($this->request->getUploadedFiles() as $obFile) {
                $file = array();
                $file['name'] = $obFile->getName();
                $file['type'] = $obFile->getType();
                $file['size'] = $obFile->getSize();
                $file['tempName'] = $obFile->getTempName();
                $file['error'] = $obFile->getError();

                $this->importObjectsXlsx($constructionProjectId, $constructionSiteId, $file);
            }
        } else {
            throw new \EngsurveyException('Ошибка загрузки файл.');
        }
        
        // HTTP редирект.
        return $this->response->redirect('survey-facilities?construction-site-id=' . $constructionSiteId);
    }
    
    
    protected function importObjectsXlsx($constructionProjectId, $constructionSiteId, $file)
    {
        // Подключение файлов библиотеке PHPExcel.
        $config = $this->di->get("config");
        $phpExcelDir = $config->phpExcel->phpExcelDir;
        require_once $phpExcelDir . "PHPExcel.php";
        require_once $phpExcelDir . "PHPExcel/Writer/Excel2007.php";

        // Чтение только данных электронной таблицы.
        $obReader = new \PHPExcel_Reader_Excel2007();
        $obReader->setReadDataOnly(true);
        $obPhpExcel = $obReader->load($file['tempName']);

        // Установка текущим первый лист файла.
        $obPhpExcel->setActiveSheetIndex(0);
        $obWorksheet = $obPhpExcel->getActiveSheet();

        // Получение последнего порядкового номера.
        $lastSequenceNumber = SurveyFacilities::getLastSequenceNumber($constructionSiteId);

        // Получение количества заполненных строк в листе.
        $rowCount = $obWorksheet->getHighestRow();
        
        // Количество импортированных объектов.
        $numberOfImportedFacilities = 0;
        
        // Цикл по строкам первого листа файла начиная со второй строки.
        for ($row = 2; $row <= $rowCount; $row++) {
            $sequenceNumber = $obWorksheet->getCell("A" . (string)$row)->getValue();
            $facilityName = $obWorksheet->getCell("B" . (string)$row)->getValue();
            $facilityDesignation = $obWorksheet->getCell("C" . (string)$row)->getValue();
            $facilityNumber = $obWorksheet->getCell("D" . (string)$row)->getValue();
            $stageOfWorks = $obWorksheet->getCell("E" . (string)$row)->getValue();
            $comment = $obWorksheet->getCell("F" . (string)$row)->getValue();
            
            
            // Генерация ID для нового объекта изысканий.
            $id = Uuid::generate();

            // Создание нового объекта изысканий.
            $facility = new SurveyFacilities();
            $facility->setId($id);
            $facility->setConstructionProjectId($constructionProjectId);
            $facility->setConstructionSiteId($constructionSiteId);
            $facility->setSequenceNumber($lastSequenceNumber + $sequenceNumber);
            $facility->setFacilityName($facilityName);
            $facility->setFacilityDesignation($facilityDesignation);
            $facility->setFacilityNumber($facilityNumber);
            $facility->setStageOfWorks($stageOfWorks);
            $facility->setComment($comment);
            
            if ($facility->create() === false) {
                $msg = 'Не удалось создать новый объект изысканий: <br>';
                foreach ($facility->getMessages() as $message) {
                    $msg .= $message . '<br>';
                }
                throw new EngsurveyException($msg);
            }

            $numberOfImportedFacilities++;
        }
        
        $msg = "Импортировано {$numberOfImportedFacilities} объект(ов) изысканий.";
        $this->flashSession->success($msg);

    }

}
