<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $totalAmount = 0;

        if ($request->session()->has("cart")) {

            $cartData = $request->session()->get('cart');

            foreach ($cartData as $cartItem) {
                $product = $cartItem['product'];

                $totalAmount += $product->pdct_price * $cartItem['quantity'];
            }

            return view('cart.viewcart', ['cartData' => $cartData, 'totalAmount' => $totalAmount]);
        } else {
            return redirect()->route('welcome')->with('warning', 'Nothing has been added to the cart yet!');
        }
    }

    public function addToCart(Request $request)
    {
        $user = Auth::user();

        $product = Product::find($request->product_id);

        if ($product && $product->pdct_qty > 0) {
            $cartData = $request->session()->get('cart');

            $existingItem = collect($cartData)->firstWhere('product_id', $request->product_id);

            if ($existingItem) {

                foreach ($cartData as $index => $object) {

                    if ($object['product']->id == $request->product_id) {
                        $cartData[$index]['quantity'] = $cartData[$index]['quantity'] + 1;
                        break;
                    }
                }

            } else if (isset($cartData)) {
                array_push($cartData, [
                    'product_id' => $product->id,
                    'product' => $product,
                    'quantity' => 1,
                ]
                );
            } else {
                $cartData[] = [
                    'product_id' => $product->id,
                    'product' => $product,
                    'quantity' => 1,
                ];

            }

            $request->session()->put('cart', $cartData);

            $product->decrement('pdct_qty', 1);
            $product->save();

            return redirect()->route('welcome')->with('success', 'Item added to the cart successfully!');
        } else {
            return redirect()->route('welcome')->with('warning', 'Selected item is out of stock!');
        }
    }

    public function delete(Request $request)
    {
        $cartData = $request->session()->get('cart');

        $foundIndex = null;

        foreach ($cartData as $index => $object) {

            if ($object['product']->id == $request->product_id) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex !== null) {
            array_splice($cartData, $foundIndex, 1);
            $request->session()->put('cart', $cartData);
        }

        return redirect(route('cart.index'))->with('success', 'Product deleted successfully');
    }

}


// public function index()
// {
//     $user = Auth::user();
//     $selectedCartItems = UserCart::where('user_id', $user->id)->get();

//     $cartData = [];
//     $totalAmount = 0;

//     foreach ($selectedCartItems as $cartItem) {
//         $product = Product::find($cartItem->product_id);
//         $cartData[] = [
//             'cartItemId' => $cartItem->id,
//             'product' => $product,
//             'quantity' => $cartItem->quantity,
//         ];
//         $totalAmount += $product->pdct_price * $cartItem->quantity;
//     }

//     return view('cart.viewcart', ['cartData' => $cartData, 'totalAmount' => $totalAmount]);
// }

// public function store(Request $request){
//     $product = Product::find($request->product_id);

//     if($product && $product->pdct_qty > 0){
//         $cartData = $request->session()->get('cart');
//     }
// }

// public function addToCart(Request $request)
// {
//     $user = Auth::user();

//     if ($user['id'] && $request['product_id'] && $request['quantity']) {
//         $selectedCartItem = UserCart::where('user_id', $user->id)
//             ->where('product_id', $request->product_id)
//             ->first();

//         $selectedProductItem = Product::where('id', $request->product_id)
//             ->first();

//         $data = [
//             'user_id' => $user->id,
//             'product_id' => $request->product_id,
//         ];

//         if ($selectedProductItem->pdct_qty > 0) {
//             if ($selectedCartItem) {
//                 UserCart::updateOrCreate($data, [
//                     'quantity' => $selectedCartItem->quantity + 1,
//                 ]);
//             } else {
//                 UserCart::create([
//                     'user_id' => $user->id,
//                     'product_id' => $request->product_id,
//                     'quantity' => 1,
//                 ]);
//             }

//             $selectedProductItem->decrement('pdct_qty', 1);
//             $selectedProductItem->save();

//             return redirect()->route('welcome')->with('success', 'Item added to the cart succesfully!');
//         } else {
//             return redirect()->route('welcome')->with('warning', 'Selected item is out of stock!');
//         }
//     }

//     return redirect()->route('welcome')->with('warning', 'Error occured while adding to the cart!');
// }
