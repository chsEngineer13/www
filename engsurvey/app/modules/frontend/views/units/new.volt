<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('units') }}">Единицы измерения</a></li>
    <li class="active">Новая</li>
</ol>

<div class="content-header">
    <div class="content-title">Новая единица измерения</div>
</div>

{{ flashSession.output() }}

<form class="form-dialog form-dialog-md" role="form" autocomplete="off" 
    action="{{ url('units/create') }}"  method="post">

    <div class="form-dialog-body">
        <div id= "nameFormGroup" class="form-group">
            {{ form.label("name", ["class": "control-label"]) }}
            {{ form.render("name", ["class": "form-control form-control-xl"]) }}
            <span class="help-block"></span>
        </div>
        
        <div id= "symbolFormGroup" class="form-group">
            {{ form.label("symbol", ["class": "control-label"]) }}
            {{ form.render("symbol", ["class": "form-control form-control-md"]) }}
            <span class="help-block"></span>
        </div>
    </div>
    
    <div class="form-dialog-footer">
        {{ link_to("units", "Закрыть", "class": "btn btn-default",
            "title": "Выход без сохранения изменений") }}
        {{ submit_button("Создать", "class": "btn btn-primary", 
            "title": "Создание новой единицы измернения") }}
    </div>

</form>


<script>
    // Добавляет в форму сообщения об ошибках.
    function addErrorMessages() {
        {% if nameMessages is defined %}
            $("#nameFormGroup").addClass("has-error");
            $("#nameFormGroup .help-block").text("{{ nameMessages }}");
        {% endif %}
        
        {% if symbolMessages is defined %}
            $("#symbolFormGroup").addClass("has-error");
            $("#symbolFormGroup .help-block").text("{{ symbolMessages }}");
        {% endif %}
    };

    $(document).ready(function() {
        addErrorMessages();
    });
</script>
