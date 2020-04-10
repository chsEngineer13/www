<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Единицы измерения</li>
</ol>

<div class="content-header">
    <div class="content-title">Единицы измерения</div>
</div>

<?= $this->flashSession->output() ?>

<div class="buttons-block">
    <?= $this->tag->linkTo(['units/new', '<i class="fa fa-plus"></i>&nbsp;Новая', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание новой единицы измерения']) ?>
</div>

<div style="max-width:640px">
    <table id="unitsTable" class="display table compact table-bordered  hover"
        cellspacing="0" cellpadding="0" border="0" width="100%">
        <thead>
            <tr>
                <th>Наименование</th>
                <th>Обозначение</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($units as $unit) { ?>
                <tr>
                    <td><?= $unit->getName() ?></td>
                    <td><?= $unit->getSymbol() ?></td>
                    <td>
                        <a href="<?= $this->url->get('units/edit?id=' . $unit->getId()) ?>"
                            class="btn btn-primary btn-xs"
                            title="Редактировать"><i class="fa fa-pencil"></i></a>
                        <a href="<?= $this->url->get('units/delete?id=' . $unit->getId()) ?>"
                            class="btn btn-danger btn-xs esr-delete-confirm"
                            title="Удалить"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#unitsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
                {
                    'targets': [2],
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
