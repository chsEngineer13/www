
<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li><?= $this->tag->linkTo(['construction-projects', 'Стройки']) ?></li>
    <li><?= $this->tag->linkTo(['construction-sites?construction-project-id=' . $constructionProject->getId(), 'Участки работ']) ?></li>
    <li class="active">Объекты изысканий</li>
</ol>


<div class="content-header">
    <div class="content-title">Объекты изысканий</div>
</div>


<div class="objects-block">
    <div class="objects-block__item">
        <div class="objects-block__item-label">Стройка:</div>
        <div class="objects-block__item-name">
            <?= $constructionProject->getName() . ' (Шифр ' . $constructionProject->getCode() . ')' ?>
        </div>
    </div>
    <div class="objects-block__item">
        <div class="objects-block__item-label">Участок работ:</div>
        <div class="objects-block__item-name">
            <?= $constructionSite->getName() ?>
        </div>
    </div>
</div>


<?= $this->flashSession->output() ?>


<div class="buttons-block">
    <?= $this->tag->linkTo(['survey-facilities/new?construction-site-id=' . $constructionSite->getId(), '<i class="fa fa-plus"></i>&nbsp;Новый объект', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание нового объекта изысканий']) ?>

    <a href="#" id="importObjectsXlsxButton" class="btn btn-default"
        title="Импорт объектов изысканий из файла MS Excel (XLSX)">
        <i class="fa fa-file-excel-o"></i>&nbsp;Импорт&nbsp;</a>
</div>


<table id="surveyFacilitiesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>Обозначение</th>
            <th>Номер</th>
            <th>Этап работ</th>
            <th>Комментарий</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($surveyFacilities as $facility) { ?>
            <tr>
                <td><?= $facility->getSequenceNumber() ?></td>
                <td><?= $facility->getFacilityName() ?></td>
                <td><?= $facility->getFacilityDesignation() ?></td>
                <td><?= $facility->getFacilityNumber() ?></td>
                <td><?= $facility->getStageOfWorks() ?></td>
                <td><?= $facility->getComment() ?></td>
                <td>
                    <a href="<?= $this->url->get('survey-facilities/edit?id=' . $facility->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil"></i></a>
                    <a href="<?= 'survey-facilities/delete?id=' . $facility->getId() ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title='Удалить'><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<?= $this->partial('survey-facilities/import-objects-xlsx-modal') ?>


<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#surveyFacilitiesTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
                {
                    'targets': [0],
                    'className': 'td-nowrap td-align-center',
                    'width': "15px",
                },
                {
                    'targets': [6],
                    'className': 'td-nowrap td-align-center',
                    'width': "48px",
                    'sortable': false,
                }
            ],

            'order': [[ 0, 'asc' ]],

            "stateSave": true,
        });

        new $.fn.dataTable.FixedHeader( table, {
            'header': true,
            'headerOffset': 50
        });
    });
</script>
