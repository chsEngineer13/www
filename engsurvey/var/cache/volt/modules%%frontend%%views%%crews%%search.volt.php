
<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Бригады</li>
</ol>


<div class="content-header">
    <div class="content-title">Бригады</div>
</div>


<?= $this->flashSession->output() ?>


<div class="buttons-block">
    <?= $this->tag->linkTo(['crews/new', '<i class="fa fa-plus"></i>&nbsp;Новая', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание новой бригады']) ?>
</div>


<table id="crewsTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Филиал</th>
            <th>Вид бригады</th>
            <th>Название</th>
            <th>Руководитель</th>
            <th>Телефон</th>
            <th>Эл. почта</th>
            <th><abbr title="Численность бригады">Числ.</abbr></th>
            <th>Отчеты</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($crews as $crew) { ?>
            <tr>
                <td><?= $crew->getBranch()->getDisplayName() ?></td>
                <td><?= $crew->getCrewType()->getShortName() ?></td>
                <td><?= $crew->getCrewName() ?></td>
                <td><abbr title="<?= $crew->getHeadFullName() ?>"><?= $crew->getHeadShortName() ?></abbr></td>
                <td><?= $crew->getPhone() ?></td>
                <td><?= $crew->getEmail() ?></td>
                <td><?= $crew->getNumberOfCrew() ?></td>
                <td>
                    <?php if ($crew->getReportLink()) { ?>
                        <a href="<?= $crew->getReportLink() ?>" target="_blank">Отчет&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    <?php } ?>
                </td>
                <td>
                    <a href="<?= $this->url->get('crews/edit?id=' . $crew->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="<?= $this->url->get('crews/delete?id=' . $crew->getId()) ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#crewsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Все"]],

            'columnDefs': [
                {
                    'targets': [6],
                    'width': "30px",
                },
                {
                    'targets': [7],
                    'width': "50px",
                },
                {
                    'targets': [8],
                    'className': 'td-nowrap td-align-center',
                    'width': "48px",
                    'sortable': false,
                }
            ],

            'order': [[ 0, 'asc' ]],

            'stateSave': true,
        });

        new $.fn.dataTable.FixedHeader( table, {
            'header': true,
            'headerOffset': 50
        });

    });
</script>
