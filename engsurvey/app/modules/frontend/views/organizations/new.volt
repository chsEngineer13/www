{# Навигационный маршрут #}
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('organizations') }}">Организации</a></li>
    <li class="active">Новая</li>
</ol>

{# Заголовок контента #}
<div class="content-header">
    <div class="content-title">Новая организация</div>
</div>

{# Информационные сообщения #}
{{ flashSession.output() }}

{# Форма #}
{{ form('organizations/create', 'class': 'form-dialog form-dialog-md', 
    'role': 'form', 'method': 'post', 'autocomplete': 'off') }}

    <div class="form-dialog-body">
        <div id= "displayNameFormGroup" class="form-group">
            {{ form.label("displayName", ["class": "control-label"]) }}
            {{ form.render("displayName") }}
            <span class="help-block"></span>
        </div>
        
        <div id= "shortNameFormGroup" class="form-group">
            {{ form.label("shortName", ["class": "control-label"]) }}
            {{ form.render("shortName") }}
            <span class="help-block"></span>
        </div>
        
        <div id= "fullNameFormGroup" class="form-group">
            {{ form.label("fullName", ["class": "control-label"]) }}
            {{ form.render("fullName") }}
        </div>
        
        <div id= "additionalInfoFormGroup" class="form-group">
            {{ form.label("additionalInfo", ["class": "control-label"]) }}
            {{ form.render("additionalInfo") }}
        </div>
    </div>
    
    <div class="form-dialog-footer">
        {{ link_to("organizations", "Закрыть", "class": "btn btn-default",
            "title": "Выход без сохранения изменений") }}
        {{ submit_button("Создать", "class": "btn btn-primary", 
            "title": "Создание новой организации") }}
    </div>
    

    
{{ end_form() }}


{# Скрипты #}
<script>

    {# Добавляет в форму сообщения об ошибках. #}
    function addErrorMessages() {
        {% if displayNameMessages is defined %}
            $("#displayNameFormGroup").addClass("has-error");
            $("#displayNameFormGroup .help-block").text("{{ displayNameMessages }}");
        {% endif %}
        
        {% if shortNameMessages is defined %}
            $("#shortNameFormGroup").addClass("has-error");
            $("#shortNameFormGroup .help-block").text("{{ shortNameMessages }}");
        {% endif %}
    };
    

    $(document).ready(function() {

        addErrorMessages();

    });
    
</script>
