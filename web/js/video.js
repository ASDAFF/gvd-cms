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

$(document).ready(function() {
    if (getCookie('video_tab') == '#video_block_view') {
        $('a[href="#video_table_view"]').attr('aria-expanded', 'false');
        $('a[href="#video_table_view"]').parent().removeClass('active');
        $('a[href="#video_block_view"]').attr('aria-expanded', 'true');
        $('a[href="#video_block_view"]').parent().addClass('active');

        $('#video_table_view').removeClass('active').removeClass('in');
        $('#video_block_view').addClass('active').addClass('in');
    }
    else {
        $('#video_table_view').addClass('active').addClass('in');
        deleteCookie('video_tab');
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        setCookie('video_tab', $(this).attr('href'));
    });



    $('#dataTables-video-cat').DataTable({
        responsive: true,
        pageLength:10,
        ordering: false,
        sPaginationType: "full_numbers",
        oLanguage: {
            oPaginate: {
                sFirst: "<<",
                sPrevious: "<",
                sNext: ">",
                sLast: ">>"
            },
            "sLengthMenu": "Показывать _MENU_ записей на странице",
            "sZeroRecords": "К сожалению, ничего не найдено.",
            "sInfo": "Показано _START_-_END_ из _TOTAL_",
            "sInfoEmpty": "Показано 0 из 0",
            "sInfoFiltered": "(отфильтровано из _MAX_)",
            "sSearch": "Поиск:"
        }
    });

    var video_table = $('#dataTables-video').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[ 3, 'desc' ]],
        sPaginationType: "full_numbers",
        oLanguage: {
            oPaginate: {
                sFirst: "<<",
                sPrevious: "<",
                sNext: ">",
                sLast: ">>"
            },
            "sLengthMenu": "Показывать _MENU_ видео на странице",
            "sZeroRecords": "К сожалению, ничего не найдено.",
            "sInfo": "Показано _START_-_END_ из _TOTAL_",
            "sInfoEmpty": "Показано 0 из 0",
            "sInfoFiltered": "(отфильтровано из _MAX_)",
            "sSearch": "Поиск:"
        }
    });

    function format ( d, id, del_perm, video_perm, name ) {
        // `d` is the original data object for the row
        var res = '<tr><td colspan="6"><table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';

        res += '<tr>'+
            '<td colspan="2">';
        if (video_perm) {
            res += '<a href="/admin/video/update-video?id=' + id + '" class="btn btn-warning btn-circle" title="Редактировать"><i class="fa fa-pencil"></i></a>';
        }
        else {
            res += '<a class="btn btn-warning btn-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="У вас недостаточно прав для редактирования!"><i class="fa fa-pencil"></i></a>';
        }
        if (del_perm) {
            res += '&nbsp;&nbsp;<a href="#delete_video_modal" class="btn btn-danger btn-circle" data-toggle="modal" data-target="delete_video_modal" data-name="'+name+'" data-id="'+id+'" title="Удалить"><i class="fa fa-trash-o"></i></a>';
        }
        else {
            res += '&nbsp;&nbsp;<a class="btn btn-danger btn-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="У вас недостаточно прав для удаления!"><i class="fa fa-trash-o"></i></a>';
        }
        res += '</td></tr></table></td></tr>';
        return res;
    }

    $('body').on('click', '#dataTables-video td.manage', function () {

        var tr = $(this).closest('tr');
        var row = video_table.row( tr );

        if (tr.hasClass('shown') ) {
            tr.next().remove();
            tr.removeClass('shown');
        }
        else {
            tr.after(format(row.data(), tr.attr('data-id'), $('#dataTables-video').attr('data-delperm'), $('#dataTables-video').attr('data-videoperm'), tr.attr('data-name')));
            tr.addClass('shown');
            $('[data-toggle="tooltip"]').tooltip();
        }
    } );

    $(window).resize(function(){

        $('#dataTables-video-cat').DataTable();
        $('#dataTables-video').DataTable();

    });

    // УДАЛЕНИЕ ВИДЕО

    $('body').on('click', 'a[data-target=delete_video_modal]', function(e) {
        e.preventDefault();
        var name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        $('#delete_video_modal .modal-body').html(name);
        $('#delete_video_modal #delete_video_button').attr('data-id', id);
        $('#delete_video_modal').modal('show');
    });

    $('#delete_video_modal #delete_video_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/video/delete-video',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // УДАЛЕНИЕ КАТЕГОРИИ

    $('body').on('click', 'a[data-target=delete_video_category_modal]', function(e) {
        e.preventDefault();
        var name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        $('#delete_video_category_modal .modal-body').html(name);
        $('#delete_video_category_modal #delete_video_category_button').attr('data-id', id);
        $('#delete_video_category_modal').modal('show');
    });

    $('#delete_video_category_modal #delete_video_category_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/video/delete-category',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });
});
