<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Election;

class InviteController extends Controller
{
    public function index(Election $election)
    {
        return view('dash.elections.invite', compact('election'));
    }
}
