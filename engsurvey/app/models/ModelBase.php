<?php
namespace Engsurvey\Models;

use Phalcon\Mvc\Model as PhalconModel;

class ModelBase extends PhalconModel
{
    /**
     * Возвращает текущего пользователя, если он осуществил вход в систему.
     * Если текущий пользователя нет, функция возвращает null.
     *
     */
    protected function getCurrentUser(): ?Users
    {
        $session = $this->getDI()->getShared('session');
        
        if ($session->has('currentUser')) {
            $currentUser = $session->get('currentUser');
            return $currentUser;
        }

        return null;
    }
}
