<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Распределение бригад</li>
</ol>

<div class="content-header">
    <div class="content-title">Распределение бригад по объектам</div>
</div>

<?= $this->flashSession->output() ?>

<div class="buttons-block">
    <?= $this->tag->linkTo(['crew-assignments/new', '<i class="fa fa-plus"></i>&nbsp;Новое назначение', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание нового назначения']) ?>
</div>

<table id="crewAssignmentsTable" class="display table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Филиал</th>
            <th>Бригада</th>
            <th>Стройка</th>
            <th>Участок работ</th>
            <th>Начало работ</th>
            <th>Завершение работ</th>
            <th>Комментарий</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($crewAssignments as $assignment) { ?>
            <tr>
                <td><?= $assignment->getBranch()->getDisplayName() ?></td>
                <?php if ($assignment->getCrew()->getHeadShortName()) { ?>
                    <td><?= $assignment->getCrew()->getCrewName() . ' (' . $assignment->getCrew()->getHeadShortName() . ')' ?></td>
                <?php } else { ?>
                    <td><?= $assignment->getCrew()->getCrewName() ?></td>
                <?php } ?>
                <td><?= $assignment->getConstructionProject()->getCode() ?></td>
                <td><?= $assignment->getConstructionSite()->getSiteNumber() ?></td>
                <td><?= $assignment->getFormattedStartDate('d.m.Y') ?></td>
                <td><?= $assignment->getFormattedEndDate('d.m.Y') ?></td>
                <td><?= $assignment->getComment() ?></td>
                <td>
                    <a href="<?= $this->url->get('crew-assignments/edit?id=' . $assignment->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="<?= $this->url->get('crew-assignments/delete?id=' . $assignment->getId()) ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<script type="text/javascript">
    
    $(document).ready(function() {
        $.fn.dataTable.moment( 'DD.MM.YYYY' );
        var table = $('#crewAssignmentsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
                {
                    'targets': [7],
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
