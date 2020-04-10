<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Виды работ</li>
</ol>

<div class="content-header">
    <div class="content-title">Виды работ</div>
</div>

<?= $this->flashSession->output() ?>

<div class="buttons-block">
    <?= $this->tag->linkTo(['work-types/new', '<i class="fa fa-plus"></i>&nbsp;Новый', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание нового вида работ']) ?>
</div>

<table id="workTypesTable" class="display table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Порядковый номер вида изысканий</th>
            <th>Вид изысканий</th>
            <th>Наименование вида работ</th>
            <th>Сокращенное наименование</th>
            <th>Ед. изм.</th>
            <th>Норма выработки</th>
            <th>Комментарий</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($workTypes as $workType) { ?>
            <tr>
                <td><?= $workType->getSurveyType()->getSequenceNumber() ?></td>
                <td><?= $workType->getSurveyType()->getName() ?></td>
                <td><?= $workType->getName() ?></td>
                <td><?= $workType->getShortName() ?></td>
                <td><?= $workType->getUnit()->getSymbol() ?></td>
                <td><?= $workType->getFormattedProductionRate() ?></td>
                <td><?= $workType->getComment() ?></td>
                <td>
                    <a href="<?= $this->url->get('work-types/edit?id=' . $workType->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="<?= $this->url->get('work-types/delete?id=' . $workType->getId()) ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#workTypesTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
                { 'targets': [0], 'visible': false, 'sortable': false },
                { 'targets': [1], 'visible': false, 'sortable': false },
                { 'targets': [2], 'orderData': [0, 2] },
                { 'targets': [3], 'orderData': [0, 3] },
                { 'targets': [4], 'orderData': [0, 4] },
                { 'targets': [5], 'orderData': [0, 5] },
                { 'targets': [6], 'orderData': [0, 6] },
                { 'targets': [7], 'className': 'td-nowrap td-align-center', 'width': "48px", 'sortable': false },
            ],

            'order': [[ 2, 'asc' ]],

            'stateSave': true,
            
            // Функция группировки строк.
            'drawCallback': function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;
                // Группировка по столбцу 1.
                api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="7">'+group+'</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });

        new $.fn.dataTable.FixedHeader( table, {
            'header': true,
            'headerOffset': 50
        });

    });
</script>
