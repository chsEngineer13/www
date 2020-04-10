<?php
namespace Engsurvey\Frontend\Controllers;

use Engsurvey\Models\Users;
use Engsurvey\Frontend\Forms\LoginForm;
use Engsurvey\Frontend\Forms\ChangePasswordForm;
use Phalcon\Mvc\Controller as PhalconController;

class AuthController extends PhalconController
{
    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function initialize()
    {
        $appShortName = $this->config->app->appShortName;
        $this->tag->setTitle($appShortName);
    }


    /**
     * Показывает форму входа в систему.
     */
    public function loginAction()
    {
        // Префикс заголовка страницы.
        $this->tag->prependTitle('Вход в систему - ');

        // Форма используемая в представлении.
        if ($this->request->isPost()) {
            $form = new LoginForm();
            $form->isValid($_POST);
            $this->view->form = $form;
        } else {
            $this->view->form = new LoginForm();
        }

        // Шаблон используемый для создания представления.
        $this->view->pick('auth/login');
    }


    /**
     * Проверяет имя пользователя (логин) и пароль, полученные из формы,
     * на корректность. Если данные корректны, сохраняет пользователя их в сессии.
     */
    public function verificationAction()
    {
        if ($this->request->isPost()) {

            $form = new LoginForm();
            if (!$form->isValid($_POST)) {
                // При ошибках ввода логина или пароля повторно вызывается
                // форма входа в систему (аутентификация).
                return $this->dispatcher->forward(
                    [
                        'action' => 'login'
                    ]
                );
            }

            // Получение данных от пользователя.
            $login = $this->request->getPost('login');
            $password = $this->request->getPost('password');

            // Поиск пользователя в базе данных.
            $user = Users::findFirst(
                [
                    "login = :login:",
                    'bind' => ['login' => $login]
                ]
            );

            if ($user) {
                // Проверка - не отключена ли учетная запись пользователя.
                if ($user->isDisabled()) {
                    $this->flashSession->error('Ваша учетная запись отключена.');
                    return $this->dispatcher->forward(
                        [
                            'action' => 'login'
                        ]
                    );
                }
                
                // Проверка - не заблокирована ли учетная запись пользователя.
                if ($user->isLocked()) {
                    $this->flashSession->error('Ваша учетная запись заблокирована.');
                    return $this->dispatcher->forward(
                        [
                            'action' => 'login'
                        ]
                    );
                }

                // Проверка пароля пользователя.
                if (password_verify($password , $user->getPasswordHash())) {

                    // Формирование имени пользователя используемого в интерфейсе системы.
                    $displayName = $user->getLogin();
                    if (!empty($user->getEmployee())) {
                        $displayName = $user->getEmployee()->getShortName();
                    }

                    // Сохранение пользователя в сессии.
                    $this->session->set('currentUser', $user);
                    
                    // HTTP редирект на главную страницу приложения.
                    return $this->response->redirect();
                }
                
            } else {
                // Для защиты от временных атак. Независимо от того, существует
                // пользователь или нет, сценарий будет выполнен.
                password_hash(random_bytes(10), PASSWORD_DEFAULT);
                
            }

            // Неудачная проверка.
            $this->flashSession->error('Неверный логин или пароль.');
            return $this->dispatcher->forward(
                [
                    'action' => 'login'
                ]
            );

        }
    }


    /**
     * Показывает форму для смена пароля.
     */
    public function changePasswordAction()
    {
        // Префикс заголовка страницы.
        $this->tag->prependTitle('Смена пароля - ');

        // Форма используемая в представлении.
        if ($this->request->isPost()) {
            $form = new ChangePasswordForm();
            $form->isValid($_POST);
            $this->view->form = $form;
        } else {
            $this->view->form = new ChangePasswordForm();
        }

        // Шаблон используемый для создания представления.
        $this->view->pick('auth/change-password');
    }


    /**
     * Проверяет данные для смены пароля, полученные из формы, на корректность.
     * Если данные корректны, сохраняет в базе данных новый пароль пользователя.
     */
    public function verificationNewPasswordAction()
    {
        if ($this->request->isPost()) {

            $form = new ChangePasswordForm();
            if (!$form->isValid($_POST)) {
                // При ошибках ввода повторно вызывается форма смены пароля.
                return $this->dispatcher->forward(
                    [
                        'action' => 'changePassword'
                    ]
                );
            }

            // Получение данных от пользователя.
            $login = $this->request->getPost('login');
            $oldPassword = $this->request->getPost('oldPassword');
            $newPassword = $this->request->getPost('newPassword');
            $confirmationPassword = $this->request->getPost('confirmationPassword');

            // Поиск пользователя в базе данных.
            $user = Users::findFirst(
                [
                    "login = :login:",
                    'bind' => ['login' => $login]
                ]
            );

            // Пользователь не найден.
            if ($user === false) {

                // Для защиты от временных атак. Независимо от того,
                // что пользователь не найден, выполняется вычисление
                // хэша случайного числа.
                password_hash(random_bytes(10), PASSWORD_DEFAULT);

                // Повторный вызов форма смены пароля с сообщение об ошибке.
                $this->flashSession->error('Неверный логин или пароль.');

                return $this->dispatcher->forward(
                    [
                        'action' => 'changePassword'
                    ]
                );
            }

            // Проверка пароля пользователя.
            if (password_verify($oldPassword , $user->getPasswordHash()) === false) {
                // Если пароль неверный, выполняется повторный вызов формы
                // смены пароля с сообщением об ошибке.
                $this->flashSession->error('Неверный логин или пароль.');

                return $this->dispatcher->forward(
                    [
                        'action' => 'changePassword'
                    ]
                );
            }

            // Сравнение нового пароля и его подтверждения.
            if (strcmp($newPassword, $confirmationPassword) !== 0) {

                // Если новый пароль и его подтверждение не совпадают,
                // выполняется повторный вызов формы смены пароля
                // с сообщением об ошибке.
                $this->flashSession->error('Новый пароль и его подтверждение не совпадают.');

                return $this->dispatcher->forward(
                    [
                        'action' => 'changePassword'
                    ]
                );
            }

            // Смена пароля пользователя.
            $user->setPasswordHash(password_hash($newPassword, PASSWORD_DEFAULT));
            $user->setMustChangePassword(false);

            if ($user->update() === false) {

                // Если не удалось изменить пароль, выводится сообщение об ошибке.
                $this->flashSession->error('Не удалось изменить пароль.');

                return $this->dispatcher->forward(
                    [
                        'action' => 'changePassword'
                    ]
                );
            }

            // При удачной смене пароля, завершается старый сеанс
            // и предлагается войти с новым паролем.
            $this->flashSession->success('Пароль успешно изменен. Войдите в систему с новым паролем.');
            $this->session->remove('currentUser');

            // HTTP редирект на главную страницу приложения.
            return $this->response->redirect();
        }
    }


    /**
     * Завершает сеанс пользователя.
     */
    public function logoutAction()
    {
        $this->session->remove('currentUser');

        // HTTP редирект на главную страницу приложения.
        return $this->response->redirect();
    }

}
