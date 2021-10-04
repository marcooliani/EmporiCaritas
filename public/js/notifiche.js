var title = $(document).attr("title");

$('#all_reads').click(function() {
    $.ajax({
        url: "/notifiche/cancella_avvisi",
        dataType: "json",
        cache: false,
        success: function(response) {
            $(document).attr("title", title);
            notifiche();
        }
    });
});

function notifiche() {
        $('#notify_counter').hide();

        $.ajax({
            url: "/notifiche/avvisi",
            dataType: "json",
            cache: false,
            success: function(message) {
                // ANIMATEDLY DISPLAY THE NOTIFICATION COUNTER.
                if(message.responseText2 != "0") {
                    $('#notify_counter')
                        .show()
                        .css({ opacity: 0 })
                        .text(message.responseText2)              // ADD DYNAMIC VALUE (YOU CAN EXTRACT DATA FROM DATABASE OR XML).
                        .css({ top: '0px', left: '10px' })
                        .animate({ top: '2px', opacity: 1 }, 500);

                    $('#notify_icon').removeClass('fa-globe').addClass('fa-globe fa-inverse');
                    $(document).attr("title", "(" + message.responseText2 + ") " + title);
                    
                    //var audio = new Audio('/public/files/fbsound.mp3');
                    //audio.play();
                }
                else {
                    $('#notify_counter').hide();
                    $('#notify_icon').removeClass('fa-globe fa-inverse').addClass('fa-globe');
                    $(document).attr("title", title);
                }

                $('#messages').html(message.responseText1);
                $('.messaggio').click(function() {
                    var idnotifica = $(this).attr('id_notifica');
                    $.ajax({
                        url: "/notifiche/letto/" + idnotifica,
                        dataType: "json",
                        cache: false,
                        success: function(response) {
                            $(document).attr("title", title);
                        }
                    });

                    var url = $(this).attr('data-url');
                    window.location.href = url;
                });
            }
        });
}

$(document).ready(function () {
    notifiche();
    /* 10 minutes */
    setInterval(notifiche, 600000 );
    
    $('#notify_button').click(function () {

        // TOGGLE (SHOW OR HIDE) NOTIFICATION WINDOW.
        $('#notifications').fadeToggle('fast', 'linear', function () {
            if ($('#notifications').is(':hidden')) {
            }
            if($('#chats').is(':hidden')) {
            }
            else {
                $('#chats').hide();
                $('#chat_icon').removeClass('fa-comment fa-inverse').addClass('fa-comment');
             //   $('.messaggio_chat').removeAttr('style');
            }
        });

        $('#notify_counter').fadeOut('slow');                 // HIDE THE COUNTER.
        $('#notify_icon').removeClass('fa-globe').addClass('fa-globe fa-inverse');
        $(document).attr("title", title);

        return false;
    });

    // HIDE NOTIFICATIONS WHEN CLICKED ANYWHERE ON THE PAGE.
    $(document).click(function () {
        $('#notifications').hide();

        // CHECK IF NOTIFICATION COUNTER IS HIDDEN.
        if ($('#notify_counter').is(':hidden')) {
            // CHANGE BACKGROUND COLOR OF THE BUTTON.
            // $('#notify_button').css('background-color', '#2E467C');
            $('#notify_icon').removeClass('fa-globe fa-inverse').addClass('fa-globe');
            $('.messaggio').removeAttr('style');
        }

    });

    $('#notifications').click(function () {
        return false;       // DO NOTHING WHEN CONTAINER IS CLICKED.
    });

});
