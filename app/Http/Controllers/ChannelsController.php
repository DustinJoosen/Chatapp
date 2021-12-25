<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(){
        $channels = Auth::user()->channels;

        return view("channels.index", [
            "channels" => $channels
        ]);
    }

    public function create(){
        return view('channels.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'description' => '',
            'image' => ['image']
        ]);

        $data = array_merge(
            $data,
            ["admin_id" => Auth::id()]
        );


        if(isset($data["image"])){
            //store the image and get the name
            $image_fp = $data["image"]->store('uploads', 'public') ?? "notfound.png";

            $data = array_merge(
                $data,
                ["image" => $image_fp]
            );
        }

        //make the channel
        $channel = Channel::create($data);

        //get user and attach to channel
        $user = Auth::user();
        $user->channels()->save($channel);

        return redirect('/channels');
    }

    public function join_via_link(Channel $channel){
        $user = Auth::user();
        if($user == null || $channel == null){
            return response("channel or user is not found", 404);
        }

        if(!($channel->users()->where('user_id', $user->id)->exists())){
            $channel->users()->save($user);
        }

        return redirect('/channels');
    }
}
