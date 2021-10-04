var title = $(document).attr("title");

function sidebar() {
    $.ajax({
            url: "/messaggi/loadsidebar",
            dataType: "json",
            cache: false,
            success: function(message) {
                $('.sidebar-userlist').html(message);
                $('.sidebar-singleuser').click(function() {
                    var idconversazione = $(this).attr('id_conv');
                    var idsender = $(this).attr('peer');
                    var namesender = $(this).attr('nome_emporio');

                    register_popup(idconversazione, idsender, namesender);
        
                    $.ajax({ 
                        url: "/messaggi/letto/" + idconversazione,
                        dataType: "json",
                        cache: false,
                        success: function(response) { 
                            $(document).attr("title", title);
                        }
                    });

                    $.ajax({
                        url: "/messaggi/visualizza/" + idconversazione,
                        dataType: "json",
                        cache: false,
                        success: function(response) {
                            $("#conv" + idconversazione).html(response);
                            $('#conv' + idconversazione).animate({ scrollTop: $('#conv' + idconversazione).get(0).scrollHeight }, 20);
                            $("textarea#send" + idconversazione).val('');
                            $("textarea#send" + idconversazione).focus();
                        }
                    });
                });
            }
    });

    var element = '<div class="sidebar">';
    element = element + '<div class="sidebar-head" >Empori</div>';
    element = element + '<div class="sidebar-userlist">&nbsp;</div>';
    element = element + '</div>';
    $("body").append(element);

    return false;
}

function messaggi() {
        $('#chat_counter').hide();

        $.ajax({
            url: "/messaggi/check",
            dataType: "json",
            cache: false,
            success: function(message) {
                // ANIMATEDLY DISPLAY THE NOTIFICATION COUNTER.
                if(message.responseText2 != "0") {
                    $('#chat_counter')
                        .show()
                        .css({ opacity: 0 })
                        .text(message.responseText2)              // ADD DYNAMIC VALUE (YOU CAN EXTRACT DATA FROM DATABASE OR XML).
                        .css({ top: '0px', left: '10px' })
                        .animate({ top: '2px', opacity: 1 }, 500);

                    $('#chat_icon').removeClass('fa-comment').addClass('fa-comment fa-inverse');
                    $(document).attr("title", "(" + message.responseText2 + ") " + title);
                    
                    //var audio = new Audio('/public/files/fbsound.mp3');
                    //audio.play();
                }
                else {
                    $('#chat_counter').hide();
                    $('#chat_icon').removeClass('fa-comment fa-inverse').addClass('fa-comment');
                    $(document).attr("title", title);
                }

                $('#messages_chat').html(message.responseText1);
                $('.messaggio_chat').click(function(e) {
                    e.preventDefault();
                    var idconversazione = $(this).attr('id_conv');
                    var idsender = $(this).attr('id_sender');
                    var namesender = $(this).attr('name_sender');
                    $.ajax({
                        url: "/messaggi/letto/" + idconversazione,
                        dataType: "json",
                        cache: false,
                        success: function(response) {
                            $(document).attr("title", title);
                        }
                    });
                    $('#chats').fadeToggle('fast', 'linear');

                    register_popup(idconversazione, idsender, namesender);
                    $.ajax({ 
                        url: "/messaggi/visualizza/" + idconversazione,
                        dataType: "json",
                        cache: false,
                        success: function(response) {
                            $("#conv" + idconversazione).html(response);
                            $('#conv' + idconversazione).animate({ scrollTop: $('#conv' + idconversazione).get(0).scrollHeight }, 20);
                            $("textarea#send" + idconversazione).val('');
                            $("textarea#send" + idconversazione).focus();
                        }
                    });

                }); 
            }
        });

        return false;
}

$(document).ready(function () {
    sidebar();
    messaggi();
    /* 10 minutes */
    setInterval(messaggi, 600000 );
    
    $('#chat_button').click(function () {

        // TOGGLE (SHOW OR HIDE) NOTIFICATION WINDOW.
        $('#chats').fadeToggle('fast', 'linear', function () {
            if ($('#chats').is(':hidden')) {
            }
            if($('#notifications').is(':hidden')) {
            }
            else {
                $('#notifications').hide();
                $('#notify_icon').removeClass('fa-globe fa-inverse').addClass('fa-globe');
            //    $('.messaggio').removeAttr('style');
            }
            
        });

        $('#chat_counter').fadeOut('slow');                 // HIDE THE COUNTER.
        $('#chat_icon').removeClass('fa-comment').addClass('fa-comment fa-inverse');
        $(document).attr("title", title);

     /*   $.ajax({
            url: "/messaggi/cancella_messaggi",
            dataType: "json",
            cache: false,
            success: function(response) {
                $(document).attr("title", title);
            }
        }); */

        return false;
    });

    // HIDE NOTIFICATIONS WHEN CLICKED ANYWHERE ON THE PAGE.
    $(document).click(function () {
        $('#chats').hide();

        // CHECK IF NOTIFICATION COUNTER IS HIDDEN.
        if ($('#chat_counter').is(':hidden')) {
            // CHANGE BACKGROUND COLOR OF THE BUTTON.
            // $('#notify_button').css('background-color', '#2E467C');
            $('#chat_icon').removeClass('fa-comment fa-inverse').addClass('fa-comment');
            $('.messaggio_chat').removeAttr('style');
        }

    });

    $('#chats').click(function () {
        return false;       // DO NOTHING WHEN CONTAINER IS CLICKED.
    });

    $('.sidebar-head').click(function() {
        $('.sidebar-userlist').toggle(function() {
        /*    if ($('.sidebar-userlist').is(":hidden")) {
                $('.sidebar-head').css("background", "#c0392b");
                $('.sidebar-head').css("color", "#fff");
            }
            else if ($('.sidebar-userlist').is(":visible")) {
                $('.sidebar-head').css("background", "#dedede");
                $('.sidebar-head').css("color", "#333");
            } */

        });

        return false;
    });

});
