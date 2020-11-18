<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Course;
use App\User;
use App\Models\Tax;
use App\Models\Transaction;

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
        $earned_this_month = $this->getEarned('month');
        $balance = $this->getEarned('balance');
        $total = $this->getEarned('total');
        return view('backend.payment.orders', compact('orders', 'earned_this_month', 'balance', 'total'));
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

        if (!file_exists(public_path('storage/invoices'))) {
            mkdir(public_path('storage/invoices'), 0777);
        }

        $pdf->save(public_path('storage/invoices/' . $invoice_name))->setPaper('', 'portrait');

        $file = public_path('storage/invoices/' . $invoice_name);
        return Response::download($file);
    }

    /**
     * Get earned
     */
    private function getEarned($type)
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $purchased_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');
        $earned = 0;

        switch($type) {
            case 'month':

                $start = new Carbon('first day of this month');
                $now = Carbon::now();
                $end = new Carbon('last day of this month');

                // Get courses end_date is in this month
                $course_ids_this_month = Course::whereBetween('end_date', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $earned = OrderItem::whereIn('item_id', $course_ids_this_month)
                        ->whereBetween('created_at', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                        ->sum('price');

                return $earned;
            break;

            case 'balance':
                $now = Carbon::now();
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                $withdraws = Transaction::where('user_id', auth()->user()->id)->where('type', 'withdraw')->sum('amount');
                $balance = $total - $withdraws;
                return $balance;
            break;

            case 'total':
                $now = Carbon::now();
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                return $total;
            break;
        }
        
    }

}
