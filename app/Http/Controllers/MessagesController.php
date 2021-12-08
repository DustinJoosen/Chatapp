<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct()
    {
    }

    public function get(Channel $channel){
        //include the user and channel inside the channel
        $channel = $channel::with(['messages.channel', 'messages.user'])->get()->find($channel->id);
        $messages = $channel->messages;

        return response($messages, 200);
    }

    public function store(Request $request){
        $channel_id = $request->post('channel_id');
        $text = $request->post('text');

        if($channel_id == null || $text == null){
            return response("missing data. 'channel_id' and 'text' are required fields", 422);
        }

        if(auth()->check()){
            $user = auth()->user();

            $message = Message::create([
                "channel_id" => $channel_id,
                "user_id" => $user->id,
                "text" => $text
            ]);

            //include the user, so that it gets send along with the broadcast
            $message = $message::with(['user'])->get()->find($message->id);

            //invoke the message posted event, broadcasting the update
            event(new MessagePosted($channel_id, $message));

            return response($message, 200);
        }
        else{
            return response('you need to be authorized to post a message', 401);
        }
    }

    public function remove(Request $request, Message $message){
        $message->delete();
        return response($message, 200);
    }
}
