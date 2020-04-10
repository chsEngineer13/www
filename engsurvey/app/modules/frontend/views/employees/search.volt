<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li class="active">Сотрудники</li>
</ol>

<div class="content-header">
    <div class="content-title">Сотрудники</div>
</div>

{{ flashSession.output() }}

<div class="buttons-block">
    {{ link_to('employees/new',
        '<i class="fa fa-plus"></i>&nbsp;Новый сотрудник',
        'class': 'btn btn-default', 'role': 'button',
        'title': 'Создание нового сотрудника') }}
</div>

<table id="employeesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Ф. И. О.</th>
            <th>Подразделение</th>
            <th>Филиал</th>
            <th>Местонахождение</th>
            <th>Телефоны</th>
            <th>Эл. почта</th>
            <th><!-- Действия --></th>
        </tr>
    </thead>
    <tbody>
        {% for employee in employees %}
            <tr>
                <td>
                    <!-- Ф. И. О. -->
                    <a href="{{ url('employees/edit?id=' ~ employee.getId()) }}">
                        {{ employee.getFullName() }}
                    </a>
                    <!-- Должность -->
                    <br><em>{{ employee.getJobTitle() }}</em>
                </td>
                <td>
                    <!-- Подразделение -->
                    {{ employee.getDepartment() }}
                </td>
                <td>
                    <!-- Филиал -->
                    {{ employee.getBranch().getDisplayName() }}
                </td>
                <td>
                    <!-- Местонахождение -->
                    {{ employee.getLocation() }}
                </td>
                <td>
                    <!-- Телефоны -->
                    {% if employee.getPhoneWork() is not empty %}
                        <nobr>{{ '<em>раб.&nbsp;</em>' ~ employee.getPhoneWork() }}</nobr><br>
                    {% endif %}

                    {% if employee.getPhoneGas() is not empty %}
                        <nobr>{{ '<em>газ.&nbsp;</em>' ~ employee.getPhoneGas() }}</nobr><br>
                    {% endif %}
                    
                    {% if employee.getPhoneMobile() is not empty %}
                        <nobr>{{ '<em>моб.&nbsp;</em>' ~ employee.getPhoneMobile() }}</nobr>
                    {% endif %}
                </td>
                <td>
                    <!-- Эл. почта -->
                    {{ employee.getEmail() }}
                </td>
                <td>
                    <!-- Действия -->
                    <a href="{{ url('employees/edit?id=' ~ employee.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="{{ url('employees/delete?id=' ~ employee.getId()) }}"
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
        var table = $('#employeesTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'columnDefs': [
                {
                    'targets': [6],
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