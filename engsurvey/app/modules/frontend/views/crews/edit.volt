<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('crews') }}">Бригады</a></li>
    <li class="active">Редактирование</li>
</ol>

<div class="content-header">
    <div class="content-title">Редактирование бригады</div>
</div>

{{ flashSession.output() }}

<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url('crews/update') }}"  method="post">

    <div class="form-dialog-body">

        {{ form.render('id') }}

        <div id= "branchFormGroup" class="form-group">
            {{ form.label("branchId", ["class": "control-label"]) }}
            <div class="pull-left form-control-xl">
                {{ form.render("branchId", ["class": "form-control selectpicker"]) }}
            </div>
            <span class="help-block"></span>
        </div>

        <div id= "crewTypeFormGroup" class="form-group">
            {{ form.label("crewTypeId", ["class": "control-label"]) }}
            <div class="pull-left form-control-xl">
                {{ form.render("crewTypeId", ["class": "form-control selectpicker"]) }}
            </div>
            <span class="help-block"></span>
        </div>

        <div id= "crewNameFormGroup" class="form-group">
            {{ form.label("crewName", ["class": "control-label"]) }}
            {{ form.render("crewName", ["class": "form-control form-control-xl"]) }}
            <span class="help-block"></span>
        </div>

        <div class="form-group">
            {{ form.label("headLastName", ["class": "control-label"]) }}
            {{ form.render("headLastName", ["class": "form-control form-control-md"]) }}
        </div>

        <div class="form-group">
            {{ form.label("headFirstName", ["class": "control-label"]) }}
            {{ form.render("headFirstName", ["class": "form-control form-control-md"]) }}
        </div>

        <div class="form-group">
            {{ form.label("headMiddleName", ["class": "control-label"]) }}
            {{ form.render("headMiddleName", ["class": "form-control form-control-md"]) }}
        </div>

        <div class="form-group">
            {{ form.label("phone", ["class": "control-label"]) }}
            {{ form.render("phone", ["class": "form-control form-control-xl"]) }}
        </div>

        <div class="form-group">
            {{ form.label("email", ["class": "control-label"]) }}
            {{ form.render("email", ["class": "form-control form-control-xl"]) }}
        </div>

        <div id= "numberOfCrewFormGroup" class="form-group">
            {{ form.label("numberOfCrew", ["class": "control-label"]) }}
            {{ form.render("numberOfCrew", ["class": "form-control form-control-sm"]) }}
            <span class="help-block"></span>
        </div>

        <div id= "reportLinkFormGroup" class="form-group">
            {{ form.label("reportLink", ["class": "control-label"]) }}
            {{ form.render("reportLink", ["class": "form-control form-control-xl"]) }}
        </div>
    </div>

    <div class="form-dialog-footer">
        <a href="{{ url('crews') }}" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
        <button type="submit" class="btn btn-primary"
            title="Сохранение внесенных изменений">Сохранить</button>
    </div>

</form>


<script>
    // Добавляет в форму сообщения об ошибках.
    function addErrorMessages() {
        {% if branchMessages is defined %}
            $("#branchFormGroup").addClass("has-error");
            $("#branchFormGroup .help-block").text("{{ branchMessages }}");
        {% endif %}

        {% if crewTypeMessages is defined %}
            $("#crewTypeFormGroup").addClass("has-error");
            $("#crewTypeFormGroup .help-block").text("{{ crewTypeMessages }}");
        {% endif %}

        {% if crewNameMessages is defined %}
            $("#crewNameFormGroup").addClass("has-error");
            $("#crewNameFormGroup .help-block").text("{{ crewNameMessages }}");
        {% endif %}

        {% if numberOfCrewMessages is defined %}
            $("#numberOfCrewFormGroup").addClass("has-error");
            $("#numberOfCrewFormGroup .help-block").text("{{ numberOfCrewMessages }}");
        {% endif %}
    };

    $(document).ready(function() {
        addErrorMessages();
    });
</script>
