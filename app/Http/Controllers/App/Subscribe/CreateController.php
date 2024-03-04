<?php

namespace App\Http\Controllers\App\Subscribe;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $products = Product::all();
        return Inertia::render('App/Subscribe/Create', [
            'intent' => auth()->user()->createSetupIntent(),
            'products' => $products,
            'stripeKey' => config('cashier.key')
        ]);
    }
}
