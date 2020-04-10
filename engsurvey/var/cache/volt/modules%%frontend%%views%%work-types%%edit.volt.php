<ol class="breadcrumb">
    <li><a href="<?= $this->url->get() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?= $this->url->get('work-types') ?>">Виды работ</a></li>
    <li class="active">Редактирование</li>
</ol>

<div class="content-header">
    <div class="content-title">Редактирование вида работ</div>
</div>

<?= $this->flashSession->output() ?>

<form class="form-dialog form-dialog-md" role="form" autocomplete="off" 
    action="<?= $this->url->get('work-types/update') ?>"  method="post">

    <div class="form-dialog-body">
        
        <?= $form->render('id') ?>
    
        
        <div id= "surveyTypeFormGroup" class="form-group">
            <?= $form->label('surveyTypeId', ['class' => 'control-label']) ?>
            <div class="pull-left form-control-xl"> 
                <?= $form->render('surveyTypeId', ['class' => 'form-control selectpicker']) ?>
            </div> 
            <span class="help-block"></span>
        </div>

        
        <div id= "nameFormGroup" class="form-group">
            <?= $form->label('name', ['class' => 'control-label']) ?>
            <?= $form->render('name', ['class' => 'form-control form-control-xl']) ?>
            <span class="help-block"></span>
        </div>

        
        <div id= "shortNameFormGroup" class="form-group">
            <?= $form->label('shortName', ['class' => 'control-label']) ?>
            <?= $form->render('shortName', ['class' => 'form-control form-control-xl']) ?>
            <span class="help-block"></span>
        </div>

        
        <div id= "unitFormGroup" class="form-group">
            <?= $form->label('unitId', ['class' => 'control-label']) ?>
            <div class="pull-left form-control-md"> 
                <?= $form->render('unitId', ['class' => 'form-control selectpicker']) ?>
            </div> 
            <span class="help-block"></span>
        </div>

        
        <div id= "productionRateFormGroup" class="form-group">
            <?= $form->label('productionRate', ['class' => 'control-label']) ?>
            <?= $form->render('productionRate', ['class' => 'form-control form-control-sm']) ?>
            <span class="help-block"></span>
        </div>

        <fieldset>
            <legend>Состав исполнителей</legend>
        
            
            <div id= "numderOfEngineersFormGroup" class="form-group">
                <?= $form->label('numderOfEngineers', ['class' => 'control-label']) ?>
                <?= $form->render('numderOfEngineers', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "numderOfWorkersFormGroup" class="form-group">
                <?= $form->label('numderOfWorkers', ['class' => 'control-label']) ?>
                <?= $form->render('numderOfWorkers', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "numderOfDriversFormGroup" class="form-group">
                <?= $form->label('numderOfDrivers', ['class' => 'control-label']) ?>
                <?= $form->render('numderOfDrivers', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "numderOfDrillersFormGroup" class="form-group">
                <?= $form->label('numderOfDrillers', ['class' => 'control-label']) ?>
                <?= $form->render('numderOfDrillers', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Зональные коэффициенты</legend>

            
            <div id= "zfTaigaFormGroup" class="form-group">
                <?= $form->label('zfTaiga', ['class' => 'control-label']) ?>
                <?= $form->render('zfTaiga', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "zfForestTundraFormGroup" class="form-group">
                <?= $form->label('zfForestTundra', ['class' => 'control-label']) ?>
                <?= $form->render('zfForestTundra', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "zfTundraFormGroup" class="form-group">
                <?= $form->label('zfTundra', ['class' => 'control-label']) ?>
                <?= $form->render('zfTundra', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "zfForestSteppeFormGroup" class="form-group">
                <?= $form->label('zfForestSteppe', ['class' => 'control-label']) ?>
                <?= $form->render('zfForestSteppe', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Сезонные коэффициенты</legend>
        
            
            <div id= "sfSummerFormGroup" class="form-group">
                <?= $form->label('sfSummer', ['class' => 'control-label']) ?>
                <?= $form->render('sfSummer', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "sfAutumnSpringFormGroup" class="form-group">
                <?= $form->label('sfAutumnSpring', ['class' => 'control-label']) ?>
                <?= $form->render('sfAutumnSpring', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>

            
            <div id= "sfWinterFormGroup" class="form-group">
                <?= $form->label('sfWinter', ['class' => 'control-label']) ?>
                <?= $form->render('sfWinter', ['class' => 'form-control form-control-sm']) ?>
                <span class="help-block"></span>
            </div>
            
        </fieldset>

        
        <div id= "commentFormGroup" class="form-group">
            <?= $form->label('comment', ['class' => 'control-label']) ?>
            <?= $form->render('comment', ['class' => 'form-control form-control-xl']) ?>
        </div>
    </div>
    
    <div class="form-dialog-footer">
        <a href="<?= $this->url->get('work-types') ?>" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
        <button type="submit" class="btn btn-primary"
            title="Сохранение внесенных изменений">Сохранить</button>
    </div>
</form>


<script>
    
    function addErrorMessages() {
        <?php if (isset($surveyTypeMessages)) { ?>
            $("#surveyTypeFormGroup").addClass("has-error");
            $("#surveyTypeFormGroup .help-block").text("<?= $surveyTypeMessages ?>");
        <?php } ?>

        <?php if (isset($nameMessages)) { ?>
            $("#nameFormGroup").addClass("has-error");
            $("#nameFormGroup .help-block").text("<?= $nameMessages ?>");
        <?php } ?>

        <?php if (isset($shortNameMessages)) { ?>
            $("#shortNameFormGroup").addClass("has-error");
            $("#shortNameFormGroup .help-block").text("<?= $shortNameMessages ?>");
        <?php } ?>
        
        <?php if (isset($unitMessages)) { ?>
            $("#unitFormGroup").addClass("has-error");
            $("#unitFormGroup .help-block").text("<?= $unitMessages ?>");
        <?php } ?>

        <?php if (isset($productionRateMessages)) { ?>
            $("#productionRateFormGroup").addClass("has-error");
            $("#productionRateFormGroup .help-block").text("<?= $productionRateMessages ?>");
        <?php } ?>

        <?php if (isset($numderOfEngineersMessages)) { ?>
            $("#numderOfEngineersFormGroup").addClass("has-error");
            $("#numderOfEngineersFormGroup .help-block").text("<?= $numderOfEngineersMessages ?>");
        <?php } ?>

        <?php if (isset($numderOfWorkersMessages)) { ?>
            $("#numderOfWorkersFormGroup").addClass("has-error");
            $("#numderOfWorkersFormGroup .help-block").text("<?= $numderOfWorkersMessages ?>");
        <?php } ?>

        <?php if (isset($numderOfDriversMessages)) { ?>
            $("#numderOfDriversFormGroup").addClass("has-error");
            $("#numderOfDriversFormGroup .help-block").text("<?= $numderOfDriversMessages ?>");
        <?php } ?>

        <?php if (isset($numderOfDrillersMessages)) { ?>
            $("#numderOfDrillersFormGroup").addClass("has-error");
            $("#numderOfDrillersFormGroup .help-block").text("<?= $numderOfDrillersMessages ?>");
        <?php } ?>

        <?php if (isset($zfTaigaMessages)) { ?>
            $("#zfTaigaFormGroup").addClass("has-error");
            $("#zfTaigaFormGroup .help-block").text("<?= $zfTaigaMessages ?>");
        <?php } ?>

        <?php if (isset($zfForestTundraMessages)) { ?>
            $("#zfForestTundraFormGroup").addClass("has-error");
            $("#zfForestTundraFormGroup .help-block").text("<?= $zfForestTundraMessages ?>");
        <?php } ?>

        <?php if (isset($zfTundraMessages)) { ?>
            $("#zfTundraFormGroup").addClass("has-error");
            $("#zfTundraFormGroup .help-block").text("<?= $zfTundraMessages ?>");
        <?php } ?>

        <?php if (isset($zfForestSteppeMessages)) { ?>
            $("#zfForestSteppeFormGroup").addClass("has-error");
            $("#zfForestSteppeFormGroup .help-block").text("<?= $zfForestSteppeMessages ?>");
        <?php } ?>

        <?php if (isset($sfSummerMessages)) { ?>
            $("#sfSummerFormGroup").addClass("has-error");
            $("#sfSummerFormGroup .help-block").text("<?= $sfSummerMessages ?>");
        <?php } ?>

        <?php if (isset($sfAutumnSpringMessages)) { ?>
            $("#sfAutumnSpringFormGroup").addClass("has-error");
            $("#sfAutumnSpringFormGroup .help-block").text("<?= $sfAutumnSpringMessages ?>");
        <?php } ?>

        <?php if (isset($sfWinterMessages)) { ?>
            $("#sfWinterFormGroup").addClass("has-error");
            $("#sfWinterFormGroup .help-block").text("<?= $sfWinterMessages ?>");
        <?php } ?>
    };

    $(document).ready(function() {
        addErrorMessages();
    });
</script>
