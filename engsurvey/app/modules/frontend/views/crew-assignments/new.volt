<ol class="breadcrumb">
    <li><a href="{{ url() }}"><i class="fa fa-home"></i></a></li>
    <li><a href="{{ url("crew-assignments") }}">Распределение бригад</a></li>
    <li class="active">Новое</li>
</ol>

<div class="content-header">
    <div class="content-title">Новое назначение бригады на объект</div>
</div>

{{ flashSession.output() }}

<form class="form-dialog form-dialog-md" role="form" autocomplete="off"
    action="{{ url("crew-assignments/create") }}"  method="post">

    <div class="form-dialog-body">
        <div id= "branchFormGroup" class="form-group">
            {{ form.label("branchId", ["class": "control-label"]) }}
            {{ form.render("branchId", ["class": "selectpicker form-control-xl"]) }}
            <span id="branchErrors" class="help-block"></span>
            <span id="branchHelp" class="help-block"></span>
        </div>

        <div id= "crewFormGroup" class="form-group">
            {{ form.label("crewId", ["class": "control-label"]) }}
            {{ form.render("crewId", ["class": "selectpicker form-control-xl", "data-live-search": "true"]) }}
            <span id="crewErrors" class="help-block"></span>
            <span id="crewHelp" class="help-block"></span>
        </div>

        <div id="constructionProjectFormGroup" class="form-group">
            {{ form.label("constructionProjectId", ["class": "control-label"]) }}
            {{ form.render("constructionProjectId", ["class": "selectpicker form-control-sm", "data-live-search": "true"]) }}
            <span id="constructionProjectErrors" class="help-block"></span>
            <span id="constructionProjectHelp" class="help-block"></span>
        </div>

        <div id="constructionSiteFormGroup" class="form-group">
            {{ form.label("constructionSiteId", ["class": "control-label disabled"]) }}
            {{ form.render("constructionSiteId", ["class": "selectpicker form-control-sm"]) }}
            <span id="constructionSiteErrors" class="help-block"></span>
            <span id="constructionSiteHelp" class="help-block"></span>
        </div>

        <div id="startDateFormGroup" class="form-group">
            {{ form.label("startDate", ["class": "control-label"]) }}
            <div class="input-group date datepicker datepicker__size_sm">
                {{ form.render("startDate", ["class": "form-control"]) }}
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span id="startDateErrors" class="help-block"></span>
            <span id="startDateHelp" class="help-block"></span>
        </div>

        <div id="endDateFormGroup" class="form-group">
            {{ form.label("endDate", ["class": "control-label"]) }}
            <div class="input-group date datepicker datepicker__size_sm">
                {{ form.render("endDate", ["class": "form-control"]) }}
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <span id="endDateErrors" class="help-block"></span>
            <span id="endDateHelp" class="help-block"></span>
        </div>

        <div id= "commentFormGroup" class="form-group">
            {{ form.label("comment", ["class": "control-label"]) }}
            {{ form.render("comment", ["class": "form-control form-control-xl"]) }}
        </div>
    </div>

    <div class="form-dialog-footer">
        <a href="{{ url("crew-assignments") }}" class="btn btn-default" role="button"
            title="Выход без сохранения изменений">Закрыть</a>
        <button type="submit" class="btn btn-primary"
            title="Создание нового назначения на объект">Создать</button>
    </div>
</form>


