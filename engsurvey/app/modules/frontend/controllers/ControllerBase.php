<?php
declare(strict_types=1);

namespace Engsurvey\Frontend\Controllers;

use Phalcon\Mvc\Controller as PhalconController;
use Phalcon\Mvc\Dispatcher;
use Engsurvey\Models\Users;
use Engsurvey\Models\UserGroupMemberships;

/**
 * ControllerBase
 * Базовый контроллер для всех контроллеров модуля.
 */
class ControllerBase extends PhalconController
{
    /**
     * Инициализация контроллера.
     */
    public function initialize()
    {
        $this->view->setTemplateBefore('main');

        // Наименование приложения.
        $appShortName = $this->config->app->appShortName;
        $this->tag->setTitle($appShortName);

        // Имя пользователя.
        $currentUserName = '';
        $currentUser = $this->getCurrentUser();
        if (!is_null($currentUser)) {
            $currentUserName = $currentUser->getEmployee()->getShortName();
        }
        $this->view->setVar('currentUserName', $currentUserName);

        // Добавление коллекций ресурсов.
        $this->assets->collection('headerCss');
        $this->assets->collection('headerJs');
        $this->assets->collection('footerJs');
    }
    
    
    /**
     * Выполняется перед работой маршрутизатора. Проверяет, прошел ли
     * пользователь аутентификацию.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        // Если пользователь не прошел аутентификацию,
        // он перенаправляем на страницу для ее прохождения.
        $currentUser = $this->getCurrentUser();
        if (is_null($currentUser)) {
            $dispatcher->forward([
                'controller' => 'auth',
                'action' => 'login'
            ]);
            
            return false;
        }

        // Если пользователю требуется смена пароля перенаправляем его
        // на соответствующую страницу.
        $mustChangePassword = $currentUser->getMustChangePassword();
        if ($mustChangePassword) {
            $this->flashSession->warning('Требуется сменить пароль.');

            $dispatcher->forward([
                'controller' => 'auth',
                'action' => 'changePassword'
            ]);

            return false;
        }

        return true;
    }
    

    /**
     * Возвращает текущего пользователя, если он осуществил вход в систему.
     * Если текущий пользователя нет, функция возвращает null.
     *
     */
    protected function getCurrentUser(): ?Users
    {
        if ($this->session->has('currentUser')) {
            $currentUser = $this->session->get('currentUser');
            return $currentUser;
        }

        return null;
    }
    

    /**
     * Проверяет, имеет ли текущий пользователь права на выполнение операции.
     */
    protected function isAllowedCurrentUser(string $controller, string $action): bool
    {
        // Получение списка контроля доступа (ACL).
        $acl = $this->di->get('acl');

        // Получение ID текущего пользователя.
        $currentUser = $this->getCurrentUser();
        $currentUserId = $currentUser->getId();

        // Поиск групп пользователей, членом которых
        // является текущий пользователь.
        $memberships = UserGroupMemberships::find("userId='$currentUserId'");

        // Перебор ролей пользователя.
        foreach ($memberships as $member) {
            // Получение системного имени группы пользователей,
            // которое является групповой ролью.
            $role = $member->getUserGroup()->getSystemName();

            // Если хотя бы одна из ролей пользователя дает ему право
            // на выполнение операции функция возвращает true.
            if ($acl->isAllowed($role, $controller, $action)) {
                return true;
            }
        }

        // Если роли пользователя не дают ему право на выполнение оперции,
        // функция возвращает false.
        return false;
    }


    /**
     * Добавление ресурсов DataTables.
     */
    protected function addDatatablesAssets()
    {
        $this->assets
             ->collection('headerCss')
             ->addCss($this->config->dataTables->dataTablesBootstrapCss)
             ->addCss($this->config->dataTables->fixedHeaderBootstrapCss);

        $this->assets
             ->collection('footerJs')
             ->addJs($this->config->dataTables->dataTablesJs)
             ->addJs($this->config->dataTables->dataTablesBootstrapJs)
             ->addJs($this->config->dataTables->fixedHeaderJs)
             //->addJs($this->config->dataTables->naturalJs)
             ->addJs($this->config->dataTables->datetimeMomentJs);
    }

}
