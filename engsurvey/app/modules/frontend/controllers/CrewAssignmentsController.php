<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\CrewAssignments;
use Engsurvey\Models\Branches;
use Engsurvey\Frontend\Forms\CrewAssignmentForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;

class CrewAssignmentsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'crew-assignments',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск текущих и предстоящих назначений бригад на объекты.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Получение текущей даты в формате YYYY-MM-DD.
        $today = date('Y-m-d');

        // Поиск текущих и предстоящих назначений бригад на объекты
        // и передача их в представление.
        /*$parameters = [
            'conditions' => "finishDate >= $today",
        ];*/
        $crewAssignments = CrewAssignments::find(/*$parameters*/);
        $this->view->setVar('crewAssignments', $crewAssignments);

        // Шаблон, используемый для создания представления.
        $this->view->pick('crew-assignments/search');

        // Добавление ресурсов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания нового назначения бригады.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('CrewAssignments', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crew-assignments');
        }
        
        // Форма, используемая в представлении.
        $this->view->form = new CrewAssignmentForm;
        
        // Шаблон, используемый для создания представления.
        $this->view->pick('crew-assignments/new');
    }


    /**
     * Отображает форму редактирования назначения бригады.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('CrewAssignments', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crew-assignments');
        }
        
        if (!$this->request->isPost()) {
            // Получение и валидация обязательного параметра 'id' переданного методом GET.
            $id = $this->request->getQuery('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }

            // Поиск назначения.
            $assignment = CrewAssignments::findFirstById($id);
            if (!$assignment) {
                throw new EngsurveyException('Назначение бригады не найдено.');
            }

            // Найденные данные связываются с формой,
            // передавая модель первым параметром.
            $this->view->form = new CrewAssignmentForm($assignment);
        } else {
            // Форма, используемая в представлении.
            $this->view->form = new CrewAssignmentForm();
        }

        // Шаблон представления.
        $this->view->pick('crew-assignments/edit');
    }


    /**
     * Создает новое назначение бригады на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('CrewAssignments', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crew-assignments');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы создания нового назначения бригады
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Сохранение полученных данных.
        $branchId = $this->request->getPost('branchId');
        $crewId = $this->request->getPost('crewId');
        $constructionProjectId = $this->request->getPost('constructionProjectId');
        $constructionSiteId = $this->request->getPost('constructionSiteId');
        $startDate = $this->request->getPost('startDate', 'trim');
        $endDate = $this->request->getPost('endDate', 'trim');
        $comment = $this->request->getPost('comment', 'trim');

        // Форматирование даты начала работ.
        $dt = \DateTime::createFromFormat('d.m.Y', $startDate);
        $startDate = $dt->format('Y-m-d');

        // Форматирование даты завершения работ.
        $dt = \DateTime::createFromFormat('d.m.Y', $endDate);
        $endDate = $dt->format('Y-m-d');

        // Дата начала работ должна предшествовать или совпадать с датой завершения.
        if ($startDate > $endDate) {
            $this->flashSession->error('Дата начала работ должна предшествовать или совпадать с датой завершения.');
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой записи.
        $assignment = new CrewAssignments();
        $assignment->setId($id);
        $assignment->setBranchId($branchId);
        $assignment->setCrewId($crewId);
        $assignment->setConstructionProjectId($constructionProjectId);
        $assignment->setConstructionSiteId($constructionSiteId);
        $assignment->setStartDate($startDate);
        $assignment->setEndDate($endDate);
        $assignment->setComment($comment);

        if ($assignment->create() === false) {
            $this->flashSession->error('Ошибка создания нового назначения для бригады.');
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // HTTP редирект.
        return $this->response->redirect('crew-assignments');
    }


    /**
     * Изменяет запись о назначении бригады на основе данных, введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('CrewAssignments', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crew-assignments');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Получение и валидация ID назначения бригады.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный ID вида работ.');
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы создания нового назначения бригады
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Сохранение полученных данных в переменных.
        $branchId = $this->request->getPost('branchId');
        $crewId = $this->request->getPost('crewId');
        $constructionProjectId = $this->request->getPost('constructionProjectId');
        $constructionSiteId = $this->request->getPost('constructionSiteId');
        $startDate = $this->request->getPost('startDate', 'trim');
        $endDate = $this->request->getPost('endDate', 'trim');
        $comment = $this->request->getPost('comment', 'trim');

        // Форматирование даты начала работ.
        $dt = \DateTime::createFromFormat('d.m.Y', $startDate);
        $startDate = $dt->format('Y-m-d');

        // Форматирование даты завершения работ.
        $dt = \DateTime::createFromFormat('d.m.Y', $endDate);
        $endDate = $dt->format('Y-m-d');

        // Дата начала работ должна предшествовать или совпадать с датой завершения.
        if ($startDate > $endDate) {
            $this->flashSession->error('Дата начала работ должна предшествовать или совпадать с датой завершения.');
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Поиск назначения бригады.
        $assignment = CrewAssignments::findFirstById($id);
        if (!$assignment) {
            throw new EngsurveyException('Назначение бригады на объект не найден.');
        }

        // Обновление назначения бригады.
        $assignment->setBranchId($branchId);
        $assignment->setCrewId($crewId);
        $assignment->setConstructionProjectId($constructionProjectId);
        $assignment->setConstructionSiteId($constructionSiteId);
        $assignment->setStartDate($startDate);
        $assignment->setEndDate($endDate);
        $assignment->setComment($comment);

        if ($assignment->update() === false) {
            throw new EngsurveyException('Не удалось обновить назначение бригады на объект.');
        }

        // HTTP редирект.
        return $this->response->redirect('crew-assignments');
    }


    /**
     * Удаляет назначение бригады на объект.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('CrewAssignments', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('crew-assignments');
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

        // Поиск назначения бригады.
        $assignment = CrewAssignments::findFirstById($id);
        if (!$assignment) {
            throw new EngsurveyException('Назначение бригады на объект не найдено.');
        }

        $crewName = $assignment->getCrew()->getCrewName();

        // Удаление назначения бригады.
        if ($assignment->delete() === false) {
            $msg = 'Не удалось удалить назначение бригады "' . $crewName. '" на объект: <br>';
            foreach ($crew->getMessages() as $message) {
                $msg .= $message . '<br>';
            }
            throw new EngsurveyException($msg);
        }

        $msg = 'Назначение бригады «' . $crewName . '» на объект успешно удалено.';
        $this->flashSession->success($msg);

        // HTTP редирект.
        return $this->response->redirect('crew-assignments');
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
        $form = new CrewAssignmentForm();

        if ($form->isValid($data) === false) {

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

            // Сообщения для элемента 'crewId'.
            $messages = $form->getMessagesFor('crewId');
            if (count($messages)) {
                $crewMessages = '';

                foreach ($messages as $message) {
                    $crewMessages .= $message . '<br>';
                }

                $crewMessages = rtrim($crewMessages, '<br>');

                $this->view->setVar('crewMessages', $crewMessages);
            }

            // Сообщения для элемента 'constructionProjectId'.
            $messages = $form->getMessagesFor('constructionProjectId');
            if (count($messages)) {
                $constructionProjectMessages = '';

                foreach ($messages as $message) {
                    $constructionProjectMessages .= $message . '<br>';
                }

                $constructionProjectMessages = rtrim($constructionProjectMessages, '<br>');

                $this->view->setVar('constructionProjectMessages', $constructionProjectMessages);
            }

            // Сообщения для элемента 'constructionSiteId'.
            $messages = $form->getMessagesFor('constructionSiteId');
            if (count($messages)) {
                $constructionSiteMessages = '';

                foreach ($messages as $message) {
                    $constructionSiteMessages .= $message . '<br>';
                }

                $constructionSiteMessages = rtrim($constructionSiteMessages, '<br>');

                $this->view->setVar('constructionSiteMessages', $constructionSiteMessages);
            }

            // Сообщения для элемента 'startDate'.
            $messages = $form->getMessagesFor('startDate');
            if (count($messages)) {
                $startDateMessages = '';

                foreach ($messages as $message) {
                    $startDateMessages .= $message . '<br>';
                }

                $startDateMessages = rtrim($startDateMessages, '<br>');

                $this->view->setVar('startDateMessages', $startDateMessages);
            }

            // Сообщения для элемента 'endDate'.
            $messages = $form->getMessagesFor('endDate');
            if (count($messages)) {
                $endDateMessages = '';

                foreach ($messages as $message) {
                    $endDateMessages .= $message . '<br>';
                }

                $endDateMessages = rtrim($endDateMessages, '<br>');

                $this->view->setVar('endDateMessages', $endDateMessages);
            }

            return false;
        }

        return true;
    }

}
