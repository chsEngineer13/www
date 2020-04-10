<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li>{{ link_to('employee-groups', 'Группы сотрудников') }}</li>
    <li class="active">{{ employeeGroup.getName() }}</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">{{ employeeGroup.getName() }}</div>
</div>

<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Группы кнопок -->
<div class="buttons-block">
    <a href="#" id="addEmployee" class="btn btn-default" 
        title='Добавление сотрудника в группу « {{ employeeGroup.getName() }}»'>
        <i class="fa fa-plus"></i>&nbsp;Добавить</a>
</div>

<!-- Таблица "Члены группы сотрудников". -->
<div>
    <table id="employeeGroupMembershipsTable" class="table compact table-bordered  hover"
        cellspacing="0" cellpadding="0" border="0" width="100%">
        <thead>
            <tr>
                <th>Ф. И. О.</th>
                <th>Подразделение</th>
                <th>Филиал</th>
                <th>Телефоны</th>
                <th>Эл. почта</th>
                <th><!-- Действия --></th>
            </tr>
        </thead>
        <tbody>
            {% for member in employeeGroupMemberships %}
                <tr>
                    <td>
                        <!-- Ф. И. О. -->
                        {{ member.getEmployee().getFullName() }}
                        <!-- Должность -->
                        <br><em>{{ member.getEmployee().getJobTitle() }}</em>
                    </td>
                    <td>
                        <!-- Подразделение -->
                        {{ member.getEmployee().getDepartment() }}
                    </td>
                    <td>
                        <!-- Филиал -->
                        {{ member.getEmployee().getBranch().getDisplayName() }}
                    </td>
                    <td>
                        <!-- Телефоны -->
                        {% if member.getEmployee().getPhoneWork() is not empty %}
                            <nobr>{{ '<em>раб.&nbsp;</em>' ~ member.getEmployee().getPhoneWork() }}</nobr><br>
                        {% endif %}

                        {% if member.getEmployee().getPhoneGas() is not empty %}
                            <nobr>{{ '<em>газ.&nbsp;</em>' ~ member.getEmployee().getPhoneGas() }}</nobr><br>
                        {% endif %}
                        
                        {% if member.getEmployee().getPhoneMobile() is not empty %}
                            <nobr>{{ '<em>моб.&nbsp;</em>' ~ member.getEmployee().getPhoneMobile() }}</nobr>
                        {% endif %}
                    </td>
                    <td>
                        <!-- Эл. почта -->
                        {{ member.getEmployee().getEmail() }}
                    </td>
                    <td>
                        <!-- Действия -->
                        <a href="{{ url('employee-group-memberships/delete?id=' ~ member.getId()) }}"
                            class="btn btn-danger btn-xs esr-delete-confirm"
                            title="Удалить"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
</div>

<!-- Модальное диалоговое окно добавления нового члена в группу сотрудников. -->
<div class="modal" id="addEmployeeModal" tabindex="-1" role="dialog" data-backdrop="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Заголовок модального окна. -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Добавление сотрудника в группу «{{ employeeGroup.getName() }}»</h4>
            </div>
            <!-- Тело модального окна. -->
            <div class="modal-body">
                <form role="form">
                    {{ hidden_field("employeeGroupId", "value": employeeGroup.getId()) }}
                    <div id= "employeeFormGroup" class="form-group">
                        {{ select_static(
                            'employeeId', 
                            employees, 
                            'useEmpty': true, 
                            'emptyText': '. . .', 
                            'emptyValue': '', 
                            'class': 'form-control selectpicker', 
                            'data-live-search': 'true',
                            'data-width': '100%'
                        ) }}
                        <span id="employeeHelp" class="help-block"></span>
                    </div>
                </form>
            </div>
            <!-- Подвал модального окна. -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" id="addEmployeeButton" class="btn btn-primary">Добавить</button>
            </div>
        </div>
    </div>
</div>


<!-- Скрипт "Добавление сотрудника." -->
<script>
    $('#addEmployee').on('click', function(e){
        $('#addEmployeeModal').modal('show');
        
        $('#addEmployeeButton').on('click', function(e){
            var employeeGroupId = $('#employeeGroupId').val();
            var employeeId = $('.selectpicker option:selected').val();
            
            var url = '{{ url('employee-group-memberships/add') }}' +
                '?employee_group_id=' + employeeGroupId + '&employee_id=' + employeeId;

            if(employeeId) {
                $("#addEmployeeModal").modal("hide");
                document.location.replace(url);
            } else {
                $('#employeeFormGroup').addClass('has-error');
                $('#employeeHelp').text('Необходимо выбрать сотрудника.');
            };

            return false;

        });
    });
    
    // Очистка полей модального окна "addEmployeeModal" при его закрытии.
    $('#addEmployeeModal').on('hidden.bs.modal', function (e) {
        $('#employeeId').val('');
        $('#employeeId').selectpicker('render');
        
        $('#employeeFormGroup').removeClass('has-error');
        $('#employeeHelp').text('');
    })
</script>


<!-- Скрипт таблицы "Члены группы сотрудников" -->
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#employeeGroupMembershipsTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'columnDefs': [
                {
                    'targets': [5],
                    'className': 'td-nowrap td-align-center',
                    'width': "24px",
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
