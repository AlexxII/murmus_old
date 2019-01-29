
$(document).ready(function () {
    $(".news_content").click(function () {
        if ($(this).css("height") === "60px") {
            $(this).css("height", "auto");
            $(this).css("max-height", "300px");
        } else {
            $(this).css("height", "60px");
        }
    });
});

// скрипт при нажатии на поле news_content
$(document).ready(function () {
    $(".news_content").dblclick(function () {
        $(this).attr('contenteditable', 'true');
    });
});


// скрипт при нажатии на поле news_content
$(document).ready(function () {
    $(".news_content").dblclick(function () {
//            $(this).attr('contenteditable', 'false');
    });
});

$(function() {
    $('.right_news_wrap .news_block').draggable({
        axis: "x"
    });
});

$(document).ready(function () {
    $(".right_news_wrap").fadeOut();
    $(".back").css("display", "none");
});


// скрипт для подвижного блока
$(document).ready(function () {
    $(function() {
        $('.trash').droppable({
            drop: function(event, ui) {
                $(ui.draggable).remove();
            }
        });
    });
});

// скрипт при нажатии на клавишу анализ дублей
$(document).ready(function () {
    $(".button24").click(function () {
        var toplen = $(window).scrollTop();
        var dec = $(this).closest(".news_block");
         $(".overlay").fadeIn();
        dec.find(".button24").fadeOut();
        $(".main_wrap").css('width', '95%');
        $(".main_wrap").css('margin-left', '3%');
        $(".left_news_wrap").css("width", "50%");
        $(".right_news_wrap").css("width", "46%");
        $(".right_news_wrap").css("position", "absolute");
        $(".right_news_wrap").css("z-index", "101");
        if (toplen != 0) {
            toplen = toplen + 10;
            $(".right_news_wrap").css("top", toplen);
            var docheight = ($(window).height() - 20);
            $(".right_news_wrap").css('max-height', docheight, 'important');
        } else {
            var docheight = ($(window).height() - 20);
            $(".right_news_wrap").css("top", "10px");
            $(".right_news_wrap").css('max-height', docheight, 'important');
        }
        $(".right_news_wrap").css("right", "10px");
        $(".right_news_wrap").css('overflow', 'auto', 'important');
        $(".right_news_wrap").fadeIn(100);
        dec.find(".back").fadeIn();
        dec.css("position", "fixed");
        dec.css("top", "100px");
        dec.css("z-index", "101");
        dec.css("left", "30px");
        dec.css("width", "800px");
        dec.children(".news_content").css("overflow-y", "scroll");
        dec.children(".news_content").css("max-height", "300px");
        $("body").css("overflow-y", "hidden");
    });
})

// функция возрата в положение чтения
function ex() {
    $(".news_block").each(function(){
        if($(this).css("position")=="fixed"){
            var dec = $(this).closest(".news_block");
            dec.css("position", "inherit");
            dec.css("width", "auto");
            dec.animate({ scrollTop: dec.prop("scrollHeight")}, 1000);
            $(".right_news_wrap").fadeOut(200);
            $(".left_news_wrap").css("width", "100%");
            $("body").css("overflow-y", "auto");
            $(".main_wrap").css('width', '80%');
            $(".main_wrap").css('margin-left', '9%');
            $(".back").css("display", "none");
            $(".overlay").fadeOut();
            dec.children(".news_content").css("overflow-y", "hidden");
        }
    });
}

$(document).ready(function () {
    $('html').keydown(function (e) { //отлавливаем нажатие клавиш
        if (e.keyCode == 27) { //если нажали ESC, то вызов ex
            ex();
        }
    });
});

// нажатие клавиши назад - из режима анализа
$(document).ready(function () {
    $(".back").click(ex);
});

$(document).ready(function () {                         // при нажатии на поле news_service_block
    $(".left_news_wrap .news_block .news_service_block").click(ex);
});


