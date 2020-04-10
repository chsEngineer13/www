<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('branches') }}">Филиалы</a></li>
    <li class="active">Редактирование</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">Редактирование филиала</div>
</div>

<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("branches/update") }}"  method="post">

    <div class="form-dialog-body">
        <!-- Идентификатор филиала. -->
       {{ form.render("id") }}

        <!-- Порядковый номер. -->
        <div id= "sequenceNumberFormGroup" class="form-group">
            {{ form.label("sequenceNumber", ["class": "control-label"]) }}
            {{ form.render("sequenceNumber", ["class": "form-control form-control-sm"]) }}
            <span id="sequenceNumberHelp" class="help-block"></span>
        </div>
    
        <!-- Организация. -->
        <div id= "organizationFormGroup" class="form-group">
            {{ form.label("organizationId", ["class": "control-label"]) }}
            {{ form.render("organizationId", ["class": "selectpicker form-control-xl"]) }}
            <span id="organizationHelp" class="help-block"></span>
        </div>
        
        <!-- Наименование филиала. -->
        <div id= "displayNameFormGroup" class="form-group">
            {{ form.label("displayName", ["class": "control-label"]) }}
            {{ form.render("displayName", ["class": "form-control form-control-xl"]) }}
            <span id="displayNameHelp" class="help-block"></span>
        </div>
    
        <!-- Код филиала. -->
        <div id= "codeFormGroup" class="form-group">
            {{ form.label("code", ["class": "control-label"]) }}
            {{ form.render("code", ["class": "form-control form-control-sm"]) }}
            <span id="codeHelp" class="help-block"></span>
        </div>
    </div>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("branches") }}" class="btn btn-default" role="button"
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
        {% if sequenceNumberMessages is defined %}
            $("#sequenceNumberFormGroup").addClass("has-error");
            $("#sequenceNumberFormGroup .help-block").text("{{ sequenceNumberMessages }}");
        {% endif %}
        
        {% if organizationMessages is defined %}
            $("#organizationFormGroup").addClass("has-error");
            $("#organizationFormGroup .help-block").text("{{ organizationMessages }}");
        {% endif %}
        
        {% if displayNameMessages is defined %}
            $("#displayNameFormGroup").addClass("has-error");
            $("#displayNameFormGroup .help-block").text("{{ displayNameMessages }}");
        {% endif %}
        
        {% if codeMessages is defined %}
            $("#codeFormGroup").addClass("has-error");
            $("#codeFormGroup .help-block").text("{{ codeMessages }}");
        {% endif %}
    };

    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });
</script>
