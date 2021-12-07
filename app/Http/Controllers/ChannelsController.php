<?php

namespace App\Http\Controllers;

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
}
