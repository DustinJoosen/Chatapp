@extends('layouts.app')

@section("content")
    <div class="row" style="" id="app">
        <div class="col-3" id="channels_list">
            @foreach($channels as $channel)
                <div class="set_channel_button" id="channel_button_{{ $channel->id }}" content="{{ $channel->id }}">
                    <p>
                        <img src="/storage/{{ $channel->image ?? "notfound.png" }}" class="channel_list_image"/>
                        {{ $channel->name }}
                    </p>
                </div>
            @endforeach

        </div>
        <div class="col-6 card" id="chat_screen">
            <div id="channel_info" style="padding:5px">
                <h4 style="text-align: center" id="channel_name"></h4>
                <h6 style="text-align: center" id="channel_desc"></h6>
                <div style="text-align:center">
                    <i id="people_list_icon" class="fa fa-users" style="font-size:24px; cursor:pointer"></i> |
                    <i id="settings_icon" class="fa fa-gear" style="font-size:24px; cursor:pointer"></i>
                </div>
            </div>
            <hr/>
            <div id="messages_screen"></div>
            <div id="messages_screen_creating" class="card-footer">
                <form>
                    @csrf
                    <input type="hidden" value="" name="channel_id" id="channel_id"/>
                    <div class="">
                        <input type="text" name="text" id="text" class="form-control" placeholder="Type your message here"/>
                        <input type="submit" class="form-control" id="send_message" value="Send"/>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-3" id="chat_side_panel">
            <div id="chat_screen_extra_panel_people" style="display: none">
                <h3>Members</h3>
                <ul id="chat_screen_panel_memberlist">

                </ul>
            </div>
            <div id="chat_screen_extra_panel_settings" style="display: none">
                <h3>Settings</h3>
            </div>
        </div>

    </div>

@endsection
