{# Навигационный маршрут #}
<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li class="active">Стройки</li>
</ol>

{# Заголовок контента #}
<div class="content-header">
    <div class="content-title">Стройки</div>
</div>

{# Информационные сообщения #}
{{ flashSession.output() }}

{# Группы кнопок #}
<div class="buttons-block">
    {{ link_to('construction-projects/new', '<i class="fa fa-plus"></i>&nbsp;Новая стройка',
        'class': 'btn btn-default', 'role': 'button',
        'title': 'Создание новой стройки') }}
</div>

{# Таблица "Стройки" #}
<table id="constructionProjectsTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Шифр</th>
            <th>Наименование</th>
            <th>Вид строительства</th>
            <th>Заказчик (агент)</th>
            <th>Технический директор</th>
            <th>Участки работ</th>
            <th>Отчеты</th>
            <th>Комментарий</th>
            <th>{# Группа кнопок #}</th>
        </tr>
    </thead>
    <tbody>
        {% for project in constructionProjects %}
            <tr>
                <td>{{ project.getCode() }}</td>
                <td>{{ project.getName() }}</td>
                <td>
                    {% if project.getConstructionType() %}
                        {{ project.getConstructionType().getName() }}
                    {% endif %}
                </td>
                <td>
                    {% if project.getCustomer() %}
                        {{ project.getCustomer().getDisplayName() }}
                    {% endif %}
                </td>
                <td nowrap>
                    {% if project.getTechnicalDirector() %}
                        {{ project.getTechnicalDirector().getShortName() }}
                    {% endif %}
                </td>
                <td>
                    <a href="{{ url('construction-sites?construction-project-id=' ~ project.getId()) }}">
                        Участки работ&nbsp;({{ project.getConstructionSites().count() }})</a>
                </td>
                <td>
                    {% if project.getReportLink() %}
                        <a href="{{ project.getReportLink() }}" target="_blank">Отчет&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    {% endif %}
                </td>
                <td>{{ project.getComment() }}</td>
                <td>
                    {% if project.getMapLink() %}
                        <a href="{{ project.getMapLink() }}" class="btn btn-primary btn-xs" target="_blank"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    {% else %}
                        <a href="" class="btn btn-primary btn-xs disabled"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    {% endif %}
                    <a href="{{ url('construction-projects/edit?id=' ~ project.getId()) }}" class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="{{ 'construction-projects/delete?id=' ~ project.getId() }}" class="btn btn-danger btn-xs"
                        title='Удалить'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>


<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#constructionProjectsTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },
            
            'columnDefs': [
                {
                    'targets': [0],
                    'className': 'td-nowrap td-align-center',
                    'width': "40px",
                },
                {
                    'targets': [5],
                    'width': "110px",
                },   
                {
                    'targets': [6],
                    'width': "50px",
                },
                {
                    'targets': [8],
                    'className': 'td-nowrap td-align-center',
                    'width': "75px",
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
