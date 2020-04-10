<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="<?= $this->url->get() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?= $this->url->get('contracts') ?>">Договоры</a></li>
    <li class="active">Свойства</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title"><?= 'Договор № ' . $contract->getContractNumber() ?></div>
    <?php if ($contract->getSupplementalAgreementNumber()) { ?>
        <div class="content-subtitle"><?= 'Дополнительное соглашение № ' . $contract->getSupplementalAgreementNumber() ?></div>
    <?php } ?>
</div>

<!-- Информационные сообщения -->
<?= $this->flashSession->output() ?>

<!-- Вкладки -->
<div style="margin-bottom: 20px">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?= $this->url->get('contracts/properties?id=' . $contract->getId()) ?>">Свойства</a></li>
        <li><a href="<?= $this->url->get('contract-files?contract_id=' . $contract->getId()) ?>">Файлы</a></li>
        <li><a href="<?= $this->url->get('contract-stages?contract_id=' . $contract->getId()) ?>">Календарный план</a></li>
    </ul>
</div>

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off">

    <fieldset>

        <div class="form-dialog-body">
            <!-- Идентификатор договора. -->
            <?= $this->tag->hiddenField(['id']) ?>
        
            <!-- Номер договора. -->
            <div class="form-group">
                <label for="contractNumber" class="control-label">Номер договора</label>
                <?= $this->tag->textField(['contractNumber', 'class' => 'form-control form-control-xl']) ?>
            </div>
            
            <!-- Номер дополнительного соглашения. -->
            <div class="form-group">
                <label for="supplementalAgreementNumber" class="control-label">Номер ДС</label>
                <?= $this->tag->textField(['supplementalAgreementNumber', 'class' => 'form-control form-control-sm']) ?>
            </div>

            <!-- Дата подписания договора. -->
            <div id="signatureDateFormGroup" class="form-group">
                <label for="signatureDate" class="control-label">Дата подписания</label>
                <?= $this->tag->textField(['signatureDate', 'class' => 'form-control form-control-sm']) ?>
            </div>

            <!-- Предмет договора. -->
            <div id= "subjectOfContractFormGroup" class="form-group">
                <label for="subjectOfContract" class="control-label">Предмет договора</label>
                <?= $this->tag->textArea(['subjectOfContract', 'class' => 'form-control form-control-xl', 'rows' => 4]) ?>
                <span class="help-block"></span>
            </div>

            <!-- Объект строительства (стройка). -->
            <div id= "constructionProjectFormGroup" class="form-group">
                <label for="constructionProject" class="control-label">Стройка</label>
                <?= $this->tag->textField(['constructionProject', 'class' => 'form-control form-control-sm']) ?>
            </div>

            <!-- Заказчик (агент). -->
            <div id= "customerFormGroup" class="form-group">
                <label for="customer" class="control-label">Заказчик (агент)</label>
                <?= $this->tag->textField(['customer', 'class' => 'form-control form-control-xl']) ?>
            </div>

            <!-- Ответственный филиал. -->
            <div id= "branchFormGroup" class="form-group">
                <label for="branch" class="control-label">Ответственный филиал</label>
                <?= $this->tag->textField(['branch', 'class' => 'form-control form-control-xl']) ?>
            </div>

            <!-- Статус договора. -->
            <div id= "contractStatusFormGroup" class="form-group">
                <label for="contractStatus" class="control-label">Статус договора</label>
                <?= $this->tag->textField(['contractStatus', 'class' => 'form-control form-control-sm']) ?>
            </div>

            <!-- Стоимость работ по договору. -->
            <div id="contractCostFormGroup" class="form-group">
                <label for="contractCost" class="control-label">Стоимость работ</label>
                <?= $this->tag->textField(['contractCost', 'class' => 'form-control form-control-sm']) ?>
            </div>

            <!-- Комментарий. -->
            <div id= "commentFormGroup" class="form-group">
                <label for="comment" class="control-label">Комментарий</label>
                <?= $this->tag->textArea(['comment', 'class' => 'form-control form-control-xl', 'rows' => 4]) ?>
            </div>

        </div>
    
    </fieldset>

    <!-- Действия. -->
    <div class="form-dialog-footer">
        <a href="<?= $this->url->get('contracts') ?>" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
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
