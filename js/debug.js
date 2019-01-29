$(document).ready(function() {
    $('.load_badnews').click(function() {

        var formData = 'id=6401';

        $.ajax({
            type:"POST",
            url:"/debug/debug_proc.php",
            data:formData,
            cache:false,
            dataType:"HTML",
            contentType:false,
            processData:false,
            success:function (data) {
//                $('.text_area').val(data);
//                $('.right_side').html(data);
                $('.right_side').append(data);
            }
        });
    });
});