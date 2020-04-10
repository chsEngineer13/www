<?php
namespace Engsurvey\Frontend\Controllers;

class SysteminfoController extends ControllerBase
{
    
    public function indexAction()
    {
        
    }
    
    /**
     * Выводит информацию о текущей конфигурации PHP.
     */
    public function phpInfoAction()
    {
        ob_start();
            phpinfo();
        $info = ob_get_clean();
        
        $info = preg_replace("/^.*?\<body\>/is", "", $info);
        $info = preg_replace("/<\/body\>.*?$/is", "", $info);

        echo $info;
    }

}

