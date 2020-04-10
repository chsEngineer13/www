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

<h4>Редактирование свойств файла</h4>

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("contract-files/update") }}"  method="post">

    <div class="form-dialog-body">
        <!-- Идентификатор записи. -->
        {{ hidden_field("id") }}

        <!-- Порядковый номер. -->
        <div id="seqNumberFormGroup" class="form-group">
            <label for="seqNumber" class="control-label">Порядковый номер&nbsp;*</label>
            {{ text_field("seqNumber", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Имя файла. -->
        <div id="filenameFormGroup" class="form-group">
            <label for="filename" class="control-label">Имя файла&nbsp;*</label>
            {{ text_field("filename", "class": "form-control form-control-xl", "disabled": "disabled") }}
        </div>

        <!-- Описание файла. -->
        <div id= "descriptionFormGroup" class="form-group">
            <label for="description" class="control-label">Описание</label>
            {{ text_area("description", "class": "form-control form-control-xl", "rows": 3) }}
        </div>
    </div>
        
    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("contract-files?contract_id=" ~ contract.getId()) }}" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Отмена</a>
        <button type="submit" class="btn btn-primary"
            title="Сохранение внесенных изменений">Сохранить</button>
    </div>

</form>


<script>
    /**
     * Добавляет сообщения об ошибках к полям формы.
     */
    function addErrorMessages() {
        // Порядковый номер.
        {% if seqNumberMessages is defined %}
            $("#seqNumberFormGroup").addClass("has-error");
            $("#seqNumberFormGroup .help-block").text("{{ seqNumberMessages }}");
        {% endif %}
    };


    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });
</script>
