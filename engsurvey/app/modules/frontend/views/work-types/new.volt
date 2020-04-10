<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('work-types') }}">Виды работ</a></li>
    <li class="active">Новый</li>
</ol>

<div class="content-header">
    <div class="content-title">Новый вид работ</div>
</div>

{{ flashSession.output() }}

<form class="form-dialog form-dialog-md" role="form" autocomplete="off" 
    action="{{ url('work-types/create') }}"  method="post">

    <div class="form-dialog-body">
        {# Вид изысканий. #}
        <div id= "surveyTypeFormGroup" class="form-group">
            {{ form.label("surveyTypeId", ["class": "control-label"]) }}
            <div class="pull-left form-control-xl"> 
                {{ form.render("surveyTypeId", ["class": "form-control selectpicker"]) }}
            </div> 
            <span class="help-block"></span>
        </div>

        {# Наименование вида работ. #}
        <div id= "nameFormGroup" class="form-group">
            {{ form.label("name", ["class": "control-label"]) }}
            {{ form.render("name", ["class": "form-control form-control-xl"]) }}
            <span class="help-block"></span>
        </div>

        {# Сокращенное наименование вида работ. #}
        <div id= "shortNameFormGroup" class="form-group">
            {{ form.label("shortName", ["class": "control-label"]) }}
            {{ form.render("shortName", ["class": "form-control form-control-xl"]) }}
            <span class="help-block"></span>
        </div>

        {# Единица измерения. #}
        <div id= "unitFormGroup" class="form-group">
            {{ form.label("unitId", ["class": "control-label"]) }}
            <div class="pull-left form-control-md"> 
                {{ form.render("unitId", ["class": "form-control selectpicker"]) }}
            </div> 
            <span class="help-block"></span>
        </div>

        {# Норма выработки за день. #}
        <div id= "productionRateFormGroup" class="form-group">
            {{ form.label("productionRate", ["class": "control-label"]) }}
            {{ form.render("productionRate", ["class": "form-control form-control-sm"]) }}
            <span class="help-block"></span>
        </div>

        <fieldset>
            <legend>Состав исполнителей</legend>
        
            {# Количество ИТР. #}
            <div id= "numderOfEngineersFormGroup" class="form-group">
                {{ form.label("numderOfEngineers", ["class": "control-label"]) }}
                {{ form.render("numderOfEngineers", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Количество рабочих. #}
            <div id= "numderOfWorkersFormGroup" class="form-group">
                {{ form.label("numderOfWorkers", ["class": "control-label"]) }}
                {{ form.render("numderOfWorkers", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Количество водителей. #}
            <div id= "numderOfDriversFormGroup" class="form-group">
                {{ form.label("numderOfDrivers", ["class": "control-label"]) }}
                {{ form.render("numderOfDrivers", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Количество бурмастеров. #}
            <div id= "numderOfDrillersFormGroup" class="form-group">
                {{ form.label("numderOfDrillers", ["class": "control-label"]) }}
                {{ form.render("numderOfDrillers", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Зональные коэффициенты</legend>

            {# Зональный коэффициент при работе в тайге. #}
            <div id= "zfTaigaFormGroup" class="form-group">
                {{ form.label("zfTaiga", ["class": "control-label"]) }}
                {{ form.render("zfTaiga", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Зональный коэффициент при работе в лесотундре. #}
            <div id= "zfForestTundraFormGroup" class="form-group">
                {{ form.label("zfForestTundra", ["class": "control-label"]) }}
                {{ form.render("zfForestTundra", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Зональный коэффициент при работе в тундре. #}
            <div id= "zfTundraFormGroup" class="form-group">
                {{ form.label("zfTundra", ["class": "control-label"]) }}
                {{ form.render("zfTundra", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Зональный коэффициент при работе в лесостепи. #}
            <div id= "zfForestSteppeFormGroup" class="form-group">
                {{ form.label("zfForestSteppe", ["class": "control-label"]) }}
                {{ form.render("zfForestSteppe", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Сезонные коэффициенты</legend>
        
            {# Сезонный коэффициент при работе в летний период. #}
            <div id= "sfSummerFormGroup" class="form-group">
                {{ form.label("sfSummer", ["class": "control-label"]) }}
                {{ form.render("sfSummer", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Сезонный коэффициент при работе в осенне-весенний период. #}
            <div id= "sfAutumnSpringFormGroup" class="form-group">
                {{ form.label("sfAutumnSpring", ["class": "control-label"]) }}
                {{ form.render("sfAutumnSpring", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>

            {# Сезонный коэффициент при работе в зимний период. #}
            <div id= "sfWinterFormGroup" class="form-group">
                {{ form.label("sfWinter", ["class": "control-label"]) }}
                {{ form.render("sfWinter", ["class": "form-control form-control-sm"]) }}
                <span class="help-block"></span>
            </div>
            
        </fieldset>

        {#  Комментарий. #}
        <div id= "commentFormGroup" class="form-group">
            {{ form.label("comment", ["class": "control-label"]) }}
            {{ form.render("comment", ["class": "form-control form-control-xl"]) }}
        </div>
    </div>
    
    <div class="form-dialog-footer">
        <a href="{{ url('work-types') }}" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
        <button type="submit" class="btn btn-primary" 
            title="Создание нового вида работ">Создать</button>
    </div>
</form>


<script>
    {# Добавляет в форму сообщения об ошибках. #}
    function addErrorMessages() {
        {% if surveyTypeMessages is defined %}
            $("#surveyTypeFormGroup").addClass("has-error");
            $("#surveyTypeFormGroup .help-block").text("{{ surveyTypeMessages }}");
        {% endif %}

        {% if nameMessages is defined %}
            $("#nameFormGroup").addClass("has-error");
            $("#nameFormGroup .help-block").text("{{ nameMessages }}");
        {% endif %}

        {% if shortNameMessages is defined %}
            $("#shortNameFormGroup").addClass("has-error");
            $("#shortNameFormGroup .help-block").text("{{ shortNameMessages }}");
        {% endif %}
        
        {% if unitMessages is defined %}
            $("#unitFormGroup").addClass("has-error");
            $("#unitFormGroup .help-block").text("{{ unitMessages }}");
        {% endif %}

        {% if productionRateMessages is defined %}
            $("#productionRateFormGroup").addClass("has-error");
            $("#productionRateFormGroup .help-block").text("{{ productionRateMessages }}");
        {% endif %}

        {% if numderOfEngineersMessages is defined %}
            $("#numderOfEngineersFormGroup").addClass("has-error");
            $("#numderOfEngineersFormGroup .help-block").text("{{ numderOfEngineersMessages }}");
        {% endif %}

        {% if numderOfWorkersMessages is defined %}
            $("#numderOfWorkersFormGroup").addClass("has-error");
            $("#numderOfWorkersFormGroup .help-block").text("{{ numderOfWorkersMessages }}");
        {% endif %}

        {% if numderOfDriversMessages is defined %}
            $("#numderOfDriversFormGroup").addClass("has-error");
            $("#numderOfDriversFormGroup .help-block").text("{{ numderOfDriversMessages }}");
        {% endif %}

        {% if numderOfDrillersMessages is defined %}
            $("#numderOfDrillersFormGroup").addClass("has-error");
            $("#numderOfDrillersFormGroup .help-block").text("{{ numderOfDrillersMessages }}");
        {% endif %}

        {% if zfTaigaMessages is defined %}
            $("#zfTaigaFormGroup").addClass("has-error");
            $("#zfTaigaFormGroup .help-block").text("{{ zfTaigaMessages }}");
        {% endif %}

        {% if zfForestTundraMessages is defined %}
            $("#zfForestTundraFormGroup").addClass("has-error");
            $("#zfForestTundraFormGroup .help-block").text("{{ zfForestTundraMessages }}");
        {% endif %}

        {% if zfTundraMessages is defined %}
            $("#zfTundraFormGroup").addClass("has-error");
            $("#zfTundraFormGroup .help-block").text("{{ zfTundraMessages }}");
        {% endif %}

        {% if zfForestSteppeMessages is defined %}
            $("#zfForestSteppeFormGroup").addClass("has-error");
            $("#zfForestSteppeFormGroup .help-block").text("{{ zfForestSteppeMessages }}");
        {% endif %}

        {% if sfSummerMessages is defined %}
            $("#sfSummerFormGroup").addClass("has-error");
            $("#sfSummerFormGroup .help-block").text("{{ sfSummerMessages }}");
        {% endif %}

        {% if sfAutumnSpringMessages is defined %}
            $("#sfAutumnSpringFormGroup").addClass("has-error");
            $("#sfAutumnSpringFormGroup .help-block").text("{{ sfAutumnSpringMessages }}");
        {% endif %}

        {% if sfWinterMessages is defined %}
            $("#sfWinterFormGroup").addClass("has-error");
            $("#sfWinterFormGroup .help-block").text("{{ sfWinterMessages }}");
        {% endif %}
    };

    $(document).ready(function() {
        addErrorMessages();
    });
</script>
