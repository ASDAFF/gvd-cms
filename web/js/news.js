function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}

function get_slider_photos() {
    $.ajax({
        url: '/admin/news/get-slider-photos',
        data: {'id': $('#newsimageform-news_id').val()},
        method: 'POST',
        success: function (response) {
            $('#upload_slider_photo_block .memberblock').html(response);
        }
    });
}

var news_img_block;

$(document).ready(function () {

    if (getCookie('news_tab') == '#block_view') {
        $('a[href="#table_view"]').attr('aria-expanded', 'false');
        $('a[href="#table_view"]').parent().removeClass('active');
        $('a[href="#block_view"]').attr('aria-expanded', 'true');
        $('a[href="#block_view"]').parent().addClass('active');

        $('#table_view').removeClass('active').removeClass('in');
        $('#block_view').addClass('active').addClass('in');
    }
    else {
        $('#table_view').addClass('active').addClass('in');
        deleteCookie('news_tab');
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        setCookie('news_tab', $(this).attr('href'));
    });

    // ЗАГРУЗКА ФОТО В СЛАЙДЕР

    $('#news_slider_photo_switch').change(function () {
        if ($(this).val() == '0') {
            $(this).val(1);
            $('#upload_slider_photo_block').slideDown();
        }
        else {
            $(this).val(0);
            $('#upload_slider_photo_block').slideUp();
        }
    });

    $('#upload_slider_photo').click(function () {
        $('#slider_photo_form').click();
    });

    $('#slider_photo_form').dropzone({
        url: "/admin/news/upload-photo",
        uploadMultiple: true,
        paramName: 'NewsImageForm[images]',
        complete: function () {
            get_slider_photos();
        }
    });


    // ЗАГРУЗКА ФОТО НОВОСТЕЙ

    $('#news_cover').click(function () {
        news_img_block = $('#news_cover');
        $('#news_img_form #newsimageform-label').val('cover');
        $('#news_img_form').click();
    });

    $('#news_main').click(function () {
        news_img_block = $('#news_main');
        $('#news_img_form #newsimageform-label').val('main');
        $('#news_img_form').click();
    });

    $('#news_extra').click(function () {
        news_img_block = $('#news_extra');
        $('#news_img_form #newsimageform-label').val('extra');
        $('#news_img_form').click();
    });

    $('#news_img_form').dropzone({
        url: "/admin/news/upload-photo",
        uploadMultiple: false,
        paramName: 'NewsImageForm[image]',
        complete: function () {
            $.ajax({
                url: '/admin/news/get-img',
                data: {'id': $('#newsimageform-news_id').val(), 'label': $('#news_img_form #newsimageform-label').val()},
                method: 'POST',
                success: function (response) {
                    response = $.parseJSON(response);
                    news_img_block.find('div').addClass('member');
                    news_img_block.find('div').attr('style', 'background-image: url('+response['image']+');');
                    news_img_block.find('div a').attr('data-id', response['id']);
                }
            });
        }
    });


    // УДАЛЕНИЕ ФОТО В СЛАЙДЕРЕ

    $('body').on('click', '.delete_slider_photo', function(e) {
        e.preventDefault();
        var block = $(this).parent();
        $.ajax({
            url: '/admin/news/delete-photo',
            data: {'id': $(this).attr('data-id')},
            method: 'POST',
            success: function (response) {
                block.remove();
            }
        });
    });

    // УДАЛЕНИЕ ФОТО НОВОСТЕЙ

    $('body').on('click', '.delete_news_photo', function(e) {
        e.preventDefault();
        var block = $(this).parent();
        $.ajax({
            url: '/admin/news/delete-photo',
            data: {'id': $(this).attr('data-id')},
            method: 'POST',
            success: function (response) {
                block.removeClass('member');
                block.removeAttr('style');
            }
        });
    });


    // УДАЛЕНИЕ НОВОСТИ

    $('body').on('click', 'a[data-target=delete_news_modal]', function(e) {
        e.preventDefault();
        var name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        $('#delete_news_modal .modal-body').html(name);
        $('#delete_news_modal #delete_news_button').attr('data-id', id);
        $('#delete_news_modal').modal('show');
    });

    $('#delete_news_modal #delete_news_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/news/delete',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // ПУБЛИКАЦИЯ НОВОСТЕЙ

    $('body').on('click', '.publishing_news', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/news/publish',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // ПОПУЛЯРИЗАЦИЯ НОВОСТЕЙ

    $('body').on('click', '.popularing_news', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/news/popular',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // ВЫВОД НОВОСТЕЙ В ТАБЛИЦЕ

    var table = $('#dataTables-news').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[ 5, 'desc' ]],
        sPaginationType: "full_numbers",
        oLanguage: {
            oPaginate: {
                sFirst: "<<",
                sPrevious: "<",
                sNext: ">",
                sLast: ">>"
            },
            "sLengthMenu": "Показывать _MENU_ новостей на странице",
            "sZeroRecords": "К сожалению, ничего не найдено.",
            "sInfo": "Показано _START_-_END_ из _TOTAL_",
            "sInfoEmpty": "Показано 0 из 0",
            "sInfoFiltered": "(отфильтровано из _MAX_)",
            "sSearch": "Поиск:"
        }
    });

    function format ( d, id, del_perm, news_perm, name, url, publish, popular ) {
        // `d` is the original data object for the row
        var pub = 'Опубликовать';
        var pub_but = 'success';
        var pub_icon = 'check';
        if (publish == '1') {
            pub = 'Убрать из публикации';
            pub_but = 'warning';
            pub_icon = 'ban';
        }

        var pop = 'Сделать популярной';
        var pop_but = 'success';
        var pop_icon = 'star';
        if (popular == '1') {
            pop = 'Убрать из популярных';
            pop_but = 'warning';
            pop_icon = 'star-o';
        }

        var res = '<tr><td colspan="6"><table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';

        res += '<tr>'+
            '<td colspan="2">'+
            '<a href="'+url+'" class="btn btn-info btn-circle" title="Посмотреть на сайте" target="_blank"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
        if (news_perm) {
            res += '<a href="#" data-id="' + id + '" class="btn btn-' + pub_but + ' btn-circle publishing_news" title="' + pub + '" target="_blank"><i class="fa fa-' + pub_icon + '"></i></a>&nbsp;&nbsp;' +
                '<a href="#" data-id="' + id + '" class="btn btn-' + pop_but + ' btn-circle popularing_news" title="' + pop + '" target="_blank"><i class="fa fa-' + pop_icon + '"></i></a>&nbsp;&nbsp;' +
                '<a href="/admin/news/update?id=' + id + '" class="btn btn-warning btn-circle" title="Редактировать"><i class="fa fa-pencil"></i></a>';
        }
        else {
            res += '<a class="btn btn-' + pub_but + ' btn-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="У вас недостаточно прав, чтобы '+pub+'!"><i class="fa fa-' + pub_icon + '"></i></a>&nbsp;&nbsp;' +
                '<a class="btn btn-warning btn-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="У вас недостаточно прав для редактирования!"><i class="fa fa-pencil"></i></a>';
        }
        if (del_perm) {
            res += '&nbsp;&nbsp;<a href="#delete_news_modal" class="btn btn-danger btn-circle" data-toggle="modal" data-target="delete_news_modal" data-name="'+name+'" data-id="'+id+'" title="Удалить"><i class="fa fa-trash-o"></i></a>';
        }
        else {
            res += '&nbsp;&nbsp;<a class="btn btn-danger btn-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="У вас недостаточно прав для удаления!"><i class="fa fa-trash-o"></i></a>';
        }
        res += '</td></tr></table></td></tr>';
        return res;
    }

    $('body').on('click', '#dataTables-news td.manage', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if (tr.hasClass('shown') ) {
            tr.next().remove();
            tr.removeClass('shown');
        }
        else {
            tr.after(format(row.data(), tr.attr('data-id'), $('#dataTables-news').attr('data-delperm'), $('#dataTables-news').attr('data-newsperm'), tr.attr('data-name'), tr.attr('data-url'), tr.attr('data-publish'), tr.attr('data-popular')));
            tr.addClass('shown');
            $('[data-toggle="tooltip"]').tooltip();
        }
    } );

    $(window).resize(function(){

        $('#dataTables-news').DataTable();

    });
});