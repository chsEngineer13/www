<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('units') }}">Единицы измерения</a></li>
    <li class="active">Редактирование</li>
</ol>

<div class="content-header">
    <div class="content-title">Редактирование единица измерения</div>
</div>

{{ flashSession.output() }}

<form class="form-dialog form-dialog-md" role="form" autocomplete="off" 
    action="{{ url('units/update') }}"  method="post">

    <div class="form-dialog-body">
		{{ form.render("id") }}
	
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
        <a href="{{ url('units') }}" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
        <button type="submit" class="btn btn-primary" 
            title="Сохранение внесенных изменений">Сохранить</button>
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
