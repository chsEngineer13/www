<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\ContractFiles;
use Engsurvey\Models\Contracts;
use Engsurvey\Utils\Uuid;
use Engsurvey\Exception as EngsurveyException;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex;

class ContractFilesController extends ControllerBase
{
    /**
     * Начальное действие, которое позволяет отправить запрос к "search".
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controllers' => 'contracts-files',
                'action' => 'search',
            ]
        );
    }


   /**
    * Выполняет поиск файлов договора.
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
                throw new EngsurveyException('Ошибка получения идентификатора договора.');
            }
        }

        // Поиск договора и передача его представление.
        $contract = Contracts::findFirst("id = '$contractId'");
        if ($contract === false) {
            throw new EngsurveyException('Договор с id=' . $contractId . ' не найден.');
        }
        $this->view->setVar('contract', $contract);

        // Поиск файлов договора и передача их в представление.
        $contractFiles = ContractFiles::find(
            [
                "contractId = '$contractId'",
                "order" => "seqNumber",
            ]
        );
        $this->view->setVar('contractFiles', $contractFiles);

        // Шаблон, используемый для создания представления.
        $this->view->pick('contract-files/search');

        // Добавление скриптов DataTables.
        $this->addDatatablesAssets();
    }


    /**
     * Отображает форму редактирования атрибутов записи.
     */
    public function editAction()
    {
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $contractFileId = $this->request->getQuery('id');
        if (!Uuid::validate($contractFileId)) {
            // Попытка получение идентификатора файла договорной документации
            // из внутреннего перенаправления (forward).
            $contractFileId = $this->dispatcher->getParam('id');
            if (!Uuid::validate($contractFileId)) {
                throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
            }
        }

        // Поиск и передача их в представление файла договорной документации.
        $contractFile = ContractFiles::findFirst("id = '$contractFileId'");
        if ($contractFile === false) {
            throw new EngsurveyException('Файл договорной документации не найден.');
        }
        $this->view->setVar('contractFile', $contractFile);

        // Получение идентификатора договора.
        $contractId = $contractFile->getContractId();

        // Поиск и передача их в представление договора.
        $contract = Contracts::findFirst("id = '$contractId'");
        if ($contract === false) {
            throw new EngsurveyException('Договор не найден.');
        }
        $this->view->setVar('contract', $contract);

        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractFiles', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contract-files/search?contract_id=' . $contractId);
        }

        // Для данных переданных методом GET, установка значений элементов формы свойствами
        // найденого файла договорной документации.
        if ($this->request->isGet()) {
            // Установка значений элементов формы.
            $this->tag->setDefault('id', $contractFile->getId());
            $this->tag->setDefault('seqNumber', $contractFile->getSeqNumber());
            $this->tag->setDefault('filename', $contractFile->getFilename());
            $this->tag->setDefault('description', $contractFile->getDescription());
        }

        // Шаблон представления.
        $this->view->pick('contract-files/edit');

    }


    /**
     * Изменяет запись на основе данных, введенных в действии 'edit'.
     */
    public function updateAction()
    {
        // Проверка, что данные получены методом POST.
        if (!$this->request->isPost()) {
            throw new EngsurveyException('Ошибка получения данных методом POST.');
        }
        
        // Валидация идентификатора файла договорной документации.
        $contractFileId = $this->request->getPost('id');
        if (!Uuid::validate($contractFileId)) {
            throw new EngsurveyException('Некорректный идентификатор файла договорной документации.');
        }

        // Сохранение данных полученных методом POST.
        $data = [];
        $data['id'] = $this->request->getPost('id');
        $data['seqNumber'] = $this->request->getPost('seqNumber', 'trim');
        $data['description'] = $this->request->getPost('description', 'trim');

        // Валидация полученных данных.
        if ($this->validate($data) === false) {
            // Повторный вывод формы редактирования свойст файла
            // с сообщениями об ошибках.
            return $this->dispatcher->forward(
                [
                    'action' => 'edit',
                    'params' => ['id' => $contractFileId],
                ]
            );
        }

        // Форматирование данных полученных из формы.
        $data = $this->format($data);
        
        // Поиск файла договорной документации.
        $сontractFile = ContractFiles::findFirst("id = '$contractFileId'");
        if ($сontractFile === false) {
            throw new EngsurveyException('Файл договорной документации не найден.');
        }
        
        // Получение идентификатора договора.
        $contractId = $сontractFile->getContractId();
        
        // Порядковые номера: старый и новый.
        $oldSeqNumber = $сontractFile->getSeqNumber();
        $newSeqNumber = $data['seqNumber'];
        
        // Сдвиг порядковых номеров перед обновлением записи.
        if ($oldSeqNumber !== $newSeqNumber) {
            if (ContractFiles::shiftSeqNumbers($contractId, $data['seqNumber']) === false) {
                throw new EngsurveyException('Не удалось выполнить сдвиг порядковых номеров перед обновлением записи.');
            }
        }

        // Обновление записи.
        $сontractFile->setSeqNumber($data['seqNumber']);
        $сontractFile->setDescription($data['description']);

        if ($сontractFile->update() === false) {
            throw new EngsurveyException('Не удалось обновить запись в базе данных.');
        }
        
        // Перенумерация порядковых номеров после обновления записи.
        if ($oldSeqNumber !== $newSeqNumber) {
            if (ContractFiles::renumberSeqNumbers($contractId) === false) {
                throw new EngsurveyException('Не удалось перенумеровать порядковые номера после обновления записи.');
            }
        }

        // HTTP редирект.
        return $this->response->redirect('contract-files/search?contract_id=' . $contractId);
    }


   /**
    * Выполняет загрузку файлов договора в файловое хранилище.
    * Сохраняет информацию о загруженных файла в базе данных.
    */
    public function uploadAction()
    {
        // Получение и валидация идентификатора договора переданного методом GET.
        $contractId = $this->request->getQuery('contract_id');
        if (!Uuid::validate($contractId)) {
            throw new EngsurveyException('Ошибка получения идентификатора договора.');
        }

        // Проверка загрузки файлов.
        if ($this->request->hasFiles() != true) {
            throw new EngsurveyException('Ошибка загрузки файлов.');
        }

        // Описание файла.
        $description = $this->request->getPost('description');

        // Путь к хранилищу файлов договорной документации.
        $config = $this->di->get('config');
        $contractsDir = $config->storage->contractsDir;
        
        // Получение последнего порядкового номер присвоенного файлу договора.
        $seqNumber = ContractFiles::getLastSeqNumber($contractId);

        // Получение вложенных файлов.
        $uploadedFiles = $this->request->getUploadedFiles();

        // Цикл по переносу вложенных файлов в файловое хранилище.
        foreach ($uploadedFiles as $file) {
            $filename = $file->getName();
            $fileSize = $file->getSize();
            $mimeType = $file->getType();

            // Получение ID, под которым файл будет храниться в системе.
            $fileId = Uuid::generate();


            // Создание директории, если она не существует,
            // куда необходимо переместить файл.
            $dirName = substr($fileId, 0, 2);
            $dir = $contractsDir . $dirName . DS;
            if(!file_exists($dir)) {
                if(!is_dir(!file_exists($dir))){
                    if(!mkdir($dir)) {
                        throw new EngsurveyException('Ошибка создания директории.');
                    }
                }
            }

            // Создание поддиректории, если она не существует,
            // куда необходимо переместить файл.
            $subDirName = substr($fileId, 2, 2);
            $subDir = $dir . $subDirName . DS;
            if(!file_exists($subDir)) {
                if(!is_dir(!file_exists($subDir))){
                    if(!mkdir($subDir)) {
                        throw new EngsurveyException('Ошибка создания поддиректории.');
                    }
                }
            }

            // Перемещение файла в хранилище.
            $fullFilename = $subDir . $fileId;
            $file->moveTo($fullFilename);

            // Увеличение порядкового номера файла.
            $seqNumber++;

            // Добавление информации о файле в БД.
            $contractFiles = new ContractFiles();
            $contractFiles->setId($fileId);
            $contractFiles->setContractId($contractId);
            $contractFiles->setSeqNumber($seqNumber);
            $contractFiles->setFilename($filename);
            $contractFiles->setSize($fileSize);
            $contractFiles->setMimeType($mimeType);
            $contractFiles->setDescription($description);

            if ($contractFiles->create() == false) {
                foreach ($contractFiles->getMessages() as $message) {
                    $msg = 'Message: ' . $message->getMessage() . '. ';
                    $msg .= 'Field: ' . $message->getField() . '. ';
                    $msg .= 'Type: ' . $message->getType() . '.';
                }

                throw new EngsurveyException('Не удалось сохранить запись в базе данных: ' . $msg);
            }
        }

        // HTTP редирект.
        return $this->response->redirect('contract-files/search?contract_id=' . $contractId);

    }


    /**
     * Отдает файла из хранилища.
     */
    public function downloadAction()
    {
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        // Получение файла договорной документации.
        $contractFile = ContractFiles::findFirst("id = '$id'");
        if ($contractFile === false) {
            throw new EngsurveyException('Файл договорной документации не найден.');
        }

        // Получение имени файла.
        $filename = $contractFile->getFilename();

        // Получение полного имени файла в хранилище по ID.
        $fullFilename = $this->getFullFilenameById($id);

        // Отдача файла.
        // TODO(24.06.2015): Попытаться сделать средствами Falcone.
        header('Content-Type: "application/force-download"; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . iconv('UTF-8', 'cp1251', $filename) . '"');
        echo file_get_contents($fullFilename);

        exit();
    }


    /**
     * Удаляет файл договорной документации.
     */
    public function deleteAction()
    {
        // Получение и валидация обязательного параметра 'id' переданного методом GET.
        $id = $this->request->getQuery('id');
        if (!Uuid::validate($id)) {
            throw new EngsurveyException('Отсутствует или некорректный параметр URL.');
        }

        // Поиск файла договорной документации.
        $contractFile = ContractFiles::findFirst("id = '$id'");
        if ($contractFile === false) {
            throw new EngsurveyException('Файл договорной документации не найден.');
        }

        // Получение идентификатора договора.
        $contractId = $contractFile->getContractId();

        // Проверка доступ к данному действию текущего пользователя.
        if ($this->isAllowedCurrentUser('ContractFiles', 'delete') === false) {
            $this->flashSession->error('У вас отсутствуют права для выполнение данной операции.');
            return $this->response->redirect('contract-files/search?contract_id=' . $contractId);
        }

        // Удаление файла договорной документации.
        if ($contractFile->delete() === false) {
            $msg = 'Не удалось удалить файл.';
            $this->flashSession->error($msg);
        } else {
            $msg = 'Файл успешно удален.';
            $this->flashSession->success($msg);
        }
        
        // Перенумерация порядковых номеров после обновления записи.
        if (ContractFiles::renumberSeqNumbers($contractId) === false) {
            throw new EngsurveyException('Не удалось перенумеровать порядковые номера после обновления записи.');
        }

        // HTTP редирект.
        return $this->response->redirect('contract-files/search?contract_id=' . $contractId);
    }


    /**
     * Возвращает полное имя файла в хранилище по ID.
     */
    protected function getFullFilenameById(string $id): string
    {
        // Путь к хранилищу файлов договорной документации.
        $config = $this->di->get('config');
        $contractsDir = $config->storage->contractsDir;

        // Получение имени директории.
        $dirName = substr($id, 0, 2);

        // Получение имени поддиректории.
        $subDirName = substr($id, 2, 2);

        // Формирование полного имени файла договорной документации.
        $fullFilename = $contractsDir . $dirName . DS . $subDirName . DS . $id;

        return $fullFilename;
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
        
        $messages = $validation->validate($data);
        
        if (count($messages)) {
            // Сообщения для элемента формы 'seqNumber'.
            $filteredMessages = $messages->filter('seqNumber');
            if (count($filteredMessages)) {
                $this->addMessages('seqNumber', $filteredMessages);
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
