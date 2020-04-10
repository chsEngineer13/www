<!-- Навигационный маршрут -->
<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Группы сотрудников</li>
</ol>

<!-- Заголовок контента -->
<div class="content-header">
    <div class="content-title">Группы сотрудников</div>
</div>

<!-- Информационные сообщения -->
<?= $this->flashSession->output() ?>

<!-- Группы кнопок -->
<div class="buttons-block">
    <a href="<?= $this->url->get('employee-groups/new') ?>" class="btn btn-default" title='Создание новой группы'>
        <i class="fa fa-plus"></i>&nbsp;Новая группа</a>
</div>

<!-- Таблица "Группы сотрудников" -->
<table id="employeeGroupsTable" class="table compact table-bordered hover"
    cellspacing="0" cellpadding="0" border="0" width="800px">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>Описание</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employeeGroups as $group) { ?>
            <tr>
                <td align="center"><?= $group->getSeqNumber() ?></td>
                <td>
                    <a href="<?= $this->url->get('employee-group-memberships?employee_group_id=' . $group->getId()) ?>">
                        <?= $group->getName() ?></a>
                </td>
                <td><?= $group->getDescription() ?></td>
                <td>
                    <a href="<?= $this->url->get('employee-groups/edit?id=' . $group->getId()) ?>" class="btn btn-primary btn-xs"
                        title='Редактировать'><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="<?= 'employee-groups/delete?id=' . $group->getId() ?>" class="btn btn-danger btn-xs esr-delete-confirm"
                        title='Удалить'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#employeeGroupsTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },
            
            'paging': false,
            'searching': false,
            'info': false,
            
            'columnDefs': [
                {
                    'targets': [0],
                    'width': '20px',
                },
                
                {
                    'targets': [1],
                    'width': '300px',
                },

                {
                    'targets': [2],
                    'width': '450px',
                },

                {
                    'targets': [3],
                    'className': 'td-nowrap td-align-center',
                    'width': '50px',
                    'sortable': false,
                }
            ],

            'order': [[ 0, 'asc' ]],

        });
        
        new $.fn.dataTable.FixedHeader( table, {
            'header': true,
            'headerOffset': 50
        });
        
    });
</script>