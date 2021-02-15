<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Razorpay\Api\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Course;
use App\User;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\Refund;

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
        if(auth()->user()->hasRole('Instructor')) {
            $transactions = Transaction::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(15);
            return view('backend.payment.teacher.transactions', compact('transactions'));
        }

        if(auth()->user()->hasRole('Administrator')) {
            $transactions = Transaction::orderBy('created_at', 'desc')->paginate(15);
            return view('backend.payment.admin.transactions', compact('transactions'));
        }
        
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
        if(auth()->user()->hasRole('Administrator')) {
            $orders = Order::orderBy('created_at', 'desc')->paginate(15);
            $earned_this_month = $this->getAdminEarned('month');
            $balance = $this->getAdminEarned('balance');
            $total = $this->getAdminEarned('total');
            return view('backend.payment.admin.orders', compact('orders', 'earned_this_month', 'balance', 'total'));
        }

        if(auth()->user()->hasRole('Instructor')) {
            $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
            $purchased_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');
            $order_ids = OrderItem::whereIn('item_id', $purchased_ids)->pluck('order_id');
            $orders = Order::whereIn('id', $order_ids)->paginate(15);
            $earned_this_month = $this->getEarned('month');
            $balance = $this->getEarned('balance');
            $total = $this->getEarned('total');
            return view('backend.payment.teacher.orders', compact('orders', 'earned_this_month', 'balance', 'total'));
        }

        if(auth()->user()->hasRole('Student')) {
            $orders = Order::where('user_id', auth()->user()->id)->paginate(15);
            return view('backend.payment.student.orders', compact('orders'));
        }
    }

    /**
     * Order Detail
     */
    public function orderDetail($id)
    {
        $order = Order::find($id);
        if(auth()->user()->hasRole('Student')) {
            return view('backend.payment.student.order-detail', compact('order'));
        } else {
            return view('backend.payment.teacher.order-detail', compact('order'));
        }
        
    }

    /**
     * Refund request from Student
     */
    public function refundRequest(Request $request, $id)
    {
        try {
            Refund::updateOrCreate([
                'order_id' => $id,
                'user_id' => auth()->user()->id,
                'reason' => $request->reason
            ]);
            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get Refunds
     */
    public function getRefunds()
    {
        $refunds = Refund::paginate(15);
        return view('backend.payment.admin.refunds', compact('refunds'));
    }

    /**
     * Refund Detail
     */
    public function refundDetail($id)
    {
        $refund = Refund::find($id);
        return view('backend.payment.admin.refund-detail', compact('refund'));
    }

    /**
     * Process Refund
     */
    public function processRefund($id)
    {
        $refund = Refund::find($id);
        $pay_id = $refund->order->payment_id;

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $payment = $api->payment->fetch($pay_id);
        // $refunds = $payment->refunds();
        $refund_payment = $payment->refund();
        // $refund_payment = $payment->refund(array('amount' => 100));

        if($refund_payment) {
            $refund->status = 1;
            $refund->save();

            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_id' => 'trans-' . str_random(8),
                'amount' => $refund_payment->amount,
                'type' => 'refund',
                'order_id' => $refund->order->id,
                'status' => $refund_payment->status,
                'payout_id' => $refund_payment->id
            ]);

            // Deactive Course
            $order_id = $refund->order_id;
            $order = Order::find($order_id);
            $orderItems = $order->items;

            foreach($orderItems as $item) {
                $item_type = $item->item_type;
                if($item_type == 'App\Models\Course') {
                    DB::table('course_student')
                        ->where('course_id', $item->item_id)
                        ->where('user_id', $order->user_for)
                        ->delete();
                }
            }

            return response()->json([
                'success' => true
            ]);
        }
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
     * withdraw
     */
    public function withdraw(Request $request)
    {
        $curl_headers = [
            'Content-Type: application/json',
            'Authorization: Basic '. base64_encode(config('services.razorpay.key') . ':' . config('services.razorpay.secret'))
        ];

        if(!auth()->user()->bank) {
            return response()->json([
                'success' => false,
                'message' => 'Bank Account is not added! Please confirm in your account setting.'
            ]);
        }

        $params = [
            'account_number' => config('services.razorpayX.number'),
            'fund_account_id' => auth()->user()->bank->fund_account_id,
            'amount' => $request->amount * 100,
            'currency' => $request->currency,
            'mode' => 'NEFT',
            'purpose' => 'payout'
        ];

        $options = [
            CURLOPT_URL => 'https://api.razorpay.com/v1/payouts',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => $curl_headers,
            CURLOPT_RETURNTRANSFER => 1
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        // dd($result);
        $payout_id = $result['id'];
        $status = $result['status'];
        curl_close($ch);

        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'transaction_id' => 'trans-' . str_random(8),
            'amount' => $request->amount,
            'type' => 'withdraw',
            'payout_id' => $payout_id,
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'transaction' => $transaction->id
        ]);
    }

    /**
     * Get earned
     */
    private function getEarned($type)
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $purchased_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');
        $earned = 0;
        $subdays = auth()->user()->withhold;

        switch($type) {
            case 'month':

                $start = new Carbon('first day of this month');
                $now = Carbon::now();
                $end = new Carbon('last day of this month');

                // Get courses end_date is in this month
                $course_ids_this_month = Course::whereBetween('end_date', [
                        $start->subDays($subdays)->format('Y-m-d')." 00:00:00",
                        $now->subDays($subdays)->format('Y-m-d')." 23:59:59"])
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
                $course_ids_since_now = Course::where('end_date', '<', $now->subDays($subdays)->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                $withdraws = Transaction::where('user_id', auth()->user()->id)->where('type', 'withdraw')->sum('amount');
                $refunds = Transaction::where('user_id', auth()->user()->id)->where('type', 'refund')->sum('amount');
                $balance = $total - $withdraws - $refunds;
                return $balance;
            break;

            case 'total':
                $now = Carbon::now();
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->subDays($subdays)->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                $refunds = Transaction::where('user_id', auth()->user()->id)->where('type', 'refund')->sum('amount');
                return $total - $refunds;
            break;
        }
        
    }

    /**
     * Get earned by Admin
     */
    private function getAdminEarned($type)
    {
        $course_ids = DB::table('course_student')->pluck('course_id');
        $now = Carbon::now();

        switch($type)
        {
            case 'month':
                $start = new Carbon('first day of this month');
                

                // Get courses end_date is in this month
                $course_ids_this_month = Course::whereBetween('end_date', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                    ->whereIn('id', $course_ids)
                    ->pluck('id');

                $earned = OrderItem::whereIn('item_id', $course_ids_this_month)
                        ->whereBetween('created_at', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                        ->sum('price');

                return $earned;
            break;

            case 'total':
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $course_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                return $total;
            break;

            case 'balance':
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                ->whereIn('id', $course_ids)
                ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                $earned = $total * config('account.fee') / 100;
                return $earned;
            break;
        }
    }

}
