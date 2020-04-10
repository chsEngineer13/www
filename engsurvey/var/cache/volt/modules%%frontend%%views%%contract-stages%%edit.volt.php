<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><a href="<?= $this->url->get() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?= $this->url->get('contracts') ?>">Договоры</a></li>
    <li><a href="<?= $this->url->get('contract-stages?contract_id=' . $contract->getId()) ?>">Этапы работ</a></li>
    <li class="active">Редактирование</li>
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

<h3>Редактирование свойств этапа работ</h3>

<!-- Форма -->
<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="<?= $this->url->get('contract-stages/update') ?>"  method="post">

    <div class="form-dialog-body">
        <!-- Уникальный идентификатор этапа работ. -->
        <?= $this->tag->hiddenField(['id']) ?>

        <!-- Уникальный идентификатор договора. -->
        <?= $this->tag->hiddenField(['contractId']) ?>
        
        <!-- Номер раздела календарного плана. -->
        <div id="sectionNumberFormGroup" class="form-group">
            <label for="sectionNumber" class="control-label">Номер раздела КП</label>
            <?= $this->tag->textField(['sectionNumber', 'class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <!-- Наименование раздела календарного плана. -->
        <div id= "sectionNameFormGroup" class="form-group">
            <label for="sectionName" class="control-label">Наименование раздела КП</label>
            <?= $this->tag->textArea(['sectionName', 'class' => 'form-control form-control-xl', 'rows' => 3]) ?>
            <span class="help-block"></span>
        </div>

        <!-- Номер этапа работ. -->
        <div id="stageNumberFormGroup" class="form-group">
            <label for="stageNumber" class="control-label">Номер этапа&nbsp;*</label>
            <?= $this->tag->textField(['stageNumber', 'class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <!-- Наименование работ (этапа работ). -->
        <div id= "stageNameFormGroup" class="form-group">
            <label for="stageName" class="control-label">Наименование работ (этапа работ)&nbsp;*</label>
            <?= $this->tag->textArea(['stageName', 'class' => 'form-control form-control-xl', 'rows' => 3]) ?>
            <span class="help-block"></span>
        </div>

        <!-- Идентификатор участка работ. -->
        <div id="constructionSiteFormGroup" class="form-group">
            <label for="constructionSiteId" class="control-label">Участок работ</label>
            <?= $this->tag->select(['constructionSiteId', $constructionSites, 'using' => ['id', 'name'], 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '', 'class' => 'selectpicker form-control-xl', 'data-live-search' => true]) ?>
            <span class="help-block"></span>
        </div>

        <!-- Дата начала работ. -->
        <div id="startDateFormGroup" class="form-group">
            <label for="startDate" class="control-label">Начало работ</label>
            <div class="input-group date datepicker datepicker__size_sm">
                <?= $this->tag->textField(['startDate', 'class' => 'form-control']) ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span class="help-block"></span>
        </div>

        <!-- Дата окончания работ. -->
        <div id="endDateFormGroup" class="form-group">
            <label for="endDate" class="control-label">Окончание работ</label>
            <div class="input-group date datepicker datepicker__size_sm">
                <?= $this->tag->textField(['endDate', 'class' => 'form-control']) ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span class="help-block"></span>
        </div>

        <!-- Стоимость работ без НДС. -->
        <div id="costWithoutVatFormGroup" class="form-group">
            <label for="costWithoutVat" class="control-label">Стоимость без НДС</label>
            <?= $this->tag->textField(['costWithoutVat', 'class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <!-- НДС -->
        <div id="vatFormGroup" class="form-group">
            <label for="vat" class="control-label">НДС</label>
            <?= $this->tag->textField(['vat', 'class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <!-- Стоимость работ с учетом НДС. -->
        <div id="costWithVatFormGroup" class="form-group">
            <label for="costWithVat" class="control-label">Стоимость с НДС</label>
            <?= $this->tag->textField(['costWithVat', 'class' => 'form-control form-control-sm']) ?>
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
        <a href="<?= $this->url->get('contract-stages?contract_id=' . $contract->getId()) ?>"
            class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Отмена</a>
        <button type="submit" class="btn btn-primary"
            title="Сохранение внесенных изменений">Сохранить</button>
    </div>

</form>


<script>

    /**
     * Добавляет сообщения об ошибках к полям формы.
     */
    function addErrorMessages() {
        // Номер этапа работ.
        <?php if (isset($stageNumberMessage)) { ?>
            $("#stageNumberFormGroup").addClass("has-error");
            $("#stageNumberFormGroup .help-block").text("<?= $stageNumberMessage ?>");
        <?php } ?>

        // Наименование работ (этапа работ).
        <?php if (isset($stageNameMessage)) { ?>
            $("#stageNameFormGroup").addClass("has-error");
            $("#stageNameFormGroup .help-block").text("<?= $stageNameMessage ?>");
        <?php } ?>

        // Дата начала работ.
        <?php if (isset($startDateMessage)) { ?>
            $("#startDateFormGroup").addClass("has-error");
            $("#startDateFormGroup .help-block").text("<?= $startDateMessage ?>");
        <?php } ?>

        // Дата окончания работ.
        <?php if (isset($endDateMessage)) { ?>
            $("#endDateFormGroup").addClass("has-error");
            $("#endDateFormGroup .help-block").text("<?= $endDateMessage ?>");
        <?php } ?>

        // Стоимость работ без НДС.
        <?php if (isset($costWithoutVatMessage)) { ?>
            $("#costWithoutVatFormGroup").addClass("has-error");
            $("#costWithoutVatFormGroup .help-block").text("<?= $costWithoutVatMessage ?>");
        <?php } ?>

        // НДС
        <?php if (isset($vatMessage)) { ?>
            $("#vatFormGroup").addClass("has-error");
            $("#vatFormGroup .help-block").text("<?= $vatMessage ?>");
        <?php } ?>

        // Стоимость работ с учетом НДС.
        <?php if (isset($costWithVatMessage)) { ?>
            $("#costWithVatFormGroup").addClass("has-error");
            $("#costWithVatFormGroup .help-block").text("<?= $costWithVatMessage ?>");
        <?php } ?>
     };


    /**
     * Скрипт выполняемый после загрузки файла.
     */
    $(document).ready(function() {
        addErrorMessages();
    });

</script>
