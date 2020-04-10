<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\PerformanceDynamics;
use Engsurvey\Models\Organizations;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class PerformanceDynamicsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'performance-dynamics',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск отчетов.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск отчетов и передача их в представление.
        $performanceDynamics = PerformanceDynamics::find();
        $this->view->setVar('performanceDynamics', $performanceDynamics);

        // Шаблон, используемый для создания представления.
        $this->view->pick('performance-dynamics/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания новой группы отчетов.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('PerformanceDynamics', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('performance-dynamics');
        }
        
        // Поиск организаций и передача их в представление.
        $organizations = Organizations::find(['order' => 'displayName']);
        $this->view->setVar('organizations', $organizations);

        // Установка очередного порядкового номера, для новой записи.
        if ($this->request->isGet()) {
            $this->tag->setDefault('seqNumber', PerformanceDynamics::getNextSeqNumber());
        }

        // Шаблон, используемый для создания представления.
        $this->view->pick('performance-dynamics/new');
    }


    /**
     * Отображает форму редактирования атрибутов записи.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('PerformanceDynamics', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('performance-dynamics');
        }

        // Получение и валидация идентификатора записи переданного методом GET.
        $id = $this->request->getQuery('id');
        if (!Uuid::validate($id)) {
            // Попытка получение идентификатора договора из внутреннего перенаправления (forward).
            $id = $this->dispatcher->getParam('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Ошибка получения ID записи.');
            }
        }
        
        // Поиск записи.
        $performanceDynamics = PerformanceDynamics::findFirst("id = '$id'");
        if ($performanceDynamics === false) {
            $this->flashSession->error('Запись не найдена.');
            
            return $this->response->redirect('performance-dynamics');
        }
        
        // Поиск организаций и передача их в представление.
        $organizations = Organizations::find(['order' => 'displayName']);
        $this->view->setVar('organizations', $organizations);

        // Установка значений элементов формы свойствами найденной записи,
        // для данных переданных методом GET.
        if ($this->request->isGet()) {
            $this->tag->setDefault('id', $performanceDynamics->getId());
            $this->tag->setDefault('seqNumber', $performanceDynamics->getSeqNumber());
            $this->tag->setDefault('organizationId', $performanceDynamics->getOrganizationId());
            $this->tag->setDefault('initialDataReportLink', $performanceDynamics->getInitialDataReportLink());
            $this->tag->setDefault('engineeringSurveyReportLink', $performanceDynamics->getEngineeringSurveyReportLink());
            $this->tag->setDefault('laboratoryReportLink', $performanceDynamics->getLaboratoryReportLink());
            $this->tag->setDefault('territoryPlanningReportLink', $performanceDynamics->getTerritoryPlanningReportLink());
        }

        // Шаблон представления.
        $this->view->pick('performance-dynamics/edit');

    }


    /**
     * Создает новую запись в базе данных с ссылками на отчеты организации, введенные в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('PerformanceDynamics', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('performance-dynamics');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }
        
        // Сохранение данных полученных методом POST.
        $data = [];
        $data['seqNumber'] = $this->request->getPost('seqNumber', 'trim');
        $data['organizationId'] = $this->request->getPost('organizationId');
        $data['initialDataReportLink'] = $this->request->getPost('initialDataReportLink', 'trim');
        $data['engineeringSurveyReportLink'] = $this->request->getPost('engineeringSurveyReportLink', 'trim');
        $data['laboratoryReportLink'] = $this->request->getPost('laboratoryReportLink', 'trim');
        $data['territoryPlanningReportLink'] = $this->request->getPost('territoryPlanningReportLink', 'trim');

        // Валидация полученных данных.
        if ($this->validate($data) === false) {
            // Повторный вывод формы создания новой записи
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Форматирование данных полученных из формы.
        $data = $this->format($data);
        
        // Сдвиг порядковых номеров перед созданием записи.
        if (PerformanceDynamics::shiftSeqNumbers($data['seqNumber']) === false) {
            throw new EngsurveyException('Не удалось выполнить сдвиг порядковых номеров перед созданием записи.');
        }

        // Генерация ID для новой записи.
        $id = Uuid::generate();
        
        // Создание новой записи.
        $performanceDynamics = new PerformanceDynamics();
        $performanceDynamics->setId($id);
        $performanceDynamics->setSeqNumber($data['seqNumber']);
        $performanceDynamics->setOrganizationId($data['organizationId']);
        $performanceDynamics->setInitialDataReportLink($data['initialDataReportLink']);
        $performanceDynamics->setEngineeringSurveyReportLink($data['engineeringSurveyReportLink']);
        $performanceDynamics->setLaboratoryReportLink($data['laboratoryReportLink']);
        $performanceDynamics->setTerritoryPlanningReportLink($data['territoryPlanningReportLink']);
        
        if ($performanceDynamics->create() === false) {
            $this->flashSession->error('Ошибка создания новой записи.');
            return $this->dispatcher->forward(['action' => 'new']);
        }
        
        // Перенумерация порядковых номеров после создания  записи.
        if (PerformanceDynamics::renumberSeqNumbers() === false) {
            throw new EngsurveyException('Не удалось перенумеровать порядковые номера после создания  записи.');
        }

        // HTTP редирект.
        return $this->response->redirect('performance-dynamics');
    }


    /**
     * Изменяет запись на основе данных, введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('PerformanceDynamics', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('performance-dynamics');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }
        
        // Получение и валидация идентификатора записи.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный идентификатор записи.');
        }
        
        // Сохранение данных полученных методом POST.
        $data = [];
        $data['id'] = $this->request->getPost('id');
        $data['seqNumber'] = $this->request->getPost('seqNumber', 'trim');
        $data['organizationId'] = $this->request->getPost('organizationId');
        $data['initialDataReportLink'] = $this->request->getPost('initialDataReportLink', 'trim');
        $data['engineeringSurveyReportLink'] = $this->request->getPost('engineeringSurveyReportLink', 'trim');
        $data['laboratoryReportLink'] = $this->request->getPost('laboratoryReportLink', 'trim');
        $data['territoryPlanningReportLink'] = $this->request->getPost('territoryPlanningReportLink', 'trim');

        // Валидация полученных данных.
        if ($this->validate($data) === false) {
            // Повторный вывод формы редактирования атрибутов записи
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // Форматирование данных полученных из формы.
        $data = $this->format($data);
        
        // Поиск записи.
        $performanceDynamics = PerformanceDynamics::findFirst("id = '$id'");
        if ($performanceDynamics === false) {
            $this->flashSession->error('Запись не найдена.');
            return $this->response->redirect('performance-dynamics');
        }
        
        // Сдвиг порядковых номеров перед обновлением записи.
        if (PerformanceDynamics::shiftSeqNumbers($data['seqNumber']) === false) {
            throw new EngsurveyException('Не удалось выполнить сдвиг порядковых номеров перед обновлением записи.');
        }

        // Обновление записи.
        $performanceDynamics->setSeqNumber($data['seqNumber']);
        $performanceDynamics->setOrganizationId($data['organizationId']);
        $performanceDynamics->setInitialDataReportLink($data['initialDataReportLink']);
        $performanceDynamics->setEngineeringSurveyReportLink($data['engineeringSurveyReportLink']);
        $performanceDynamics->setLaboratoryReportLink($data['laboratoryReportLink']);
        $performanceDynamics->setTerritoryPlanningReportLink($data['territoryPlanningReportLink']);
        
        if ($performanceDynamics->update() === false) {
            $this->flashSession->error('Не удалось обновить запись.');
            // Повторный вывод формы редактирования записи с сообщением об ошибке.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }
        
        // Перенумерация порядковых номеров после обновления записи.
        if (PerformanceDynamics::renumberSeqNumbers() === false) {
            throw new EngsurveyException('Не удалось перенумеровать порядковые номера после обновления записи.');
        }

        // HTTP редирект.
        return $this->response->redirect('performance-dynamics');
    }


    /**
     * Удаляет запись.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('PerformanceDynamics', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('performance-dynamics');
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
        
        // Поиск записи.
        $performanceDynamics = PerformanceDynamics::findFirst("id = '$id'");
        if ($performanceDynamics === false) {
            $this->flashSession->error('Запись не найдена.');
            return $this->response->redirect('performance-dynamics');
        }

        // Удаление записи.
        if ($performanceDynamics->delete() === false) {
            $msg = 'Не удалось удалить запись.';
            $this->flashSession->error($msg);
        } else {
            $msg = 'Запись успешно удалена.';
            $this->flashSession->success($msg);
        }
        
        // Перенумерация порядковых номеров после удаления  записи.
        if (PerformanceDynamics::renumberSeqNumbers() === false) {
            throw new EngsurveyException('Не удалось перенумеровать порядковые номера после удаления записи.');
        }

        // HTTP редирект.
        return $this->response->redirect('performance-dynamics');
    }


    /**
     * Выполняет валидацию полученных данных и формирует сообщения об ошибках 
     * с передачей их в представление.
     * Возвращают булево значение, показывающее, прошла валидация
     * успешно, либо нет.
     *
     * @param  array $data Данные полученные из формы.
     * @return bool
     */
    protected function validate(array $data): bool
    {
        $validation = new Validation();
        
        // Порядковый номер.
        $validation->add(
            'seqNumber',
            new Regex(
                [
                    // Положительное целое число > 0.
                    'pattern' => '/^([1-9]\d*)$/',
                    'message' => 'Введите положительное целое число больше 0.'
                ]
            )
        );
        
        // Организация.
        $validation->add(
            'organizationId',
            new PresenceOf(
                [
                    'message' => 'Необходимо выбрать организацию.',
                ]
            )
        );
        
        
        $messages = $validation->validate($data);
        
        if (count($messages)) {
            // Сообщения для элемента формы 'seqNumber'.
            $filteredMessages = $messages->filter('seqNumber');
            if (count($filteredMessages)) {
                $this->addMessages('seqNumber', $filteredMessages);
            }
            
            // Сообщения для элемента формы 'organizationId'.
            $filteredMessages = $messages->filter('organizationId');
            if (count($filteredMessages)) {
                $this->addMessages('organizationId', $filteredMessages);
            }
            
            return false;
        }

        return true;

    }
    
    
    /**
     * Выполняет передачу сообщений об ошибках в представление.
     *
     * @param  array $data Данные полученные из формы.
     * @return void
     */
     
    protected function addMessages(string $formControl, array $messages)
    {
        $msgs = '';

        foreach ($messages as $msg) {
            $msgs .= $msg . '<br>';
        }

        $msgs = rtrim($msgs, '<br>');

        $this->view->setVar($formControl . 'Messages', $msgs);
    }
    
    
    /**
     * Форматирование полученных данных.
     *
     * @param  array $data Полученные данные.
     * @return array
     */
    protected function format(array $data): array
    {
        $formattedData = $data;

        // Форматирование порядкового номера.
        $seqNumber = $data['seqNumber'];
        $formattedData['seqNumber'] = (int)$seqNumber;

        return $formattedData;
    }
    
}
