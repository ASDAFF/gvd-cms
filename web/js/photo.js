$(document).ready(function() {
    $('body').on('click', '.upload_few_photos', function (e) {
        e.preventDefault();
        $('#photosform-cat').val($(this).attr('data-cat'));
        $('#photosform-images').click();
    });

    $('#photosform-images').change(function () {
        $(this).parents('form').submit();
    });

    $('.checked_photo').change(function () {
        var c = 0;
        $('.checked_photo:checked').each(function () {
            c = 1;
                return true;
        });
        if (c == 1) {
            $('#delete_chosen_photo').fadeIn();
        }
        else {
            $('#delete_chosen_photo').fadeOut();
        }
    });

    // ПАКЕТНОЕ УДАЛЕНИЕ ФОТО

    $('body').on('click', 'a#delete_chosen_photo', function(e) {
        e.preventDefault();
        var c = $('.checked_photo:checked').length;
        var id = $(this).attr('data-id');
        $('#delete_chosen_photo_modal .modal-body').html('Выбрано '+c+' фото.');
        $('#delete_chosen_photo_modal').modal('show');
    });

    $('#delete_chosen_photo_modal #delete_chosen_photo_button').click(function(e) {
        e.preventDefault();
        var ids = [];
        $('.checked_photo:checked').each(function () {
            ids.push($(this).attr('data-id'));
        });
        $.ajax({
            url: '/admin/photo/delete-few-photos',
            data: {'ids': ids},
            dataType: 'json',
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // УДАЛЕНИЕ ФОТО

    $('body').on('click', 'a[data-target=delete_photo_modal]', function(e) {
        e.preventDefault();
        var img = $(this).attr('data-img');
        var id = $(this).attr('data-id');
        $('#delete_photo_modal .modal-body').html('<img src="'+img+'" class="img-responsive">');
        $('#delete_photo_modal #delete_photo_button').attr('data-id', id);
        $('#delete_photo_modal').modal('show');
    });

    $('#delete_photo_modal #delete_photo_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/photo/delete-photo',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // УДАЛЕНИЕ КАТЕГОРИИ

    $('body').on('click', 'a[data-target=delete_photo_category_modal]', function(e) {
        e.preventDefault();
        var name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        $('#delete_photo_category_modal .modal-body').html(name);
        $('#delete_photo_category_modal #delete_photo_category_button').attr('data-id', id);
        $('#delete_photo_category_modal').modal('show');
    });

    $('#delete_photo_category_modal #delete_photo_category_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/photo/delete-category',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });

    // УДАЛЕНИЕ ВСЕХ ФОТО В КАТЕГОРИИ

    $('body').on('click', 'a[data-target=delete_all_photo_category_modal]', function(e) {
        e.preventDefault();
        var c = $(this).attr('data-count');
        var name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        $('#delete_all_photo_category_modal .modal-body').html('Категория "'+name+'" содержит '+c+' фото.');
        $('#delete_all_photo_category_modal #delete_all_photo_category_button').attr('data-id', id);
        $('#delete_all_photo_category_modal').modal('show');
    });

    $('#delete_all_photo_category_modal #delete_all_photo_category_button').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/photo/delete-all-photos-in-category',
            data: {'id': id},
            method: 'POST',
            success: function(response) {
                location.reload();
            }
        });
    });


    $('.grid').masonry({
        itemSelector: '.grid-item',
    });

    $(".galleryone").click(function(){
        $(".grid").addClass("one");
        $(".grid").removeClass("two three");
        $('.grid').masonry({
            itemSelector: '.grid-item'
        });
    });

    $(".gallerytwo").click(function(){
        $(".grid").addClass("two");
        $(".grid").removeClass("one  three");
        $('.grid').masonry({
            itemSelector: '.grid-item'
        });
    });

    $(".gallerythree").click(function(){
        $(".grid").addClass("three");
        $(".grid").removeClass("two one");
        $('.grid').masonry({
            itemSelector: '.grid-item'
        });
    });
    $(window).bind("load resize", function(){
        $('.grid').masonry({
            itemSelector: '.grid-item'
        });
    });
});
