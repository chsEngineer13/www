{# Модальное окно "Импорт объектов изысканий". #}
<div id="importObjectsXlsxModal" class="modal" role="dialog"
    tabindex="-1" data-backdrop="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Импорт объектов изысканий</h4>
            </div>
            <div class="modal-body">
                <form id="importObjectsXlsxForm" class="form" method="POST" enctype="multipart/form-data"
                    action="{{ url('survey-facilities/import-objects-xlsx' ~ 
                        '?construction-project-id=' ~ constructionProject.getId() ~
                        '&construction-site-id=' ~ constructionSite.getId()) }}">
                    <fieldset>
                        <div class="form-group">
                            <input type="file" name="inputFile" class="filestyle" data-buttonBefore="true" 
                                data-buttonName="btn-default" data-buttonText="&nbsp;Выбрать файл...">
                            <p class="file-msg"></p>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"
                    title="Закрыть диалоговое окно">&nbsp;Закрыть&nbsp;</button>
                <button type="submit" id="importObjectsXlsxSubmit" class="btn btn-default"
                    title="Импорт объектов изысканий из файла MS Excel (XLSX)">
                    <i class="fa fa-file-excel-o"></i>&nbsp;Импорт&nbsp;</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#importObjectsXlsxButton").on("click", function(e){
        $("#importObjectsXlsxModal").modal("show");
        $("#importObjectsXlsxSubmit").on("click", function(e){
            var fileInput = $('#importObjectsXlsxForm input[type="file"]').val();
            if (fileInput == "") {
                $("#importObjectsXlsxForm .file-msg").addClass("text-danger");
                $("#importObjectsXlsxForm .file-msg").html("Необходимо выбрать файл.");
            } else {
                $("#importObjectsXlsxModal").modal("hide");
                $("#importObjectsXlsxForm").submit();
            }
            
            return false;
        });
    });
    
    // Очистка элементов формы при закрытии модального окна.
    $('#importObjectsXlsxModal').on('hidden.bs.modal', function (e) {
        $("#importObjectsXlsxForm .file-msg").removeClass("text-danger");
        $("#importObjectsXlsxForm .file-msg").html("");
    })
</script>
