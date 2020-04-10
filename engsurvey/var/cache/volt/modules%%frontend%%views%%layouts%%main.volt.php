<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= $this->tag->getTitle() ?>
        <link rel="shortcut icon" href="<?= $this->config->assets->favicon ?>">
        <?= $this->tag->stylesheetLink($this->config->fontAwesome->fontAwesomeCss) ?>
        <?= $this->tag->stylesheetLink($this->config->bootstrap->bootstrapCss) ?>
        <?= $this->tag->stylesheetLink($this->config->bootstrapSelect->bootstrapSelectCss) ?>
        <?= $this->tag->stylesheetLink($this->config->bootstrapDatepicker->bootstrapDatepickerCss) ?>
        <?= $this->assets->outputCss('headerCss') ?>
        <?= $this->tag->stylesheetLink($this->config->assets->engsurveyCss) ?>
        <?= $this->tag->stylesheetLink($this->config->assets->engsurveyBemCss) ?>
        <?= $this->tag->javascriptInclude($this->config->jquery->jqueryJs) ?>
        <?= $this->tag->javascriptInclude($this->config->bootstrap->bootstrapJs) ?>
        <?= $this->tag->javascriptInclude($this->config->bootstrapSelect->bootstrapSelectJs) ?>
        <?= $this->tag->javascriptInclude($this->config->bootstrapSelect->bootstrapSelectRuJs) ?>
        <?= $this->tag->javascriptInclude($this->config->bootstrapFilestyle->bootstrapFilestyleJs) ?>
        <?= $this->tag->javascriptInclude($this->config->bootstrapDatepicker->bootstrapDatepickerJs) ?>
        <?= $this->tag->javascriptInclude($this->config->bootstrapDatepicker->bootstrapDatepickerRuJs) ?>
        <?= $this->tag->javascriptInclude($this->config->moment->momentJs) ?>
        <?= $this->tag->javascriptInclude($this->config->moment->momentRuJs) ?>
        <?= $this->assets->outputJs('headerJs') ?>
    </head>
    <body>
        <div class="page">
            <div class="page-header">
                <header class="header">
                    <nav class="navbar navbar-gpp navbar-fixed-top">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button type="button" class="btn navbar-btn btn-transparent left-sidebar-toggle pull-left">
                                    <i class="fa fa-bars"></i>
                                </button>
                                <a class="navbar-brand" href="<?= $this->url->get() ?>"><?= $this->config->app->appShortName ?></a>
                            </div>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-user"></i>&nbsp;&nbsp;<?= $currentUserName ?>&nbsp;&nbsp;<i class="fa fa-chevron-down"></i></a>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="change-password">Смена пароля</a></li>
                                        <li class="divider"></li>
                                        <li><a href="logout"><i class="fa fa-power-off"></i>&nbsp;Выход</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </header>
            </div>
            <div class="page-main page-main">
                <aside class="left-sidebar">
                    <nav class="sidebar-nav">
                        <ul class="sidebar-nav__list">
                            <li class="sidebar-nav__parent-item">
                                <a class="sidebar-nav__parent-link" href="#">
                                    <span>Справочники</span><i class="fa fa-lg sidebar-nav__arrow"></i>
                                </a>
                                <ul class="sidebar-nav__sublist">
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('organizations') ?>" class="sidebar-nav__sublink">
                                            <span>Организации</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('branches') ?>" class="sidebar-nav__sublink">
                                            <span>Филиалы</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__divider"></li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('employees') ?>" class="sidebar-nav__sublink">
                                            <span>Сотрудники</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('employee-groups') ?>" class="sidebar-nav__sublink">
                                            <span>Группы сотрудников</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__divider"></li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('units') ?>" class="sidebar-nav__sublink">
                                            <span>Единицы измерения</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('work-types') ?>" class="sidebar-nav__sublink">
                                            <span>Виды работ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-nav__item">
                                <a href="<?= $this->url->get('construction-projects') ?>" class="sidebar-nav__link">
                                    <span>Стройки</span>
                                </a>
                            </li>
                            <li class="sidebar-nav__item">
                                <a href="<?= $this->url->get('contracts') ?>" class="sidebar-nav__link">
                                    <span>Договоры</span>
                                </a>
                            </li>
                            <li class="sidebar-nav__parent-item">
                                <a href="#" class="sidebar-nav__parent-link">
                                    <span>Ресурсы</span><i class="fa fa-lg sidebar-nav__arrow"></i>
                                </a>
                                <ul class="sidebar-nav__sublist">
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('crews') ?>" class="sidebar-nav__sublink">
                                            <span>Бригады</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('vehicles') ?>" class="sidebar-nav__sublink_disabled">
                                            <span>Техника</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-nav__parent-item">
                                <a href="#" class="sidebar-nav__parent-link">
                                    <span>Планирование</span><i class="fa fa-lg sidebar-nav__arrow"></i>
                                </a>
                                <ul class="sidebar-nav__sublist">
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('crew-assignments') ?>" class="sidebar-nav__sublink">
                                            <span>Распределение бригад</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('vehicle-allocation') ?>" class="sidebar-nav__sublink_disabled">
                                            <span>Распределение техники</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="sidebar-nav__parent-item">
                                <a href="" class="sidebar-nav__parent-link">
                                    <span>Мониторинг</span><i class="fa fa-lg sidebar-nav__arrow"></i>
                                </a>
                                <ul class="sidebar-nav__sublist">
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('performed-works') ?>" class="sidebar-nav__sublink_disabled">
                                            <span>Выполнение работ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="sidebar-nav__parent-item">
                                <a href="" class="sidebar-nav__parent-link">
                                    <span>Отчеты</span><i class="fa fa-lg sidebar-nav__arrow"></i>
                                </a>
                                <ul class="sidebar-nav__sublist">
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('performance-dynamics') ?>" class="sidebar-nav__sublink">
                                            <span>Динамика выполнения работ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="sidebar-nav__parent-item">
                                <a href="" class="sidebar-nav__parent-link">
                                    <span>Помощь</span><i class="fa fa-lg sidebar-nav__arrow"></i>
                                </a>
                                <ul class="sidebar-nav__sublist">
                                    <li class="sidebar-nav__subitem">
                                        <a href="<?= $this->url->get('about') ?>" class="sidebar-nav__sublink_disabled">
                                            <span>О программе</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    
                </aside>
                <main class="main">
                    <?= $this->getContent() ?>
                </main>
            </div>
            <div class="page-footer">
                <footer class="footer">
                    <?= $this->config->app->copyright ?>
                </footer>
            </div>
        </div>
        <?= $this->assets->outputJs('footerJs') ?>
        <?= $this->tag->javascriptInclude($this->config->assets->engsurveyJs) ?>
    </body>
</html>
