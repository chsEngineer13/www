<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
define('VERSION_NUMBER', '0.5.1');
define('VERSION_DATE', '20180419T1739');

return new \Phalcon\Config(
    [
        'app' => [
            'appFullName'   => 'Информационно-управляющая система «Инженерные изыскания»',
            'appShortName'  => 'ИУС «Инженерные изыскания»',
            'copyright'     => '© ООО «Газпром проектирование», 2018',
            'company'       => 'ООО «Газпром проектирование»',
            'appDir'        => APP_PATH . '/',
            'libraryDir'    => APP_PATH . '/library/',
            'modelsDir'     => APP_PATH . '/models/',
            'modulesDir'    => APP_PATH . '/modules/',
            'cacheDir'      => BASE_PATH . '/var/cache/',
            'baseUri'       => '/engsurvey/',
        ],

        'version' => [
            'number' => VERSION_NUMBER,
            'date'   => VERSION_DATE,
        ],

        'database' => [
            'adapter'  => 'Postgresql',
            'host'     => 'localhost',
            'port'     => '5432',
            'username' => 'engsurvey',
            'password' => '111@@@aaa',
            'dbname'   => 'engsurvey',
            'charset'  => 'utf8',
        ],
        
        
        'storage' => [
            'storageDir' => BASE_PATH . DS . 'storage' . DS,
            'contractsDir' => BASE_PATH . DS . 'storage' . DS . 'contracts' . DS,
        ],
        

        'assets' => [
            'engsurveyCss'    => 'css/engsurvey.css?v=' . VERSION_NUMBER,
            'engsurveyBemCss' => 'css/engsurvey-bem.css?v=' . VERSION_NUMBER,
            'engsurveyJs'     => 'js/engsurvey.js?v=' . VERSION_NUMBER,
            'favicon'         => 'favicon.ico?v=' . VERSION_NUMBER,
        ],

        'jquery' => [
            'jqueryJs' => 'vendors/jquery/2.2.4/jquery.min.js?v=2.2.4',
        ],

        'fontAwesome' => [
            'fontAwesomeCss' => 'vendors/font-awesome/4.7.0/css/font-awesome.min.css?v=4.7.0',
        ],

        'bootstrap' => [
            'bootstrapCss' => 'vendors/bootstrap/3.3.7/css/bootstrap.min.css?v=3.3.7',
            'bootstrapJs'  => 'vendors/bootstrap/3.3.7/js/bootstrap.min.js?v=3.3.7',
        ],

        'bootstrapDatepicker' => [
            'bootstrapDatepickerCss'  => 'vendors/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.min.css?v=1.7.1',
            'bootstrapDatepickerJs'   => 'vendors/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js?v=1.7.1',
            'bootstrapDatepickerRuJs' => 'vendors/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.ru.min.js?v=1.7.1',
        ],

        'bootstrapSelect' => [
            'bootstrapSelectCss'  => 'vendors/bootstrap-select/1.12.4/css/bootstrap-select.min.css?v=1.12.4',
            'bootstrapSelectJs'   => 'vendors/bootstrap-select/1.12.4/js/bootstrap-select.min.js?v=1.12.4',
            'bootstrapSelectRuJs' => 'vendors/bootstrap-select/1.12.4/js/i18n/defaults-ru_RU.min.js?v=1.12.4',
        ],
        
        'bootstrapFilestyle' => [
            'bootstrapFilestyleJs'   => 'vendors/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js?v=2.1.0',
        ],
        
        'dataTables' => [
            'dataTablesJs'            => 'vendors/datatables/versions/1.10.15/js/jquery.dataTables.min.js?v=1.10.15',
            'dataTablesBootstrapJs'   => 'vendors/datatables/versions/1.10.15/js/dataTables.bootstrap.min.js?v=1.10.15',
            'dataTablesBootstrapCss'  => 'vendors/datatables/versions/1.10.15/css/dataTables.bootstrap.min.css?v=1.10.15',
            'dataTablesRu'            => 'vendors/datatables/plugins/1.10.15/i18n/Russian.lang.txt?v=1.10.15',
            'datetimeMomentJs'        => 'vendors/datatables/plugins/1.10.15/sorting/datetime-moment.js?v=1.10.15',
            'naturalJs'               => 'vendors/datatables/plugins/1.10.15/sorting/natural.js?v=1.10.15',
            'fixedHeaderJs'           => 'vendors/datatables/extensions/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js?v=3.1.2',
            'fixedHeaderBootstrapCss' => 'vendors/datatables/extensions/fixedheader/3.1.2/css/fixedHeader.bootstrap.min.css?v=3.1.2',
        ],

        'phpExcel' => [
            'phpExcelDir' => BASE_PATH . '/vendors/PHPExcel/1.8.1/',
        ],
        
        'moment' => [
            'momentJs' => 'vendors/moment/2.19.2/moment.min.js?v=2.19.2',
            'momentRuJs'  => 'vendors/moment/2.19.2/locale/moment.ru.js?v=2.19.2',
        ],
        

        /**
         * if true, then we print a new line at the end of each CLI execution
         *
         * If we dont print a new line,
         * then the next command prompt will be placed directly on the left of the output
         * and it is less readable.
         *
         * You can disable this behaviour if the output of your application needs to don't have a new line at end
         */
        'printNewLine' => true,
    ]
);
