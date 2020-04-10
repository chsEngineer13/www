<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends Form
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

        // Пароль.
        $password = new Password(
            'password',
            [
                'class' => 'form-control',
                'placeholder' => 'Пароль'
            ]
        );
        $password->setLabel('Пароль');
        $password->addValidators(
            [
                new PresenceOf(
                    [
                        'message' => 'Введите пароль.'
                    ]
                )
            ]
        );
        $this->add($password);
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
