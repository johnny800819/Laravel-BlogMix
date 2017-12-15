<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('Home.Payment.shopping-step-2');
    }
}
