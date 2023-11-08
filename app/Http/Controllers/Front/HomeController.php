<?php

namespace App\Http\Controllers\Front;
use App\Models\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->active()
            //->latest()
            ->limit(8)
            ->get();

            // dd(LaravelLocalization::getCurrentLocale());

        return view('front.home', compact('products'));
    }
}
