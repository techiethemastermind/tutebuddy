<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

use Mail;
use App\Mail\SendMail;

use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tax;
use App\Models\Bundle;

class CartController extends Controller
{
    private $currency;
    private $api;

    public function __construct()
    {
        $this->currency = getCurrency(config('app.currency'));
        $this->api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
    }

    public function index(Request $request)
    {
        return view('frontend.cart.cart');
    }

    public function checkout(Request $request)
    {
        $total = Cart::session(auth()->user()->id)->getTotal();

        //Apply Tax
        $taxData = $this->applyTax('total');
        $orderId = $this->getRazorOrderId($total);

        return view('frontend.cart.checkout', compact('total', 'taxData', 'orderId'));
    }

    public function addToCart(Request $request)
    {
        $product = "";
        $teachers = "";
        $product_type = "";

        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $product_type = 'course';

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $product_type = 'bundle';
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();

        if (!in_array($product->id, $cart_items)) {

            if($request->price_type == 'group') {
                $price = $product->group_price;
            } elseif ($request->price_type == 'private') {
                $price = $product->private_price;
            }

            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->short_description,
                        'image' => $product->course_image,
                        'product_type' => $product_type,
                        'price_type' => $request->price_type,
                        'teachers' => $teachers
                    ]);
        }

        Session::flash('success', trans('labels.frontend.cart.product_added'));
        return back();
    }

    public function process(Request $request)
    {
        $product = '';
        $teachers = '';
        $product_type = '';

        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $product_type = 'course';

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $product_type = 'bundle';
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();

        if (!in_array($product->id, $cart_items)) {

            if($request->price_type == 'group') {
                $price = $product->group_price;
            } elseif ($request->price_type == 'private') {
                $price = $product->private_price;
            }

            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'product_type' => $product_type,
                        'price_type' => $request->price_type,
                        'teachers' => $teachers
                    ]);
        }

        $total = Cart::session(auth()->user()->id)->getTotal();

        //Apply Tax
        $taxData = $this->applyTax('total');
        $orderId = $this->getRazorOrderId($total);

        return view('frontend.cart.checkout', compact('total', 'taxData', 'orderId'));
    }

    public function clear(Request $request)
    {
        Cart::session(auth()->user()->id)->clear();
        return back();
    }

    public function remove(Request $request)
    {
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
        if(Cart::session(auth()->user()->id)->getContent()->count() < 2){
            Cart::session(auth()->user()->id)->clearCartConditions();
            Cart::session(auth()->user()->id)->removeConditionsByType('tax');
            Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
            Cart::session(auth()->user()->id)->clear();
        }
        Cart::session(auth()->user()->id)->remove($request->course);
        return redirect(route('cart.index'));
    }

    private function applyTax($target)
    {
        //Apply Conditions on Cart
        $taxes = Tax::where('status', '=', 1)->get();
        Cart::session(auth()->user()->id)->removeConditionsByType('tax');
        if ($taxes != null) {
            $taxData = [];
            foreach ($taxes as $tax){
                $total = Cart::session(auth()->user()->id)->getTotal();
                $taxData[] = ['name'=> '+'.$tax->rate.'% '.$tax->name,'amount'=> $total*$tax->rate/100 ];
            }

            $condition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'Tax',
                'type' => 'tax',
                'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => $taxes->sum('rate') .'%',
                'order' => 2
            ));
            Cart::session(auth()->user()->id)->condition($condition);
            return $taxData;
        }
    }

    public function razorpay(Request $request)
    {
        if(isset($request->payment_id)) {

            // Verify Payment
            $generated_signature = hash_hmac('sha256', $request->order_id . '|' . $request->payment_id , env('RAZOR_SECRET'));

            if ($generated_signature == $request->signature) {

                $payment = $this->api->payment->fetch($request->payment_id);

                // Create an Order for Transaction
                $new_order = Order::create([
                    'user_id' => auth()->user()->id,
                    'payment_id' => $request->payment_id,
                    'order_id' => $request->order_id,
                    'signature' => $request->signature,
                    'amount' => $request->amount,
                    'status' => $payment['status']
                ]);

                // Add data to course_student table and Make Order Items
                foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
                    if ($item->attributes->product_type == 'bundle') {

                        $new_orderItem = OrderItem::create([
                            'order_id' => $new_order->id,
                            'item_type' => 'App\Models\Bundle',
                            'item_id' => $item->id,
                            'amount' => $item->price,
                        ]);

                        DB::table('bundle_student')->insert([
                            'bundle_id' => $item->id,
                            'user_id' => auth()->user()->id
                        ]);
                    } else {

                        $new_orderItem = OrderItem::create([
                            'order_id' => $new_order->id,
                            'item_type' => 'App\Models\Bundle',
                            'item_id' => $item->id,
                            'amount' => $item->price,
                        ]);

                        DB::table('course_student')->insert([
                            'course_id' => $item->id,
                            'user_id' => auth()->user()->id,
                            'type' => $item->attributes->price_type
                        ]);
                    }
                }

                $this->sendOrderEmail($new_order->id);

                // Remove Cart
                Cart::clear();

                return response()->json([
                    'success' => true,
                    'razorpay_id' => $new_order->id
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment Failed'
                ]);
            }
            
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Payment Failed'
            ]);
        }
    }

    private function getRazorOrderId($amount)
    {
        $receipt = 'order_' . str_random(8);
        $order  = $this->api->order->create([
            'receipt'         => $receipt,
            'amount'          => $amount * 100,
            'currency'        => $this->currency['short_code'],
        ]);
        return $order['id'];
    }

    private function sendOrderEmail($order_id)
    {
        $order = Order::find($order_id);
        $data = [
            'customer_name' => $order->user->name,
            'order_id' => $order->order_id,
            'payment_id' => $order->payment_id,
            'order_items_table' => ''
        ];

        $html = '<table>
                    <thead>
                        <tr>
                            <th class="course">Course</th>
                            <th>PRICE</th>
                            <th>GST</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach($order->items as $item) {
            $gst = $item->amount * 0.18;
            $total = $item->amount + $gst;
            $html .= '<tr>
                        <td class="course">'. $item->course->title .'</td>
                        <td class="unit">'. getCurrency(config('app.currency'))['symbol'] . $item->amount .'</td>
                        <td>'. getCurrency(config('app.currency'))['symbol'] . $gst .'</td>
                        <td class="total">'. getCurrency(config('app.currency'))['symbol'] . $total .'</td>
                    </tr>';
        }

        $html .= '</tbody></table>';
        $data['order_items_table'] = $html;

        Mail::to($order->user->email)->send(new SendMail($data));
    }
}
