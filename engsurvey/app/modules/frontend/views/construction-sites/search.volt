{# Навигационный маршрут #}
<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li>{{ link_to('construction-projects', 'Стройки') }}</li>
    <li class="active">Участки работ</li>
</ol>

{# Заголовок контента #}
<div class="content-header">
    <div class="content-title">Участки работ</div>
</div>

{# Объекты #}
<div class="objects-block">
    <div class="objects-block__item">
        <div class="objects-block__item-label">Стройка:</div>
        <div class="objects-block__item-name">
            {{ constructionProject.getName() ~ ' (Шифр ' ~ constructionProject.getCode() ~ ')' }}
        </div>
    </div>
</div>

{# Информационные сообщения #}
{{ flashSession.output() }}

{# Группы кнопок #}
<div class="buttons-block">
    {{ link_to('construction-sites/new?construction-project-id=' ~ constructionProject.getId(),
        '<i class="fa fa-plus"></i>&nbsp;Новый участок',
        'class': 'btn btn-default', 'role': 'button',
        'title': 'Создание нового участка работ') }}
</div>

{# Таблица "Участки работ" #}
<table id="constructionSitesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>ГИП</th>
            <th>Объекты изысканий</th>
            <th>Отчеты</th>
            <th>Комментарий</th>
            <th>{# Группа кнопок #}</th>
        </tr>
    </thead>
    <tbody>
        {% for site in constructionSites %}
            <tr>
                <td>{{ site.getSiteNumber() }}</td>
                <td>{{ site.getName() }}</td>
                <td nowrap>
                    {% if site.getChiefProjectEngineer() %}
                        {{ site.getChiefProjectEngineer().getShortName() }}
                    {% endif %}
                </td>
                <td>
                    <a href="{{ url('survey-facilities?construction-site-id=' ~ site.getId()) }}">
                        Объекты изысканий ({{ site.getSurveyFacilities().count() }})</a>
                </td>
                <td>
                    {% if site.getReportLink() %}
                        <a href="{{ site.getReportLink() }}" target="_blank">Отчет&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    {% endif %}
                </td>
                <td>{{ site.getComment() }}</td>
                <td>
                    {% if site.getMapLink() %}
                        <a href="{{ site.getMapLink() }}" class="btn btn-primary btn-xs" target="_blank"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    {% else %}
                        <a href="" class="btn btn-primary btn-xs disabled"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    {% endif %}
                    <a href="{{ url('construction-sites/edit?id=' ~ site.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil"></i></a>
                    <a href="{{ 'construction-sites/delete?id=' ~ site.getId() }}"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title='Удалить'><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#constructionSitesTable').DataTable({
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
                    'targets': [3],
                    'width': "150px",
                },
                {
                    'targets': [4],
                    'width': "50px",
                },
                {
                    'targets': [6],
                    'className': 'td-nowrap td-align-center',
                    'width': "72px",
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
