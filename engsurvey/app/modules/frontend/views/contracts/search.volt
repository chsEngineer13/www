<ol class="breadcrumb">
    <li>{{ link_to('', '<i class="fa fa-home"></i>') }}</li>
    <li class="active">Договоры</li>
</ol>

<div class="content-header">
    <div class="content-title">Договоры</div>
</div>

{{ flashSession.output() }}

<div class="buttons-block">
    {{ link_to('contracts/new',
        '<i class="fa fa-plus"></i>&nbsp;Новый договор',
        'class': 'btn btn-default', 'role': 'button',
        'title': 'Создание нового договора') }}
</div>

<table id="contractsTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Номер договора</th>
            <th>ДС</th>
            <th>Предмет договора</th>
            <th>Шифр стройки</th>
            <th>Заказчик</th>
            <th>Ответственный филиал</th>
            <th>Дата подписания</th>
            <th>Статус</th>
            <th>Комментарий</th>
            <th><!-- Действия --></th>
        </tr>
    </thead>
    <tbody>
        {% for contract in contracts %}
            <tr>
                <td>
                    <a href="{{ url('contracts/properties?id=' ~ contract.getId()) }}">
                        {{ contract.getContractNumber() }}</a>
                </td>
                <td>{{ contract.getSupplementalAgreementNumber() }}</td>
                <td>{{ contract.getSubjectOfContract() }}</td>
                <td>{{ contract.getConstructionProject().getCode() }}</td>
                <td>{{ contract.getCustomer().getDisplayName() }}</td>
                <td>
                    {% if contract.getBranch() %}
                        {{ contract.getBranch().getDisplayName() }}
                    {% endif %}
                </td>
                <td>
                    {% if contract.getSignatureDate() %}
                        {{ contract.getFormattedSignatureDate('d.m.Y') }}
                    {% endif %}
                </td>
                <td>
                    {% if contract.getContractStatus() %}
                        {{ contract.getContractStatus().getName() }}
                    {% endif %}
                </td>
                <td>{{ contract.getComment() }}</td>
                <td>
                    <!-- Действия -->
                    <a href="{{ url('contracts/edit?id=' ~ contract.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="{{ url('contracts/delete?id=' ~ contract.getId()) }}"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $.fn.dataTable.moment('DD.MM.YYYY');
        var table = $('#contractsTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'columnDefs': [
                {
                    'targets': [0],
                    'orderData': [0, 1],
                },
            
                {
                    'targets': [1],
                    'className': 'td-align-center',
                    'width': "30px",
                    'sortable': false,
                },
                
                {
                    'targets': [9],
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