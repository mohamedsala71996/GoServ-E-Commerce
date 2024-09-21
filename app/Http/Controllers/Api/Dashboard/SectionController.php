<?php

namespace App\Http\Controllers\Api\Dashboard;

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

public function updateOrder(Request $request)
{
    $data = $request->all(); // Expecting the array of sections with their IDs and names

    foreach ($data as $key => $section) {
        // Update the order field based on the current position in the array
        Section::where('id', $section['id'])->update(['order' => $key + 1]);
    }

    return response()->json(['success' => true], 200);
}

}
