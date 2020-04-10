{# Навигационный маршрут #}
<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li class="active">Филиалы</li>
</ol>

{# Заголовок контента #}
<div class="content-header">
    <div class="content-title">Филиалы</div>
</div>

{# Информационные сообщения #}
{{ flashSession.output() }}

{# Группы кнопок #}
<div class="buttons-block">
    <a href="{{ url('branches/new') }}" class="btn btn-default" title='Создание нового филиала'>
        <i class="fa fa-plus"></i>&nbsp;Новый филиал</a>
</div>

{# Таблица "Филиалы" #}
<table id="branchesTable" class="table compact table-bordered hover"
    cellspacing="0" cellpadding="0" border="0" width="550px">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>Код</th>
            <th>{# Группа кнопок #}</th>
        </tr>
    </thead>
    <tbody>
        {% for branch in branches %}
            <tr>
                <td align="center">{{ branch.getSequenceNumber() }}</td>
                <td>{{ branch.getDisplayName() }}</td>
                <td align="center">{{ branch.getCode() }}</td>
                <td>
                    <a href="{{ url('branches/edit?id=' ~ branch.getId()) }}" class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="{{ 'branches/delete?id=' ~ branch.getId() }}" class="btn btn-danger btn-xs esr-delete-confirm"
                        title='Удалить'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $.fn.dataTable.moment( 'DD.MM.YYYY' );
        var table = $('#branchesTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'paging': false,
            'searching': false,
            'info': false,

            'columnDefs': [
                {
                    'targets': [0],
                    'width': '20px',
                },

                {
                    'targets': [1],
                },

                {
                    'targets': [2],
                    'width': '50px',
                },

                {
                    'targets': [3],
                    'className': 'td-nowrap td-align-center',
                    'width': '48px',
                    'sortable': false,
                }
            ],

            'order': [[ 0, 'asc' ]],

            'stateSave': true,

        });

        new $.fn.dataTable.FixedHeader( table, {
            'header': true,
            'headerOffset': 50
        });

    });
</script>
