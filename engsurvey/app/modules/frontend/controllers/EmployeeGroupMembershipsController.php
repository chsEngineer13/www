<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\EmployeeGroupMemberships;
use Engsurvey\Models\EmployeeGroups;
use Engsurvey\Models\Employees;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;

class EmployeeGroupMembershipsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'employee-group-memberships',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск членов группы сотрудников.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Получение и валидация идентификатора группы сотрудников.
        $employeeGroupId = $this->request->getQuery('employee_group_id');
        if (!Uuid::validate($employeeGroupId)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        // Поиск группы сотрудников и передача ее в представление.
        $employeeGroup = EmployeeGroups::findFirst("id = '$employeeGroupId'");
        if ($employeeGroup === false) {
            $this->flashSession->error('Группа сотрудников не найдена.');

            return $this->response->redirect('employee-group-memberships?employee_group_id=' . $employeeGroupId);
        }
        $this->view->setVar('employeeGroup', $employeeGroup);

        // Поиск членов группы сотрудников и передача их в представление.
        $employeeGroupMemberships = EmployeeGroupMemberships::find("employeeGroupId = '$employeeGroupId'");
        $this->view->setVar('employeeGroupMemberships', $employeeGroupMemberships);

        // Поиск сотрудников.
        $obEmployees = Employees::find();

        // Формирование массива сотрудников с полными именами и
        // принадлежностью к филиалу.
        $arEmployees = [];
        foreach ($obEmployees as $obEmployee) {
            $employeeId = $obEmployee->getId();
            $fullName = $obEmployee->getFullName();
            $branch = $obEmployee->getBranch()->getDisplayName();

            $arEmployees[$employeeId] = "${fullName} (${branch})";
        }

        // Натуральная сортировка массива сотрудников.
        natsort($arEmployees);

        // Передача массива сотрудников в представление.
        $this->view->setVar('employees', $arEmployees);

        // Шаблон, используемый для создания представления.
        $this->view->pick('employee-group-memberships/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Добавляет сотрудника в группу сотрудников.
     */
    public function addAction()
    {
        // Получение и валидация идентификатора группы сотрудников.
        $employeeGroupId = $this->request->getQuery('employee_group_id');
        if (!Uuid::validate($employeeGroupId)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('EmployeeGroupMemberships', 'add') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employee-group-memberships?employee_group_id=' . $employeeGroupId);
        }

        // Получение и валидация идентификатора сотрудника.
        $employeeId = $this->request->getQuery('employee_id');
        if (!Uuid::validate($employeeId)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }
        
        // Поиск группы сотрудников.
        $employeeGroup = EmployeeGroups::findFirst("id = '$employeeGroupId'");
        if ($employeeGroup === false) {
            throw new EngsurveyException('Группа сотрудников не найдена.');
        }
        
        // Поиск сотрудника.
        $employee = Employees::findFirst("id = '$employeeId'");
        if ($employee === false) {
            throw new EngsurveyException('Сотрудник не найден.');
        }
        
        // Проверка вхождения сотрудника в группу сотрудников.
        if ($this->isEmployeeInEmployeeGroup($employeeId, $employeeGroupId)) {
            $this->flashSession->error('Сотрудник ' . $employee->getFullName() . 
                ' уже входит в группу «' . $employeeGroup->getName() . '».');
                
            return $this->response->redirect('employee-group-memberships?employee_group_id=' . $employeeGroupId);
        }
        
        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой записи.
        $model = new EmployeeGroupMemberships();
        $model->setId($id);
        $model->setEmployeeGroupId($employeeGroupId);
        $model->setEmployeeId($employeeId);

        if ($model->create() === false) {
            $this->flashSession->error('Ошибка добавления сотрудника ' . $employee->getFullName() . 
                ' в группу «' . $employeeGroup->getName() . '».');
        } else {
            $this->flashSession->success('Сотрудник ' . $employee->getFullName() . 
                ' успешно добавлен в группу ' . $employeeGroup->getName() . '.');
        }

        // HTTP редирект.
        return $this->response->redirect('employee-group-memberships?employee_group_id=' . $employeeGroupId);

    }
    
    
    /**
     * Удаляет сотрудника из группы сотрудников.
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

        // Поиск члена группы сотрудников.
        $member = EmployeeGroupMemberships::findFirst("id = '$id'");
        if ($member === false) {
            throw new EngsurveyException('Сотрудник не найден.');
        }

        $employeeGroupId = $member->getEmployeeGroup()->getId();
        $employeeGroupName = $member->getEmployeeGroup()->getName();
        $employeeFullName = $member->getEmployee()->getFullName();
        
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('EmployeeGroupMemberships', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('employee-group-memberships?employee_group_id=' . $employeeGroupId);
        }

        // Удаление члена группы сотрудников.
        if ($member->delete() === false) {
            $this->flashSession->error('Не удалось удалить сотрудника ' . $employeeFullName . 
                ' из группы «' . $employeeGroupName . '».');
        } else {
            $this->flashSession->success('Сотрудник ' . $employeeFullName . 
                ' успешно удален из группы «' . $employeeGroupName . '».');
        }

        // HTTP редирект.
        return $this->response->redirect('employee-group-memberships?employee_group_id=' . $employeeGroupId);
    }


    /**
     * Выполняет проверку на вхождение сотрудника в группу сотрудников.
     *
     * @param string $employeeId Уникальный идентификатор сотрудника.
     * @param string $employeeGroupId Уникальный идентификатор группы сотрудников.
     *
     * @return bool
     */
    protected function isEmployeeInEmployeeGroup(string $employeeId, string $employeeGroupId): bool
    {
        $member = EmployeeGroupMemberships::findFirst("employeeGroupId = '$employeeGroupId' AND employeeId = '$employeeId'");

        if ($member === false) {
            return false;
        }
        
        return true;
    }

}
