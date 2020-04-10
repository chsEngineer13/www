<ol class="breadcrumb">
    <li><?= $this->tag->linkTo(['', '<i class="fa fa-home"></i>']) ?></li>
    <li class="active">Сотрудники</li>
</ol>

<div class="content-header">
    <div class="content-title">Сотрудники</div>
</div>

<?= $this->flashSession->output() ?>

<div class="buttons-block">
    <?= $this->tag->linkTo(['employees/new', '<i class="fa fa-plus"></i>&nbsp;Новый сотрудник', 'class' => 'btn btn-default', 'role' => 'button', 'title' => 'Создание нового сотрудника']) ?>
</div>

<table id="employeesTable" class="table compact table-bordered  hover"
    cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Ф. И. О.</th>
            <th>Подразделение</th>
            <th>Филиал</th>
            <th>Местонахождение</th>
            <th>Телефоны</th>
            <th>Эл. почта</th>
            <th><!-- Действия --></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employees as $employee) { ?>
            <tr>
                <td>
                    <!-- Ф. И. О. -->
                    <a href="<?= $this->url->get('employees/edit?id=' . $employee->getId()) ?>">
                        <?= $employee->getFullName() ?>
                    </a>
                    <!-- Должность -->
                    <br><em><?= $employee->getJobTitle() ?></em>
                </td>
                <td>
                    <!-- Подразделение -->
                    <?= $employee->getDepartment() ?>
                </td>
                <td>
                    <!-- Филиал -->
                    <?= $employee->getBranch()->getDisplayName() ?>
                </td>
                <td>
                    <!-- Местонахождение -->
                    <?= $employee->getLocation() ?>
                </td>
                <td>
                    <!-- Телефоны -->
                    <?php if (!empty($employee->getPhoneWork())) { ?>
                        <nobr><?= '<em>раб.&nbsp;</em>' . $employee->getPhoneWork() ?></nobr><br>
                    <?php } ?>

                    <?php if (!empty($employee->getPhoneGas())) { ?>
                        <nobr><?= '<em>газ.&nbsp;</em>' . $employee->getPhoneGas() ?></nobr><br>
                    <?php } ?>
                    
                    <?php if (!empty($employee->getPhoneMobile())) { ?>
                        <nobr><?= '<em>моб.&nbsp;</em>' . $employee->getPhoneMobile() ?></nobr>
                    <?php } ?>
                </td>
                <td>
                    <!-- Эл. почта -->
                    <?= $employee->getEmail() ?>
                </td>
                <td>
                    <!-- Действия -->
                    <a href="<?= $this->url->get('employees/edit?id=' . $employee->getId()) ?>"
                        class="btn btn-primary btn-xs"
                        title="Редактировать"><i class="fa fa-pencil"></i></a>
                    <a href="<?= $this->url->get('employees/delete?id=' . $employee->getId()) ?>"
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
        var table = $('#employeesTable').DataTable({
            'language': {
                'url': '<?= $this->url->get($this->config->dataTables->dataTablesRu) ?>'
            },

            'columnDefs': [
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