<!-- Подключение модальных окон. -->
{{ partial("contract-stages/import-xlsx-modal") }}

<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url("contracts") }}">Договоры</a></li>
    <li class="active">Этапы работ</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">{{ "Договор № " ~  contract.getContractNumber()}}</div>
    {% if contract.getSupplementalAgreementNumber() %}
        <div class="content-subtitle">{{ "Дополнительное соглашение № " ~  contract.getSupplementalAgreementNumber()}}</div>
    {% endif %}
</div>

<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Вкладки -->
<div style="margin-bottom: 20px">
    <ul class="nav nav-tabs">
        <li><a href="{{ url("contracts/properties?id=" ~ contract.getId()) }}">Свойства</a></li>
        <li><a href="{{ url("contract-files?contract_id=" ~ contract.getId()) }}">Файлы</a></li>
        <li class="active"><a href="{{ url("contract-stages?contract_id=" ~ contract.getId()) }}">Календарный план</a></li>
    </ul>
</div>

<!-- Блок кнопок. -->
<div class="buttons-block">
    <a href="{{ url('contract-stages/new?contract_id=' ~ contract.getId()) }}"
        class="btn btn-default" title="Создание нового этапа работ">
        <i class="fa fa-plus fa-fw"></i>&nbsp;Новый этап работ</a>

    <a href="{{ url('contract-stages/export-xlsx?contract_id=' ~ contract.getId()) }}" 
        class="btn btn-default" title="Экспорт календарного плана в файл MS Excel (XLSX)">
        <i class="fa fa-file-excel-o fa-fw"></i>&nbsp;Экспорт</a>
        
    <a href="#" id="importXlsxButton" class="btn btn-default" 
        title="Импорт календарного плана из файла MS Excel (XLSX)">
        <i class="fa fa-file-excel-o fa-fw"></i>&nbsp;Импорт</a>
</div>

<!-- Таблица этапов работ по договору. -->
<table id="contractStagesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>№ раздела КП</th>
            <th>Наименование раздела КП</th>
            <th>№ этапа</th>
            <th>Наименование работ (этапа работ)</th>
            <th>Участок работ</th>
            <th>Начало<br>работ</th>
            <th>Окончание<br>работ</th>
            <th>Стоимость<br>без НДС</th>
            <th>НДС</th>
            <th>Стоимость<br>с НДС</th>
            <th>Комментарий</th>
            <th><!-- Действия --></th>
        </tr>
    </thead>
    <tbody>
        {% for stage in contractStages %}
            <tr>
                <td>{{ stage.getSectionNumber() }}</td>
                <td>{{ stage.getSectionName() }}</td>
                <td>{{ stage.getStageNumber() }}</td>
                <td>{{ stage.getStageName() }}</td>
                <td>
                    {% if stage.getConstructionSiteId() %}
                        {{ stage.getConstructionSite().getName() }}
                    {% endif %}
                </td>
                <td>{{ stage.getFormattedStartDate('d.m.Y') }}</td>
                <td>{{ stage.getFormattedEndDate('d.m.Y') }}</td>
                <td>{{ stage.getFormattedCostWithoutVat(1, ',', ' ') }}</td>
                <td>{{ stage.getFormattedVat(1, ',', ' ') }}</td>
                <td>{{ stage.getFormattedCostWithVat(1, ',', ' ') }}</td>
                <td>{{ stage.getComment() }}</td>
                <td>
                    <!-- Действия -->
                    <a href="{{ url('contract-stages/edit?id=' ~ stage.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil fa-fw"></i></a>
                    <a href="{{ url('contract-stages/delete?id=' ~ stage.getId()) }}"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o fa-fw"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>


<!-- Скрипты таблицы этапов работ по договору. -->
<script>
    $(document).ready(function() {
        $.fn.dataTable.moment('DD.MM.YYYY');
        var table = $('#contractStagesTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },
            
            // Количество выводимых строк в таблице по умолчанию.
            'pageLength': 25,
            
            // Настройка колонок.
            'columnDefs': [
                {
                    'targets': [0],
                    'sortable': false,
                    'visible': false,
                },
                
                {
                    'targets': [1],
                    'sortable': false,
                    'visible': false,
                },
                
                {
                    'targets': [2],
                    'sortable': false,
                    'width': '20px',
                },
                
                {
                    'targets': [3],
                    'sortable': false,
                },
                
                {
                    'targets': [4],
                    'sortable': false,
                },
                
                {
                    'targets': [5],
                    'className': 'td-nowrap',
                    'sortable': false,
                },
                
                {
                    'targets': [6],
                    'className': 'td-nowrap',
                    'sortable': false,
                },
                
                {
                    'targets': [7],
                    'className': 'td-nowrap',
                    'sortable': false,
                },
                
                {
                    'targets': [8],
                    'className': 'td-nowrap',
                    'sortable': false,
                },
                
                {
                    'targets': [9],
                    'className': 'td-nowrap',
                    'sortable': false,
                },
                
                {
                    'targets': [10],
                    'sortable': false,
                },
                
                {
                    'targets': [11],
                    'className': 'td-nowrap td-align-center',
                    'sortable': false,
                    'width': '72px',
                }
            ],
            
            
            'order': [[ 0, 'asc' ]],
            
            //'stateSave': true,

            // Функция группировки строк.
            'drawCallback': function(settings) {
                var api = this.api();
                var rows = api.rows({page:'current'}).nodes();
                var last=null;
                // Группировка по столбцу 1.
                api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="10">'+group+'</td></tr>'
                        );
                        last = group;
                    }
                });
            }

        });

        new $.fn.dataTable.FixedHeader( table, {
            'header': true,
            'headerOffset': 50
        });
    });
</script>

<script>
    $('#importXlsxButton').on('click', function(){
        $('#importXlsxModal').modal('show');
    });
</script>
