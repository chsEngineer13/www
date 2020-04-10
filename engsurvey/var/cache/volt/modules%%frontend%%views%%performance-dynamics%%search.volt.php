<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Динамика выполнения работ</li>
</ol>

<div class="content-header">
    <div class="content-title">Динамика выполнения работ</div>
</div>

<?= $this->flashSession->output() ?>

<div class="buttons-block">
    <?= $this->tag->linkTo(['performance-dynamics/new', '<i class="fa fa-plus"></i>&nbsp;Новые отчеты', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание ссылок на отчеты для новой организации']) ?>
</div>

<table id="performanceDynamicsTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>№</th>
            <th>Организания</th>
            <th>Отчет по СИД</th>
            <th>Отчет по ИИ</th>
            <th>Отчет по ЛИ</th>
            <th>Отчет по ДПТ</th>
            <th><!-- Действия --></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($performanceDynamics as $record) { ?>
            <tr>
                <td align="center">
                    <?php if ($record->getSeqNumber()) { ?>
                        <?= $record->getSeqNumber() ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($record->getOrganization()) { ?>
                        <?= $record->getOrganization()->getDisplayName() ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($record->getInitialDataReportLink()) { ?>
                        <a href="<?= $record->getInitialDataReportLink() ?>" target="_blank">Отчет&nbsp;по&nbsp;СИД&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($record->getEngineeringSurveyReportLink()) { ?>
                        <a href="<?= $record->getEngineeringSurveyReportLink() ?>" target="_blank">Отчет&nbsp;по&nbsp;ИИ&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($record->getLaboratoryReportLink()) { ?>
                        <a href="<?= $record->getLaboratoryReportLink() ?>" target="_blank">Отчет&nbsp;по&nbsp;ЛИ&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($record->getTerritoryPlanningReportLink()) { ?>
                        <a href="<?= $record->getTerritoryPlanningReportLink() ?>" target="_blank">Отчет&nbsp;по&nbsp;ДПТ&nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>
                    <?php } ?>
                </td>
                <td>
                    <!-- Действия -->
                    <a href="<?= $this->url->get('performance-dynamics/edit?id=' . $record->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="<?= $this->url->get('performance-dynamics/delete?id=' . $record->getId()) ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#performanceDynamicsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
                {
                    'targets': [0],
                    'width': '20px',
                },
                {
                    'targets': [6],
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