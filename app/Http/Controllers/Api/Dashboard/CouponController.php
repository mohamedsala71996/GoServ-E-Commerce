<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Retrieve all coupons
    public function index()
    {
        $coupons = Coupon::all();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $coupons
        ], 200);
    }

    // Store a newly created coupon
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $coupon = Coupon::create($request->all());

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'data' => $coupon
        ], 201);
    }

    // Store several coupons
    public function storeSeveralCoupons(Request $request)
    {
        $data = $request->validate([
            'coupons' => 'required|array',
            'coupons.*.code' => 'required|string|unique:coupons,code',
            'coupons.*.discount_amount' => 'nullable|numeric|min:0',
            'coupons.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'coupons.*.valid_from' => 'nullable|date',
            'coupons.*.valid_until' => 'nullable|date|after_or_equal:valid_from',
            'coupons.*.usage_limit' => 'nullable|integer|min:1',
        ]);

        $coupons = [];

        foreach ($data['coupons'] as $couponData) {
            $coupons[] = Coupon::create($couponData);
        }

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'data' => $coupons
        ], 201);
    }

    // Update several coupons
    public function updateSeveralCoupons(Request $request)
    {
        $data = $request->validate([
            'coupons' => 'required|array',
            'coupons.*.id' => 'sometimes|exists:coupons,id',
            'coupons.*.code' => 'required|string|unique:coupons,code',
            'coupons.*.discount_amount' => 'nullable|numeric|min:0',
            'coupons.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'coupons.*.valid_from' => 'nullable|date',
            'coupons.*.valid_until' => 'nullable|date|after_or_equal:valid_from',
            'coupons.*.usage_limit' => 'nullable|integer|min:1',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:coupons,id'
        ]);

        $coupons = [];
        $couponIdsToRemove = $request->input('remove_items', []);

        foreach ($data['coupons'] as $couponData) {
            if (isset($couponData['id'])) {
                // Update existing coupon
                $coupon = Coupon::find($couponData['id']);
                if ($coupon) {
                    if (Coupon::where('code', $couponData['code'])->where('id', '!=', $coupon->id)->exists()) {
                        return response()->json([
                            'status' => 'error',
                            'code' => 422,
                            'message' => "The code '{$couponData['code']}' has already been taken."
                        ], 422);
                    }
                    $coupon->update($couponData);
                    $coupons[] = $coupon;
                }
            } else {
                // Create new coupon
                if (Coupon::where('code', $couponData['code'])->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'code' => 422,
                        'message' => "The code '{$couponData['code']}' has already been taken."
                    ], 422);
                }
                $coupons[] = Coupon::create($couponData);
            }
        }

        // Delete coupons
        if (!empty($couponIdsToRemove)) {
            Coupon::whereIn('id', $couponIdsToRemove)->delete();
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $coupons
        ], 200);
    }

    // Update a specific coupon
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $id,
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Coupon not found'
            ], 404);
        }

        $coupon->update($request->all());

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $coupon
        ], 200);
    }

    // Remove a specific coupon
    public function destroy($id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Coupon not found'
            ], 404);
        }

        $coupon->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Coupon deleted successfully'
        ], 200);
    }
}
