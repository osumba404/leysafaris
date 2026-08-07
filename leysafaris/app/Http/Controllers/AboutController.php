<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $settings = Setting::allGrouped();

        return view('about.index', compact('settings'));
    }
}
