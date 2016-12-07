$(document).ready(function() {
    $('#dataTables-log-enter').DataTable({
        responsive: true,
        pageLength:10,
        order: [[ 2, 'desc' ]],
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

    $('#dataTables-log-news').DataTable({
        responsive: true,
        pageLength:10,
        order: [[ 3, 'desc' ]],
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

        $('#dataTables-log-enter').DataTable();
        $('#dataTables-log-news').DataTable();

    });
});
