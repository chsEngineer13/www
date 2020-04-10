<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url('employee-groups') }}">Группы сотрудников</a></li>
    <li class="active">Редактирование</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">Редактирование группы сотрудников</div>
</div>

<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("employee-groups/update") }}"  method="post">

    <div class="form-dialog-body">
        <!-- Идентификатор группы пользователей. -->
       {{ hidden_field("id") }}

        <!-- Порядковый номер. -->
        <div id="seqNumberFormGroup" class="form-group">
            <label for="seqNumber" class="control-label">Порядковый номер&nbsp;*</label>
            {{ text_field("seqNumber", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Системное имя группы сотрудников. -->
        <div id="systemNameFormGroup" class="form-group">
            <label for="systemName" class="control-label">Системное имя&nbsp;*</label>
            {{ text_field("systemName", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>

        <!-- Наименование группы сотрудников. -->
        <div id="nameFormGroup" class="form-group">
            <label for="name" class="control-label">Наименование&nbsp;*</label>
            {{ text_field("name", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>

        <!-- Описание группы сотрудников. -->
        <div id= "descriptionFormGroup" class="form-group">
            <label for="description" class="control-label">Описание</label>
            {{ text_area("description", "class": "form-control form-control-xl", "rows": 3) }}
        </div>
    </div>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("employee-groups") }}" class="btn btn-default" role="button"
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
        {% if seqNumberMessages is defined %}
            $("#seqNumberFormGroup").addClass("has-error");
            $("#seqNumberFormGroup .help-block").html("{{ seqNumberMessages }}");
        {% endif %}
    
        {% if systemNameMessages is defined %}
            $("#systemNameFormGroup").addClass("has-error");
            $("#systemNameFormGroup .help-block").html("{{ systemNameMessages }}");
        {% endif %}
        
        {% if nameMessages is defined %}
            $("#nameFormGroup").addClass("has-error");
            $("#nameFormGroup .help-block").html("{{ nameMessages }}");
        {% endif %}
    };

    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });
</script>
