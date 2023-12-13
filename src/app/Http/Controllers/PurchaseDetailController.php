<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseDetailController extends Controller
{
    public function stripe(Request $request)
    {
        $user = Auth::user();
        $generatedId = uniqid();
        $cartItemData = $request->input('cartItemId');
        $quantity = $request->input('quantity');
        $totalAmount = $request->input('totalAmount');

        // $paymentData = session('stripe_payment_data');
        // $cartQuantity = $paymentData['quantity'];
        // $totalAmount = $paymentData['totalAmount'];
        // session(['stripe_payment_data' => compact('cartItemData', 'quantity', 'totalAmount')]);

        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $line_items = [];

        foreach ($cartItemData as $cartItem) {
            $product = Product::find($cartItem['product_id']);

            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->pdct_name,
                        'images' => [$product->image],
                    ],
                    'unit_amount' => $product->pdct_price * 100,
                ],
                'quantity' => $cartItem['quantity'],
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('payment.unsuccess', [], true),
        ]);

        foreach ($cartItemData as $cartItem) {

            $product = Product::find($cartItem['product_id']);

            if ($product) {

                PurchaseDetail::create([
                    'purchase_id' => $generatedId,
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem['quantity'],
                    'price' => $product->pdct_price * $cartItem['quantity'],
                    'product_name' => $product->pdct_name,
                    'description' => $product->pdct_description,
                    'status' => "Unpaid",
                    'session_id' => $session->id,
                ]);
            }
        }

        return redirect()->away($session->url);
    }

    public function checkout(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.sk'));
        $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
        $session_id = $request->get('session_id');

        if (!$session) {
            throw new NotFoundHttpException;
        }

        $orders = PurchaseDetail::where('session_id', $session_id)->get();

        if (!$orders) {
            throw new NotFoundHttpException();
        }

        foreach ($orders as $order) {
            if ($order->status === 'Unpaid') {
                $order->status = 'paid';
                $order->save();
            }
        }

        // if (!empty($session->customer)) {
        //     $customer = $stripe->customers->retrieve($session->customer);
        //     dd($customer);
        // } else {
        //     throw new \Exception("Customer ID not found in the session");
        // }

        $request->session()->forget('cart');

        // echo "<h1>Thanks for your order, $customer->name!</h1>";

        return view('cart.paymentsuccess');

    }

    public function webhook()
    {
        $stripe = new \Stripe\StripeClient(config('stripe.sk'));

        $endpoint_secret = 'whsec_5f319ce320727e2c63084bb021c8d313375df0bf427732f9373ec5bad722fea6';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $checkoutSession = $event->data->object;

                $orders = PurchaseDetail::where('session_id', $checkoutSession->id)->where('status', 'Unpaid')->get();

                foreach ($orders as $order) {
                    $order->payload = $checkoutSession->toJson();

                    if ($order->status === 'Unpaid') {
                        $order->status = 'paid';
                    }
                    $order->save();

                }
                
                // Log payment details
                Log::info('Payment Succeeded', [
                    'type' => gettype($checkoutSession),
                    'Object' => $checkoutSession,
                    'id'=> $checkoutSession->id,
                ]);

                break;

            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response()->json(['status' => 'success']);

        // http_response_code(200);
    }

    public function unsuccess(Request $request)
    {
        return view('cart.paymentunsuccess', ['status' => "canceled"]);
    }

    public function index()
    {

        $purchases = PurchaseDetail::selectRaw('purchase_id, MAX(created_at) as date_created, SUM(price) as total_amount')
            ->groupBy('purchase_id')
            ->get();

        $purchaseDetails = PurchaseDetail::whereIn('purchase_id', $purchases->pluck('purchase_id'))->get();

        return view("cart.purchaseInfo", ['userpurchases' => $purchases, 'purchaseDetails' => $purchaseDetails]);

    }

    public function userindex()
    {
        $purchases = PurchaseDetail::where('user_id', Auth::user()->id)
            ->selectRaw('purchase_id, MAX(created_at) as date_created, SUM(price) as total_amount')
            ->groupBy('purchase_id')
            ->get();

        $purchaseDetails = PurchaseDetail::whereIn('purchase_id', $purchases->pluck('purchase_id'))->get();

        return view("cart.userpurchaseInfo", ['userpurchases' => $purchases, 'purchaseDetails' => $purchaseDetails]);
    }
}

// public function see(Request $request)
// {

//     dd($request->cartItemId);

//     $user = Auth::user();
//     $product = Product::find($request->cartItemId);
//     $selectedCartItems = UserCart::where('user_id', $user->id)->get();

//     dd($selectedCartItems);

//     $generatedId = uniqid();

//     dd($generatedId);
//     dd($cartItem);

//     foreach ($selectedCartItems as $cartItem) {
//         $product = Product::find($cartItem->product_id);
//         $selectedCartItem = UserCart::where('user_id', $user->id)
//             ->where('product_id', $cartItem->product_id)
//             ->first();

//         PurchaseDetail::create([
//             'purchase_id' => $generatedId,
//             'user_id' => $user->id,
//             'product_id' => $cartItem->product_id,
//             'quantity' => $cartItem->quantity,
//             'price' => $product->pdct_price * $cartItem->quantity,
//             'product_name' => $product->pdct_name,
//             'description' => $product->pdct_description,
//             'status' => $request->status,
//         ]);

//         $selectedCartItem->delete();

//     }

//     return redirect(route('welcome'))->with('success', 'Chekout is done!');

// }
