@extends('layouts.app')


@section("content")
    <div class="row" id="app">
        <div class="col-2">
            <table class="table">
                @foreach($channels as $channel)
                    <tr class="set_channel_button" id="channel_button_{{ $channel->id }}" content="{{ $channel->id }}">
                        <td>{{ $channel->name }}</td>
                    </tr>
                @endforeach
            </table>

        </div>
        <div class="col-10" id="chat_screen">
            <div id="messages_screen"></div>
            <div id="messages_screen_creating">
                <form>
                    @csrf
                    <input type="hidden" value="" name="channel_id" id="channel_id"/>
                    <div class="form-group">
                        <input type="text" name="text" id="text" class="form-control" placeholder="Type your message here"/>
                        <input type="submit" class="form-control" id="send_message" value="Send"/>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
