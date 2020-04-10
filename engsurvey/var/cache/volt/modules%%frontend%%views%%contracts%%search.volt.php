<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Договоры</li>
</ol>

<div class="content-header">
    <div class="content-title">Договоры</div>
</div>

<?= $this->flashSession->output() ?>

<div class="buttons-block">
    <?= $this->tag->linkTo(['contracts/new', '<i class="fa fa-plus"></i>&nbsp;Новый договор', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание нового договора']) ?>
</div>

<table id="contractsTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Номер договора</th>
            <th>ДС</th>
            <th>Предмет договора</th>
            <th>Шифр стройки</th>
            <th>Заказчик</th>
            <th>Ответственный филиал</th>
            <th>Дата подписания</th>
            <th>Статус</th>
            <th>Комментарий</th>
            <th><!-- Действия --></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contracts as $contract) { ?>
            <tr>
                <td>
                    <a href="<?= $this->url->get('contracts/properties?id=' . $contract->getId()) ?>">
                        <?= $contract->getContractNumber() ?></a>
                </td>
                <td><?= $contract->getSupplementalAgreementNumber() ?></td>
                <td><?= $contract->getSubjectOfContract() ?></td>
                <td><?= $contract->getConstructionProject()->getCode() ?></td>
                <td><?= $contract->getCustomer()->getDisplayName() ?></td>
                <td>
                    <?php if ($contract->getBranch()) { ?>
                        <?= $contract->getBranch()->getDisplayName() ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($contract->getSignatureDate()) { ?>
                        <?= $contract->getFormattedSignatureDate('d.m.Y') ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($contract->getContractStatus()) { ?>
                        <?= $contract->getContractStatus()->getName() ?>
                    <?php } ?>
                </td>
                <td><?= $contract->getComment() ?></td>
                <td>
                    <!-- Действия -->
                    <a href="<?= $this->url->get('contracts/edit?id=' . $contract->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="<?= $this->url->get('contracts/delete?id=' . $contract->getId()) ?>"
                        class="btn btn-danger btn-xs esr-delete-confirm"
                        title="Удалить"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $.fn.dataTable.moment('DD.MM.YYYY');
        var table = $('#contractsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
                {
                    'targets': [0],
                    'orderData': [0, 1],
                },
            
                {
                    'targets': [1],
                    'className': 'td-align-center',
                    'width': "30px",
                    'sortable': false,
                },
                
                {
                    'targets': [9],
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