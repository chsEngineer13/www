<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="<?= $this->url->get() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?= $this->url->get('contracts') ?>">Договоры</a></li>
    <li class="active">Редактирование</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title"><?= 'Редактирование договора № ' . $contract->getContractNumber() ?></div>
    <?php if ($contract->getSupplementalAgreementNumber()) { ?>
        <div class="content-subtitle"><?= 'Дополнительное соглашение № ' . $contract->getSupplementalAgreementNumber() ?></div>
    <?php } ?>
</div>


<!-- Информационные сообщения -->
<?= $this->flashSession->output() ?>

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="<?= $this->url->get('contracts/update') ?>"  method="post">

    <div class="form-dialog-body">
        <!-- Идентификатор договора. -->
        <?= $this->tag->hiddenField(['id']) ?>
    
        <!-- Номер договора. -->
        <div id="contractNumberFormGroup" class="form-group">
            <label for="contractNumber" class="control-label">Номер договора&nbsp;*</label>
            <?= $this->tag->textField(['contractNumber', 'class' => 'form-control form-control-xl']) ?>
            <span class="help-block"></span>
        </div>
        
        <!-- Номер дополнительного соглашения. -->
        <div id="supplementalAgreementNumberFormGroup" class="form-group">
            <label for="supplementalAgreementNumber" class="control-label">Номер ДС</label>
            <?= $this->tag->textField(['supplementalAgreementNumber', 'class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <!-- Дата подписания договора. -->
        <div id="signatureDateFormGroup" class="form-group">
            <label for="signatureDate" class="control-label">Дата подписания</label>
            <div class="input-group date datepicker datepicker__size_sm">
                <?= $this->tag->textField(['signatureDate', 'class' => 'form-control']) ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span class="help-block"></span>
        </div>

        <!-- Предмет договора. -->
        <div id= "subjectOfContractFormGroup" class="form-group">
            <label for="subjectOfContract" class="control-label">Предмет договора&nbsp;*</label>
            <?= $this->tag->textArea(['subjectOfContract', 'class' => 'form-control form-control-xl', 'rows' => 3]) ?>
            <span class="help-block"></span>
        </div>

        <!-- Объект строительства (стройка). -->
        <div id= "constructionProjectFormGroup" class="form-group">
            <label for="constructionProjectId" class="control-label">Стройка&nbsp;*</label>
            <?= $this->tag->select(['constructionProjectId', $constructionProjects, 'using' => ['id', 'code'], 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '', 'class' => 'selectpicker form-control-sm', 'data-live-search' => true]) ?>
            <span class="help-block"></span>
        </div>

        <!-- Заказчик (агент). -->
        <div id= "customerFormGroup" class="form-group">
            <label for="customerId" class="control-label">Заказчик (агент)&nbsp;*</label>
            <?= $this->tag->select(['customerId', $organizations, 'using' => ['id', 'displayName'], 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '', 'class' => 'selectpicker form-control-xl', 'data-live-search' => true]) ?>
            <span class="help-block"></span>
        </div>

        <!-- Ответственный филиал. -->
        <div id= "branchFormGroup" class="form-group">
            <label for="branchId" class="control-label">Ответственный филиал</label>
            <?= $this->tag->select(['branchId', $branches, 'using' => ['id', 'displayName'], 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '', 'class' => 'selectpicker form-control-xl', 'data-live-search' => true]) ?>
            <span class="help-block"></span>
        </div>

        <!-- Статус договора. -->
        <div id= "contractStatusFormGroup" class="form-group">
            <label for="contractStatusId" class="control-label">Статус договора&nbsp;*</label>
            <?= $this->tag->select(['contractStatusId', $contractStatuses, 'using' => ['id', 'name'], 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '', 'class' => 'selectpicker form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <!-- Стоимость работ по договору. -->
        <div id="contractCostFormGroup" class="form-group">
            <label for="contractCost" class="control-label">Стоимость работ</label>
            <?= $this->tag->textField(['contractCost', 'class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <!-- Комментарий. -->
        <div id= "commentFormGroup" class="form-group">
            <label for="comment" class="control-label">Комментарий</label>
            <?= $this->tag->textArea(['comment', 'class' => 'form-control form-control-xl', 'rows' => 3]) ?>
        </div>

    </div>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="<?= $this->url->get('contracts') ?>" class="btn btn-default" role="button"
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
        <?php if (isset($contractNumberMessages)) { ?>
            $("#contractNumberFormGroup").addClass("has-error");
            $("#contractNumberFormGroup .help-block").text("<?= $contractNumberMessages ?>");
        <?php } ?>

        // Дата подписания договора.
        <?php if (isset($signatureDateMessages)) { ?>
            $("#signatureDateFormGroup").addClass("has-error");
            $("#signatureDateFormGroup .help-block").text("<?= $signatureDateMessages ?>");
        <?php } ?>

        // Предмет договора.
        <?php if (isset($subjectOfContractMessages)) { ?>
            $("#subjectOfContractFormGroup").addClass("has-error");
            $("#subjectOfContractFormGroup .help-block").text("<?= $subjectOfContractMessages ?>");
        <?php } ?>

        // Объект строительства (стройка).
        <?php if (isset($constructionProjectMessages)) { ?>
            $("#constructionProjectFormGroup").addClass("has-error");
            $("#constructionProjectFormGroup .help-block").text("<?= $constructionProjectMessages ?>");
        <?php } ?>

        // Заказчик (агент).
        <?php if (isset($customerMessages)) { ?>
            $("#customerFormGroup").addClass("has-error");
            $("#customerFormGroup .help-block").text("<?= $customerMessages ?>");
        <?php } ?>
    
        // Ответственный филиал.
        <?php if (isset($branchMessages)) { ?>
            $("#branchFormGroup").addClass("has-error");
            $("#branchFormGroup .help-block").text("<?= $branchMessages ?>");
        <?php } ?>
        
        // Статус договора.
        <?php if (isset($contractStatusMessages)) { ?>
            $("#contractStatusFormGroup").addClass("has-error");
            $("#contractStatusFormGroup .help-block").text("<?= $contractStatusMessages ?>");
        <?php } ?>

        // Стоимость работ по договору.
        <?php if (isset($contractCostMessages)) { ?>
            $("#contractCostFormGroup").addClass("has-error");
            $("#contractCostFormGroup .help-block").text("<?= $contractCostMessages ?>");
        <?php } ?>
    };
    

    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });
</script>
