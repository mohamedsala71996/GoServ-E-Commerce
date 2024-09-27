<?php

namespace App\Http\Controllers\Api\Dashboard\Policies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StorePrivacyPolicyRequest;
use App\Http\Resources\PrivacyPolicyResource;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        $item = PrivacyPolicy::first();
        if (!$item) {
            return response()->json(['message' => 'Privacy Policy Not Found'], 404);
        }
        return new PrivacyPolicyResource($item);
    }

    public function store(StorePrivacyPolicyRequest $request)
    {
        $item = PrivacyPolicy::first();
        if ($item) {
            return response()->json(['error' => 'Privacy Policy Already Exists'], 404);
        }
        $data = $request->validated();
        $item = PrivacyPolicy::create($data);
        return new PrivacyPolicyResource($item);
    }

    public function update(StorePrivacyPolicyRequest $request)
    {
        $item = PrivacyPolicy::first();
        $data = $request->validated();
        $item->update($data);
        return new PrivacyPolicyResource($item);
    }

    public function destroy()
    {
        $item = PrivacyPolicy::first();
        $item->delete();
        return response()->json(['message' => 'Privacy Policy deleted successfully']);
    }

    public function setStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $item = PrivacyPolicy::first();
        if (!$item) {
            return response()->json(['message' => 'Privacy Policy Not Found'], 404);
        }

        $item->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated successfully', 'status' => $item->status]);
    }
}
