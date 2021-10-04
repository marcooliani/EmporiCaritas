//this function can remove a array element.
Array.remove = function(array, from, to) {
    var rest = array.slice((to || from) + 1 || array.length);
    array.length = from < 0 ? array.length + from : from;
                
    return array.push.apply(array, rest);
};
        
//this variable represents the total number of popups can be displayed according to the viewport width
var total_popups = 0;
            
//arrays of popups ids
var popups = [];

var refreshIntervalId = '';
        
//this is used to close a popup
function close_popup(id) {
    for(var iii = 0; iii < popups.length; iii++) {
        if(id == popups[iii]) {
            Array.remove(popups, iii);
            //document.getElementById(id).style.display = "none";
            $('#'+ id).remove(); // Dato che ho fatto l'append del boxino, devo rimuoverlo per evitare i doppioni
                                 // che poi si vanno a sovrapporre...
            clearInterval(refreshIntervalId);
            calculate_popups();
                        
            return;
        }
    }   
}
        
//displays the popups. Displays based on the maximum number of popups that can be displayed on the current viewport width
function display_popups() {
    var right = 250;            
    var iii = 0;
                
    for(iii; iii < total_popups; iii++) {
        if(popups[iii] != undefined) {
            var element = document.getElementById(popups[iii]);
            element.style.right = right + "px";
            right = right + 320;
            element.style.display = "block";
        }
    }
                
    for(var jjj = iii; jjj < popups.length; jjj++) {
        var element = document.getElementById(popups[jjj]);
        element.style.display = "none";
    }
}
            
//creates markup for a new popup. Adds the id to popups array.
function register_popup(conv, id, name) {
    for(var iii = 0; iii < popups.length; iii++) {   
        //already registered. Bring it to front.
        if(conv == popups[iii]) {
            Array.remove(popups, iii);        
            popups.unshift(conv);            
            calculate_popups();
                    
            return;
        }
    }               
                
    var element = '<div class="popup-box" id="'+ conv +'">';
    element = element + '<div class="popup-head" >';
    element = element + '<div class="popup-head-left" id="head'+ conv +'">'+ name +'</div>';
    element = element + '<div class="popup-head-right"><a href="javascript:close_popup(\''+ conv +'\');">&#10005;</a></div>';
    element = element + '<div style="clear: both"></div></div><div id="conv'+ conv +'" class="popup-messages"></div><div class="popup-write">'+
                                    '<textarea id="send'+ conv +'" name="send" receiver="'+ id +'" rows="1" placeholder="Scrivi un messaggio" class="form-control"></textarea>'+
                                    '</div></div>';
    $("body").append(element);  
        
    popups.unshift(conv);                   
    calculate_popups();

    listen(conv);
    refreshIntervalId = setInterval(function() { listen(conv) }, 1000);

    $("textarea#send" + conv ).on('keyup', function(e) {
        e.preventDefault();

        if (e.which == 13 && ! e.shiftKey) {
            var idreceiver = $(this).attr('receiver');
            var msg = $(this).val();

            $.ajax({
                url: "/messaggi/scrivi",
                dataType: "json",
                data: {'conv': conv, 'recv': idreceiver, 'msg': msg },
                cache: false,
                success: function(response) {
                    $('#conv' + conv).append(response);
                    $("textarea#send" + conv).val('');
                    $("textarea#send" + conv).focus();
                }
            });
        }

        return false;
    });

    $('.popup-head-left').click(function() {
        $('.popup-messages').toggle();
        $('.popup-write').toggle();
        return false;
    });
}
            
//calculate the total number of popups suitable and then populate the toatal_popups variable.
function calculate_popups() {
    var width = window.innerWidth;

    if(width < 540) {
        total_popups = 0;
    }
    else {
        width = width - 200;
        //320 is width of a single popup box
        total_popups = parseInt(width/320);
    }
                
    display_popups();                
}

function listen(idconversazione) {
    $.ajax({
        url: "/messaggi/update_chat/" + idconversazione,
        dataType: "json",
        cache: false,
        success: function(response) {
            $("#conv" + idconversazione).append(response);
            $('#conv' + idconversazione).animate({ scrollTop: $('#conv' + idconversazione).get(0).scrollHeight }, 20);
        }
    });
}

//recalculate when window is loaded and also when window is resized.
window.addEventListener("resize", calculate_popups);
window.addEventListener("load", calculate_popups);


