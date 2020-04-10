
<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Стройки</li>
</ol>


<div class="content-header">
    <div class="content-title">Стройки</div>
</div>


<?= $this->flashSession->output() ?>


<div class="buttons-block">
    <?= $this->tag->linkTo(['construction-projects/new', '<i class="fa fa-plus"></i>&nbsp;Новая стройка', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание новой стройки']) ?>
</div>


<table id="constructionProjectsTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Шифр</th>
            <th>Наименование</th>
            <th>Вид строительства</th>
            <th>Заказчик (агент)</th>
            <th>Технический директор</th>
            <th>Участки работ</th>
            <th>Отчеты</th>
            <th>Комментарий</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($constructionProjects as $project) { ?>
            <tr>
                <td><?= $project->getCode() ?></td>
                <td><?= $project->getName() ?></td>
                <td>
                    <?php if ($project->getConstructionType()) { ?>
                        <?= $project->getConstructionType()->getName() ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($project->getCustomer()) { ?>
                        <?= $project->getCustomer()->getDisplayName() ?>
                    <?php } ?>
                </td>
                <td nowrap>
                    <?php if ($project->getTechnicalDirector()) { ?>
                        <?= $project->getTechnicalDirector()->getShortName() ?>
                    <?php } ?>
                </td>
                <td>
                    <a href="<?= $this->url->get('construction-sites?construction-project-id=' . $project->getId()) ?>">
                        Участки работ&nbsp;(<?= $project->getConstructionSites()->count() ?>)</a>
                </td>
                <td>
                    <?php if ($project->getReportLink()) { ?>
                        <a href="<?= $project->getReportLink() ?>" target="_blank">Отчет&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    <?php } ?>
                </td>
                <td><?= $project->getComment() ?></td>
                <td>
                    <?php if ($project->getMapLink()) { ?>
                        <a href="<?= $project->getMapLink() ?>" class="btn btn-primary btn-xs" target="_blank"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    <?php } else { ?>
                        <a href="" class="btn btn-primary btn-xs disabled"
                            title='Объект на карте'><i class="fa fa-map-o" aria-hidden="true"></i></a>
                    <?php } ?>
                    <a href="<?= $this->url->get('construction-projects/edit?id=' . $project->getId()) ?>" class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="<?= 'construction-projects/delete?id=' . $project->getId() ?>" class="btn btn-danger btn-xs"
                        title='Удалить'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#constructionProjectsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },
            
            'columnDefs': [
                {
                    'targets': [0],
                    'className': 'td-nowrap td-align-center',
                    'width': "40px",
                },
                {
                    'targets': [5],
                    'width': "110px",
                },   
                {
                    'targets': [6],
                    'width': "50px",
                },
                {
                    'targets': [8],
                    'className': 'td-nowrap td-align-center',
                    'width': "75px",
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
