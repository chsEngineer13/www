<?php
namespace Engsurvey\Frontend\Controllers;

class ErrorsController extends ControllerBase
{
    public function indexAction()
    {

    }


    public function error404Action()
    {
        $this->tag->prependTitle('Ошибка 404 - ');
    }

}
