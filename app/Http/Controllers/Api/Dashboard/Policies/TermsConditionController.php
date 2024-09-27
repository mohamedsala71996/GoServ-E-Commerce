<?php

namespace App\Http\Controllers\Api\Dashboard\Policies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreTermsConditionRequest;
use App\Http\Resources\TermsConditionResource;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class TermsConditionController extends Controller
{
    public function index()
    {
        $item = TermsCondition::first();
        if (!$item) {
            return response()->json(['message' => 'Terms and Conditions Not Found'], 404);
        }
        return new TermsConditionResource($item);
    }

    public function store(StoreTermsConditionRequest $request)
    {
        $item = TermsCondition::first();
        if ($item) {
            return response()->json(['error' => 'Terms and Conditions Already Exists'], 404);
        }
        $data = $request->validated();
        $item = TermsCondition::create($data);
        return new TermsConditionResource($item);
    }

    public function update(StoreTermsConditionRequest $request)
    {
        $item = TermsCondition::first();
        $data = $request->validated();
        $item->update($data);
        return new TermsConditionResource($item);
    }

    public function destroy()
    {
        $item = TermsCondition::first();
        $item->delete();
        return response()->json(['message' => 'Terms and Conditions deleted successfully']);
    }

    public function setStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $item = TermsCondition::first();
        if (!$item) {
            return response()->json(['message' => 'Terms and Conditions Not Found'], 404);
        }

        $item->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated successfully', 'status' => $item->status]);
    }
}
