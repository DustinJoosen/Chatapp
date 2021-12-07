@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Welcome to novachat</h3>

    @foreach($messages as $message)
        <form action="/api/messages/{{ $message->id }}" method="post">
            @csrf
            @method("delete")
            <p>{{ $message->channel->name }} | {{ $message->text }} | {{ $message->user->name }} | {{ $message->created_at }} | <input type="submit" value="x"/></p>

        </form>
    @endforeach

    <form action="api/messages" method="post">
        @csrf

        <input type="text" name="text" placeholder="message" required/>
        <input type="int" value="1" name="channel_id" required placeholder="channel"/>
        <input type="submit"/>
    </form>
</div>
@endsection
