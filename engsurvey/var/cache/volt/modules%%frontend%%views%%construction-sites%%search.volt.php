
<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li><?= $this->tag->linkTo(['construction-projects', 'Стройки']) ?></li>
    <li class="active">Участки работ</li>
</ol>


<div class="content-header">
    <div class="content-title">Участки работ</div>
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


<div class="buttons-block">
    <?= $this->tag->linkTo(['construction-sites/new?construction-project-id=' . $constructionProject->getId(), '<i class="fa fa-plus"></i>&nbsp;Новый участок', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание нового участка работ']) ?>
</div>


<table id="constructionSitesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>ГИП</th>
            <th>Объекты изысканий</th>
            <th>Отчеты</th>
            <th>Комментарий</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($constructionSites as $site) { ?>
            <tr>
                <td><?= $site->getSiteNumber() ?></td>
                <td><?= $site->getName() ?></td>
                <td nowrap>
                    <?php if ($site->getChiefProjectEngineer()) { ?>
                        <?= $site->getChiefProjectEngineer()->getShortName() ?>
                    <?php } ?>
                </td>
                <td>
                    <a href="<?= $this->url->get('survey-facilities?construction-site-id=' . $site->getId()) ?>">
                        Объекты изысканий (<?= $site->getSurveyFacilities()->count() ?>)</a>
                </td>
                <td>
                    <?php if ($site->getReportLink()) { ?>
                        <a href="<?= $site->getReportLink() ?>" target="_blank">Отчет&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    <?php } ?>
                </td>
                <td><?= $site->getComment() ?></td>
                <td>
                    <?php if ($site->getMapLink()) { ?>
                        <a href="<?= $site->getMapLink() ?>" class="btn btn-primary btn-xs" target="_blank"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    <?php } else { ?>
                        <a href="" class="btn btn-primary btn-xs disabled"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    <?php } ?>
                    <a href="<?= $this->url->get('construction-sites/edit?id=' . $site->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil"></i></a>
                    <a href="<?= 'construction-sites/delete?id=' . $site->getId() ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title='Удалить'><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#constructionSitesTable').DataTable({
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
                    'targets': [3],
                    'width': "150px",
                },
                {
                    'targets': [4],
                    'width': "50px",
                },
                {
                    'targets': [6],
                    'className': 'td-nowrap td-align-center',
                    'width': "72px",
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
