{# Навигационный маршрут #}
<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li>{{ link_to('construction-projects', 'Стройки') }}</li>
    <li>{{ link_to('construction-sites?construction-project-id=' ~ constructionProject.getId(), 'Участки работ') }}</li>
    <li class="active">Объекты изысканий</li>
</ol>

{# Заголовок контента #}
<div class="content-header">
    <div class="content-title">Объекты изысканий</div>
</div>

{# Объекты #}
<div class="objects-block">
    <div class="objects-block__item">
        <div class="objects-block__item-label">Стройка:</div>
        <div class="objects-block__item-name">
            {{ constructionProject.getName() ~ ' (Шифр ' ~ constructionProject.getCode() ~ ')' }}
        </div>
    </div>
    <div class="objects-block__item">
        <div class="objects-block__item-label">Участок работ:</div>
        <div class="objects-block__item-name">
            {{ constructionSite.getName() }}
        </div>
    </div>
</div>

{# Информационные сообщения #}
{{ flashSession.output() }}

{# Группы кнопок #}
<div class="buttons-block">
    {{ link_to('survey-facilities/new?construction-site-id=' ~ constructionSite.getId(),
        '<i class="fa fa-plus"></i>&nbsp;Новый объект',
        'class': 'btn btn-default', 'role': 'button',
        'title': 'Создание нового объекта изысканий') }}

    <a href="#" id="importObjectsXlsxButton" class="btn btn-default"
        title="Импорт объектов изысканий из файла MS Excel (XLSX)">
        <i class="fa fa-file-excel-o"></i>&nbsp;Импорт&nbsp;</a>
</div>

{# Таблица "Объекты изысканий" #}
<table id="surveyFacilitiesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>Обозначение</th>
            <th>Номер</th>
            <th>Этап работ</th>
            <th>Комментарий</th>
            <th>{# Группа кнопок #}</th>
        </tr>
    </thead>
    <tbody>
        {% for facility in surveyFacilities %}
            <tr>
                <td>{{ facility.getSequenceNumber() }}</td>
                <td>{{ facility.getFacilityName() }}</td>
                <td>{{ facility.getFacilityDesignation() }}</td>
                <td>{{ facility.getFacilityNumber() }}</td>
                <td>{{ facility.getStageOfWorks() }}</td>
                <td>{{ facility.getComment() }}</td>
                <td>
                    <a href="{{ url('survey-facilities/edit?id=' ~ facility.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil"></i></a>
                    <a href="{{ 'survey-facilities/delete?id=' ~ facility.getId() }}"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title='Удалить'><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>

{# Подключение модального окна "Импорт объектов изысканий (XLSX)". #}
{{ partial("survey-facilities/import-objects-xlsx-modal") }}

{# Скрипты #}
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#surveyFacilitiesTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'columnDefs': [
                {
                    'targets': [0],
                    'className': 'td-nowrap td-align-center',
                    'width': "15px",
                },
                {
                    'targets': [6],
                    'className': 'td-nowrap td-align-center',
                    'width': "48px",
                    'sortable': false,
                }
            ],

            'order': [[ 0, 'asc' ]],

            "stateSave": true,
        });

        new $.fn.dataTable.FixedHeader( table, {
            'header': true,
            'headerOffset': 50
        });
    });
</script>
