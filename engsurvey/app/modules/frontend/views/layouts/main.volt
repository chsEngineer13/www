<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ get_title() }}
        <link rel="shortcut icon" href="{{ config.assets.favicon }}">
        {{ stylesheet_link(config.fontAwesome.fontAwesomeCss) }}
        {{ stylesheet_link(config.bootstrap.bootstrapCss) }}
        {{ stylesheet_link(config.bootstrapSelect.bootstrapSelectCss) }}
        {{ stylesheet_link(config.bootstrapDatepicker.bootstrapDatepickerCss) }}
        {{ assets.outputCss('headerCss') }}
        {{ stylesheet_link(config.assets.engsurveyCss) }}
        {{ stylesheet_link(config.assets.engsurveyBemCss) }}
        {{ javascript_include(config.jquery.jqueryJs) }}
        {{ javascript_include(config.bootstrap.bootstrapJs) }}
        {{ javascript_include(config.bootstrapSelect.bootstrapSelectJs) }}
        {{ javascript_include(config.bootstrapSelect.bootstrapSelectRuJs) }}
        {{ javascript_include(config.bootstrapFilestyle.bootstrapFilestyleJs) }}
        {{ javascript_include(config.bootstrapDatepicker.bootstrapDatepickerJs) }}
        {{ javascript_include(config.bootstrapDatepicker.bootstrapDatepickerRuJs) }}
        {{ javascript_include(config.moment.momentJs) }}
        {{ javascript_include(config.moment.momentRuJs) }}
        {{ assets.outputJs('headerJs') }}
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
                                <a class="navbar-brand" href="{{ url() }}">{{ config.app.appShortName }}</a>
                            </div>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-user"></i>&nbsp;&nbsp;{{ currentUserName }}&nbsp;&nbsp;<i class="fa fa-chevron-down"></i></a>
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
                                        <a href="{{ url('organizations')  }}" class="sidebar-nav__sublink">
                                            <span>Организации</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('branches')}}" class="sidebar-nav__sublink">
                                            <span>Филиалы</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__divider"></li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('employees')}}" class="sidebar-nav__sublink">
                                            <span>Сотрудники</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('employee-groups')}}" class="sidebar-nav__sublink">
                                            <span>Группы сотрудников</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__divider"></li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('units')}}" class="sidebar-nav__sublink">
                                            <span>Единицы измерения</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('work-types')}}" class="sidebar-nav__sublink">
                                            <span>Виды работ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-nav__item">
                                <a href="{{ url('construction-projects') }}" class="sidebar-nav__link">
                                    <span>Стройки</span>
                                </a>
                            </li>
                            <li class="sidebar-nav__item">
                                <a href="{{ url('contracts') }}" class="sidebar-nav__link">
                                    <span>Договоры</span>
                                </a>
                            </li>
                            <li class="sidebar-nav__parent-item">
                                <a href="#" class="sidebar-nav__parent-link">
                                    <span>Ресурсы</span><i class="fa fa-lg sidebar-nav__arrow"></i>
                                </a>
                                <ul class="sidebar-nav__sublist">
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('crews') }}" class="sidebar-nav__sublink">
                                            <span>Бригады</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('vehicles') }}" class="sidebar-nav__sublink_disabled">
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
                                        <a href="{{ url('crew-assignments') }}" class="sidebar-nav__sublink">
                                            <span>Распределение бригад</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-nav__subitem">
                                        <a href="{{ url('vehicle-allocation') }}" class="sidebar-nav__sublink_disabled">
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
                                        <a href="{{ url('performed-works') }}" class="sidebar-nav__sublink_disabled">
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
                                        <a href="{{ url('performance-dynamics') }}" class="sidebar-nav__sublink">
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
                                        <a href="{{ url('about') }}" class="sidebar-nav__sublink_disabled">
                                            <span>О программе</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    
                </aside>
                <main class="main">
                    {{ content() }}
                </main>
            </div>
            <div class="page-footer">
                <footer class="footer">
                    {{ config.app.copyright }}
                </footer>
            </div>
        </div>
        {{ assets.outputJs('footerJs') }}
        {{ javascript_include(config.assets.engsurveyJs) }}
    </body>
</html>
