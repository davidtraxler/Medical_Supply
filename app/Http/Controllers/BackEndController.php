<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class BackEndController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile(Request $request)
    {
        $user = Auth::user();

        $profile = User::find($user->id);
        $method = $request->method();
        if ($request->isMethod('PATCH')) {
            $profile->fill($request->all());
            $profile->save();
        }

        $profileInfo = [
            'firstName' => $profile->first_name,
            'middleName' => $profile->middle_name,
            'lastName' => $profile->last_name,
            'institution' => $profile->institution,
            'streetAddress' => $profile->street_address,
            'city' => $profile->city,
            'state' => $profile->state,
            'zipCode' => $profile->zip_code,
            'email' => $profile->email,
            'telephoneNumber' => $profile->telephone_number,
            'cardholderName' => $profile->cardholder_name,
            'cardNumber' => $profile->card_number,
            'month' => $profile->month,
            'year' => $profile->year,
            'cvv' => $profile->cvv,
        ];

        return view('profile', $profileInfo);
    }

    public function home()
    {
        return view('home');
    }

    public function orders()
    {
        //TODO logic to get table data
        return view('orders');
    }

    public function addToCart(Request $request)
    {
        $method = $request->method();
        if (Auth::check() && $request->isMethod('POST')) {
            $user = Auth::user();
            $cart = new Cart();
            $cart->user_id = $user->getAuthIdentifier();
            $cart->order_equipment_id = $request->equipmentId;
            $cart->quantity = $request->quantity;
            $cart->save();
            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Something went wrong',
            ];
        }
    }

    public function checkCart()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $numberOfCartItems = Cart::where('user_id', $user->getAuthIdentifier())->count();
            //$numberOfCartItems = count($c)
            if ($numberOfCartItems > 0) {
                return [
                    'success' => true
                ];
            }
        }
        return [
            'success' => false
        ];
    }
}
