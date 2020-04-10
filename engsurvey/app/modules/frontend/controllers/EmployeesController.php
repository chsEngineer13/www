<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Employees;
use Engsurvey\Frontend\Forms\EmployeeForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;

class EmployeesController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'employees',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск сотрудников.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск сотрудников и передача их в представление.
        $employees = Employees::find();
        $this->view->setVar('employees', $employees);

        // Шаблон, используемый для создания представления.
        $this->view->pick('employees/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания нового назначения бригады.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Employees', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employees');
        }
        
        // Форма, используемая в представлении.
        $this->view->form = new EmployeeForm;

        // Шаблон, используемый для создания представления.
        $this->view->pick('employees/new');
    }


    /**
     * Отображает форму редактирования данных о сотруднике.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Employees', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employees');
        }
        
        // Данные пришли методом GET.
        if ($this->request->isGet()) {

            // Получение и валидация ID сотрудника.
            $id = $this->request->getQuery('id');
            if (!Uuid::validate($id)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }

            // Поиск сотрудника.
            $employee = Employees::findFirst("id = '$id'");
            if ($employee === false) {
                $this->flashSession->error('Сотрудник не найден.');
                return $this->response->redirect('employees');
            }

            // Найденные данные связываются с формой,
            // передавая модель первым параметром.
            $this->view->form = new EmployeeForm($employee);
        }

        // Данные пришли методом POST.
        if ($this->request->isPost()) {
            // Форма, используемая в представлении.
            $this->view->form = new EmployeeForm();
        }

        // Шаблон представления.
        $this->view->pick('employees/edit');
    }


    /**
     * Создает нового сотрудника на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Employees', 'create') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employees');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы создания нового сотрудника
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Сохранение данных полученных из формы в переменных.
        $lastName = $this->request->getPost('lastName', 'trim');
        $firstName = $this->request->getPost('firstName', 'trim');
        $middleName = $this->request->getPost('middleName', 'trim');
        $branchId = $this->request->getPost('branchId');
        $jobTitle = $this->request->getPost('jobTitle', 'trim');
        $department = $this->request->getPost('department', 'trim');
        $location = $this->request->getPost('location', 'trim');
        $phoneWork = $this->request->getPost('phoneWork', 'trim');
        $phoneGas = $this->request->getPost('phoneGas', 'trim');
        $phoneMobile = $this->request->getPost('phoneMobile', 'trim');
        $email = $this->request->getPost('email', 'trim');
        
        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой записи.
        $employee = new Employees();
        $employee->setId($id);
        $employee->setLastName($lastName);
        $employee->setFirstName($firstName);
        $employee->setMiddleName($middleName);
        $employee->setBranchId($branchId);
        $employee->setJobTitle($jobTitle);
        $employee->setDepartment($department);
        $employee->setLocation($location);
        $employee->setPhoneWork($phoneWork);
        $employee->setPhoneGas($phoneGas);
        $employee->setPhoneMobile($phoneMobile);
        $employee->setEmail($email);

        if ($employee->create() === false) {
            $this->flashSession->error('Ошибка создания нового сотрудника.');
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // HTTP редирект.
        return $this->response->redirect('employees');
    }


    /**
     * Изменяет запись о сотруднике на основе данных, введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Employees', 'update') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employees');
        }
        
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }
        
        // Получение и валидация ID сотрудника.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный идентификатор сотрудника.');
        }

        // Валидация полученных данных.
        if (!$this->validate($this->request->getPost())) {
            // Повторный вывод формы редактирования данных о сотруднике
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // Сохранение данных полученных из формы в переменных.
        $lastName = $this->request->getPost('lastName', 'trim');
        $firstName = $this->request->getPost('firstName', 'trim');
        $middleName = $this->request->getPost('middleName', 'trim');
        $branchId = $this->request->getPost('branchId');
        $jobTitle = $this->request->getPost('jobTitle', 'trim');
        $department = $this->request->getPost('department', 'trim');
        $location = $this->request->getPost('location', 'trim');
        $phoneWork = $this->request->getPost('phoneWork', 'trim');
        $phoneGas = $this->request->getPost('phoneGas', 'trim');
        $phoneMobile = $this->request->getPost('phoneMobile', 'trim');
        $email = $this->request->getPost('email', 'trim');

        // Поиск сотрудника.
        $employee = Employees::findFirst("id = '$id'");
        if ($employee === false) {
            $this->flashSession->error('Сотрудник не найден.');
            return $this->response->redirect('employees');
        }
        
        // Обновление записи.
        $employee->setLastName($lastName);
        $employee->setFirstName($firstName);
        $employee->setMiddleName($middleName);
        $employee->setBranchId($branchId);
        $employee->setJobTitle($jobTitle);
        $employee->setDepartment($department);
        $employee->setLocation($location);
        $employee->setPhoneWork($phoneWork);
        $employee->setPhoneGas($phoneGas);
        $employee->setPhoneMobile($phoneMobile);
        $employee->setEmail($email);

        if ($employee->update() === false) {
            $this->flashSession->error('Не удалось обновить данные о сотруднике.');
            // Повторный вывод формы редактирования данных о сотруднике
            // с сообщением об ошибке.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // HTTP редирект.
        return $this->response->redirect('employees');
    }


    /**
     * Удаляет назначение бригады на объект.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Employees', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employees');
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
        
        // Поиск сотрудника.
        $employee = Employees::findFirst("id = '$id'");
        if ($employee === false) {
            $this->flashSession->error('Сотрудник не найден.');
            return $this->response->redirect('employees');
        }

        $shortName = $employee->getShortName();

        // Удаление записи о сотруднике.
        if ($employee->delete() === false) {
            $msg = "Не удалось удалить сотрудника ${shortName}.";
            $this->flashSession->error($msg);
        } else {
            $msg = "Сотрудник ${shortName} успешно удален.";
            $this->flashSession->success($msg);
        }

        // HTTP редирект.
        return $this->response->redirect('employees');
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
        $form = new EmployeeForm();

        if ($form->isValid($data) === false) {

            // Сообщения для элемента 'lastName'.
            $messages = $form->getMessagesFor('lastName');
            if (count($messages)) {
                $lastNameMessages = '';

                foreach ($messages as $message) {
                    $lastNameMessages .= $message . '<br>';
                }

                $lastNameMessages = rtrim($lastNameMessages, '<br>');

                $this->view->setVar('lastNameMessages', $lastNameMessages);
            }

            // Сообщения для элемента 'firstName'.
            $messages = $form->getMessagesFor('firstName');
            if (count($messages)) {
                $firstNameMessages = '';

                foreach ($messages as $message) {
                    $firstNameMessages .= $message . '<br>';
                }

                $firstNameMessages = rtrim($firstNameMessages, '<br>');

                $this->view->setVar('firstNameMessages', $firstNameMessages);
            }

            // Сообщения для элемента 'middleName'.
            $messages = $form->getMessagesFor('middleName');
            if (count($messages)) {
                $middleNameMessages = '';

                foreach ($messages as $message) {
                    $middleNameMessages .= $message . '<br>';
                }

                $middleNameMessages = rtrim($middleNameMessages, '<br>');

                $this->view->setVar('middleNameMessages', $middleNameMessages);
            }

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

            // Сообщения для элемента 'jobTitle'.
            $messages = $form->getMessagesFor('jobTitle');
            if (count($messages)) {
                $jobTitleMessages = '';

                foreach ($messages as $message) {
                    $jobTitleMessages .= $message . '<br>';
                }

                $jobTitleMessages = rtrim($jobTitleMessages, '<br>');

                $this->view->setVar('jobTitleMessages', $jobTitleMessages);
            }


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

            return false;
        }

        return true;
    }

}
