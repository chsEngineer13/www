# CHANGELOG
## Версия 0.4.0
- Создан раздел "Сотрудники".
- Создан раздел "Группы сотрудников".
- Внесены изменения в раздел "Объекты": Стройки, Участки работ.

## Версия 0.3.0
- Создан раздел "Распределение бригад".
- Внесены изменения в справочник "Филиалы".

## Версия 0.2.0
- Создан справочник "Виды работ".
- Добавлены поле "Ссылка на отчет" в разделы "Стройки", "Участки работ", "Полевые бригады".
- Добавлены поле "Ссылка на объект на карте" в разделы "Стройки", "Участки работ".

## Версия 0.1.0
- Создан раздел "Полевые бригады".

## Версия 0.1.0-dev.6
- Создан раздел "Объекты": Стройки, Участки работ, Объекты изысканий.
- В базу данных импортированы объекты изысканий по второму этапу Ковыктинского ГКМ.
- Добавлен пользователь "guest".

## Версия 0.1.0-dev.5
- Создан справочник "Организации".
- В боковой панели навигации создана структура меню программы первого этапа разработки.
- Программа перенесена на CentOS.

## Версия 0.1.0-dev.4
- Внесены изменения в структуру каталогов.
- Добавлена боковая панель навигации.

## Версия 0.1.0-dev.3
- Изменена система нумерации версий.
- Внесены изменения в таблицу "users" базе данных "engsurvey".
- Добавлена аутентификация пользователей.

## Версия 0.1.0-dev.2
- Внесены изменения в структуру каталогов.
- PHP добавлен в директорию vendors приложения.
- Добавлена директория dev, для хранения файлов связанных с разработкой.
- Заведен словарь данных ИУС "Инженерные изыскания". Файл "engsurvey/dev/docs/data-dictionary.docx".
- В PostgreSQL заведена база данных "engsurvey". Резервная копия базы данных хранится в директории "engsurvey\schemas".
- Создана роль "engsurvey" суперпользователя PostgreSQL для входа в ИУС "Инженерные изыскания".
- В базе данных "engsurvey" созданы функции "generate_uuid", "get_user_id_by_username".
- В базе данных "engsurvey" созданы триггерные функции "set_initials", "set_updated_at".
- В базе данных "engsurvey" создана таблица "users".

## Версия 0.1.0-dev.1
- Внесены изменения в структуру каталогов.
- Подключены локально сторонние библиотеки: jQuery, Bootstrap, Font Awesome.
- Созданы пустые файлы ресурсов: "engsurvey/public/css/engsurvey.css", "engsurvey/public/js/engsurvey.js".
- Добавлен значок приложения (favicon.ico). Временно это значок Phalcon, в дальнейшем он будет заменен.
- Внесены измениения в файлы: "engsurvey/app/bootstrap_web.php", "engsurvey/app/config/config.php", 
  "engsurvey/app/modules/frontend/views/index.volt".

## Версия 0.1.0-dev
- С помощью инструмента разработчика Phalcon (Developer Tools) сгенерирован скелет проекта Engsurvey.
- Сгенерирован конфигурационный файл "web.config".
- Приложение запущено в Windows (IIS).
