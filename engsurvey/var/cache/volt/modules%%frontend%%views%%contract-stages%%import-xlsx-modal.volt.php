<!-- Модальное окно. -->
<div id="importXlsxModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Импорт из Excel этапов работ по КП</h4>
            </div>
            <div class="modal-body">
                <form id="importXlsxModalForm" role="form" enctype="multipart/form-data" method="post" 
                    action="<?= $this->url->get('contract-stages/import-xlsx?contract_id=' . $contract->getId()) ?>" >
                    
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-default">
                                    Выбрать файл...
                                    <input type="file" id="importXlsxModalInputFile" 
                                        name="inputFile" class="es-input-file">
                                </span>
                            </span>
                            <input type="text" id="importXlsxModalInputFileLabel" 
                                class="form-control" disabled>
                        </div>
                        <span id="importXlsxModalInputFileMessage" class="es-error-message"></span>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="submit" id="importXlsxModalSubmit" class="btn btn-primary">Импорт</button>
            </div>
        </div>
    </div>
</div>


<script>
    // Обработка выбора файла.
    $('#importXlsxModalInputFile').change(function() {
        var label = this.files[0].name;
        $('#importXlsxModalInputFileLabel').val(label);
    });

    
    // Отправка данных формы.
    $('#importXlsxModalSubmit').on('click', function(){
        var inputFile = $('#importXlsxModalInputFile').val();
        if (inputFile === '') {
            $('#importXlsxModalInputFileMessage').text('Необходимо выбрать файл.');
        } else {
            $('#importXlsxModalForm').submit();
            return false;
        }
    });
    
    
    // Очистка полей модального окна после закрытия.
    $('#importXlsxModal').on('hidden.bs.modal', function (e) {
        $('#importXlsxModalInputFile').val('');
        $('#importXlsxModalInputFileLabel').val('');
        $('#importXlsxModalInputFileMessage').text('');
    })
</script>
