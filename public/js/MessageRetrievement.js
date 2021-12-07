let CHANNEL = null;

$(document).ready(function(){
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
        }
    });

    $('.set_channel_button').on("click", function(){
        $("#messages_screen").empty();

        CHANNEL = $(this).attr("content");
        console.log("sending http get request");

        $.ajax({
            type: 'get',
            url: '/api/messages/channel/' + CHANNEL,
            success :function(data){
                console.log('http request successfull');
                console.log(data);

                for(var i = 0; i < data.length; i++){
                    var html = get_html(data[i]);
                    $("#messages_screen").append(html);
                }
            },
            error:function(e){
                console.log(e.error);
            }
        })
    })
});

function get_html(message){
    var formated_date = format_date(message["created_at"]);
    return "" +
        "<div class='message'>\n" +
        "<p class='message_name_time'>" + message["user"]["name"] + " | " + formated_date  + "</p>" +
        "<p class='message_text'>" + message["text"] + "</p>" +
        "</div>";
}

function format_date(date_string){
    var date = new Date(date_string);

    var hour = (100 + date.getHours()).toString().substring(1);
    var minute = (100 + date.getMinutes()).toString().substring(1);

    return hour + ":" + minute;
}
