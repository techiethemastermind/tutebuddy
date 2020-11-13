<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Course;
use App\User;

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
        $transactions = Order::where('user_id', auth()->user()->id)->paginate(15);
        return view('backend.payment.transactions', compact('transactions'));
    }

    /**
     * Transaction Detail
     */
    public function transactionsDetail($id)
    {
        $order = Order::find($id);
        return view('backend.payment.transaction-detail', compact('order'));
    }

    /**
     * Get Orders
     */
    public function getOrders()
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $purchased_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');
        $order_ids = OrderItem::whereIn('item_id', $purchased_ids)->pluck('order_id');
        $orders = Order::whereIn('id', $order_ids)->paginate(15);
        return view('backend.payment.orders', compact('orders'));
    }

    /**
     * Order Detail
     */
    public function orderDetail($id)
    {
        $order = Order::find($id);
        return view('backend.payment.order-detail', compact('order'));
    }

    /**
     * download Invoice
     */
    public function downloadInvoice($id)
    {
        $order = Order::find($id);
        $pdf = \PDF::loadView('downloads.invoice', compact('order'));

        $invoice_name = 'Invoice_' . $order->order_id . '.pdf';
        $pdf->save(public_path('storage/invoices/' . $invoice_name))->setPaper('', 'portrait');

        $file = public_path('storage/invoices/' . $invoice_name);
        return Response::download($file);
    }

}
