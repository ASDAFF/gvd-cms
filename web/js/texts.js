$(document).ready(function () {
    $('#dataTables-texts').DataTable({
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

        $('#dataTables-texts').DataTable();

    });

    // УДАЛЕНИЕ

    $('body').on('click', 'a[data-target=delete_texts_modal]', function(e) {
        e.preventDefault();
        var name = $(this).attr('data-name');
        var value = $(this).attr('data-value');
        var id = $(this).attr('data-id');
        $('#delete_texts_modal .modal-body').html(name+': '+value);
        $('#delete_texts_modal #delete_texts_button').attr('data-id', id);
        $('#delete_texts_modal').modal('show');
    });

    $('#delete_texts_modal #delete_texts_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/texts/delete',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });
});