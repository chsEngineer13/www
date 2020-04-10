<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li class="active">Распределение бригад</li>
</ol>

<div class="content-header">
    <div class="content-title">Распределение бригад по объектам</div>
</div>

{{ flashSession.output() }}

<div class="buttons-block">
    {{ link_to('crew-assignments/new',
        '<i class="fa fa-plus"></i>&nbsp;Новое назначение',
        'class': 'btn btn-default', 'role': 'button',
        'title': 'Создание нового назначения') }}
</div>

<table id="crewAssignmentsTable" class="display table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Филиал</th>
            <th>Бригада</th>
            <th>Стройка</th>
            <th>Участок работ</th>
            <th>Начало работ</th>
            <th>Завершение работ</th>
            <th>Комментарий</th>
            <th>{# Действия #}</th>
        </tr>
    </thead>
    <tbody>
        {% for assignment in crewAssignments %}
            <tr>
                <td>{{ assignment.getBranch().getDisplayName() }}</td>
                {% if assignment.getCrew().getHeadShortName() %}
                    <td>{{ assignment.getCrew().getCrewName() ~ 
                        ' (' ~ assignment.getCrew().getHeadShortName() ~ ')' }}</td>
                {% else %}
                    <td>{{ assignment.getCrew().getCrewName() }}</td>
                {% endif %}
                <td>{{ assignment.getConstructionProject().getCode() }}</td>
                <td>{{ assignment.getConstructionSite().getSiteNumber() }}</td>
                <td>{{ assignment.getFormattedStartDate('d.m.Y') }}</td>
                <td>{{ assignment.getFormattedEndDate('d.m.Y') }}</td>
                <td>{{ assignment.getComment() }}</td>
                <td>
                    <a href="{{ url('crew-assignments/edit?id=' ~ assignment.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="{{ url('crew-assignments/delete?id=' ~ assignment.getId()) }}"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>


<script type="text/javascript">
    
    $(document).ready(function() {
        $.fn.dataTable.moment( 'DD.MM.YYYY' );
        var table = $('#crewAssignmentsTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'columnDefs': [
                {
                    'targets': [7],
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
