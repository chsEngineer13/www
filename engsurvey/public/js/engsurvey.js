$(document).ready(function() {
    "use strict";

     //-------------------------------------------------------------------------
     // Настройка внешних библиотек.
     //-------------------------------------------------------------------------

    // Настройка списка выбора.
    $(".selectpicker").selectpicker({
        size: 6
    });
    

    // Настройка элемента выбора даты.
    $(".datepicker").datepicker({
        language: "ru",
        format: "dd.mm.yyyy",
        autoclose: true,
        forceParse: true,
    });
    
    
     //-------------------------------------------------------------------------
     // ИУС 'Инженерные изыскания'
     //-------------------------------------------------------------------------
    

    // Скрыть/отобразить левую боковую панель.
    $('.left-sidebar-toggle').click(function(e) {
        e.preventDefault();
        $('.left-sidebar').toggleClass('hidden');
    });


    // Запрет перехода по заблокированной ссылке.
    $('a[disabled]').click(function(event) {
        event.preventDefault();
    });
    
    
   

    /**
     * Боковая панель навигации.
     */

    // Сворачивание/разворачинание подменю боковой панели навигации.
    $('.sidebar-nav__parent-link').click(function(){
        var parentItem = $(this).parent('.sidebar-nav__parent-item');
        var sublist = parentItem.children('.sidebar-nav__sublist');
        var arrow = $(this).children('.sidebar-nav__arrow');

        if (parentItem.hasClass('sidebar-nav__parent-item_expanded')) {
            removeExpandedSidebarNavElements();
        } else {
            removeExpandedSidebarNavElements();

            parentItem.addClass('sidebar-nav__parent-item_expanded');
            sublist.addClass('sidebar-nav__sublist_expanded');
            arrow.addClass('sidebar-nav__arrow_expanded');
        }

        return false;
    })
    
    
    // Блокирование отключенного родительского пункта меню.
    $('.sidebar-nav__parent-link_disabled').click(function() {
        return false;
    })


    // Выбор пункта меню боковой панели навигации.
    $('.sidebar-nav__link').click(function() {
        var item = $(this).parent('.sidebar-nav__item');

        if (item.hasClass('sidebar-nav__item_selected')) {
            return false;
        }

        removeExpandedSidebarNavElements();
        removeSelectedSidebarNavElements();

        item.addClass('sidebar-nav__item_selected');
    })
    
    
    // Блокирование отключенного пункта меню.
    $('.sidebar-nav__link_disabled').click(function() {
        return false;
    })


    // Выбор пункта подменю боковой панели навигации.
    $('.sidebar-nav__sublink').click(function() {
        var subitem = $(this).parent('.sidebar-nav__subitem');
        var parentItem = $(this).closest('.sidebar-nav__parent-item');

        if (subitem.hasClass('sidebar-nav__subitem_selected')) {
            return false;
        }

        removeSelectedSidebarNavElements();

        subitem.addClass('sidebar-nav__subitem_selected');
        parentItem.addClass('sidebar-nav__parent-item_selected');
    })
    
    
    // Блокирование отключенной пункта подменю.
    $('.sidebar-nav__sublink_disabled').click(function() {
        return false;
    })


    // Удалить выделение элементов боковой панели навигации.
    function removeSelectedSidebarNavElements() {
        $('.sidebar-nav__item').removeClass('sidebar-nav__item_selected');
        $('.sidebar-nav__subitem').removeClass('sidebar-nav__subitem_selected');
        $('.sidebar-nav__parent-item').removeClass('sidebar-nav__parent-item_selected');
    }


    // Удалить разворачивание элементов боковой панели навигации.
    function removeExpandedSidebarNavElements() {
        $('.sidebar-nav__parent-item').removeClass('sidebar-nav__parent-item_expanded');
        $('.sidebar-nav__sublist').removeClass('sidebar-nav__sublist_expanded');
        $('.sidebar-nav__arrow').removeClass('sidebar-nav__arrow_expanded');
    }


    // Установить выбранным пункт меню (подменю) боковой панели навигации.
    setSelectedSidebarNavItem();
    function setSelectedSidebarNavItem() {
        var pathname = window.location.pathname;
        pathname = pathname.toLowerCase();

        // Удаление слеша в конце пути.
        if (pathname[pathname.length - 1] === '/') {
            pathname = pathname.slice(0, -1);
        }

        // Поиск пути среди ссылок меню/подменю.
        var links = $('.sidebar-nav__item').children('[href="' + pathname +'"]');
        var sublinks = $('.sidebar-nav__subitem').children('[href="' + pathname +'"]');

        if (links.length > 0) {
            var item = links.first().parent();
            item.addClass('sidebar-nav__item_selected');
        } else if (sublinks.length > 0) {
            var sublink = sublinks.first();
            var subitem = sublink.parent();
            var parentItem = subitem.closest('.sidebar-nav__parent-item');
            var sublist = parentItem.children('.sidebar-nav__sublist');
            var parentLink = parentItem.children('.sidebar-nav__parent-link');
            var arrow = parentLink.children('.sidebar-nav__arrow');

            subitem.addClass('sidebar-nav__subitem_selected');
            parentItem.addClass('sidebar-nav__parent-item_selected');

            parentItem.addClass('sidebar-nav__parent-item_expanded');
            sublist.addClass('sidebar-nav__sublist_expanded');
            arrow.addClass('sidebar-nav__arrow_expanded');
        }

    }

    /**
     * Подтверждение удаления.
     */
    $('a.esr-delete-confirm').click(function() {
        var href = $(this).attr('href');
        var modalHtml =
            '<div class="modal" id="esrDeleteConfirmModal" tabindex="-1" role="dialog" data-backdrop="false" aria-hidden="true">' +
                '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                        '<div class="modal-header">' +
                            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                            '<h4 class="modal-title">Подтверждение удаления</h4>' +
                        '</div>' +
                        '<div class="modal-body">' +
                            '<p>Вы действительно хотите удалить выбранный объект?</p>' +
                        '</div>' +
                        '<div class="modal-footer">' +
                            '<button class="btn btn-default esr-delete-confirm-мodal__button" type="button" data-dismiss="modal">Нет</button>' +
                            '<a id="esrDeleteConfirmModalYes" class="btn btn-danger esr-delete-confirm-мodal__button" role="button">Да</a>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

        if (!$('#esrDeleteConfirmModal').length) {
            $('body').append(modalHtml);
        } 

        $('#esrDeleteConfirmModalYes').attr('href', href);
        $('#esrDeleteConfirmModal').modal({show:true});
        return false;
    });

});
