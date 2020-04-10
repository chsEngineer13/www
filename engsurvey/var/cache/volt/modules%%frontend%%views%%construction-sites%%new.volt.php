
<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li><?= $this->tag->linkTo(['construction-projects', 'Стройки']) ?></li>
    <li><?= $this->tag->linkTo(['construction-sites?construction-project-id=' . $constructionProject->getId(), 'Участки работ']) ?></li>
    <li class="active">Новый</li>
</ol>


<div class="content-header">
    <div class="content-title">Новый участок</div>
</div>


<div class="objects-block">
    <div class="objects-block__item">
        <div class="objects-block__item-label">Стройка:</div>
        <div class="objects-block__item-name">
            <?= $constructionProject->getName() . ' (Шифр ' . $constructionProject->getCode() . ')' ?>
        </div>
    </div>
</div>


<?= $this->flashSession->output() ?>


<?= $this->tag->form(['construction-sites/create', 'class' => 'form-dialog form-dialog-md', 'role' => 'form', 'method' => 'post', 'autocomplete' => 'off']) ?>

    <div class="form-dialog-body">
        <?= $form->render('constructionProjectId') ?>

        <div id= "siteNumberFormGroup" class="form-group">
            <?= $form->label('siteNumber', ['class' => 'control-label']) ?>
            <?= $form->render('siteNumber', ['class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <div id= "nameFormGroup" class="form-group">
            <?= $form->label('name', ['class' => 'control-label']) ?>
            <?= $form->render('name', ['class' => 'form-control form-control-xl']) ?>
            <span class="help-block"></span>
        </div>
        
        <div id= "chiefProjectEngineerFormGroup" class="form-group">
            <?= $form->label('chiefProjectEngineerId', ['class' => 'control-label']) ?>
            <?= $form->render('chiefProjectEngineerId', ['class' => 'selectpicker form-control-xl']) ?>
        </div>
        
        <div id= "reportLinkFormGroup" class="form-group">
            <?= $form->label('reportLink', ['class' => 'control-label']) ?>
            <?= $form->render('reportLink', ['class' => 'form-control form-control-xl']) ?>
        </div>
        
        <div id= "mapLinkFormGroup" class="form-group">
            <?= $form->label('mapLink', ['class' => 'control-label']) ?>
            <?= $form->render('mapLink', ['class' => 'form-control form-control-xl']) ?>
        </div>

        <div id= "commentFormGroup" class="form-group">
            <?= $form->label('comment', ['class' => 'control-label']) ?>
            <?= $form->render('comment', ['class' => 'form-control form-control-xl']) ?>
        </div>
    </div>

    <div class="form-dialog-footer">
        <?= $this->tag->linkTo(['construction-sites?construction-project-id=' . $constructionProject->getId(), 'Закрыть', 'class' => 'btn btn-default', 'title' => 'Выход без сохранения изменений']) ?>
        <?= $this->tag->submitButton(['Создать', 'class' => 'btn btn-primary', 'title' => 'Создание нового участка']) ?>
    </div>

<?= $this->tag->endForm() ?>



<script>
    
    function addErrorMessages() {
        <?php if (isset($siteNumberMessages)) { ?>
            $("#siteNumberFormGroup").addClass("has-error");
            $("#siteNumberFormGroup .help-block").text("<?= $siteNumberMessages ?>");
        <?php } ?>

        <?php if (isset($nameMessages)) { ?>
            $("#nameFormGroup").addClass("has-error");
            $("#nameFormGroup .help-block").text("<?= $nameMessages ?>");
        <?php } ?>
    };

    $(document).ready(function() {
        addErrorMessages();
    });
</script>