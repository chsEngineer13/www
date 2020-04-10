<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('performance-dynamics') }}">Динамика выполнения работ</a></li>
    <li class="active">Редактирование</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">Редактирование ссылкок на отчеты</div>
</div>

<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("performance-dynamics/update") }}"  method="post">

    <div class="form-dialog-body">
        <!-- Идентификатор записи. -->
        {{ hidden_field("id") }}
    
        <!-- Порядковый номер. -->
        <div id="seqNumberFormGroup" class="form-group">
            <label for="seqNumber" class="control-label">Порядковый номер&nbsp;*</label>
            {{ text_field("seqNumber", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>
    
        <!-- Организация. -->
        <div id= "organizationIdFormGroup" class="form-group">
            <label for="organizationId" class="control-label">Организация&nbsp;*</label>
            {{ select(
                  "organizationId", 
                  organizations, 
                  "using": ["id", "displayName"],
                  "useEmpty": true, 
                  "emptyText": "...", 
                  "emptyValue": "",
                  "class": "selectpicker form-control-xl",
                  "data-live-search": true
            ) }}
            <span class="help-block"></span>
        </div>

        <!-- Ссылка на отчет о ходе выполнения работ по сбору исходных данных (СИД). -->
        <div id="initialDataReportLinkFormGroup" class="form-group">
            <label for="initialDataReportLink" class="control-label">Отчет по СИД (ссылка)</label>
            {{ text_field("initialDataReportLink", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>

        <!-- Ссылка на отчет о ходе выполнения работ по инженерным изысканиям (ИИ). -->
        <div id="engineeringSurveyReportLinkFormGroup" class="form-group">
            <label for="engineeringSurveyReportLink" class="control-label">Отчет по ИИ (ссылка)</label>
            {{ text_field("engineeringSurveyReportLink", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>

        <!-- Ссылка на отчет о ходе выполнения работ по лабораторным исследованиям (ЛИ). -->
        <div id="laboratoryReportLinkFormGroup" class="form-group">
            <label for="laboratoryReportLink" class="control-label">Отчет по ЛИ (ссылка)</label>
            {{ text_field("laboratoryReportLink", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>

        <!-- Ссылка на отчет о ходе выполнения работ по разработки документации планировки территории (ДПТ). -->
        <div id="territoryPlanningReportLinkFormGroup" class="form-group">
            <label for="territoryPlanningReportLink" class="control-label">Отчет по ДПТ (ссылка)</label>
            {{ text_field("territoryPlanningReportLink", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>
    </div>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("performance-dynamics") }}" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
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
    
        // Организация.
        {% if organizationIdMessages is defined %}
            $("#organizationIdFormGroup").addClass("has-error");
            $("#organizationIdFormGroup .help-block").text("{{ organizationIdMessages }}");
        {% endif %}

        // Ссылка на отчет о ходе выполнения работ по сбору исходных данных (СИД).
        {% if initialDataReportLinkMessages is defined %}
            $("#initialDataReportLinkFormGroup").addClass("has-error");
            $("#initialDataReportLinkFormGroup .help-block").text("{{ initialDataReportLinkMessages }}");
        {% endif %}

        // Ссылка на отчет о ходе выполнения работ по инженерным изысканиям (ИИ).
        {% if engineeringSurveyReportLinkMessages is defined %}
            $("#engineeringSurveyReportLinkFormGroup").addClass("has-error");
            $("#engineeringSurveyReportLinkFormGroup .help-block").text("{{ engineeringSurveyReportLinkMessages }}");
        {% endif %}

        // Ссылка на отчет о ходе выполнения работ по лабораторным исследованиям (ЛИ).
        {% if laboratoryReportLinkMessages is defined %}
            $("#laboratoryReportLinkFormGroup").addClass("has-error");
            $("#laboratoryReportLinkFormGroup .help-block").text("{{ laboratoryReportLinkMessages }}");
        {% endif %}

        // Ссылка на отчет о ходе выполнения работ по разработки документации планировки территории (ДПТ).
        {% if territoryPlanningReportLinkMessages is defined %}
            $("#territoryPlanningReportLinkFormGroup").addClass("has-error");
            $("#territoryPlanningReportLinkFormGroup .help-block").text("{{ territoryPlanningReportLinkMessages }}");
        {% endif %}
    };

    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });
</script>
