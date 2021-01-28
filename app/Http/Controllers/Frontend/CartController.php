<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cart;
use Cookie;
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
use App\Models\Transaction;

class CartController extends Controller
{
    private $currency;
    private $api;

    public function __construct()
    {
        $this->currency = getCurrency(config('app.currency'));
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function index(Request $request)
    {
        return view('frontend.cart.cart');
    }

    public function checkout(Request $request)
    {
        $total = 0;
        foreach(Cart::session(auth()->user()->id)->getContent() as $item) {
            $total += $item->price;
        }

        if($total > 0.5) {
            //Apply Tax
            $taxData = $this->applyTax('total');
            $cartTotal = Cart::session(auth()->user()->id)->getTotal();
            $orderId = $this->getRazorOrderId($cartTotal);

            return view('frontend.cart.checkout', compact('total', 'taxData', 'orderId', 'cartTotal'));
        } else {
            return redirect()->route('cart.index')->with('warning', 'Empty Cart, Please add items to cart');
        }
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
                        'teachers' => $teachers,
                        'child_id' => $request->child
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
                        'teachers' => $teachers,
                        'child_id' => $request->child
                    ]);
        }

        $total = 0;
        foreach(Cart::session(auth()->user()->id)->getContent() as $item) {
            $total += $item->price;
        }

        //Apply Tax
        $taxData = $this->applyTax('total');
        $cartTotal = Cart::session(auth()->user()->id)->getTotal();
        $orderId = $this->getRazorOrderId($cartTotal);

        return view('frontend.cart.checkout', compact('total', 'taxData', 'orderId', 'cartTotal'));
    }

    public function getChilds()
    {
        $childs = [];
        if(auth()->user()->child()->count() > 0) {
            foreach(auth()->user()->child() as $child) {
                $item = [
                    'id' => $child->id,
                    'name' => $child->name
                ];
                array_push($childs, $item);
            }

            return response()->json([
                'success' => true,
                'result' => true,
                'childs' => $childs
            ]);
        } else {
            return response()->json([
                'success' => true,
                'result' => false
            ]);
        }
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
            $condition_val = 0;
            foreach ($taxes as $tax){
                $total = Cart::session(auth()->user()->id)->getTotal();
                if($tax->condition == 'country' && in_array(auth()->user()->country, json_decode($tax->value))) {
                    $taxData[] = ['name'=> '+'.$tax->rate.'% '.$tax->name,'amount'=> $total*$tax->rate/100 ];
                    $condition_val += $tax->rate;
                }
            }

            $condition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'Tax',
                'type' => 'tax',
                'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => $condition_val .'%',
                'order' => 2
            ));
            Cart::session(auth()->user()->id)->condition($condition);
            return $taxData;
        }
    }

    private function getTax($total)
    {
        $tax_amount = 0;
        $taxes = Tax::where('status', '=', 1)->get();
        if ($taxes != null) {
            foreach ($taxes as $tax){
                if($tax->condition == 'country' && in_array(auth()->user()->country, json_decode($tax->value))) {
                    $tax_amount += $total * $tax->rate / 100;
                }
            }
        }

        return $tax_amount;
    }

    public function razorpay(Request $request)
    {
        if(isset($request->payment_id)) {

            $uuid = $this->getUUID();

            // Verify Payment
            $generated_signature = hash_hmac('sha256', $request->order_id . '|' . $request->payment_id , config('services.razorpay.secret'));

            if ($generated_signature == $request->signature) {

                $payment = $this->api->payment->fetch($request->payment_id);

                // Create an Order for Transaction
                $new_order = Order::create([
                    'user_id' => auth()->user()->id,
                    'uuid' => $uuid,
                    'payment_id' => $request->payment_id,
                    'order_id' => $request->order_id,
                    'signature' => $request->signature,
                    'price' => $request->price,
                    'tax' => $request->tax,
                    'amount' => $request->amount,
                    'status' => $payment['status']
                ]);

                $transaction = Transaction::create([
                    'user_id' => auth()->user()->id,
                    'transaction_id' => 'trans-' . str_random(8),
                    'amount' => $request->amount,
                    'type' => 'pay',
                    'order_id' => $new_order->id,
                    'status' => 1
                ]);

                // Add data to course_student table and Make Order Items
                foreach (Cart::session(auth()->user()->id)->getContent() as $item) {

                    $tax = $this->getTax($item->price);

                    $new_orderItem = OrderItem::create([
                        'order_id' => $new_order->id,
                        'item_id' => $item->id,
                        'price' => $item->price,
                        'tax' => $tax,
                        'amount' => $tax + $item->price
                    ]);

                    if ($item->attributes->product_type == 'bundle') {

                        $new_orderItem->item_type = 'App\Models\Bundle';
                        $new_orderItem->save();

                        DB::table('bundle_student')->insert([
                            'bundle_id' => $item->id,
                            'user_id' => auth()->user()->id
                        ]);
                    } else {

                        $new_orderItem->item_type = 'App\Models\Course';
                        $new_orderItem->save();

                        $userId = ($item->attributes->child_id == '') ? auth()->user()->id : $item->attributes->child_id;

                        DB::table('course_student')->insert([
                            'course_id' => $item->id,
                            'user_id' => $userId,
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

    private function getUUID() {
        $number = mt_rand(10000000, 99999999); // better than rand()
    
        // call the same function if the barcode exists already
        if ($this->uuidExist($number)) {
            return $this->getUUID();
        }
    
        // otherwise, it's valid and can be used
        return $number;
    }
    
    private function uuidExist($number) {
        return empty(Order::where('uuid', $number)->first()) ? false : true;
    }

    private function sendOrderEmail($order_id)
    {
        $order = Order::find($order_id);
        $data = [
            'template_type' => 'Enrollment_Payment_Success',
            'mail_data' => [
                'model_type' => Order::class,
                'model_id' => $order->id
            ],
            'order_items_table' => '',
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

    private function sendOrderFailEmail($reason)
    {
        $data = [
            'template_type' => 'Enrollment_Payment_Failure',
            'mail_data' => [
                'reason' => $reason
            ]
        ];
        Mail::to(auth()->user()->email)->send(new SendMail($data));
    }
}
