<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;

class ChangePasswordForm extends Form
{
    /**
     * Инициализация формы.
     *
     * @param $entity
     * @param array $options
     */
    public function initialize($entity = null, $options = null)
    {
        // Логин
        $login = new Text(
            'login',
            [
                'class' => 'form-control',
                'placeholder' => 'Логин'
            ]
        );
        $login->setLabel('Логин');
        $login->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Введите логин.'
                    ]
                )
            ]
        );
        $this->add($login);


        // Старый пароль.
        $oldPassword = new Password(
            'oldPassword',
            [
                'class' => 'form-control',
                'placeholder' => 'Старый пароль'
            ]
        );
        $oldPassword->setLabel('Старый пароль');
        $oldPassword->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Введите старый пароль.'
                    ]
                )
            ]
        );
        $this->add($oldPassword);


        // Новый пароль.
        $newPassword = new Password(
            'newPassword',
            [
                'class' => 'form-control',
                'placeholder' => 'Новый пароль'
            ]
        );
        $newPassword->setLabel('Новый пароль');
        $newPassword->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Введите новый пароль.'
                    ]
                )
            ]
        );
        $this->add($newPassword);


        // Подтверждение пароля.
        $confirmationPassword = new Password(
            'confirmationPassword',
            [
                'class' => 'form-control',
                'placeholder' => 'Подтверждение нового пароля'
            ]
        );
        $confirmationPassword->setLabel('Подтверждение нового пароля');
        $confirmationPassword->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Введите повторно новый пароль.'
                    ]
                )
            ]
        );
        $this->add($confirmationPassword);

    }


    /**
     * Отрисовывает элементы формы по их наименованию.
     *
     * @param string $name Наименование элемента.
     */
    public function renderDecorated($name)
    {
        $element = $this->get($name);

        // Сбор всех сгенерированных сообщения для текущего элемента.
        $messages = $this->getMessagesFor($element->getName());

        if (count($messages)) {
            echo '<div class="form-group has-error">';
        } else {
            echo '<div class="form-group">';
        }

        echo '<label for="' . $element->getName() . '" class="control-label sr-only">' . $element->getLabel() . '</label>';

        echo $element;

        // Вывод сообщений.
        if (count($messages)) {
            echo '<span class="help-block">';

            foreach ($messages as $message) {
                echo $message . '<br/>';
            }

            rtrim($message, '<br/>');

            echo '</span>';
        }

        echo '</div>';
    }

}
