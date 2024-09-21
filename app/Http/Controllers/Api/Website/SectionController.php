<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function index()
    {
        $sections = Section::orderBy('order', 'asc')
        ->get(['id', 'name']);

    return response()->json($sections);
}



}
