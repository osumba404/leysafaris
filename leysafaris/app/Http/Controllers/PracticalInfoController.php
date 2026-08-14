<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class PracticalInfoController extends Controller
{
    public function index(): View
    {
        $settings = Setting::allGrouped();

        return view('practical.index', compact('settings'));
    }
}
