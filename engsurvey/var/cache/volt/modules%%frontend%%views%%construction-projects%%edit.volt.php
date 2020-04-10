
<ol class="breadcrumb">
    <li><a href="<?= $this->url->get() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?= $this->url->get('construction-projects') ?>">Стройки</a></li>
    <li class="active">Редактирование</li>
</ol>


<div class="content-header">
    <div class="content-title">Редактирование стройки</div>
</div>


<?= $this->flashSession->output() ?>


<?= $this->tag->form(['construction-projects/update', 'class' => 'form-dialog form-dialog-md', 'role' => 'form', 'method' => 'post', 'autocomplete' => 'off']) ?>

    <div class="form-dialog-body">
        <?= $form->render('id') ?>
    
        <div id= "codeFormGroup" class="form-group">
            <?= $form->label('code', ['class' => 'control-label']) ?>
            <?= $form->render('code', ['class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <div id= "nameFormGroup" class="form-group">
            <?= $form->label('name', ['class' => 'control-label']) ?>
            <?= $form->render('name', ['class' => 'form-control form-control-xl']) ?>
            <span class="help-block"></span>
        </div>

        <div id= "constructionTypeIdFormGroup" class="form-group">
            <?= $form->label('constructionTypeId', ['class' => 'control-label']) ?>
            <div class="pull-left form-control-xl">
                <?= $form->render('constructionTypeId') ?>
            </div>
            <span class="help-block"></span>
        </div>

        <div id= "customerIdFormGroup" class="form-group"> 
            <?= $form->label('customerId', ['class' => 'control-label']) ?> 
            <div class="pull-left form-control-xl"> 
                <?= $form->render('customerId') ?> 
            </div> 
            <span class="help-block"></span> 
        </div>

        <div id= "technicalDirectorFormGroup" class="form-group">
            <?= $form->label('technicalDirectorId', ['class' => 'control-label']) ?>
            <?= $form->render('technicalDirectorId', ['class' => 'selectpicker form-control-xl']) ?>
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
        <?= $this->tag->linkTo(['construction-projects', 'Закрыть', 'class' => 'btn btn-default', 'title' => 'Выход без сохранения изменений']) ?>
        <?= $this->tag->submitButton(['Сохранить', 'class' => 'btn btn-primary', 'title' => 'Сохранение внесенных изменений']) ?>
    </div>

<?= $this->tag->endForm() ?>



<script>

    
    function addErrorMessages() {
        <?php if (isset($codeMessages)) { ?>
            $("#codeFormGroup").addClass("has-error");
            $("#codeFormGroup .help-block").text("<?= $codeMessages ?>");
        <?php } ?>

        <?php if (isset($nameMessages)) { ?>
            $("#nameFormGroup").addClass("has-error");
            $("#nameFormGroup .help-block").text("<?= $nameMessages ?>");
        <?php } ?>
        
        <?php if (isset($customerIdMessages)) { ?>
            $("#customerIdFormGroup").addClass("has-error");
            $("#customerIdFormGroup .help-block").text("<?= $customerIdMessages ?>");
        <?php } ?>
    };

    $(document).ready(function() {

        addErrorMessages();

    });

</script>
