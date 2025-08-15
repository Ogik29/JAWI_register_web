<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contingent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class historyController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $contingents = Contingent::with(['event', 'user', 'players'])->where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('historyContingent.index', [
            'contingents' => $contingents
        ]);
    }
}
