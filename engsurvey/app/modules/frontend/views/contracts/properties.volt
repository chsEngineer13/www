<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url("contracts") }}">Договоры</a></li>
    <li class="active">Свойства</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">{{ "Договор № " ~  contract.getContractNumber()}}</div>
    {% if contract.getSupplementalAgreementNumber() %}
        <div class="content-subtitle">{{ "Дополнительное соглашение № " ~  contract.getSupplementalAgreementNumber()}}</div>
    {% endif %}
</div>

<!-- Информационные сообщения -->
{{ flashSession.output() }}

<!-- Вкладки -->
<div style="margin-bottom: 20px">
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{ url("contracts/properties?id=" ~ contract.getId()) }}">Свойства</a></li>
        <li><a href="{{ url("contract-files?contract_id=" ~ contract.getId()) }}">Файлы</a></li>
        <li><a href="{{ url("contract-stages?contract_id=" ~ contract.getId()) }}">Календарный план</a></li>
    </ul>
</div>

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off">

    <fieldset>

        <div class="form-dialog-body">
            <!-- Идентификатор договора. -->
            {{ hidden_field("id") }}
        
            <!-- Номер договора. -->
            <div class="form-group">
                <label for="contractNumber" class="control-label">Номер договора</label>
                {{ text_field("contractNumber", "class": "form-control form-control-xl") }}
            </div>
            
            <!-- Номер дополнительного соглашения. -->
            <div class="form-group">
                <label for="supplementalAgreementNumber" class="control-label">Номер ДС</label>
                {{ text_field("supplementalAgreementNumber", "class": "form-control form-control-sm") }}
            </div>

            <!-- Дата подписания договора. -->
            <div id="signatureDateFormGroup" class="form-group">
                <label for="signatureDate" class="control-label">Дата подписания</label>
                {{ text_field("signatureDate", "class": "form-control form-control-sm") }}
            </div>

            <!-- Предмет договора. -->
            <div id= "subjectOfContractFormGroup" class="form-group">
                <label for="subjectOfContract" class="control-label">Предмет договора</label>
                {{ text_area("subjectOfContract", "class": "form-control form-control-xl", "rows": 4) }}
                <span class="help-block"></span>
            </div>

            <!-- Объект строительства (стройка). -->
            <div id= "constructionProjectFormGroup" class="form-group">
                <label for="constructionProject" class="control-label">Стройка</label>
                {{ text_field("constructionProject", "class": "form-control form-control-sm") }}
            </div>

            <!-- Заказчик (агент). -->
            <div id= "customerFormGroup" class="form-group">
                <label for="customer" class="control-label">Заказчик (агент)</label>
                {{ text_field("customer", "class": "form-control form-control-xl") }}
            </div>

            <!-- Ответственный филиал. -->
            <div id= "branchFormGroup" class="form-group">
                <label for="branch" class="control-label">Ответственный филиал</label>
                {{ text_field("branch", "class": "form-control form-control-xl") }}
            </div>

            <!-- Статус договора. -->
            <div id= "contractStatusFormGroup" class="form-group">
                <label for="contractStatus" class="control-label">Статус договора</label>
                {{ text_field("contractStatus", "class": "form-control form-control-sm") }}
            </div>

            <!-- Стоимость работ по договору. -->
            <div id="contractCostFormGroup" class="form-group">
                <label for="contractCost" class="control-label">Стоимость работ</label>
                {{ text_field("contractCost", "class": "form-control form-control-sm") }}
            </div>

            <!-- Комментарий. -->
            <div id= "commentFormGroup" class="form-group">
                <label for="comment" class="control-label">Комментарий</label>
                {{ text_area("comment", "class": "form-control form-control-xl", "rows": 4) }}
            </div>

        </div>
    
    </fieldset>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="{{ url("contracts") }}" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
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
