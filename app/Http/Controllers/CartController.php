<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\LineItem;

class CartController extends Controller
{
    public function index()
    {
        $cart_id = Session::get('cart');
        $cart = Cart::find($cart_id);

        $total_price = 0;
        foreach ($cart->products as $product) {
            $total_price += $product->price * $product->pivot->quantity;
        }
        return view('cart.index')
            ->with('line_items', $cart->products)
            ->with('total_price', $total_price);
    }

    public function checkout()
    {
        $cart_id = Session::get('cart');
        $cart = Cart::find($cart_id);
        if (count($cart->products) <= 0) {
            return redirect(route('cart.index'));
        }

        $line_items = [];
        foreach ($cart->products as $product) {
            $line_item = [
                'name'        => $product->name,
                'description' => $product->description,
                'amount'      => $product->price,
                'currency'    => 'jpy',
                'quantity'    => $product->pivot->quantity,
            ];
            array_push($line_items, $line_item);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [$line_items],
            // 'success_url'          => route('product.index'),
            'success_url'          => route('cart.success'),
            'cancel_url'           => route('cart.index'),
            'billing_address_collection' => 'required',
        ]);

        return view('cart.checkout',[
            'session' => $session,
            'publicKey' => env('STRIPE_PUBLIC_KEY')
        ]);
    }

    public function success(Request $request)
    {
        // $cart_id = Session::get('cart');
        // LineItem::where('cart_id', $cart_id)->delete();

        $request->session()->forget('cart');
        return redirect(route('product.index'));
    }
}
