<!-- Подключение модального окна загрузки файлов.  -->
{% set fileUploadModalAction = url("contract-files/upload?contract_id=" ~ contract.getId()) %}
{{ partial("partials/file-upload-modal", ["fileUploadModalAction": fileUploadModalAction]) }}

<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url("contracts") }}">Договоры</a></li>
    <li class="active">Файлы</li>
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
        <li class="active"><a href="{{ url("contract-files?contract_id=" ~ contract.getId()) }}">Файлы</a></li>
        <li><a href="{{ url("contract-stages?contract_id=" ~ contract.getId()) }}">Календарный план</a></li>
    </ul>
</div>

<!-- Кнопка загрузки файла. -->
<div style="margin-bottom: 20px">
    <button type="button" id="fileUploadButton" class="btn btn-default"><i class="fa fa-upload fa-fw"></i>&nbsp;Загрузить файл</button>
</div>

<!-- Таблица со списком файлов договора. -->
<table id="contractFilesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>№</th>
            <th>Имя файла</th>
            <th>Описание</th>
            <th>Размер</th>
            <th>Дата</th>
            <th>Пользователь</th>
            <th><!-- Действия --></th>
        </tr>
    </thead>
    <tbody>
        {% for file in contractFiles %}
            <tr>
                <td>{{ file.getSeqNumber() }}</td>
                <td>
                    <a href="{{ url('contract-files/download?id=' ~ file.getId()) }}">
                        {{ file.getFilename() }}</a>
                </td>
                <td>{{ file.getDescription() }}</td>
                <td>{{ file.getFormattedSize(1, ',', ' ') }}</td>
                <td>{{ file.getFormattedUpdatedAt('d.m.Y H:i:s') }}</td>
                <td>{{ file.getUpdatedUser().getEmployee().getShortName() }}</td>
                <td>
                    <!-- Действия -->
                    <a href="{{ url('contract-files/download?id=' ~ file.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title="Скачать файл"><i class="fa fa-upload fa-fw"></i></a>
                    <a href="{{ url('contract-files/edit?id=' ~ file.getId()) }}"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil fa-fw"></i></a>
                    <a href="{{ url('contract-files/delete?id=' ~ file.getId()) }}"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o fa-fw"></i></a>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>


<!-- Скрипты таблицы со списком файлов договора. -->
<script>
    $(document).ready(function() {
        $.fn.dataTable.moment('DD.MM.YYYY');
        var table = $('#contractFilesTable').DataTable({
            'language': {
                'url': '{{ url(config.dataTables.dataTablesRu) }}'
            },

            'columnDefs': [
                {
                    'targets': [0],
                    'width': '20px',
                },
                {
                    'targets': [6],
                    'className': 'td-nowrap td-align-center',
                    'width': "72px",
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


<!-- Вызов модального окна загрузки файла. -->
<script>
    $('#fileUploadButton').on('click', function(){
        $('#fileUploadModal').modal('show');
    });
</script>
