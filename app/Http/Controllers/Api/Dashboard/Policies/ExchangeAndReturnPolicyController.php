<?php

namespace App\Http\Controllers\Api\Dashboard\Policies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreExchangeAndReturnPolicyRequest;
use App\Http\Resources\ExchangeAndReturnPolicyResource;
use App\Models\Admin;
use App\Models\ExchangeAndReturnPolicy;
use Illuminate\Http\Request;

class ExchangeAndReturnPolicyController extends Controller
{
    // public function __construct()
    // {
    //     // Apply middleware to the constructor
    //     $this->middleware(function ($request, $next) {
    //         if ( !auth()->user() instanceof Admin) {
    //             return response()->json(['message' => 'You are not authenticated as an admin.'], 401);
    //         }
    //         return $next($request);
    //     });
    // }
    public function index()
    {
        $item = ExchangeAndReturnPolicy::first();
        if (!$item) {
            return response()->json(['message' => 'Exchange And Return Policy Not Found'], 404);
        }
        return new ExchangeAndReturnPolicyResource($item);
    }

    // public function show($id)
    // {
    //     $item = ExchangeAndReturnPolicy::findOrFail($id);
    //     return new ExchangeAndReturnPolicyResource($item);
    // }

    public function store(StoreExchangeAndReturnPolicyRequest $request)
    {
        $item = ExchangeAndReturnPolicy::first();

        if ($item) {
            return response()->json([
                "error" => 'Exchange And Return Policy Already Exists'
            ], 404);
        }

        $data = $request->validated();
        $item = ExchangeAndReturnPolicy::create($data);

        return new ExchangeAndReturnPolicyResource($item);
    }

    public function update(StoreExchangeAndReturnPolicyRequest $request)
    {
        $item = ExchangeAndReturnPolicy::first();
        $data = $request->validated();
        $item->update($data);

        return new ExchangeAndReturnPolicyResource($item);
    }

    public function destroy()
    {
        $item = ExchangeAndReturnPolicy::first();
        $item->delete();

        return response()->json(['message' => 'item deleted successfully']);
    }


    public function setStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive', // Validate that status is either active or inactive
        ]);

        $item = ExchangeAndReturnPolicy::first();
        if (!$item) {
            return response()->json(['message' => 'Exchange And Return Policy Not Found'], 404);
        }

        $item->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status updated successfully.',
            'status' => $item->status,
        ], 200);
    }

}
