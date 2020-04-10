<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Contracts;
use Engsurvey\Models\ConstructionProjects;
use Engsurvey\Models\Organizations;
use Engsurvey\Models\Branches;
use Engsurvey\Models\ContractStatuses;
use Engsurvey\Frontend\Forms\ContractForm;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;


class ContractsController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'contracts',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск договоров.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Поиск договоров и передача их в представление.
        $contracts = Contracts::find();
        $this->view->setVar('contracts', $contracts);

        // Шаблон, используемый для создания представления.
        $this->view->pick('contracts/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму со свойствами договора.
     */
    public function propertiesAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Contracts', 'properties') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contracts');
        }

        // Получение и валидация идентификатора договора переданного методом GET.
        $contactId = $this->request->getQuery('id');
        if (!Uuid::validate($contactId)) {
            throw new EngsurveyException('Ошибка получения ID ');
        }

        // Поиск объектов строительства (строек) и передача их в представление.
        $constructionProjects = ConstructionProjects::find(['order' => 'code']);
        $this->view->setVar('constructionProjects', $constructionProjects);

        // Поиск организаций и передача их в представление.
        $organizations = Organizations::find(['order' => 'displayName']);
        $this->view->setVar('organizations', $organizations);

        // Поиск филиалов и передача их в представление.
        $branches = Branches::find(['order' => 'sequenceNumber']);
        $this->view->setVar('branches', $branches);

        // Поиск cтатусов договоров и передача их в представление.
        $contractStatuses = ContractStatuses::find(['order' => 'sequenceNumber']);
        $this->view->setVar('contractStatuses', $contractStatuses);

        // Поиск договора и передача его представление.
        $contract = Contracts::findFirst("id = '$contactId'");
        if ($contract === false) {
            $this->flashSession->error('Договор не найден.');
            return $this->response->redirect('contracts');
        }
        $this->view->setVar('contract', $contract);

        // Установка значений элементов формы.
        $this->tag->setDefault('id', $contract->getId());
        $this->tag->setDefault('contractNumber', $contract->getContractNumber());
        $this->tag->setDefault('supplementalAgreementNumber', $contract->getSupplementalAgreementNumber());
        if (!is_null($contract->getSignatureDate())) {
            $this->tag->setDefault('signatureDate', $contract->getFormattedSignatureDate('d.m.Y'));
        }
        $this->tag->setDefault('subjectOfContract', $contract->getSubjectOfContract());
        if (!is_null($contract->getConstructionProjectId())) {
            $this->tag->setDefault('constructionProject', $contract->getConstructionProject()->getCode());
        }
        if (!is_null($contract->getCustomerId())) {
            $this->tag->setDefault('customer', $contract->getCustomer()->getDisplayName());
        }
        if (!is_null($contract->getBranchId())) {
            $this->tag->setDefault('branch', $contract->getBranch()->getDisplayName());
        }
        if (!is_null($contract->getContractStatusId())) {
            $this->tag->setDefault('contractStatus', $contract->getContractStatus()->getName());
        }
        if (!is_null($contract->getContractCost())) {
            $this->tag->setDefault('contractCost', $contract->getFormattedContractCost(2, ',', ' '));
        }
        $this->tag->setDefault('comment', $contract->getComment());

        // Шаблон представления.
        $this->view->pick('contracts/properties');
    }


    /**
     * Отображает форму создания нового договора.
     */
    public function newAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Contracts', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contracts');
        }

        // Поиск объектов строительства (строек) и передача их в представление.
        $constructionProjects = ConstructionProjects::find(['order' => 'code']);
        $this->view->setVar('constructionProjects', $constructionProjects);

        // Поиск организаций и передача их в представление.
        $organizations = Organizations::find(['order' => 'displayName']);
        $this->view->setVar('organizations', $organizations);

        // Поиск филиалов и передача их в представление.
        $branches = Branches::find(['order' => 'sequenceNumber']);
        $this->view->setVar('branches', $branches);

        // Поиск cтатусов договоров и передача их в представление.
        $contractStatuses = ContractStatuses::find(['order' => 'sequenceNumber']);
        $this->view->setVar('contractStatuses', $contractStatuses);

        // Форма, используемая в представлении.
        //$this->view->form = new ContractForm();

        // Шаблон, используемый для создания представления.
        $this->view->pick('contracts/new');
    }


    /**
     * Отображает форму редактирования атрибутов записи.
     */
    public function editAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Contracts', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contracts');
        }

        // Получение и валидация идентификатора договора переданного методом GET.
        $contactId = $this->request->getQuery('id');
        if (!Uuid::validate($contactId)) {
            // Попытка получение идентификатора договора из внутреннего перенаправления (forward).
            $contactId = $this->dispatcher->getParam('id');
            if (!Uuid::validate($contactId)) {
                throw new EngsurveyException('Ошибка получения ID ');
            }
        }

        // Поиск объектов строительства (строек) и передача их в представление.
        $constructionProjects = ConstructionProjects::find(['order' => 'code']);
        $this->view->setVar('constructionProjects', $constructionProjects);

        // Поиск организаций и передача их в представление.
        $organizations = Organizations::find(['order' => 'displayName']);
        $this->view->setVar('organizations', $organizations);

        // Поиск филиалов и передача их в представление.
        $branches = Branches::find(['order' => 'sequenceNumber']);
        $this->view->setVar('branches', $branches);

        // Поиск cтатусов договоров и передача их в представление.
        $contractStatuses = ContractStatuses::find(['order' => 'sequenceNumber']);
        $this->view->setVar('contractStatuses', $contractStatuses);

        // Поиск договора и передача его представление.
        $contract = Contracts::findFirst("id = '$contactId'");
        if ($contract === false) {
            $this->flashSession->error('Договор не найден.');
            return $this->response->redirect('contracts');
        }
        $this->view->setVar('contract', $contract);

        // Установка значений элементов формы свойствами найденого договора,
        // для данных переданных методом GET.
        if ($this->request->isGet()) {
            // Установка значений элементов формы.
            $this->tag->setDefault('id', $contract->getId());
            $this->tag->setDefault('contractNumber', $contract->getContractNumber());
            $this->tag->setDefault('supplementalAgreementNumber', $contract->getSupplementalAgreementNumber());
            $this->tag->setDefault('signatureDate', $contract->getFormattedSignatureDate('d.m.Y'));
            $this->tag->setDefault('subjectOfContract', $contract->getSubjectOfContract());
            $this->tag->setDefault('constructionProjectId', $contract->getConstructionProjectId());
            $this->tag->setDefault('customerId', $contract->getCustomerId());
            $this->tag->setDefault('branchId', $contract->getBranchId());
            $this->tag->setDefault('contractStatusId', $contract->getContractStatusId());
            $this->tag->setDefault('contractCost', $contract->getFormattedContractCost(2, ',', ' '));
            $this->tag->setDefault('comment', $contract->getComment());
        }

        // Шаблон представления.
        $this->view->pick('contracts/edit');
    }


    /**
     * Создает новую запись в базе данных с ссылками на отчеты организации, введенные в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Contracts', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contracts');
        }

        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Сохранение данных формы полученных методом POST.
        $formData = [];
        $formData['contractNumber'] = $this->request->getPost('contractNumber', 'trim');
        $formData['supplementalAgreementNumber'] = $this->request->getPost('supplementalAgreementNumber', 'trim');
        $formData['signatureDate'] = $this->request->getPost('signatureDate', 'trim');
        $formData['subjectOfContract'] = $this->request->getPost('subjectOfContract', 'trim');
        $formData['constructionProjectId'] = $this->request->getPost('constructionProjectId');
        $formData['customerId'] = $this->request->getPost('customerId');
        $formData['branchId'] = $this->request->getPost('branchId');
        $formData['contractStatusId'] = $this->request->getPost('contractStatusId');
        $formData['contractCost'] = $this->request->getPost('contractCost', 'trim');
        $formData['comment'] = $this->request->getPost('comment', 'trim');

        // Валидация данных формы.
        if ($this->validateFormData($formData) === false) {
            // Повторный вывод формы создания новой записи
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // Форматирование данных полученных из формы.
        $formData = $this->formatFormData($formData);

        // Генерация ID для новой записи.
        $id = Uuid::generate();

        // Создание новой записи.
        $contract = new Contracts();
        $contract->setId($id);
        $contract->setContractNumber($formData['contractNumber']);
        $contract->setSupplementalAgreementNumber($formData['supplementalAgreementNumber']);
        $contract->setSignatureDate($formData['signatureDate']);
        $contract->setSubjectOfContract($formData['subjectOfContract']);
        $contract->setConstructionProjectId($formData['constructionProjectId']);
        $contract->setCustomerId($formData['customerId']);
        $contract->setBranchId($formData['branchId']);
        $contract->setContractStatusId($formData['contractStatusId']);
        $contract->setContractCost($formData['contractCost']);
        $contract->setComment($formData['comment']);

        if ($contract->create() === false) {
            $this->flashSession->error('Ошибка создания новой записи.');
            return $this->dispatcher->forward(['action' => 'new']);
        }

        // HTTP редирект.
        return $this->response->redirect('contracts');
    }


    /**
     * Изменяет свойства договора на основе данных, введенных в действии 'edit'.
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

        // Получение и валидация идентификатора договора.
        $id = $this->request->getPost('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Некорректный идентификатор договора.');
        }

        // Сохранение данных формы полученных методом POST.
        $formData = [];
        $formData['id'] = $this->request->getPost('id');
        $formData['contractNumber'] = $this->request->getPost('contractNumber', 'trim');
        $formData['supplementalAgreementNumber'] = $this->request->getPost('supplementalAgreementNumber', 'trim');
        $formData['signatureDate'] = $this->request->getPost('signatureDate', 'trim');
        $formData['subjectOfContract'] = $this->request->getPost('subjectOfContract', 'trim');
        $formData['constructionProjectId'] = $this->request->getPost('constructionProjectId');
        $formData['customerId'] = $this->request->getPost('customerId');
        $formData['branchId'] = $this->request->getPost('branchId');
        $formData['contractStatusId'] = $this->request->getPost('contractStatusId');
        $formData['contractCost'] = $this->request->getPost('contractCost', 'trim');
        $formData['comment'] = $this->request->getPost('comment', 'trim');

        // Валидация данных формы.
        if ($this->validateFormData($formData) === false) {
            // Повторный вывод формы редактирования свойств договора
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // Форматирование данных полученных из формы.
        $formData = $this->formatFormData($formData);

        // Поиск договора.
        $contract = Contracts::findFirst("id = '$id'");
        if ($contract === false) {
            $this->flashSession->error('Договор не найден.');
            return $this->response->redirect('contracts');
        }

        // Обновление свойств договора.
        $contract->setContractNumber($formData['contractNumber']);
        $contract->setSupplementalAgreementNumber($formData['supplementalAgreementNumber']);
        $contract->setSignatureDate($formData['signatureDate']);
        $contract->setSubjectOfContract($formData['subjectOfContract']);
        $contract->setConstructionProjectId($formData['constructionProjectId']);
        $contract->setCustomerId($formData['customerId']);
        $contract->setBranchId($formData['branchId']);
        $contract->setContractStatusId($formData['contractStatusId']);
        $contract->setContractCost($formData['contractCost']);
        $contract->setComment($formData['comment']);

        if ($contract->update() === false) {
            $this->flashSession->error('Не удалось обновить свойства договора.');
            // Повторный вывод формы редактирования договора с сообщением об ошибке.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $id],
                ]
            );
        }

        // HTTP редирект.
        return $this->response->redirect('contracts');
    }


    /**
     * Удаляет договор.
     */
    public function deleteAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Contracts', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contracts');
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

        // Поиск договора.
        $contract = Contracts::findFirst("id = '$id'");
        if ($contract === false) {
            $this->flashSession->error('Договор не найден.');
            return $this->response->redirect('contracts');
        }

        // Удаление договора.
        if ($contract->delete() === false) {
            $msg = 'Не удалось удалить договор.';
            $this->flashSession->error($msg);
        } else {
            $msg = 'Договор успешно удален.';
            $this->flashSession->success($msg);
        }

        // HTTP редирект.
        return $this->response->redirect('contracts');
    }


    /**
     * Выполняет валидацию данных полученных из формы и формирует
     * сообщения об ошибках с передачей их в представление.
     * Возвращают булево значение, показывающее, прошла валидация
     * успешно, либо нет.
     *
     * @param array $formData Данные полученные из формы.
     *
     * @return bool
     */
    protected function validateFormData(array $formData): bool
    {
        $validation = new Validation();

        // Номер договора.
        $validation->add(
            'contractNumber',
            new PresenceOf(
                [
                    'message' => 'Номер договора обязателен.',
                ]
            )
        );

        // Дата подписания договора.
        $validation->add(
            'signatureDate',
            new Regex(
                [
                    // Дата в формате DD.MM.YYYY или пустое значение.
                    'pattern' => '/^(3[0-1]|0[1-9]|[1-2][0-9])\.(0[1-9]|1[0-2])\.([0-9]{4})|^$/',
                    'message' => 'Введите дату в формате ДД.ММ.ГГГГ или оставьте поле незаполненным.'
                ]
            )
        );

        // Предмет договора.
        $validation->add(
            'subjectOfContract',
            new PresenceOf(
                [
                    'message' => 'Предмет договора обязателен.',
                ]
            )
        );

        // Стройка.
        $validation->add(
            'constructionProjectId',
            new PresenceOf(
                [
                    'message' => 'Необходимо выбрать объект строительства (стройку).',
                ]
            )
        );

        // Заказчик (агент).
        $validation->add(
            'customerId',
            new PresenceOf(
                [
                    'message' => 'Необходимо выбрать заказчика.',
                ]
            )
        );

        // Стоимость работ по договору.
        $validation->add(
            'contractCost',
            new Regex(
                [
                    // Положительное дробное число (десятичный разделитель точка
                    // или запятая), с разделителем разрядов (пробел) или без него,
                    // или пустое значение.
                    'pattern' => '/^\d{1,3}( ?\d{3})*([\.,]?\d+)?$|^$/',
                    'message' => 'Недопустимое значение.'
                ]
            )
        );

        // Статус договора.
        $validation->add(
            'contractStatusId',
            new PresenceOf(
                [
                    'message' => 'Необходимо выбрать статус договора.',
                ]
            )
        );


        $messages = $validation->validate($formData);

        if (count($messages)) {

            // Сообщения для элемента формы 'contractNumber'.
            $filteredMessages = $messages->filter('contractNumber');
            if (count($filteredMessages)) {
                $contractNumberMessages = '';

                foreach ($filteredMessages as $message) {
                    $contractNumberMessages .= $message . '<br>';
                }

                $contractNumberMessages = rtrim($contractNumberMessages, '<br>');

                $this->view->setVar('contractNumberMessages', $contractNumberMessages);
            }

            // Сообщения для элемента формы 'signatureDate'.
            $filteredMessages = $messages->filter('signatureDate');
            if (count($filteredMessages)) {
                $signatureDateMessages = '';

                foreach ($filteredMessages as $message) {
                    $signatureDateMessages .= $message . '<br>';
                }

                $signatureDateMessages = rtrim($signatureDateMessages, '<br>');

                $this->view->setVar('signatureDateMessages', $signatureDateMessages);
            }

            // Сообщения для элемента формы 'subjectOfContract'.
            $filteredMessages = $messages->filter('subjectOfContract');
            if (count($filteredMessages)) {
                $subjectOfContractMessages = '';

                foreach ($filteredMessages as $message) {
                    $subjectOfContractMessages .= $message . '<br>';
                }

                $subjectOfContractMessages = rtrim($subjectOfContractMessages, '<br>');

                $this->view->setVar('subjectOfContractMessages', $subjectOfContractMessages);
            }

            // Сообщения для элемента формы 'constructionProjectId'.
            $filteredMessages = $messages->filter('constructionProjectId');
            if (count($filteredMessages)) {
                $constructionProjectMessages = '';

                foreach ($filteredMessages as $message) {
                    $constructionProjectMessages .= $message . '<br>';
                }

                $constructionProjectMessages = rtrim($constructionProjectMessages, '<br>');

                $this->view->setVar('constructionProjectMessages', $constructionProjectMessages);
            }

            // Сообщения для элемента формы 'customerId'.
            $filteredMessages = $messages->filter('customerId');
            if (count($filteredMessages)) {
                $customerMessages = '';

                foreach ($filteredMessages as $message) {
                    $customerMessages .= $message . '<br>';
                }

                $customerMessages = rtrim($customerMessages, '<br>');

                $this->view->setVar('customerMessages', $customerMessages);
            }

            // Сообщения для элемента формы 'contractStatusId'.
            $filteredMessages = $messages->filter('contractStatusId');
            if (count($filteredMessages)) {
                $contractStatusMessages = '';

                foreach ($filteredMessages as $message) {
                    $contractStatusMessages .= $message . '<br>';
                }

                $contractStatusMessages = rtrim($contractStatusMessages, '<br>');

                $this->view->setVar('contractStatusMessages', $contractStatusMessages);
            }

            // Сообщения для элемента формы 'contractCost'.
            $filteredMessages = $messages->filter('contractCost');
            if (count($filteredMessages)) {
                $contractCostMessages = '';

                foreach ($filteredMessages as $message) {
                    $contractCostMessages .= $message . '<br>';
                }

                $contractCostMessages = rtrim($contractCostMessages, '<br>');

                $this->view->setVar('contractCostMessages', $contractCostMessages);
            }

            return false;
        }

        return true;
    }


    /**
     * Форматирование данных полученных из формы.
     *
     * @param array $formData Данные полученные из формы.
     *
     * @return array
     */
    protected function formatFormData($formData)
    {
        $formattedFormData = $formData;

        // Форматирование даты подписания договора.
        $signatureDate = $formData['signatureDate'];
        if ($signatureDate === '') {
            $formattedFormData['signatureDate'] = null;
        } else {
            $dt = \DateTime::createFromFormat('d.m.Y', $signatureDate);
            $formattedFormData['signatureDate'] = $dt->format('Y-m-d');
        }
        
        // Форматирование идентификатора ответственного филиала.
        $branchId = $formData['branchId'];
        if ($branchId === '') {
            $formattedFormData['branchId'] = null;
        }

        // Форматирование стоимости работ по договору.
        $contractCost = $formData['contractCost'];
        if ($contractCost === '') {
            $formattedFormData['contractCost'] = null;
        } else {
            $contractCost = str_replace(' ', '', $contractCost);
            $contractCost = str_replace(',', '.', $contractCost);
            $formattedFormData['contractCost'] = $contractCost;
        }

        return $formattedFormData;
    }

}
