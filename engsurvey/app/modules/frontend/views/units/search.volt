<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li class="active">Единицы измерения</li>
</ol>

<div class="content-header">
    <div class="content-title">Единицы измерения</div>
</div>

{{ flashSession.output() }}

<div class="buttons-block">
    {{ link_to('units/new',
        '<i class="fa fa-plus"></i>&nbsp;Новая',
        'class': 'btn btn-default', 'role': 'button',
        'title': 'Создание новой единицы измерения') }}
</div>

<div style="max-width:640px">
    <table id="unitsTable" class="display table compact table-bordered  hover"
        cellspacing="0" cellpadding="0" border="0" width="100%">
        <thead>
            <tr>
                <th>Наименование</th>
                <th>Обозначение</th>
                <th>{# Действия #}</th>
            </tr>
        </thead>
        <tbody>
            {% for unit in units %}
                <tr>
                    <td>{{ unit.getName() }}</td>
                    <td>{{ unit.getSymbol() }}</td>
                    <td>
                        <a href="{{ url('units/edit?id=' ~ unit.getId()) }}"
                            class="btn btn-primary btn-xs"
                            title="Редактировать"><i class="fa fa-pencil"></i></a>
                        <a href="{{ url('units/delete?id=' ~ unit.getId()) }}"
                            class="btn btn-danger btn-xs esr-delete-confirm"
                            title="Удалить"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#unitsTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'columnDefs': [
                {
                    'targets': [2],
                    'className': 'td-nowrap td-align-center',
                    'width': "48px",
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
