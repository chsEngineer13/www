<div id="fileUploadModal" class="modal file-upload-modal" tabindex="-1" role="dialog" data-backdrop="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Загрузить файл</h4>
            </div>
            <div class="modal-body">
               <form id="fileUploadModalForm" role="form" method="post" action="<?= $fileUploadModalAction ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-default test">
                                    Выбрать файл...
                                    <input type="file" id="fileUploadModalInputFile" name="inputFile[]" 
                                        class="file-upload-modal__input-file" multiple>
                                </span>
                            </span>
                            <input type="text" id="fileUploadModalInputFileLabel" class="form-control" disabled>
                        </div>
                        <span id="fileUploadModalError" class="file-upload-modal__error"></span>
                    </div>
                    <div class="form-group">
                        <label for="fileUploadModalDescription" class="file-upload-modal__label">Описание файла</label>
                        <textarea id="fileUploadModalDescription" class="form-control" name="description" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="submit" id="fileUploadModalSubmit" class="btn btn-primary">Загрузить</button>
            </div>
        </div>
    </div>
</div>


<script>
    // Обработка выбора файлов.
    $('#fileUploadModalInputFile').change(function() {
        var files = this.files;
        var label = '';
        var fileCount = 0;

        fileCount = files.length;

        if (fileCount == 1) {
            label = files[0].name;
            $('#fileUploadModalInputFileLabel').val(label);
        } else if (fileCount > 1 && fileCount < 5) {
            label = 'Выбрано ' + fileCount + ' файла.';
            $('#fileUploadModalInputFileLabel').val(label);
        } else if (fileCount >= 5) {
            label = 'Выбрано ' + fileCount + ' файлов.';
            $('#fileUploadModalInputFileLabel').val(label);
        }
    });

    
    // Отправка данных формы.
    $('#fileUploadModalSubmit').on('click', function(){
        var inputFile = $('#fileUploadModalInputFile').val();

        if (inputFile === "") {
            $('#fileUploadModalError').text('Необходимо выбрать файл.');
        } else {
            $('#fileUploadModalForm').submit();
            return false;
        }
    });
    
    
    // Очистка полей модального окна после закрытия.
    $('#fileUploadModal').on('hidden.bs.modal', function (e) {
        $('#fileUploadModalInputFile').val('');
        $('#fileUploadModalInputFileLabel').val('');
        $('#fileUploadModalError').text('');
        $('#fileUploadModalDescription').val('');
    })
</script>
