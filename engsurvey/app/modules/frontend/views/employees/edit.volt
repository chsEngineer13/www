<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('employees') }}">Сотрудники</a></li>
    <li class="active">Редактирование</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">Редактирование данных о сотруднике</div>
</div>

<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("employees/update") }}"  method="post">

    <div class="form-dialog-body">
        <!-- Идентификатор сотрудника. -->
       {{ form.render("id") }}
    
        <!-- Фамилия. -->
        <div id= "lastNameFormGroup" class="form-group">
            {{ form.label("lastName", ["class": "control-label"]) }}
            {{ form.render("lastName", ["class": "form-control form-control-md"]) }}
            <span id="lastNameHelp" class="help-block"></span>
        </div>

        <!-- Имя. -->
        <div id= "firstNameFormGroup" class="form-group">
            {{ form.label("firstName", ["class": "control-label"]) }}
            {{ form.render("firstName", ["class": "form-control form-control-md"]) }}
            <span id="firstNameHelp" class="help-block"></span>
        </div>

        <!-- Отчество. -->
        <div id= "middleNameFormGroup" class="form-group">
            {{ form.label("middleName", ["class": "control-label"]) }}
            {{ form.render("middleName", ["class": "form-control form-control-md"]) }}
            <span id="middleNameHelp" class="help-block"></span>
        </div>

        <!-- Филиал -->
        <div id= "branchFormGroup" class="form-group">
            {{ form.label("branchId", ["class": "control-label"]) }}
            {{ form.render("branchId", ["class": "selectpicker form-control-xl"]) }}
            <span id="branchHelp" class="help-block"></span>
        </div>

        <!-- Должность. -->
        <div id= "jobTitleFormGroup" class="form-group">
            {{ form.label("jobTitle", ["class": "control-label"]) }}
            {{ form.render("jobTitle", ["class": "form-control form-control-xl"]) }}
            <span id="jobTitleHelp" class="help-block"></span>
        </div>

        <!-- Подразделение. -->
        <div id= "departmentFormGroup" class="form-group">
            {{ form.label("department", ["class": "control-label"]) }}
            {{ form.render("department", ["class": "form-control form-control-xl"]) }}
            <span id="departmentHelp" class="help-block"></span>
        </div>

        <!-- Местонахождение сотрудника. -->
        <div id= "locationFormGroup" class="form-group">
            {{ form.label("location", ["class": "control-label"]) }}
            {{ form.render("location", ["class": "form-control form-control-xl"]) }}
            <span id="locationHelp" class="help-block"></span>
        </div>

        <!-- Телефон рабочий. -->
        <div id= "phoneWorkFormGroup" class="form-group">
            {{ form.label("phoneWork", ["class": "control-label"]) }}
            {{ form.render("phoneWork", ["class": "form-control form-control-md"]) }}
            <span id="phoneWorkHelp" class="help-block"></span>
        </div>

        <!-- Телефон газовый. -->
        <div id= "phoneGasFormGroup" class="form-group">
            {{ form.label("phoneGas", ["class": "control-label"]) }}
            {{ form.render("phoneGas", ["class": "form-control form-control-md"]) }}
            <span id="phoneGasHelp" class="help-block"></span>
        </div>

        <!-- Телефон мобильный. -->
        <div id= "phoneMobileFormGroup" class="form-group">
            {{ form.label("phoneMobile", ["class": "control-label"]) }}
            {{ form.render("phoneMobile", ["class": "form-control form-control-md"]) }}
            <span id="phoneMobileHelp" class="help-block"></span>
        </div>
        
        <!-- Электронная почта. -->
        <div id= "emailFormGroup" class="form-group">
            {{ form.label("email", ["class": "control-label"]) }}
            {{ form.render("email", ["class": "form-control form-control-xl"]) }}
            <span id="emailHelp" class="help-block"></span>
        </div>

    </div>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("employees") }}" class="btn btn-default" role="button"
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
        {% if lastNameMessages is defined %}
            $("#lastNameFormGroup").addClass("has-error");
            $("#lastNameFormGroup .help-block").text("{{ lastNameMessages }}");
        {% endif %}

        {% if firstNameMessages is defined %}
            $("#firstNameFormGroup").addClass("has-error");
            $("#firstNameFormGroup .help-block").text("{{ firstNameMessages }}");
        {% endif %}

        {% if middleNameMessages is defined %}
            $("#middleNameFormGroup").addClass("has-error");
            $("#middleNameFormGroup .help-block").text("{{ middleNameMessages }}");
        {% endif %}

        {% if branchMessages is defined %}
            $("#branchFormGroup").addClass("has-error");
            $("#branchFormGroup .help-block").text("{{ branchMessages }}");
        {% endif %}

        {% if jobTitleMessages is defined %}
            $("#jobTitleFormGroup").addClass("has-error");
            $("#jobTitleFormGroup .help-block").text("{{ jobTitleMessages }}");
        {% endif %}
    };

    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });
</script>
