{# Навигационный маршрут #}
<ol class="breadcrumb">
    <li>{{ link_to("", '<i class="fa fa-home"></i>') }}</li>
    <li>{{ link_to("construction-projects", "Стройки") }}</li>
    <li>{{ link_to("construction-sites?construction-project-id=" ~ constructionProject.getId(), "Участки работ") }}</li>
    <li>{{ link_to("survey-facilities?construction-site-id=" ~ constructionSite.getId(), "Объекты изысканий") }}</li>
    <li class="active">Редактирование</li>
</ol>

{# Заголовок контента #}
<div class="content-header">
    <div class="content-title">Редактирование объекта изысканий</div>
</div>

{# Объекты #}
<div class="objects-block">
    <div class="objects-block__item">
        <div class="objects-block__item-label">Стройка:</div>
        <div class="objects-block__item-name">
            {{ constructionProject.getName() ~ ' (Шифр ' ~ constructionProject.getCode() ~ ')' }}
        </div>
    </div>
    <div class="objects-block__item">
        <div class="objects-block__item-label">Участок работ:</div>
        <div class="objects-block__item-name">
            {{ constructionSite.getName() }}
        </div>
    </div>
</div>

{# Информационные сообщения #}
{{ flashSession.output() }}

{# Форма #}
{{ form('survey-facilities/update', 'class': 'form-dialog form-dialog-md',
    'role': 'form', 'method': 'post', 'autocomplete': 'off') }}

    <div class="form-dialog-body">
        {{ form.render("id") }}
        {{ form.render("constructionProjectId") }}
        {{ form.render("constructionSiteId") }}

        <div id= "sequenceNumberFormGroup" class="form-group">
            {{ form.label("sequenceNumber", ["class": "control-label"]) }}
            {{ form.render("sequenceNumber") }}
            <span class="help-block"></span>
        </div>

        <div id= "facilityNameFormGroup" class="form-group">
            {{ form.label("facilityName", ["class": "control-label"]) }}
            {{ form.render("facilityName") }}
            <span class="help-block"></span>
        </div>

        <div id= "facilityDesignationFormGroup" class="form-group">
            {{ form.label("facilityDesignation", ["class": "control-label"]) }}
            {{ form.render("facilityDesignation") }}
            <span class="help-block"></span>
        </div>

        <div id= "facilityNumberFormGroup" class="form-group">
            {{ form.label("facilityNumber", ["class": "control-label"]) }}
            {{ form.render("facilityNumber") }}
            <span class="help-block"></span>
        </div>

        <div id= "stageOfWorksFormGroup" class="form-group">
            {{ form.label("stageOfWorks", ["class": "control-label"]) }}
            {{ form.render("stageOfWorks") }}
            <span class="help-block"></span>
        </div>

        <div id= "commentFormGroup" class="form-group">
            {{ form.label("comment", ["class": "control-label"]) }}
            {{ form.render("comment") }}
            <span class="help-block"></span>
        </div>
    </div>

    <div class="form-dialog-footer">
        {{ link_to("survey-facilities?construction-site-id=" ~ constructionSite.getId(),
            "Закрыть", "class": "btn btn-default",
            "title": "Выход без сохранения изменений") }}
        {{ submit_button("Сохранить", "class": "btn btn-primary",
            "title": "Сохранение внесенных изменений") }}
    </div>

{{ end_form() }}


{# Скрипты #}
<script>
    {# Добавляет в форму сообщения об ошибках. #}
    function addErrorMessages() {
        {% if sequenceNumberMessages is defined %}
            $("#sequenceNumberFormGroup").addClass("has-error");
            $("#sequenceNumberFormGroup .help-block").text("{{ sequenceNumberMessages }}");
        {% endif %}

        {% if facilityNameMessages is defined %}
            $("#facilityNameFormGroup").addClass("has-error");
            $("#facilityNameFormGroup .help-block").text("{{ facilityNameMessages }}");
        {% endif %}
    };

    $(document).ready(function() {
        addErrorMessages();
    });
</script>
