<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct()
    {
    }

    public function get(Channel $channel){
        return response($channel->messages, 200);
    }

    public function store(Request $request){
        $channel_id = $request->post('channel_id');
        $text = $request->post('text');

        if(auth()->check()){
            $user = auth()->user();

            Message::create([
                "channel_id" => $channel_id,
                "user_id" => $user->id,
                "text" => $text
            ]);

            return response('successfully created message', 200);
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
