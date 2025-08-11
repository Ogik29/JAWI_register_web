<?php

namespace App\Http\Controllers;
use App\Models\Event;

use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    public function registEvent($slug){
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('register.registEvent', compact('event'));
    }
}
