<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\User;
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

        //if an image is passed along, save it locally
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

    public function leave_channel(Request $request, Channel $channel){
        $user = $request->has('user') ? User::findOrFail($request->input('user')) : Auth::user();

        //if the user and channel are valid values
        if($user == null || $channel == null){
            return response("channel could not be found, or user is not authenticated", 404);
        }
        else{
            //if the user is in a channel, leave it
            if(($channel->users()->where('user_id', $user->id)->exists())){
                $channel->users()->detach($user);
            }

            //if there are no more users in a channel, delete the channel
            if($channel->users->count() <= 0){
                $channel->delete();
                return redirect('/channels');
            }

            //if the user is the admin of the channel, select a new admin
            if($channel->admin->id == $user->id){
                //get the first joined user
                $users = $channel->users;
                $first_user = $users[0];

                //set the first user as the new admin
                $channel->admin_id = $first_user->id;
                $channel->push();
            }

        }

        return redirect('/channels');
    }

    //lets users join a channel via a link
    //example link: http://localhost:8000/channels/join/channel:1
    public function join_via_link(Request $request, Channel $channel){
        $user = $request->has('user') ? User::findOrFail($request->input('user')) : Auth::user();

        if($user == null || $channel == null){
            return response("channel or user is not found", 404);
        }

        if(!($channel->users()->where('user_id', $user->id)->exists())){
            $channel->users()->save($user);
        }

        return redirect('/channels');
    }
}
