<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\EmployeeGroups;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class EmployeeGroupsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'employee-groups',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск групп сотрудников.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск групп сотрудников и передача их в представление.
        $employeeGroups = EmployeeGroups::find();
        $this->view->setVar('employeeGroups', $employeeGroups);

        // Шаблон, используемый для создания представления.
        $this->view->pick('employee-groups/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания новой группы сотрудников.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('EmployeeGroups', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employee-groups');
        }
        
        // Установка очередного порядкового номера, для новой записи.
        if ($this->request->isGet()) {
            $this->tag->setDefault('seqNumber', EmployeeGroups::getNextSeqNumber());
        }

        // Шаблон, используемый для создания представления.
        $this->view->pick('employee-groups/new');
    }
   

    /**
     * Отображает форму редактирования данных о группе сотрудников.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('EmployeeGroups', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employee-groups');
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

        // Поиск группы сотрудников.
        $employeeGroup = EmployeeGroups::findFirst("id = '$id'");
        if ($employeeGroup === false) {
            $this->flashSession->error('Группа сотрудников не найдена.');
            
            return $this->response->redirect('employee-groups');
        }

        // Установка значений элементов формы свойствами найденной записи,
        // для данных переданных методом GET.
        if ($this->request->isGet()) {
            $this->tag->setDefault('id', $employeeGroup->getId());
            $this->tag->setDefault('seqNumber', $employeeGroup->getSeqNumber());
            $this->tag->setDefault('systemName', $employeeGroup->getSystemName());
            $this->tag->setDefault('name', $employeeGroup->getName());
            $this->tag->setDefault('description', $employeeGroup->getDescription());
        }

        // Шаблон представления.
        $this->view->pick('employee-groups/edit');
    }
    
    
    /**
     * Создает новую группу сотрудников на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('EmployeeGroups', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employee-groups');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }
        
        // Сохранение данных полученных методом POST.
        $data = [];
        $data['seqNumber'] = $this->request->getPost('seqNumber', 'trim');
         $data['systemName'] = $this->request->getPost('systemName', 'trim');
        $data['name'] = $this->request->getPost('name', 'trim');
        $data['description'] = $this->request->getPost('description', 'trim');

        // Валидация полученных данных.
        if ($this->validate($data) === false) {
            // Повторный вывод формы создания новой записи
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Форматирование данных полученных из формы.
        $data = $this->format($data);
        
        // Сдвиг порядковых номеров перед созданием записи.
        if (EmployeeGroups::shiftSeqNumbers($data['seqNumber']) === false) {
            throw new EngsurveyException('Не удалось выполнить сдвиг порядковых номеров перед созданием записи.');
        }

        // Генерация ID для новой записи.
        $id = Uuid::generate();
        
        // Создание новой записи.
        $employeeGroup = new EmployeeGroups();
        $employeeGroup->setId($id);
        $employeeGroup->setSeqNumber($data['seqNumber']);
        $employeeGroup->setSystemName($data['systemName']);
        $employeeGroup->setName($data['name']);
        $employeeGroup->setDescription($data['description']);

        if ($employeeGroup->create() === false) {
            $this->flashSession->error('Ошибка создания новой группы сотрудников.');
            return $this->dispatcher->forward(['action' => 'new']);
        }
        
        // Перенумерация порядковых номеров после создания  записи.
        if (EmployeeGroups::renumberSeqNumbers() === false) {
            throw new EngsurveyException('Не удалось перенумеровать порядковые номера после создания  записи.');
        }

        // HTTP редирект.
        return $this->response->redirect('employee-groups');
    }    
    

    /**
     * Изменяет запись о группе сотрудников на основе данных, введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('EmployeeGroups', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employee-groups');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }
        
        // Получение и валидация идентификатора записи.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный идентификатор сотрудника.');
        }
        
        // Сохранение данных полученных методом POST.
        $data = [];
        $data['id'] = $this->request->getPost('id');
        $data['seqNumber'] = $this->request->getPost('seqNumber', 'trim');
        $data['systemName'] = $this->request->getPost('systemName', 'trim');
        $data['name'] = $this->request->getPost('name', 'trim');
        $data['description'] = $this->request->getPost('description', 'trim');        

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

        // Поиск группы сотрудников.
        $employeeGroup = EmployeeGroups::findFirst("id = '$id'");
        if ($employeeGroup === false) {
            $this->flashSession->error('Группа сотрудников не найдена.');
            return $this->response->redirect('employee-groups');
        }

        // Сдвиг порядковых номеров перед обновлением группы сотрудников.
        if (EmployeeGroups::shiftSeqNumbers($data['seqNumber']) === false) {
            throw new EngsurveyException('Не удалось выполнить сдвиг порядковых номеров перед группы сотрудников.');
        }

        // Обновление записи.
        $employeeGroup->setSeqNumber($data['seqNumber']);
        $employeeGroup->setSystemName($data['systemName']);
        $employeeGroup->setName($data['name']);
        $employeeGroup->setDescription($data['description']);
        
        if ($employeeGroup->update() === false) {
            $this->flashSession->error('Не удалось обновить данные о сотруднике.');
            // Повторный вывод формы редактирования данных о группе сотрудников
            // с сообщением об ошибке.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }
        
        // Перенумерация порядковых номеров после обновления записи.
        if (EmployeeGroups::renumberSeqNumbers() === false) {
            throw new EngsurveyException('Не удалось перенумеровать порядковые номера после обновления записи.');
        }

        // HTTP редирект.
        return $this->response->redirect('employee-groups');
    }
    

    /**
     * Удаляет группу сотрудников.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('EmployeeGroups', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employee-groups');
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
        
        // Поиск группы сотрудников.
        $employeeGroup = EmployeeGroups::findFirst("id = '$id'");
        if ($employeeGroup === false) {
            $this->flashSession->error('Группа сотрудников не найдена.');
            return $this->response->redirect('employee-groups');
        }

        $name = $employeeGroup->getName();

        // Удаление записи о группе сотрудников.
        if ($employeeGroup->delete() === false) {
            $msg = "Не удалось удалить группу сотрудников «${name}».";
            $this->flashSession->error($msg);
        } else {
            $msg = "Группа сотрудников «${name}» успешно удалена.";
            $this->flashSession->success($msg);
        }
        
        // Перенумерация порядковых номеров после удаления  записи.
        if (EmployeeGroups::renumberSeqNumbers() === false) {
            throw new EngsurveyException('Не удалось перенумеровать порядковые номера после удаления записи.');
        }

        // HTTP редирект.
        return $this->response->redirect('employee-groups');
    }
    
    
    /**
     * Выполняет валидацию полученных данных и формирует сообщения об ошибках 
     * с передачей их в представление.
     * Возвращают булево значение, показывающее, прошла валидация
     * успешно, либо нет.
     *
     * @param array $data Данные полученные из формы.
     *
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
        
        // Системное имя группы сотрудников.
        $validation->add(
            'systemName',
            new PresenceOf(
                ['message' => 'Системное имя группы сотрудников обязательно.']
            )
        );
        
        $validation->add(
            'systemName',
            new Regex(
                [
                    'pattern' => '/^[a-z0-9_]+$/',
                    'message' => 'В системном имени допустимы только латинские символы в нижнем регистре, цифры и знак подчеркивания (_).'
                ]
            )
        );

        // Наименование группы сотрудников.
        $validation->add(
            'name',
            new PresenceOf(
                [
                    'message' => 'Наименование группы сотрудников обязательно.',
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
            
            // Сообщения для элемента формы 'systemName'.
            $filteredMessages = $messages->filter('systemName');
            if (count($filteredMessages)) {
                $this->addMessages('systemName', $filteredMessages);
            }
            
            // Сообщения для элемента формы 'name'.
            $filteredMessages = $messages->filter('name');
            if (count($filteredMessages)) {
                $this->addMessages('name', $filteredMessages);
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
