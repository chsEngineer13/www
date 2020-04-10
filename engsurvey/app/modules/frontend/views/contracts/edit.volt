<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url("contracts") }}">Договоры</a></li>
    <li class="active">Редактирование</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">{{ "Редактирование договора № " ~  contract.getContractNumber()}}</div>
    {% if contract.getSupplementalAgreementNumber() %}
        <div class="content-subtitle">{{ "Дополнительное соглашение № " ~  contract.getSupplementalAgreementNumber()}}</div>
    {% endif %}
</div>


<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("contracts/update") }}"  method="post">

    <div class="form-dialog-body">
        <!-- Идентификатор договора. -->
        {{ hidden_field("id") }}
    
        <!-- Номер договора. -->
        <div id="contractNumberFormGroup" class="form-group">
            <label for="contractNumber" class="control-label">Номер договора&nbsp;*</label>
            {{ text_field("contractNumber", "class": "form-control form-control-xl") }}
            <span class="help-block"></span>
        </div>
        
        <!-- Номер дополнительного соглашения. -->
        <div id="supplementalAgreementNumberFormGroup" class="form-group">
            <label for="supplementalAgreementNumber" class="control-label">Номер ДС</label>
            {{ text_field("supplementalAgreementNumber", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Дата подписания договора. -->
        <div id="signatureDateFormGroup" class="form-group">
            <label for="signatureDate" class="control-label">Дата подписания</label>
            <div class="input-group date datepicker datepicker__size_sm">
                {{ text_field("signatureDate", "class": "form-control") }}
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span class="help-block"></span>
        </div>

        <!-- Предмет договора. -->
        <div id= "subjectOfContractFormGroup" class="form-group">
            <label for="subjectOfContract" class="control-label">Предмет договора&nbsp;*</label>
            {{ text_area("subjectOfContract", "class": "form-control form-control-xl", "rows": 3) }}
            <span class="help-block"></span>
        </div>

        <!-- Объект строительства (стройка). -->
        <div id= "constructionProjectFormGroup" class="form-group">
            <label for="constructionProjectId" class="control-label">Стройка&nbsp;*</label>
            {{ select(
                  "constructionProjectId", 
                  constructionProjects, 
                  "using": ["id", "code"],
                  "useEmpty": true, 
                  "emptyText": "...", 
                  "emptyValue": "",
                  "class": "selectpicker form-control-sm",
                  "data-live-search": true
            ) }}
            <span class="help-block"></span>
        </div>

        <!-- Заказчик (агент). -->
        <div id= "customerFormGroup" class="form-group">
            <label for="customerId" class="control-label">Заказчик (агент)&nbsp;*</label>
            {{ select(
                  "customerId", 
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

        <!-- Ответственный филиал. -->
        <div id= "branchFormGroup" class="form-group">
            <label for="branchId" class="control-label">Ответственный филиал</label>
            {{ select(
                  "branchId", 
                  branches, 
                  "using": ["id", "displayName"],
                  "useEmpty": true, 
                  "emptyText": "...", 
                  "emptyValue": "",
                  "class": "selectpicker form-control-xl",
                  "data-live-search": true
            ) }}
            <span class="help-block"></span>
        </div>

        <!-- Статус договора. -->
        <div id= "contractStatusFormGroup" class="form-group">
            <label for="contractStatusId" class="control-label">Статус договора&nbsp;*</label>
            {{ select(
                  "contractStatusId", 
                  contractStatuses, 
                  "using": ["id", "name"],
                  "useEmpty": true, 
                  "emptyText": "...", 
                  "emptyValue": "",
                  "class": "selectpicker form-control-sm"
            ) }}
            <span class="help-block"></span>
        </div>

        <!-- Стоимость работ по договору. -->
        <div id="contractCostFormGroup" class="form-group">
            <label for="contractCost" class="control-label">Стоимость работ</label>
            {{ text_field("contractCost", "class": "form-control form-control-sm") }}
            <span class="help-block"></span>
        </div>

        <!-- Комментарий. -->
        <div id= "commentFormGroup" class="form-group">
            <label for="comment" class="control-label">Комментарий</label>
            {{ text_area("comment", "class": "form-control form-control-xl", "rows": 3) }}
        </div>

    </div>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("contracts") }}" class="btn btn-default" role="button"
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
        // Номер договора.
        {% if contractNumberMessages is defined %}
            $("#contractNumberFormGroup").addClass("has-error");
            $("#contractNumberFormGroup .help-block").text("{{ contractNumberMessages }}");
        {% endif %}

        // Дата подписания договора.
        {% if signatureDateMessages is defined %}
            $("#signatureDateFormGroup").addClass("has-error");
            $("#signatureDateFormGroup .help-block").text("{{ signatureDateMessages }}");
        {% endif %}

        // Предмет договора.
        {% if subjectOfContractMessages is defined %}
            $("#subjectOfContractFormGroup").addClass("has-error");
            $("#subjectOfContractFormGroup .help-block").text("{{ subjectOfContractMessages }}");
        {% endif %}

        // Объект строительства (стройка).
        {% if constructionProjectMessages is defined %}
            $("#constructionProjectFormGroup").addClass("has-error");
            $("#constructionProjectFormGroup .help-block").text("{{ constructionProjectMessages }}");
        {% endif %}

        // Заказчик (агент).
        {% if customerMessages is defined %}
            $("#customerFormGroup").addClass("has-error");
            $("#customerFormGroup .help-block").text("{{ customerMessages }}");
        {% endif %}
    
        // Ответственный филиал.
        {% if branchMessages is defined %}
            $("#branchFormGroup").addClass("has-error");
            $("#branchFormGroup .help-block").text("{{ branchMessages }}");
        {% endif %}
        
        // Статус договора.
        {% if contractStatusMessages is defined %}
            $("#contractStatusFormGroup").addClass("has-error");
            $("#contractStatusFormGroup .help-block").text("{{ contractStatusMessages }}");
        {% endif %}

        // Стоимость работ по договору.
        {% if contractCostMessages is defined %}
            $("#contractCostFormGroup").addClass("has-error");
            $("#contractCostFormGroup .help-block").text("{{ contractCostMessages }}");
        {% endif %}
    };
    

    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });
</script>
