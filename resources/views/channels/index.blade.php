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
        <div class="col-10" id="messages_screen"></div>
    </div>

@endsection
