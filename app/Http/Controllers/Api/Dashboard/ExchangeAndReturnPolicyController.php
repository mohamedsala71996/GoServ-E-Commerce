<?php

namespace App\Http\Controllers\Api\Dashboard;

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
        $items = ExchangeAndReturnPolicy::all();
        return ExchangeAndReturnPolicyResource::collection($items);
    }

    // public function show($id)
    // {
    //     $item = ExchangeAndReturnPolicy::findOrFail($id);
    //     return new ExchangeAndReturnPolicyResource($item);
    // }

    public function store(StoreExchangeAndReturnPolicyRequest $request)
    {
        $data = $request->validated();
        $item = ExchangeAndReturnPolicy::create($data);

        return response()->json($item, 201);
    }

    public function update(StoreExchangeAndReturnPolicyRequest $request, $id)
    {
        $item = ExchangeAndReturnPolicy::findOrFail($id);
        $data = $request->validated();
        $item->update($data);

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = ExchangeAndReturnPolicy::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'item deleted successfully']);
    }

}
