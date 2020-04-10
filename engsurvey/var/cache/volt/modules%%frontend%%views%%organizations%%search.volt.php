
<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Организации</li>
</ol>


<div class="content-header">
    <div class="content-title">Организации</div>
</div>


<?= $this->flashSession->output() ?>


<div class="btn-toolbar margin-bottom-medium" role="toolbar">
    <?= $this->tag->linkTo(['organizations/new', '<i class="fa fa-plus"></i>&nbsp;Новая', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание новой организации']) ?>
</div>

<hr>


<table id="organizationsTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Представление</th>
            <th>Сокращенное наименование</th>
            <th>Полное наименование</th>
            <th>Дополнительная информация</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($organizations as $organization) { ?>
            <tr>
                <td>
                    <a href="<?= $this->url->get('organizations/edit?id=' . $organization->getId()) ?>">
                        <?= $organization->getDisplayName() ?></a>
                </td>
                <td><?= $organization->getShortName() ?></td>
                <td><?= $organization->getFullName() ?></td>
                <td><?= $organization->getAdditionalInfo() ?></td>
                <td>
                    <a href="<?= $this->url->get('organizations/edit?id=' . $organization->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil"></i></a>
                    <a href="<?= 'organizations/delete?id=' . $organization->getId() ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title='Удалить'><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#organizationsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
                {
                    'targets': [4],
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
