<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PaymentController extends Controller
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

    /**
     * Get Transactions
     */
    public function getTransactions()
    {
        return view('backend.payment.transactions');
    }

    /**
     * Get Orders
     */
    public function getOrders()
    {
        return view('backend.payment.orders');
    }

}