<script>
    /**
     * Добавляет справочную информацию о бригаде.
     */
    function addCrewHelp() {
        var crewId = $('#crewId').val();

        if (crewId != '') {
            var url = "{{ url('crews/get-json?id=') }}" + crewId;

            $.ajax({
                async: false,
                url: url,
                dataType: "json",
                success: function(data) {
                    var status = data['status'];

                    if (status === 'not_found') {
                        $('#crewHelp').text('');
                        $('#crewHelp').hide();

                        return(false);
                    }

                    if (status === 'found') {
                        var crew = data['crew'];
                        var headLastName = crew['head_last_name'];
                        var headFirstName = crew['head_first_name'];
                        var headMiddleName = crew['head_middle_name'];

                        // Формирование полного имени руководителя бригадира.
                        var headFullName = '';

                        if (typeof headLastName === 'string') {
                            headFullName = headLastName;
                        }

                        if (typeof headFirstName === 'string') {
                            headFullName = headFullName + ' ' + headFirstName;
                        }

                        if (typeof headMiddleName === 'string') {
                            headFullName = headFullName + ' ' + headMiddleName;
                        }

                        headFullName = $.trim(headFullName);

                        if (headFullName.length > 0) {
                            head = 'Бригадир ' + headFullName;
                        } else {
                            head = 'Бригадир не назначен.'
                        }

                        $("#crewHelp").text(head);
                        $("#crewHelp").show();
                    }
                },
                error: function(data){
                    return(false);
                }
            });

        } else {
            $('#crewHelp').text('');
            $('#crewHelp').hide();
        }
    }


    /**
     * Изменяет список бригад в зависимости от выбранного филиала.
     */
    function changeCrewSelect() {
        var branchId = $('#branchId').val();

        if (branchId != '') {
            var url = "{{ url('crews/get-json?branch_id=') }}" + branchId;

            $.ajax({
                async: false,
                url: url,
                dataType: 'json',
                success: function(data) {
                    var status = data['status'];

                    $('#crewId').empty();
                    $('#crewId').append('<option value="">. . .</option>');

                    if (status == 'found') {
                        var crews = data['crews'];

                        $.each(crews, function(key, crew) {
                            $('#crewId').append('<option value="' + crew['id'] + '">' + crew['crew_name'] + '</option>');
                        });
                    }

                    // Разблокируется список, если он был заблокирован.
                    $('#crewId').prop('disabled', false);
                    $('#crewId').selectpicker('refresh');
                },
                error: function(data){
                    return(false);
                }
            });

        } else {
            $('#crewId').empty();
            $('#crewId').append('<option value="">. . .</option>');
            // Если филиал не выбран, выбор бригад блокируется.
            $('#crewId').prop('disabled', true);
            $('#crewId').selectpicker('refresh');
        }
        
        // Изменение информации о бригаде.
        addCrewHelp();
    }


    /**
     * Добавляет справочную информацию о стройке.
     */
    function addConstructionProjectHelp() {
        var constructionProjectId = $('#constructionProjectId').val();
        if (constructionProjectId != '') {
            var url = "{{ url('construction-projects/get-json?id=') }}" + constructionProjectId;
            $.ajax({
                async: false,
                url: url,
                dataType: "json",
                success: function(data) {
                    var constructionProject = data['construction_project'];
                    $("#constructionProjectHelp").text(constructionProject['name']);
                    $("#constructionProjectHelp").show();
                },
                error: function(data){
                    return(false);
                }
            });
        } else {
            $('#constructionProjectHelp').text('');
            $('#constructionProjectHelp').hide();
        }
    }


    /**
     * Добавляет справочную информацию об участке работ.
     */
    function addConstructionSiteHelp() {

        var constructionSiteId = $('#constructionSiteId').val();

        if (constructionSiteId != '') {
            var url = "{{ url('construction-sites/get-json?id=') }}" + constructionSiteId;
            $.ajax({
                async: false,
                url: url,
                dataType: "json",
                success: function(data) {
                    var status = data['status'];

                    if (status === 'found') {
                        var site = data['construction_site'];
                        $("#constructionSiteHelp").text(site['name']);
                        $("#constructionSiteHelp").show();
                    }
                },
                error: function(data){
                    return(false);
                }
            });
        } else {
            $('#constructionSiteHelp').text('');
            $('#constructionSiteHelp').hide();
        }
    }
    

    /**
     * Изменяет список участков работ относящихся к стройке.
     */
    function changeConstructionSiteSelect() {
        var constructionProjectId = $('#constructionProjectId').val();

        if (constructionProjectId != '') {
            var url = "{{ url('construction-sites/get-json?construction_project_id=') }}" + constructionProjectId;
            $.ajax({
                async: false,
                url: url,
                dataType: 'json',
                success: function(data) {
                    var status = data['status'];

                    $('#constructionSiteId').empty();
                    $('#constructionSiteId').append('<option value="">. . .</option>');

                    if (status == 'found') {
                        var sites = data['construction_sites'];
                         $.each(sites, function(key, site) {
                            $('#constructionSiteId').append('<option value="' + site['id'] + '">' + site['site_number'] + '</option>');
                         });
                    }

                    // Разблокируется список, если он был заблокирован.
                    $('#constructionSiteId').prop('disabled', false)
                    $('#constructionSiteId').selectpicker('refresh');
                },
                error: function(data){
                    return(false);
                }
            });
        } else {
            $('#constructionSiteId').empty();
            $('#constructionSiteId').append('<option value="">. . .</option>');
            // Если стройка не выбрана, выбор участков работ блокируется.
            $('#constructionSiteId').prop('disabled', true);
            $('#constructionSiteId').selectpicker('refresh');
        }
        
        // Изменение информации об участке работ.
        addConstructionSiteHelp();
        
    }


    /**
     * Добавляет справочную информацию к полям формы.
     */
    function addHelpToFields() {
        addCrewHelp();
        addConstructionProjectHelp();
        addConstructionSiteHelp();
    }


    /**
     * Добавляет сообщения об ошибках к полям формы.
     */
    function addErrorMessagesToFields() {
        {% if branchMessages is defined %}
            $("#branchFormGroup").addClass("has-error");
            $("#branchErrors").text("{{ branchMessages }}");
        {% endif %}

        {% if branchMessages is defined %}
            $("#crewFormGroup").addClass("has-error");
            $("#crewErrors").text("{{ crewMessages }}");
        {% endif %}

        {% if constructionProjectMessages is defined %}
            $("#constructionProjectFormGroup").addClass("has-error");
            $("#constructionProjectErrors").text("{{ constructionProjectMessages }}");
        {% endif %}

        {% if constructionSiteMessages is defined %}
            $("#constructionSiteFormGroup").addClass("has-error");
            $("#constructionSiteErrors").text("{{ constructionSiteMessages }}");
        {% endif %}

        {% if startDateMessages is defined %}
            $("#startDateFormGroup").addClass("has-error");
            $("#startDateErrors").text("{{ startDateMessages }}");
        {% endif %}

        {% if endDateMessages is defined %}
            $("#endDateFormGroup").addClass("has-error");
            $("#endDateErrors").text("{{ endDateMessages }}");
        {% endif %}
    }


    /**
     * Изменение выбора филиала.
     */
    $('#branchId').change(function() {
        // Изменение списка бригад относящихся к филиалу.
        changeCrewSelect();
    });


    /**
     * Изменение выбора бригады.
     */
    $('#crewId').change(function() {
        // Добавление справочной информации о бригаде.
        addCrewHelp();
    });
    
    
    /**
     * Изменение выбора стройки.
     */
    $('#constructionProjectId').change(function() {
        // Добавление справочной информации о стройке.
        addConstructionProjectHelp();
        
        // Изменение списка участков работ относящихся к стройке.
        changeConstructionSiteSelect();
    });
    
    
    /**
     * Изменение выбора участка работ.
     */
    $('#constructionSiteId').change(function() {
        // Добавление справочной информации об участке работ.
        addConstructionSiteHelp();
    });


    // Скрипт выполняемый после загрузки файла.
    $(window).load(function(){
        // Настройка компонента selectpicker.
        /*$(".selectpicker").selectpicker({
            size: 6,
            liveSearch: true,
        });*/

        // Настройка компонента datepicker.
        /*$(".datepicker").datepicker({
            language: "ru",
            autoclose: true,
            forceParse: false,
        });*/

        // Добавление сообщений об ошибках к полям формы.
        addErrorMessagesToFields();

        // Добавление справочной информации к полям формы.
        addHelpToFields();
        
        // Блокирование списка бригад, если не выбран филиал.
        // Если филиал выбран, заполнить его.
        var branchId = $('#branchId').val();
        if (branchId === '') {
            $('#crewId').prop('disabled', true);
            $('#crewId').selectpicker('refresh');
        }else {
            changeCrewSelect();
        }
        
        
        // Блокирование списка участков работ, если не выбрана стройка.
        // Если стройка выбрана, заполнить его.
        var constructionProjectId = $('#constructionProjectId').val();
        if (constructionProjectId === '') {
            $('#constructionSiteId').prop('disabled', true);
            $('#constructionSiteId').selectpicker('refresh');
        }else {
            changeConstructionSiteSelect();
        }
    });

</script>
