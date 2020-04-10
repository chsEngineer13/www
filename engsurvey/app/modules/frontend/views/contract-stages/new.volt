<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url("contracts") }}">Договоры</a></li>
    <li><a href="{{ url("contract-stages?contract_id=" ~ contract.getId()) }}">Этапы работ</a></li>
    <li class="active">Новый этап</li>
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

<h3>Новый этап работ</h3>

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("contract-stages/create") }}"  method="post">

    <div class="form-dialog-body">
        <!-- Уникальный идентификатор договора. -->
        {{ hidden_field("contractId") }}
        
        <!-- Номер раздела календарного плана. -->
        <div id="sectionNumberFormGroup" class="form-group">
            <label for="sectionNumber" class="control-label">Номер раздела КП</label>
            {{ text_field("sectionNumber", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Наименование раздела календарного плана. -->
        <div id= "sectionNameFormGroup" class="form-group">
            <label for="sectionName" class="control-label">Наименование раздела КП</label>
            {{ text_area("sectionName", "class": "form-control form-control-xl", "rows": 3) }}
            <span class="help-block"></span>
        </div>

        <!-- Номер этапа работ. -->
        <div id="stageNumberFormGroup" class="form-group">
            <label for="stageNumber" class="control-label">Номер этапа&nbsp;*</label>
            {{ text_field("stageNumber", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Наименование работ (этапа работ). -->
        <div id= "stageNameFormGroup" class="form-group">
            <label for="stageName" class="control-label">Наименование работ (этапа работ)&nbsp;*</label>
            {{ text_area("stageName", "class": "form-control form-control-xl", "rows": 3) }}
            <span class="help-block"></span>
        </div>

        <!-- Идентификатор участка работ. -->
        <div id="constructionSiteFormGroup" class="form-group">
            <label for="constructionSiteId" class="control-label">Участок работ</label>
            {{ select(
                  "constructionSiteId",
                  constructionSites,
                  "using": ["id", "name"],
                  "useEmpty": true,
                  "emptyText": "...",
                  "emptyValue": "",
                  "class": "selectpicker form-control-xl",
                  "data-live-search": true
            ) }}
            <span class="help-block"></span>
        </div>

        <!-- Дата начала работ. -->
        <div id="startDateFormGroup" class="form-group">
            <label for="startDate" class="control-label">Начало работ</label>
            <div class="input-group date datepicker datepicker__size_sm">
                {{ text_field("startDate", "class": "form-control") }}
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span class="help-block"></span>
        </div>

        <!-- Дата окончания работ. -->
        <div id="endDateFormGroup" class="form-group">
            <label for="endDate" class="control-label">Окончание работ</label>
            <div class="input-group date datepicker datepicker__size_sm">
                {{ text_field("endDate", "class": "form-control") }}
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span class="help-block"></span>
        </div>

        <!-- Стоимость работ без НДС. -->
        <div id="costWithoutVatFormGroup" class="form-group">
            <label for="costWithoutVat" class="control-label">Стоимость без НДС</label>
            {{ text_field("costWithoutVat", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- НДС -->
        <div id="vatFormGroup" class="form-group">
            <label for="vat" class="control-label">НДС</label>
            {{ text_field("vat", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Стоимость работ с учетом НДС. -->
        <div id="costWithVatFormGroup" class="form-group">
            <label for="costWithVat" class="control-label">Стоимость с НДС</label>
            {{ text_field("costWithVat", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Комментарий. -->
        <div id= "commentFormGroup" class="form-group">
            <label for="comment" class="control-label">Комментарий</label>
            {{ text_area("comment", "class": "form-control form-control-xl", "rows": 3) }}
        </div>
    </div>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("contract-stages?contract_id=" ~ contract.getId()) }}"
            class="btn btn-default" role="button"
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
        // Номер этапа работ.
        {% if stageNumberMessage is defined %}
            $("#stageNumberFormGroup").addClass("has-error");
            $("#stageNumberFormGroup .help-block").text("{{ stageNumberMessage }}");
        {% endif %}

        // Наименование работ (этапа работ).
        {% if stageNameMessage is defined %}
            $("#stageNameFormGroup").addClass("has-error");
            $("#stageNameFormGroup .help-block").text("{{ stageNameMessage }}");
        {% endif %}

        // Дата начала работ.
        {% if startDateMessage is defined %}
            $("#startDateFormGroup").addClass("has-error");
            $("#startDateFormGroup .help-block").text("{{ startDateMessage }}");
        {% endif %}

        // Дата окончания работ.
        {% if endDateMessage is defined %}
            $("#endDateFormGroup").addClass("has-error");
            $("#endDateFormGroup .help-block").text("{{ endDateMessage }}");
        {% endif %}

        // Стоимость работ без НДС.
        {% if costWithoutVatMessage is defined %}
            $("#costWithoutVatFormGroup").addClass("has-error");
            $("#costWithoutVatFormGroup .help-block").text("{{ costWithoutVatMessage }}");
        {% endif %}

        // НДС
        {% if vatMessage is defined %}
            $("#vatFormGroup").addClass("has-error");
            $("#vatFormGroup .help-block").text("{{ vatMessage }}");
        {% endif %}

        // Стоимость работ с учетом НДС.
        {% if costWithVatMessage is defined %}
            $("#costWithVatFormGroup").addClass("has-error");
            $("#costWithVatFormGroup .help-block").text("{{ costWithVatMessage }}");
        {% endif %}
     };


    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });

</script>
