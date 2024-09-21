<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductColor;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PaymobController extends Controller
{

    // public function credit(Request $request) {
    //     // This function that sends all below function data to Paymob and use it for routes
    //         $request->validate([
    //             'order_id' => 'required|exists:orders,id'
    //         ]);
    //                 // Retrieve order and user details
    //     $orderId = $request->order_id;
    //     $order = Order::findOrFail($orderId);
    //     $userDetails = UserDetail::where('order_id', $orderId)->first();

    //     $tokens = $this->getToken();
    //     $paymobOrder = $this->createOrder($tokens, $order, $userDetails);
    //     $paymentToken = $this->getPaymentToken($paymobOrder, $tokens, $order);

    //     // Build the payment URL
    //     $paymentUrl = 'https://accept.paymob.com/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken;

    //     // Return the payment URL as a response
    //     return response()->json(['payment_url' => $paymentUrl]);
    // }
    // public function getToken() {
    //     //this function takes api key from env.file and get token from paymob accept
    //     $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
    //         'api_key' => env('PAYMOB_API_KEY')
    //     ]);
    //     return $response->object()->token;

    // }


    // public function createOrder($tokens, $order, $userDetails) {
    //     $totalAmount = $order->total_amount;

    //     $items = $order->items->map(function ($item) {
    //         return [
    //             "name" => $item->productColor->product->getTranslation('name', app()->getLocale()),
    //             "amount_cents" => $item->price * 100,
    //             "description" => $item->productColor->product->getTranslation('description', app()->getLocale()),
    //             "quantity" => $item->quantity
    //         ];
    //     })->toArray();

    //     $data = [
    //         "auth_token" => $tokens,
    //         "delivery_needed" => "false",
    //         "amount_cents" => $totalAmount * 100,
    //         "currency" => "EGP",
    //         "items" => $items,
    //     ];

    //     \Log::info('Paymob Create Order Request Data:', $data);

    //     $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', $data);

    //     \Log::info('Paymob Create Order Response:', $response->json());

    //     if ($response->failed()) {
    //         return response()->json(['status' => 'error', 'message' => 'Failed to create Paymob order'], $response->status());
    //     }

    //     return $response->object();
    // }

    // public function getPaymentToken($paymobOrder, $token, $order)
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
    //     }
    //     $billingData = [
    //         "email" => $user->email,
    //         "first_name" => $order->userDetail->first_name,
    //         "street" => $order->userDetail->address,
    //         "building" => "NA",
    //         "phone_number" => $order->userDetail->phone_number,
    //         "shipping_method" => "NA",
    //         "postal_code" => "NA",
    //         "city" => $order->userDetail->city,
    //         "country" => $order->userDetail->country,
    //         "last_name" => $order->userDetail->last_name,
    //         "state" => $order->userDetail->state,
    //         "floor" => "NA",
    //         "apartment" => "NA"
    //     ];

    //     $data = [
    //         "auth_token" => $token,
    //         "amount_cents" => $order->total_amount * 100,
    //         "expiration" => 3600,
    //         "order_id" => $paymobOrder->id,
    //         "billing_data" => $billingData,
    //         "currency" => "EGP",
    //         "integration_id" => env('PAYMOB_INTEGRATION_ID')
    //     ];

    //     \Log::info('Paymob Payment Token Request Data:', $data);

    //     $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', $data);

    //     \Log::info('Paymob Payment Token Response:', $response->json());

    //     if ($response->failed()) {
    //         \Log::error('Failed to retrieve payment token:', ['response' => $response->json()]);
    //         return response()->json(['status' => 'error', 'message' => 'Failed to retrieve payment token'], $response->status());
    //     }

    //     return $response->object()->token;
    // }

    public function callback(Request $request)
    {
        return $request;

        $data = $request->all();
        ksort($data);

        $hmac = $data['hmac'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];

        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }

        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        $todayDate = Carbon::today();

        if ($hased == $hmac) {
            $status = $data['success'];
            $order = Order::where('user_id',Auth::user()->id)->whereDate('created_at',$todayDate)->orderBy('created_at','desc')->first();

            if ($status == "true") {
                if ($order) {
                    $order->update(['status' => "Completed"]);
                    // Clear the carts
                    $carts = Cart::get();
                    foreach ($carts as $cart) {
                        $cart->where('user_id', Auth::id())->delete();
                    }
                    foreach ($order->items as $item) {
                        $productColor = ProductColor::find($item->product_color_id);
                        if ($productColor) {
                            $productColor->decrement('quantity', $item->quantity);
                        }
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment successful. Thank you!',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Order not found.',
                    ], 404);
                }
            } else {
                if ($order) {
                    $order->update(['status' => "failed"]);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment failed. Please try again.',
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid HMAC signature.',
            ], 400);
        }
    }
}
