function check_all() {
    $('input[class="item_checkbox"]:checkbox').each(function(){
        if($('input[class="check_all"]:checkbox:checked').length == 0){
            $(this).prop('checked', false);
        }else {
            $(this).prop('checked', true);
        }
    });
}

function delete_all(){

    $(document).on('click', '.delete_all_submit', function (){
            $("#form_data_delete").submit();
    });

    $(document).on('click', '.btn-delete', function (){
        var item_checked = $('input[class="item_checkbox"]:checkbox').filter(":checked").length;
        if (item_checked > 0){
            $('.record_count').text(item_checked);
            $('.not_empty_record').removeClass('hidden');
            $('.empty_record').addClass('hidden');
        }else {
            $('.record_count').text('');
            $('.not_empty_record').addClass('hidden');
            $('.empty_record').removeClass('hidden');
        }
        $('#delete-all-admins').modal('show');
    });

}

$(document).ready(function() {

    // setTimeout(function() {
    //     if($('html').hasClass('loaded')){
    //         $('#test1.nav-link.nav-menu-main.menu-toggle').click();
    //     }
    // },2000);

    $(document).on('click', '#logout-button', function (e){
        e.preventDefault();
        $('#logout-form').submit();
    });

    $(document).on('click', '#absence-check', function (e){
        $('.absence').css('display', 'none');
        $('.absence-inputs').removeClass('d-none');
        $('#absence-check').addClass('js-clicked');
        $('#ab1').prop('required', true);
        $('#ab2').prop('required', true);
    });

    $(document).on('click', '#absence-check.js-clicked', function (e){
        $('.absence-inputs').addClass('d-none');
        $('.absence').css('display', 'flex');
        $('.row.absence').removeClass('d-none');
        $('#absence-check').removeClass('js-clicked');
        $('#ab1').prop('checked', false);
        $('#ab2').prop('checked', false);
        $('#ab1').prop('required', false);
        $('#ab2').prop('required', false);

    });

    $(document).on('submit', '.prevent-multiple-submit-form', function(){
        $('.prevent-multiple-submit-button').attr('disabled', true);
        $('.prevent-multiple-submit-form .prevent-multiple-submit-button .fa-spinner').show();
        $('.prevent-multiple-submit-form .prevent-multiple-submit-button .ft-thumbs-up').hide();
    });
});
