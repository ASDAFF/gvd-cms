$(document).ready(function() {
    var table = $('#dataTables-users').DataTable({
        responsive: true,
        pageLength:10,
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
    function format ( d, phone, id, root ) {
        // `d` is the original data object for the row
        var res = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        if (phone != '') {
            res += '<tr>'+
                '<td>Телефон:</td>'+
                '<td>'+phone+'</td>'+
                '</tr>';
        }
        res += '<tr>'+
            '<td colspan="2"><a href="/admin/user/view?id='+id+'" class="btn btn-warning btn-circle" title="Редактировать"><i class="fa fa-pencil"></i></a>';
        if (root) {
            res += '&nbsp;&nbsp;<a href="/admin/user/delete?id=' + id + '" class="btn btn-danger btn-circle" title="Удалить"><i class="fa fa-trash-o"></i></a>';
        }
        res += '</td></tr></table>';
        return res;
    }
    $('#dataTables-users tbody').on('click', 'td.manage', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data(), tr.attr('data-phone'), tr.attr('data-id'), $('#dataTables-users').attr('data-root')) ).show();
            tr.addClass('shown');
        }
    } );

    var table_roles = $('#dataTables-roles').DataTable({
        responsive: true,
        pageLength:10,
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

    $(window).resize(function(){

        $('#dataTables-users').DataTable();
        $('#dataTables-roles').DataTable();

    });
    
    $('#user_role_switch input').change(function (e) {
        if (!$(this).attr('disabled')) {
            var user = $(this).attr('data-user');
            var role = $(this).attr('id');
            $.ajax({
                url: '/admin/user/change-role',
                data: {
                    'user': user,
                    'role': role
                },
                method: 'POST'
            });
        }
    });

    $('#user_status_switch').change(function (e) {
        var user = $(this).attr('data-user');
            $.ajax({
                url: '/admin/user/change-status',
                data: {
                    'id': user
                },
                method: 'POST'
            });
    });

    $('#change_ava').click(function (e) {
        e.preventDefault();
        $('#change_ava_form').click();
    });

    $('#change_ava_form').dropzone({
        url: "/admin/user/change-avatar",
        uploadMultiple: false,
        paramName: 'UserAvatarForm[avatar]',
        complete: function () {
            $.ajax({
                url: '/admin/user/get-avatar',
                method: 'POST',
                success: function (response) {
                    $('.userpicimg').attr('src', response);
                }
            });
        }
    });
});
