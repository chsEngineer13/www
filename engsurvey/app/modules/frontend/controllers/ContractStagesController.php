<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Contracts;
use Engsurvey\Models\ContractStages;
use Engsurvey\Models\ConstructionSites;
use Engsurvey\Utils\Uuid;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Mvc\Model\Resultset;


class ContractStagesController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'contract-stages',
                'action' => 'search',
            ]
        );
    }


    /**
    * Выполняет поиск этапов работ.
    * Возвращает результаты с пагинацией.
    */
    public function searchAction()
    {
        // Получение и валидация идентификатора договора переданного методом GET.
        $contractId = $this->request->getQuery('contract_id');
        if (!Uuid::validate($contractId)) {
            // Попытка получение идентификатора договора из внутреннего перенаправления (forward).
            $contractId = $this->dispatcher->getParam('contractId');
            if (!Uuid::validate($contractId)) {
                throw new \Exception('Не удалось получить идентификатора договора.');
            }
        }

        // Поиск договора и передача его представление.
        $contract = Contracts::findFirst("id = '$contractId'");
        if ($contract === false) {
            throw new \Exception('Договор с id="' . $contractId . '" не найден.');
        }
        $this->view->setVar('contract', $contract);

        // Поиск этапов договора и передача их в представление.
        $contractStages = ContractStages::find(
            [
                "contractId = '$contractId'",
                'order' => 'stageNumber'
            ]
        );
        $this->view->setVar('contractStages', $contractStages);

        // Шаблон, используемый для создания представления.
        $this->view->pick('contract-stages/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму создания нового этапа работ.
     */
    public function newAction()
    {
        // Получение и валидация идентификатора договора переданного методом GET.
        $contractId = $this->request->getQuery('contract_id');
        if (!Uuid::validate($contractId)) {
            // Попытка получение идентификатора договора из внутреннего перенаправления (forward).
            $contractId = $this->dispatcher->getParam('contract_id');
            if (!Uuid::validate($contractId)) {
                throw new \Exception('Не удалось получить идентификатора договора.');
            }
        }

        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractStages', 'new') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contract-stages?contract_id=' . $contractId);
        }

        // Поиск договора и передача его представление.
        $contract = Contracts::findFirst("id = '$contractId'");
        if ($contract === false) {
            throw new \Exception('Договор с id="' . $contractId . '" не найден.');
        }
        $this->view->setVar('contract', $contract);

        // Поиск участков работ и передача их в представление.
        $constructionProjectId = $contract->getConstructionProjectId();
        $constructionSites = ConstructionSites::find(
            [
                "constructionProjectId = '$constructionProjectId'",
                'order' => 'siteNumber'
            ]
        );
        $this->view->setVar('constructionSites', $constructionSites);

        // Установка значений элементов формы при запросе переданного методом GET.
        if ($this->request->isGet()) {
            $this->tag->setDefault('contractId', $contractId);
        }

        // Шаблон, используемый для создания представления.
        $this->view->pick('contract-stages/new');
    }


    /**
     * Отображает форму для редактирование существующего этапа работ.
     */
    public function editAction()
    {
        // Получение и валидация идентификатора этапа работ переданного методом GET.
        $contactStageId = $this->request->getQuery('id');
        if (!Uuid::validate($contactStageId)) {
            // Попытка получение идентификатора этапа работ из внутреннего перенаправления (forward).
            $contactStageId = $this->dispatcher->getParam('id');
            if (!Uuid::validate($contactStageId)) {
                throw new \Exception('Не удалось получить идентификатор этапа работ по договору.');
            }
        }

        // Поиск этапа работ и передача его представление.
        $contractStage = ContractStages::findFirst("id = '$contactStageId'");
        if ($contractStage === false) {
            throw new \Exception('Не удалось получить этап работ по договору.');
        }
        $this->view->setVar('contractStage', $contractStage);

        // Поиск договора и передача его представление.
        $contactId = $contractStage->getContractId();
        $contract = Contracts::findFirst("id = '$contactId'");
        if ($contract === false) {
            throw new \Exception('Не удалось получить договор.');
        }
        $this->view->setVar('contract', $contract);

        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractStages', 'edit') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contract-stages?contract_id=' . $contractId);
        }

        // Поиск участков работ и передача их в представление.
        $constructionProjectId = $contract->getConstructionProjectId();
        $constructionSites = ConstructionSites::find(
            [
                "constructionProjectId = '$constructionProjectId'",
                'order' => 'siteNumber'
            ]
        );
        $this->view->setVar('constructionSites', $constructionSites);

        // Установка значений элементов формы при запросе переданного методом GET.
        if ($this->request->isGet()) {
            $this->tag->setDefault('id', $contractStage->getId());
            $this->tag->setDefault('contractId', $contractStage->getContractId());
            $this->tag->setDefault('sectionNumber', $contractStage->getSectionNumber());
            $this->tag->setDefault('sectionName', $contractStage->getSectionName());
            $this->tag->setDefault('stageNumber', $contractStage->getStageNumber());
            $this->tag->setDefault('stageName', $contractStage->getStageName());
            $this->tag->setDefault('constructionSiteId', $contractStage->getConstructionSiteId());
            $this->tag->setDefault('startDate', $contractStage->getFormattedStartDate('d.m.Y'));
            $this->tag->setDefault('endDate', $contractStage->getFormattedEndDate('d.m.Y'));
            $this->tag->setDefault('costWithoutVat', $contractStage->getFormattedCostWithoutVat(1, ',', ' '));
            $this->tag->setDefault('vat', $contractStage->getFormattedVat(1, ',', ' '));
            $this->tag->setDefault('costWithVat', $contractStage->getFormattedCostWithVat(1, ',', ' '));
            $this->tag->setDefault('comment', $contractStage->getComment());
        }

        // Шаблон представления.
        $this->view->pick('contract-stages/edit');
    }


    /**
     * Создает этап работ на основе данных, введенных в действии "new".
     */
    public function createAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractStages', 'edit') === false) {
            throw new \Exception('У пользователя отсутствуют права для выполнение данной операции.');
        }

        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Сохранение данных формы полученных методом POST.
        $formData = [];
        $formData['contractId'] = $this->request->getPost('contractId');
        $formData['sectionNumber'] = $this->request->getPost('sectionNumber', 'trim');
        $formData['sectionName'] = $this->request->getPost('sectionName', 'trim');
        $formData['stageNumber'] = $this->request->getPost('stageNumber', 'trim');
        $formData['stageName'] = $this->request->getPost('stageName', 'trim');
        $formData['constructionSiteId'] = $this->request->getPost('constructionSiteId');
        $formData['startDate'] = $this->request->getPost('startDate', 'trim');
        $formData['endDate'] = $this->request->getPost('endDate', 'trim');
        $formData['costWithoutVat'] = $this->request->getPost('costWithoutVat', 'trim');
        $formData['vat'] = $this->request->getPost('vat', 'trim');
        $formData['costWithVat'] = $this->request->getPost('costWithVat', 'trim');
        $formData['comment'] = $this->request->getPost('comment', 'trim');

        // Валидация данных формы.
        if ($this->validateFormData($formData) === false) {

            $contractId = $this->request->getPost('contractId');

            // Если данные не прошли проверку, повторно выводится форма
            // создания нового этапа работ с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'new',
                    'params' => ['contract_id' => $contractId]
                ]
            );
        }

        // Форматирование данных формы прошедших проверку.
        $formData = $this->formatFormData($formData);

        // Генерация идентификатора для нового этапа работ.
        $contractStageId = Uuid::generate();

        // Создание нового этапа работ.
        $contractStage = new ContractStages();
        $contractStage->setId($contractStageId);
        $contractStage->setContractId($formData['contractId']);
        $contractStage->setSectionNumber($formData['sectionNumber']);
        $contractStage->setSectionName($formData['sectionName']);
        $contractStage->setStageNumber($formData['stageNumber']);
        $contractStage->setStageName($formData['stageName']);
        $contractStage->setConstructionSiteId($formData['constructionSiteId']);
        $contractStage->setStartDate($formData['startDate']);
        $contractStage->setEndDate($formData['endDate']);
        $contractStage->setCostWithoutVat($formData['costWithoutVat']);
        $contractStage->setVat($formData['vat']);
        $contractStage->setCostWithVat($formData['costWithVat']);
        $contractStage->setComment($formData['comment']);

        if ($contractStage->create() === false) {
            $exceptionMessage = "Не удалось создать этап работ по договору: \n";

            $messages = $contractStage->getMessages();
            foreach ($messages as $message) {
                $exceptionMessage .= $message . "\n";
            }

            throw new \Exception($exceptionMessage);
        }

        // HTTP редирект.
        return $this->response->redirect('contract-stages?contract_id=' . $formData['contractId']);
    }


    /**
    * Изменяет этап работ на основе данных, введенных в действии "edit".
    */
    public function updateAction()
    {
        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractStages', 'edit') === false) {
            throw new \Exception('У пользователя отсутствуют права для выполнение данной операции.');
        }

        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['action' => 'index']);
        }

        // Получение и валидация идентификатора этапа работ по договору.
        $contractStageId = $this->request->getPost('id');
        if (!Uuid::validate($contractStageId)) {
            throw new \Exception('Некорректный идентификатор этапа работ по договору.');
        }

        // Сохранение данных формы полученных методом POST.
        $formData = [];
        $formData['id'] = $this->request->getPost('id');
        $formData['contractId'] = $this->request->getPost('contractId');
        $formData['sectionNumber'] = $this->request->getPost('sectionNumber', 'trim');
        $formData['sectionName'] = $this->request->getPost('sectionName', 'trim');
        $formData['stageNumber'] = $this->request->getPost('stageNumber', 'trim');
        $formData['stageName'] = $this->request->getPost('stageName', 'trim');
        $formData['constructionSiteId'] = $this->request->getPost('constructionSiteId');
        $formData['startDate'] = $this->request->getPost('startDate', 'trim');
        $formData['endDate'] = $this->request->getPost('endDate', 'trim');
        $formData['costWithoutVat'] = $this->request->getPost('costWithoutVat', 'trim');
        $formData['vat'] = $this->request->getPost('vat', 'trim');
        $formData['costWithVat'] = $this->request->getPost('costWithVat', 'trim');
        $formData['comment'] = $this->request->getPost('comment', 'trim');

        // Валидация данных формы.
        if ($this->validateFormData($formData) === false) {
            // Если данные не прошли проверку, повторно выводится форма
            // создания нового этапа работ с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $contractStageId]
                ]
            );
        }

        // Форматирование данных полученных из формы.
        $formData = $this->formatFormData($formData);

        // Поиск этапа работ по договору.
        $contractStage = ContractStages::findFirst("id = '$contractStageId'");
        if ($contractStage === false) {
            throw new \Exception('Этап работ по договору не найден.');
        }

        // Обновление свойств договора.
        $contractStage->setSectionNumber($formData['sectionNumber']);
        $contractStage->setSectionName($formData['sectionName']);
        $contractStage->setStageNumber($formData['stageNumber']);
        $contractStage->setStageName($formData['stageName']);
        $contractStage->setConstructionSiteId($formData['constructionSiteId']);
        $contractStage->setStartDate($formData['startDate']);
        $contractStage->setEndDate($formData['endDate']);
        $contractStage->setCostWithoutVat($formData['costWithoutVat']);
        $contractStage->setVat($formData['vat']);
        $contractStage->setCostWithVat($formData['costWithVat']);
        $contractStage->setComment($formData['comment']);

        if ($contractStage->update() === false) {
            $exceptionMessage = "Не удалось обновить этап работ по договору: \n";

            $messages = $contractStage->getMessages();
            foreach ($messages as $message) {
                $exceptionMessage .= $message . "\n";
            }

            throw new \Exception($exceptionMessage);
        }

        // HTTP редирект.
        return $this->response->redirect('contract-stages?contract_id=' . $formData['contractId']);
    }


    /**
     * Удаляет существующий этап работ.
     */
    public function deleteAction()
    {
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $contractStageId = $this->request->getQuery('id');
        if (!Uuid::validate($contractStageId)) {
            throw new \Exception('Отсутствует или некорректный параметр URL.');
        }

        // Поиск этапа работ по договору.
        $contractStage = ContractStages::findFirst("id = '$contractStageId'");
        if ($contractStage === false) {
            throw new \Exception('Этап работ по договору не найден.');
        }

        // Получение свойств этапа работ.
        $contractId = $contractStage->getContractId();
        $stageNumber = $contractStage->getStageNumber();

        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('Contracts', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contract-stages?contract_id=' . $contractId);
        }

        // Удаление этапа работ по договору.
        if ($contractStage->delete() === false) {
            throw new \Exception('Не удалось удалить этап работ по договору.');
        } else {
            $msg = 'Этап работ № ' . $stageNumber . ' успешно удален.';
            $this->flashSession->success($msg);
        }

        // HTTP редирект.
        return $this->response->redirect('contract-stages?contract_id=' . $contractId);
    }


    /**
     * Выполняет импорт этапов работ из файл MS Excel (XLSX).
     */
    public function importXlsxAction()
    {
        // Получение и валидация обязательного параметра 'contract_id' переданного методом GET.
        $contractId = $this->request->getQuery('contract_id');
        if (!Uuid::validate($contractId)) {
            throw new \Exception('Отсутствует или некорректный параметр URL.');
        }

        // Проверка доступа к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractStages', 'importXlsx') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            $this->response->redirect('contract-stages?contract_id=' . $contractId);
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

                $this->importXlsx($contractId, $file);
            }
        } else {
            throw new \Exception('Ошибка загрузки файл.');
        }

        // HTTP редирект.
        return $this->response->redirect('contract-stages?contract_id=' . $contractId);
    }


    /**
     * Выполняет экспорт этапов работ в файл MS Excel (XLSX).
     */
    public function exportXlsxAction()
    {
        // Получение и валидация обязательного параметра 'contract_id' переданного методом GET.
        $contractId = $this->request->getQuery('contract_id');
        if (!Uuid::validate($contractId)) {
            throw new \Exception('Отсутствует или некорректный параметр URL.');
        }

        // Проверка доступа к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractStages', 'exportXlsx') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            $this->response->redirect('contract-stages?contract_id=' . $contractId);
        }

        // Экспорт этапов работ.
        $this->exportXlsx($contractId);

        // HTTP редирект.
        return $this->response->redirect('contract-stages?contract_id=' . $contractId);
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

        // Номер этапа работ.
        $validation->add(
            'stageNumber',
            new PresenceOf(
                [
                    'message' => 'Номер этапа работ обязателен.'
                ]
            )
        );

        // Наименование работ (этапа работ).
        $validation->add(
            'stageName',
            new PresenceOf(
                [
                    'message' => 'Наименование этапа работ обязателено.'
                ]
            )
        );

        // Дата начала работ.
        $validation->add(
            'startDate',
            new Regex(
                [
                    // Дата в формате DD.MM.YYYY или пустое значение.
                    'pattern' => '/^(3[0-1]|0[1-9]|[1-2][0-9])\.(0[1-9]|1[0-2])\.([0-9]{4})|^$/',
                    'message' => 'Введите дату в формате ДД.ММ.ГГГГ или оставьте поле незаполненным.'
                ]
            )
        );

        // Дата окончания работ.
        $validation->add(
            'endDate',
            new Regex(
                [
                    // Дата в формате DD.MM.YYYY или пустое значение.
                    'pattern' => '/^(3[0-1]|0[1-9]|[1-2][0-9])\.(0[1-9]|1[0-2])\.([0-9]{4})|^$/',
                    'message' => 'Введите дату в формате ДД.ММ.ГГГГ или оставьте поле незаполненным.'
                ]
            )
        );

        // Стоимость работ без НДС.
        $validation->add(
            'costWithoutVat',
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

        // НДС
        $validation->add(
            'vat',
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

        // Стоимость работ с учетом НДС.
        $validation->add(
            'costWithVat',
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


        // Валидация даннных и получение сообщений об ошибках.
        $messages = $validation->validate($formData);

        if (count($messages)) {
            foreach ($messages as $message) {
                // Передача сообщения об ошибке в представление.
                $this->addErrorMessage($message->getField(), $message->getMessage());
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

        // Форматирование идентификатора участка работ.
        $constructionSiteId = $formData['constructionSiteId'];
        if ($constructionSiteId === '') {
            $formattedFormData['constructionSiteId'] = null;
        }

        // Форматирование даты начала работ.
        $startDate = $formData['startDate'];
        if ($startDate === '') {
            $formattedFormData['startDate'] = null;
        } else {
            $dt = \DateTime::createFromFormat('d.m.Y', $startDate);
            $formattedFormData['startDate'] = $dt->format('Y-m-d');
        }

        // Форматирование даты окончания работ.
        $endDate = $formData['endDate'];
        if ($endDate === '') {
            $formattedFormData['endDate'] = null;
        } else {
            $dt = \DateTime::createFromFormat('d.m.Y', $endDate);
            $formattedFormData['endDate'] = $dt->format('Y-m-d');
        }

        // Форматирование стоимости работ без НДС.
        $costWithoutVat = $formData['costWithoutVat'];
        if ($costWithoutVat === '') {
            $formattedFormData['costWithoutVat'] = null;
        } else {
            $costWithoutVat = str_replace(' ', '', $costWithoutVat);
            $costWithoutVat = str_replace(',', '.', $costWithoutVat);
            $formattedFormData['costWithoutVat'] = $costWithoutVat;
        }

        // Форматирование НДС
        $vat = $formData['vat'];
        if ($vat === '') {
            $formattedFormData['vat'] = null;
        } else {
            $vat = str_replace(' ', '', $vat);
            $vat = str_replace(',', '.', $vat);
            $formattedFormData['vat'] = $vat;
        }

        // Форматирование стоимости работ с учетом НДС.
        $costWithVat = $formData['costWithVat'];
        if ($costWithVat === '') {
            $formattedFormData['costWithVat'] = null;
        } else {
            $costWithVat = str_replace(' ', '', $costWithVat);
            $costWithVat = str_replace(',', '.', $costWithVat);
            $formattedFormData['costWithVat'] = $costWithVat;
        }

        return $formattedFormData;
    }


    /**
     * Добавляет в представление сообщение об ошибкe.
     */
    protected function addErrorMessage(string $field, string $message)
    {
        $fieldMessage = $field . 'Message';
        $this->view->setVar($fieldMessage, $message);
    }


    /**
     * Возвращает объект PHPExcel.
     */
    protected function getPhpXlsx()
    {
        // Подключение файлов библиотеке PHPExcel.
        $config = $this->di->get("config");
        $phpExcelDir = $config->phpExcel->phpExcelDir;
        require_once $phpExcelDir . "PHPExcel.php";
        require_once $phpExcelDir . "PHPExcel/Writer/Excel2007.php";

        // Создание объекта PHPExcel.
        $phpExcel = new \PHPExcel();

        return $phpExcel;
    }


    //--------------------------------------------------------------------------
    // Функции импорта календарного плана в файл MS Excel (XLSX).
    //--------------------------------------------------------------------------

    /**
     * Импорт этапов работ в файл MS Excel (XLSX).
     */
    protected function importXlsx($contractId, $file)
    {
        // Поиск договора.
        $contract = Contracts::findFirst("id = '$contractId'");
        if ($contract === false) {
            throw new \Exception('Договор с id="' . $contractId . '" не найден.');
        }

        // Получение идентификатора стройки.
        $constructionProjectId = $contract->getConstructionProjectId();

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
        $sheet = $obPhpExcel->getActiveSheet();

        // Получение количества заполненных строк в листе.
        $rowCount = $sheet->getHighestRow();

        // Количество созданных записей.
        $createdCount = 0;
        // Количество обновленных записей.
        $updatedCount = 0;
        // Количество пропущенных строк.
        $missingCount = 0;

        // Цикл по строкам первого листа файла начиная с третий строки.
        for ($row = 3; $row <= $rowCount; $row++) {
            // Свойства этапа работ.
            $properties = [];

            // Идентификатор этапа работ. Столбец - 'A'.
            $contractStageId = $sheet->getCell('A' . (string)$row)->getValue();
            $contractStageId = trim($contractStageId);
            if ($contractStageId === '') {
                $contractStageId = null;
            }
            $properties['id'] = $contractStageId;

            // Идентификатор договора.
            $properties['contractId'] = $contractId;
            
            // Номер раздела календарного плана. Столбец - 'B'.
            $sectionNumber = $sheet->getCell('B' . (string)$row)->getValue();
            $sectionNumber = trim($sectionNumber);
            $properties['sectionNumber'] = $sectionNumber;

            // Наименование раздела календарного плана. Столбец - 'C'.
            $sectionName = $sheet->getCell('C' . (string)$row)->getValue();
            $sectionName = trim($sectionName);
            $properties['sectionName'] = $sectionName;

            // Номер этапа работ. Столбец - 'D'.
            $stageNumber = $sheet->getCell('D' . (string)$row)->getValue();
            $stageNumber = trim($stageNumber);
            $properties['stageNumber'] = $stageNumber;

            // Наименование этапа работ. Столбец - 'E'.
            $stageName = $sheet->getCell('E' . (string)$row)->getValue();
            $stageName = trim($stageName);
            $properties['stageName'] = $stageName;

            // Наименование участка работ. Столбец - 'F'.
            $constructionSiteName = $sheet->getCell('F' . (string)$row)->getValue();
            $constructionSiteName = trim($constructionSiteName);

            // Получение идентификатора участка работ.
            if ($constructionSiteName === '') {
                $constructionSiteId = null;
            } else {
                $constructionSiteId = $this->getConstructionSiteIdByNameImportXlsx(
                    $constructionProjectId,
                    $constructionSiteName);
            }
            $properties['constructionSiteId'] = $constructionSiteId;

            // Дата начала работ. Столбец - 'G'.
            $startDate = $sheet->getCell('G' . (string)$row)->getValue();
            $startDate = $this->formatDateImportXlsx($startDate);
            $properties['startDate'] = $startDate;

            // Дата окончания работ. Столбец - 'H'.
            $endDate = $sheet->getCell('H' . (string)$row)->getValue();
            $endDate = $this->formatDateImportXlsx($endDate);
            $properties['endDate'] = $endDate;

            // Стоимость работ без НДС. Столбец - 'I'.
            $costWithoutVat = $sheet->getCell('I' . (string)$row)->getValue();
            $costWithoutVat = trim($costWithoutVat);
            if (is_numeric($costWithoutVat)) {
                $properties['costWithoutVat'] = (float)$costWithoutVat;
            } else {
                $properties['costWithoutVat'] = null;
            }

            // НДС. Столбец - 'J'.
            $vat = $sheet->getCell('J' . (string)$row)->getValue();
            $vat = trim($vat);
            if (is_numeric($vat)) {
                $properties['vat'] = (float)$vat;
            } else {
                $properties['vat'] = null;
            }

            // Стоимость работ с учетом НДС. Столбец - 'K'.
            $costWithVat = $sheet->getCell('K' . (string)$row)->getValue();
            $costWithVat = trim($costWithVat);
            if (is_numeric($costWithVat)) {
                $properties['costWithVat'] = (float)$costWithVat;
            } else {
                $properties['costWithVat'] = null;
            }

            // Комментарий. Столбец - 'L'.
            $comment = $sheet->getCell('L' . (string)$row)->getValue();
            $comment = trim($comment);
            $properties['comment'] = $comment;

            // Импорт данных.
            if (is_null($contractStageId)) {
                // Создание нового этапа работ.
                if ($this->createImportXlsx($properties)) {
                    $createdCount++;
                } else {
                    $missingCount++;
                }
            } else {
                // Обновление существующего этапа работ.
                if ($this->updateImportXlsx($properties)) {
                    $updatedCount++;
                } else {
                    $missingCount++;
                }
            }

        }

        // Сообшения о результатах импорта.
        $msg = 'Импорт завершен.';
        $this->flashSession->success($msg);

        if ($createdCount > 0) {
            $msg = 'Cоздано объектов: ' . $createdCount . '.';
            $this->flashSession->success($msg);
        }

        if ($updatedCount > 0) {
            $msg = 'Обновлено объектов: ' . $updatedCount . '.';
            $this->flashSession->success($msg);
        }

        if ($missingCount > 0) {
            $msg = 'Пропущено строк: ' . $missingCount . '.';
            $this->flashSession->success($msg);
        }

    }


    /**
     * Создание нового этапа работ при импорте календарного плана
     * из файл формата XLSX.
     */
    protected function createImportXlsx($properties)
    {
        // Генерация ID для нового этапа работ.
        $сontractStageId = Uuid::generate();

        // Создание нового этапа работ.
        $сontractStage = new ContractStages();
        $сontractStage->setId($сontractStageId);
        $сontractStage->setContractId($properties['contractId']);
        $сontractStage->setSectionNumber($properties['sectionNumber']);
        $сontractStage->setSectionName($properties['sectionName']);
        $сontractStage->setStageNumber($properties['stageNumber']);
        $сontractStage->setStageName($properties['stageName']);
        $сontractStage->setConstructionSiteId($properties['constructionSiteId']);
        $сontractStage->setStartDate($properties['startDate']);
        $сontractStage->setEndDate($properties['endDate']);
        $сontractStage->setCostWithoutVat($properties['costWithoutVat']);
        $сontractStage->setVat($properties['vat']);
        $сontractStage->setCostWithVat($properties['costWithVat']);
        $сontractStage->setComment($properties['comment']);

        if ($сontractStage->create() === false) {
            return false;
        }

        return true;
    }


    /**
     * Обновление этапа работ при импорте календарного плана
     * из файл формата XLSX.
     */
    protected function updateImportXlsx($properties)
    {
        $contractStageId = $properties['id'];

        // Поиск этапа работ по договору.
        $contractStage = ContractStages::findFirst("id = '$contractStageId'");
        if ($contractStage === false) {
            throw new \Exception('Этап работ по договору не найден.');
        }

        $contractStage->setContractId($properties['contractId']);
        $сontractStage->setSectionNumber($properties['sectionNumber']);
        $сontractStage->setSectionName($properties['sectionName']);
        $contractStage->setStageNumber($properties['stageNumber']);
        $contractStage->setStageName($properties['stageName']);
        $contractStage->setConstructionSiteId($properties['constructionSiteId']);
        $contractStage->setStartDate($properties['startDate']);
        $contractStage->setEndDate($properties['endDate']);
        $contractStage->setCostWithoutVat($properties['costWithoutVat']);
        $contractStage->setVat($properties['vat']);
        $contractStage->setCostWithVat($properties['costWithVat']);
        $contractStage->setComment($properties['comment']);

        if ($contractStage->update() === false) {
            return false;
        }

        return true;
    }


    /**
     * Получение идентификатора участка работ по наименованию
     * при импорте календарного плана из файл формата XLSX.
     */
    protected function getConstructionSiteIdByNameImportXlsx($constructionProjectId, $constructionSiteName)
    {
        // Условия поиска.
        $conditions = "constructionProjectId = '$constructionProjectId'" .
                      " AND name = '$constructionSiteName'";

        // Поиск участка работ.
        $constructionSite = ConstructionSites::findFirst($conditions);
        if ($constructionSite === false) {
            return null;
        }

        $constructionSiteId = $constructionSite->getId();

        return $constructionSiteId;
    }


    /**
     * Форматирование даты при импорте календарного плана из файл формата XLSX.
     */
    protected function formatDateImportXlsx($date)
    {
        $formattedDate = null;

        if (is_float($date)) {
            $formattedDate = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($date));
        }

        if (is_string($date)) {
            if (strlen(trim($date)) > 0) {
                $dt = \DateTime::createFromFormat('d.m.Y', trim($date));
                $formattedDate = $dt->format('Y-m-d');
            }
        }

        return $formattedDate;
    }


    //--------------------------------------------------------------------------
    // Функции экспорта календарного плана в файл MS Excel (XLSX).
    //--------------------------------------------------------------------------

    /**
     * Экспорт календарного плана в файл MS Excel (XLSX).
     */
    protected function exportXlsx($contractId)
    {
        // Поиск договора.
        $contract = Contracts::findFirst("id = '$contractId'");
        if ($contract === false) {
            throw new \Exception('Договор с id="' . $contractId . '" не найден.');
        }

        // Поиск этапов работ по договора.
        $contractStages = ContractStages::find("contractId = '$contractId'");

        // Получение отсортированного массива этапов работ.
        $contractStagesArray = $this->getContractStagesArrayExportXlsx($contractStages);

        // Получение объекта PHPExcel.
        $phpExcel = $this->getPhpXlsx();

        // Добавление свойств документа.
        $this->setDocumentPropertiesExportXlsx($phpExcel, $contract);

        // Получение активного листа Excel.
        $sheet = $this->getActiveSheetExportXlsx($phpExcel);

        // Установка параметров печати.
        $this->setPageSetupExportXlsx($sheet);

        // Столбцы
        $contractStageIdCol = 'A';
        $sectionNumberCol = 'B';
        $sectionNameCol = 'C';
        $stageNumberCol = 'D';
        $stageNameCol = 'E';
        $constructionSiteNameCol = 'F';
        $startDateCol = 'G';
        $endDateCol = 'H';
        $costWithoutVatCol = 'I';
        $vatCol = 'J';
        $costWithVatCol = 'K';
        $commentCol = 'L';

        // Первый и последний столбцы.
        $startCol = 'A';
        $endCol = 'L';

        // Установка ширины столбцов.
        $sheet->getColumnDimension($contractStageIdCol)->setWidth(40);
        $sheet->getColumnDimension($sectionNumberCol)->setWidth(10);
        $sheet->getColumnDimension($sectionNameCol)->setWidth(60);
        $sheet->getColumnDimension($stageNumberCol)->setWidth(10);
        $sheet->getColumnDimension($stageNameCol)->setWidth(60);
        $sheet->getColumnDimension($constructionSiteNameCol)->setWidth(40);
        $sheet->getColumnDimension($startDateCol)->setWidth(15);
        $sheet->getColumnDimension($endDateCol)->setWidth(15);
        $sheet->getColumnDimension($costWithoutVatCol)->setWidth(15);
        $sheet->getColumnDimension($vatCol)->setWidth(15);
        $sheet->getColumnDimension($costWithVatCol)->setWidth(15);
        $sheet->getColumnDimension($commentCol)->setWidth(40);

        // Текущая строка.
        $currentRow = 1;

        // Получение свойств договора.
        $contractNumber = $contract->getContractNumber();
        $supplementalAgreementNumber = $contract->getSupplementalAgreementNumber();
        $subjectOfContract = $contract->getSubjectOfContract();

        // Заголовок.
        $title = "Календарный план к договору № " . $contractNumber;
        if (!empty($supplementalAgreementNumber)) {
            $title .= ' ДС № ' . $supplementalAgreementNumber;
        }
        $sheet->setCellValue($startCol . (string)$currentRow, $title);

        // Диапазон заголовка.
        $range = $startCol . (string)$currentRow . ':' .
                 $endCol . (string)$currentRow;

        // Установка стиля заголовка.
        $this->setTitleStyleExportXlsx($sheet, $range);

        // Установка высоты строки заголовка.
        $sheet->getRowDimension($currentRow)->setRowHeight(40);

        // Следующая строка.
        $currentRow++;

        // Подпись ячеек заголовка таблицы.
        $sheet->setCellValue($contractStageIdCol . (string)$currentRow, 'Идентификатор этапа работ');
        $sheet->setCellValue($sectionNumberCol . (string)$currentRow, '№ раздела КП');
        $sheet->setCellValue($sectionNameCol . (string)$currentRow, 'Наименование раздела КП');
        $sheet->setCellValue($stageNumberCol . (string)$currentRow, '№ этапа');
        $sheet->setCellValue($stageNameCol . (string)$currentRow, 'Наименование работ (этапа работ)');
        $sheet->setCellValue($constructionSiteNameCol . (string)$currentRow, 'Участок работ');
        $sheet->setCellValue($startDateCol . (string)$currentRow, 'Начало работ');
        $sheet->setCellValue($endDateCol . (string)$currentRow, 'Окончание работ');
        $sheet->setCellValue($costWithoutVatCol . (string)$currentRow, 'Стоимость без НДС');
        $sheet->setCellValue($vatCol . (string)$currentRow, 'НДС');
        $sheet->setCellValue($costWithVatCol . (string)$currentRow, 'Стоимость с НДС');
        $sheet->setCellValue($commentCol . (string)$currentRow, 'Комментарий');

        // Диапазон заголовка таблицы.
        $range = $startCol . (string)$currentRow . ':' . $endCol . (string)$currentRow;

        // Установка стиля ячеек заголовка таблицы.
        $this->setTableHeaderStyleExportXlsx($sheet, $range);

        // Установка высоты строки заголовка таблицы.
        $sheet->getRowDimension($currentRow)->setRowHeight(40);

        // Следующая строка.
        $currentRow++;

        // Закрепление области.
        $cell = $startCol . (string)$currentRow;
        $sheet->freezePane($cell);

        // Установка стиля ячеек содержимого таблицы.
        if (count($contractStagesArray) > 0) {
            // Получение начальной и заключительной строк содержимого таблицы.
            $startDataRow = $currentRow;
            $endDataRow = $currentRow + count($contractStagesArray) - 1;

            // Установка общего стиля для всех ячеек содержимого таблицы.
            $range = $startCol . (string)$startDataRow . ':' .
                     $endCol . (string)$endDataRow;
            $this->setTableBodyCommonStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с идентификаторами этапов работ.
            $range = $contractStageIdCol . (string)$startDataRow . ':' .
                     $contractStageIdCol . (string)$endDataRow;
            $this->setContractStageIdColStyleExportXlsx($sheet, $range);
            
            // Установка стиля ячеек столбца с номерами раздела календарного плана.
            $range = $sectionNumberCol . (string)$startDataRow . ':' .
                     $sectionNumberCol . (string)$endDataRow;
            $this->setSectionNumberColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с наименованием раздела календарного плана.
            $range = $sectionNameCol . (string)$startDataRow . ':' .
                     $sectionNameCol . (string)$endDataRow;
            $this->setSectionNameColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с номерами этапов работ.
            $range = $stageNumberCol . (string)$startDataRow . ':' .
                     $stageNumberCol . (string)$endDataRow;
            $this->setStageNumberColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с наименованием этапов работ.
            $range = $stageNameCol . (string)$startDataRow . ':' .
                     $stageNameCol . (string)$endDataRow;
            $this->setStageNameColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с наименованием участков работ.
            $range = $constructionSiteNameCol . (string)$startDataRow . ':' .
                     $constructionSiteNameCol . (string)$endDataRow;
            $this->setConstructionSiteNameColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с датами начала работ.
            $range = $startDateCol . (string)$startDataRow . ':' .
                     $startDateCol . (string)$endDataRow;
            $this->setStartDateColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с датами окончания работ.
            $range = $endDateCol. (string)$startDataRow . ':' .
                     $endDateCol . (string)$endDataRow;
            $this->setEndDateColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца со стоимостью работ без НДС.
            $range = $costWithoutVatCol . (string)$startDataRow . ':' .
                     $costWithoutVatCol . (string)$endDataRow;
            $this->setCostWithoutVatColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с НДС.
            $range = $vatCol. (string)$startDataRow . ':' .
                     $vatCol . (string)$endDataRow;
            $this->setVatColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца со стоимостью работ с НДС.
            $range = $costWithVatCol. (string)$startDataRow . ':' .
                     $costWithVatCol . (string)$endDataRow;
            $this->setCostWithVatColStyleExportXlsx($sheet, $range);

            // Установка стиля ячеек столбца с комментариями.
            $range = $commentCol. (string)$startDataRow . ':' .
                     $commentCol . (string)$endDataRow;
            $this->setCommentColStyleExportXlsx($sheet, $range);
        }

        // Запись в таблицу данных по этапам работ.
        foreach ($contractStagesArray as $contractStageArray) {
            // Идентификатор этапа работ.
            $contractStageId = $contractStageArray['id'];
            $sheet->setCellValue($contractStageIdCol . (string)$currentRow, $contractStageId);

            // Номер раздела календарного плана.
            $sectionNumber = $contractStageArray['sectionNumber'];
            // Чтобы Excel, в русской версии, не заменял в номере точку на запятую
            // используется функция setValueExplicit.
            $sheet->getCell($sectionNumberCol . (string)$currentRow)
                  ->setValueExplicit($sectionNumber, \PHPExcel_Cell_DataType::TYPE_STRING);

            // Наименование раздела календарного плана.
            $sectionName = $contractStageArray['sectionName'];
            $sheet->setCellValue($sectionNameCol . (string)$currentRow, $sectionName);
            
            // Номер этапа работ.
            $stageNumber = $contractStageArray['stageNumber'];
            // Чтобы Excel, в русской версии, не заменял в номере точку на запятую
            // используется функция setValueExplicit.
            $sheet->getCell($stageNumberCol . (string)$currentRow)
                  ->setValueExplicit($stageNumber, \PHPExcel_Cell_DataType::TYPE_STRING);

            // Наименование работ (этапа работ).
            $stageName = $contractStageArray['stageName'];
            $sheet->setCellValue($stageNameCol . (string)$currentRow, $stageName);

            // Наименование участка работ.
            $constructionSiteName = $contractStageArray['constructionSiteName'];
            $sheet->setCellValue($constructionSiteNameCol . (string)$currentRow, $constructionSiteName);

            // Дата начала работ.
            $startDate = $contractStageArray['startDate'];
            $sheet->setCellValue($startDateCol . (string)$currentRow, $startDate);

            // Дата окончания работ.
            $endDate = $contractStageArray['endDate'];
            $sheet->setCellValue($endDateCol . (string)$currentRow, $endDate);

            // Стоимость работ без НДС.
            $costWithoutVat = $contractStageArray['costWithoutVat'];
            $sheet->setCellValue($costWithoutVatCol . (string)$currentRow, $costWithoutVat);

            // НДС
            $vat = $contractStageArray['vat'];
            $sheet->setCellValue($vatCol . (string)$currentRow, $vat);

            // Стоимость работ с учетом НДС.
            $costWithVat = $contractStageArray['costWithVat'];
            $sheet->setCellValue($costWithVatCol . (string)$currentRow, $costWithVat);

            // Комментарий.
            $comment = $contractStageArray['comment'];
            $sheet->setCellValue($commentCol . (string)$currentRow, $comment);

            // Следующая строка.
            $currentRow++;

        } // foreach

        // Получение имени файла.
        $filename = $this->getFilenameExportXlsx($contract);

        // Перенаправление вывода на браузер клиента
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=" . $filename);
        header("Cache-Control: max-age=0");

        // Отдаем файл на скачивание
        $writer = new \PHPExcel_Writer_Excel2007($phpExcel);
        $writer->save('php://output');

        // Удаление объекта PHPExcel
        $phpExcel->disconnectWorksheets();
        unset($phpExcel);

        exit();

    } // function exportXlsx


    /**
     * Настраивает и возвращает активный лист при экспорте календарного плана
     * в файл формата XLSX.
     */
    protected function getActiveSheetExportXlsx(&$phpExcel)
    {
        // Получение листа Excel.
        $phpExcel->setActiveSheetIndex(0);
        $sheet = $phpExcel->getActiveSheet();

        // Подпись листа.
        $sheet->setTitle('КП');

        // Масштаба вывода на экран.
        $sheet->getSheetView()->setZoomScale(85);

        return $sheet;
    }


    /**
     * Возвращает имя файла при экспорте календарного плана
     * в файл формата XLSX.
     */
    protected function getFilenameExportXlsx($contract)
    {
        // Получение свойст договора.
        $contractNumber = $contract->getContractNumber();
        $supplementalAgreementNumber = $contract->getSupplementalAgreementNumber();

        // Имя файла
        $filename = $contractNumber;
        if (!empty($supplementalAgreementNumber)) {
            $filename .= ' ДС ' . $supplementalAgreementNumber;
        }
        $filename .= ' КП ' . date('Y-m-d');

        // Добавление расширения к имени файла
        $filename .= '.xlsx';

        // Замена пробелов и недопустимых символов на знак подчеркивания.
        $filename = str_replace(['\\', '/', ' '], '_', $filename);

        // Конвертация имени файла в кодировку 'windows-1251'
        $filename = iconv('utf-8', 'windows-1251', $filename);

        return $filename;
    }



    /**
     * Возвращает отсортированный массив этапов работ
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function getContractStagesArrayExportXlsx($contractStages)
    {
        $contractStagesArray = [];

        foreach ($contractStages as $contractStage) {
            $contractStageArray = [];

            $contractStageArray['id'] = $contractStage->getId();
            $contractStageArray['contractId'] = $contractStage->getContractId();
            $contractStageArray['sectionNumber'] = $contractStage->getSectionNumber();
            $contractStageArray['sectionName'] = $contractStage->getSectionName();
            $contractStageArray['stageNumber'] = $contractStage->getStageNumber();
            $contractStageArray['stageName'] = $contractStage->getStageName();
            $contractStageArray['constructionSiteId'] = $contractStage->getConstructionSiteId();

            $constructionSiteId = $contractStage->getConstructionSiteId();
            $constructionSiteName = '';
            if (!is_null($constructionSiteId)) {
                $constructionSiteName = $contractStage->getConstructionSite()->getName();
            }
            $contractStageArray['constructionSiteName'] =  $constructionSiteName;

            $contractStageArray['startDate'] = $contractStage->getFormattedStartDate('d.m.Y');
            $contractStageArray['endDate'] = $contractStage->getFormattedEndDate('d.m.Y');
            $contractStageArray['costWithoutVat'] = $contractStage->getCostWithoutVat();
            $contractStageArray['vat'] = $contractStage->getVat();
            $contractStageArray['costWithVat'] = $contractStage->getCostWithVat();
            $contractStageArray['comment'] = $contractStage->getComment();

            $contractStagesArray[] = $contractStageArray;
        }

        // Естественная (натуральная) сортировка массива этапов работ
        // по номеру (без учета регистра).
        $order = [];
        foreach($contractStagesArray as $key=>$value){
          $order[$key] = $value['stageNumber'];
        }
        $nameOrderLower = array_map('mb_strtolower', $order);
        array_multisort($nameOrderLower, SORT_ASC, SORT_NATURAL, $contractStagesArray);

        return $contractStagesArray;
    }


    /**
     * Добавляет свойста документа при экспорта календарного плана
     * в файл формата XLSX.
     */
    protected function setDocumentPropertiesExportXlsx(&$phpExcel, $contract)
    {
        // Получение конфигурации.
        $config = $this->di->get("config");

        // Получение свойст договора.
        $contractNumber = $contract->getContractNumber();
        $supplementalAgreementNumber = $contract->getSupplementalAgreementNumber();
        $subjectOfContract = $contract->getSubjectOfContract();

        // Заголовок документа.
        $docTitle = 'Календарный план к договору № ' . $contractNumber;
        if (!empty($supplementalAgreementNumber)) {
            $docTitle .= ' ДС № ' . $supplementalAgreementNumber;
        }

        // Автор документа - текущий пользователь.
        $author = $this->getCurrentUser()->getEmployee()->getShortName();

        // Организация
        $company = $config->app->company;

        // Краткое наименование программы.
        $appShortName = $config->app->appShortName;

        // Описание документа.
        $docDescription = 'Документ создан программой ' . $appShortName;

        // Установка свойства документа.
        $phpExcel->getProperties()->setCreator($author);
        $phpExcel->getProperties()->setLastModifiedBy($author);
        $phpExcel->getProperties()->setTitle($docTitle);
        $phpExcel->getProperties()->setDescription($docDescription);
        $phpExcel->getProperties()->setCompany($company);
    }


    /**
     * Устанавливает параметры печати при экспорте календарного плана
     * в файл формата XLSX.
     */
    protected function setPageSetupExportXlsx(&$sheet)
    {
        // Размер и ориентация листа.
        $sheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        // Поля страницы (в дюймах)
        $sheet->getPageMargins()->setTop(0.5906);
        $sheet->getPageMargins()->setRight(0.5906);
        $sheet->getPageMargins()->setLeft(0.5906);
        $sheet->getPageMargins()->setBottom(0.5906);
        $sheet->getPageMargins()->setHeader(0.3937);
        $sheet->getPageMargins()->setFooter(0.3937);

        // Горизонтальное/вертикальное центрирование страницы .
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);

        // Ширину в одну страницу.
        $sheet->getPageSetup()->setFitToWidth(1);
        // Высоту в бесконечность.
        $sheet->getPageSetup()->setFitToHeight(0);

        // Получение сокращенного наименования программы.
        $config = $this->di->get("config");
        $appShortName = $config->app->appShortName;

        // Колонтитулы.
        $sheet->getHeaderFooter()->setOddHeader('&L&K808080' . $appShortName);
        $sheet->getHeaderFooter()->setOddFooter('&R&P');
    }


    /**
     * Устанавливает стиль ячеек заголовка
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setTitleStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'font'=>[
                'bold' => true,
                'size' => 16
             ],
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
             ],
        ];
        $sheet->getStyle($range)->applyFromArray($style);

        // Объединение ячеек заголовка.
        $sheet->mergeCells($range);
    }


    /**
     * Устанавливает стиль ячеек заголовка таблицы даннных
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setTableHeaderStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'font'=>[
                'bold' => true,
                'size' => 11,
             ],
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
             ],
            'borders' => [
                'outline' => [
                   'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                ],
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает общий стиль ячеек содержимого таблицы
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setTableBodyCommonStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'font'=>[
                'size' => 11,
            ],
            'borders' => [
                'outline' => [
                   'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                ],
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с идентификаторами этапов работ
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setContractStageIdColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            'numberformat'=> [
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }
    
    
    /**
     * Устанавливает стиль ячеек столбца с номером раздела календарного плана
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setSectionNumberColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            'numberformat'=> [
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с наименованием раздела календарного плана.
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setSectionNameColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            // В MS Excel 2007 некорректно заполняет ячейку длинной строкой
            // в текстовом формате. Формат ячеки заменен на общий.
            'numberformat'=> [
                //'code' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_GENERAL,
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с номером этапа работ
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setStageNumberColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            'numberformat'=> [
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с наименованием этапов работ.
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setStageNameColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            // В MS Excel 2007 некорректно заполняет ячейку длинной строкой
            // в текстовом формате. Формат ячеки заменен на общий.
            'numberformat'=> [
                //'code' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_GENERAL,
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с наименованием участков работ
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setConstructionSiteNameColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            'numberformat'=> [
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с датами начала работ
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setStartDateColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            'numberformat'=>[
                // Дата в формате ДД.ММ.ГГГГ
                'code' => 'dd/mm/yyyy',
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с датами окончания работ
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setEndDateColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            'numberformat'=>[
                // Дата в формате ДД.ММ.ГГГГ
                'code' => 'dd/mm/yyyy',
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца со стоимостью работ без НДС
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setCostWithoutVatColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => 'right',
                'vertical' => 'center',
                'wrap' => true
            ],
            'numberformat'=>[
                // '#,##0.00'
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ]
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с НДС
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setVatColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => 'right',
                'vertical' => 'center',
                'wrap' => true
            ],
            'numberformat'=>[
                // '#,##0.00'
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ]
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца со стоимостью работ с НДС
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setCostWithVatColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => 'right',
                'vertical' => 'center',
                'wrap' => true
            ],
            'numberformat'=>[
                // '#,##0.00'
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ]
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }


    /**
     * Устанавливает стиль ячеек столбца с комментариями
     * при экспорте календарного плана в файл формата XLSX.
     */
    protected function setCommentColStyleExportXlsx(&$sheet, $range)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                'wrap' => true,
            ],
            'numberformat'=> [
                'code' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($style);
    }

}
