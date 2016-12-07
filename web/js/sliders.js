$(document).ready(function () {
    $('#dataTables-sliders').DataTable({
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

        $('#dataTables-sliders').DataTable();

    });

    // УДАЛЕНИЕ СЛАЙДЕРА

    $('body').on('click', 'a[data-target=delete_sliders_modal]', function(e) {
        e.preventDefault();
        var name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        $('#delete_sliders_modal .modal-body').html(name);
        $('#delete_sliders_modal #delete_sliders_button').attr('data-id', id);
        $('#delete_sliders_modal').modal('show');
    });

    $('#delete_sliders_modal #delete_sliders_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/sliders/delete',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // УДАЛЕНИЕ ПОЛЯ

    $('body').on('click', 'a[data-target=delete_pages_data_modal]', function(e) {
        e.preventDefault();
        var name = $(this).attr('data-name');
        var key = $(this).attr('data-key');
        var page = $(this).attr('data-page');
        $('#delete_pages_data_modal .modal-body').html(name);
        $('#delete_pages_data_modal #delete_pages_data_button').attr('data-key', key);
        $('#delete_pages_data_modal #delete_pages_data_button').attr('data-page', page);
        $('#delete_pages_data_modal').modal('show');
    });

    $('#delete_pages_data_modal #delete_pages_data_button').click(function(e) {
        e.preventDefault();
        var key = $(this).attr('data-key');
        var page = $(this).attr('data-page');
        $.ajax({
            url: '/admin/pages/delete-data',
            data: {'key': key, 'page': page},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });
});