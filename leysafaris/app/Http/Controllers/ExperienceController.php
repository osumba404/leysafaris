<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(): View
    {
        $experiences = Experience::published()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        return view('experiences.index', compact('experiences'));
    }
}
