<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class HomepageController extends Controller
{
    public function homepage()
    {
        // TODO: Query for 'show on homepage'
        $plans = Plan::all();
        return view('home', ['plans' => $plans]);
    }
}