//the id of the currently selected channel
let CHANNEL = null;

//a list of all the messages currently shown.
//prevents needing to completely request all the messages, when a new message is posted
let MESSAGES = null;

//the last data recieved
let LAST_DATA = null;

//the id of the currently logged in user
let USER_ID = null;


$(document).ready(function(){
    //set the user_id var to the logged in user
    USER_ID = $("#user_id").val();

    //set a header so laravel won't get mad at you and throw a 419 to your face
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
        }
    });

    //when you click on one of the channel buttons, set the messages
    $('.set_channel_button').on("click", function(){
        //set this own class to be selected
        $(".set_channel_button").removeClass("selected");
        $(this).addClass("selected");

        //this would mean a channel is selected. So display the input
        //todo: make this different
        $("#messages_screen_creating").css("display", "block");

        //clear all other messages
        $("#messages_screen").empty();

        CHANNEL = $(this).attr("content");
        console.log("sending http get request");

        //sending a http get request to get all the messages
        $.ajax({
            type: 'get',
            url: '/api/messages/channel/' + CHANNEL,
            success :function(data){
                console.log('http request successfull');
                console.log(data);

                //as all messages are received, set them to MESSAGES, and the data itself to LAST_DATA
                LAST_DATA = data;
                MESSAGES = data.messages;

                //show all the values (name, description etc.) of the channel
                display_channel_values(data);

                //write all the messages to the dom
                display_messages();
            },
            error:function(e){
                console.log(e.error);
            }
        })

        //set the channel value to the posting input
        $("#channel_id").val(CHANNEL.toString());

    });
    $("#send_message").on("click", function(e){
        //prevent form from submitting to a different page
        e.preventDefault();

        $.ajax({
            type: 'post',
            url: '/api/messages',
            data: $("#text, #channel_id").serialize(),
            success:function(store){
                //message is send
            },
            error:function(e){
                console.log(e.error);
            }
        })

        $("#text").val("");
    });

    $("#people_list_icon").on("click", function(){
        if(CHANNEL == null){
            alert('no channel selected');
            return
        }

        $("#chat_screen_extra_panel_settings").css("display", "none");
        $("#chat_screen_extra_panel_people").css("display", "block");

        console.log("last data: ");
        console.log(LAST_DATA);

        if(LAST_DATA == null){
            return;
        }

        $("#chat_screen_panel_memberlist").empty();
        for(var i = 0; i < LAST_DATA.users.length; i++){
            var user = LAST_DATA.users[i];
            var html;

            if(is_admin(user.id)){
                // html = "<li class='memberlist_item' onclick='remove_user("+user.id+")'>" + user.name +"<div class='admin_icon'><p>a</p></div></li>";
                html = "<li class='memberlist_item' content='" + user.id +"'>" + user.name + "<div class='admin_icon'><p>a</p></div></li>"
            }
            else{
                // html = "<li class='memberlist_item' onclick='remove_user("  + user.id + ")'>" + user.name + "</li>";
                html = "<li class='memberlist_item' content='" + user.id +"'>" + user.name + "</li>"
            }
            $("#chat_screen_panel_memberlist").append(html);
        }

        //make the 'leave' button
        $("#chat_screen_panel_memberlist").append(
            "<a class='memberlist_item memberlist_item_leave' href='/channels/leave/channel:" + CHANNEL + "'>Leave channel</a>"
        );
    });

    //WHY THE FUCK IS THIS NOT TRIGGERING
    $(".memberlist_item").on("click", function(){
        alert('deleted');
    });

    //starting on settings page
    $("#settings_icon").on("click", function(){
        if(CHANNEL == null){
            alert('no channel selected');
            return
        }

        $("#chat_screen_extra_panel_people").css("display", "none");
        $("#chat_screen_extra_panel_settings").css("display", "block");

    });
});

function remove_user(user_id){
    //trying to send a http request, to remove the current user

    $.ajax({
        type: 'get',
        url: '/channels/leave/channel:' + CHANNEL + '?user=' + user_id,
        success :function(data){
            alert('suc7!');
        },
        error:function(e){
            console.log(e.error);
        }
    })
}

function reset_channel_values(){
    //todo
}

function display_channel_values(channel){
    $("#channel_name").html(channel.name);
    $("#channel_desc").html(channel.description);
}

//when a new message is recieved from the broadcast, add it to the list and display it.
//this is done via this message so that you don't have to completely reload all messages for every message.
//called from the listener(recources/js/app.js)
function add_message(message){
    MESSAGES.push(message);

    //ask for the html markup for the message, and add it to the dom
    var html = get_html(message);
    $("#messages_screen").prepend(html);
}

//basically adds everyting in the MESSAGES variable to the chatpage
function display_messages(){
    for(var i = 0; i < MESSAGES.length; i++){
        //ask for the html markup for the message, and add it to the dom
        var html = get_html(MESSAGES[i]);
        $("#messages_screen").prepend(html);
    }
}

//returns if you are an admin in the currently selected channel
function is_admin(user_id=null){
    if(LAST_DATA == null || LAST_DATA.admin_id == null){
        return false;
    }

    //if there is nothing passed, set it to the currently logged in user
    if(user_id == null){
        user_id = USER_ID;
    }

    return LAST_DATA.admin_id == user_id;
}

//returns a proper bit of personalized html
function get_html(message){
    var formated_time = format_time(message["created_at"]);
    return "" +
        "<div class='message'>\n" +
        "<p class='message_name_time'>" + message["user"]["name"] + " | " + formated_time  + "</p>" +
        "<p class='message_text'>" + message["text"] + "</p>" +
        "</div>";
}

function format_time(date_string){
    var date = new Date(date_string);

    //nice trick to add the correct amount of leading zeros to the number.
    //it adds 100 to it (7 minutes -> 107, 13 minutes -> 113), and then removes the first char (107 -> 07, 113 -> 13)
    var hour = (100 + date.getHours()).toString().substring(1);
    var minute = (100 + date.getMinutes()).toString().substring(1);

    return hour + ":" + minute;
}
