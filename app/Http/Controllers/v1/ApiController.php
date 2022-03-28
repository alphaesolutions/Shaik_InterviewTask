<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Order_detail;
use Config;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    public function index(Request $request) {
        $data = array();
        $order = Orders::orderBy('created_at', 'desc');
        if(isset($request->order_id) && $request->order_id != '') {
            $order->where('id', $request->order_id);
        }
        if(isset($request->status) && $request->status != '') {
            $order->where('status', $request->status);
        }

        $orders = $order->get();
        if(!empty($orders)) {
            foreach($orders as $order) {
                $order_detail_arr = array();
                $order_details = $order->order_detail;
                $customer_detail = $order->customer;
                if(!empty($order_details)) {
                    foreach($order_details as $order_detail) {
                        $item = $order_detail->item;
                        $order_detail_arr[] = array(
                            'item_no' => $item['item_no'],
                            'name' => $item['name'],
                            'total_quantity' => $order_detail['total_quantity'],
                            'total_price' => $order_detail['total_price']
                        );
                    }
                }
                $data[] = array(
                    'id' => $order['id'],
                    'customer_no' => $customer_detail['customer_no'],
                    'customer_name' => $customer_detail['name'],
                    'order_no' => $order['order_no'],
                    'order_date' => date('d/m/Y', strtotime($order['order_date'])),
                    'estimate_delivery_date' => date('d/m/Y H:i', strtotime($order['delivery_date'])),
                    'total_price' => $order['total_price'],
                    'billing_address' => $order['billing_address'],
                    'delivery_address' => $order['delivery_address'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'order_details' => $order_detail_arr
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'Order list', 'data' => $data], 200);
    }

    public function delayed() {
        $data = array();
        $orders = Orders::where('status', 3)->get();
        if(!empty($orders)) {
            foreach($orders as $order) {
                $order_detail_arr = array();
                $order_details = $order->order_detail;
                $customer_detail = $order->customer;
                if(!empty($order_details)) {
                    foreach($order_details as $order_detail) {
                        $item = $order_detail->item;
                        $order_detail_arr[] = array(
                            'item_no' => $item['item_no'],
                            'name' => $item['name'],
                            'total_quantity' => $order_detail['total_quantity'],
                            'total_price' => $order_detail['total_price']
                        );
                    }
                }
                $data[] = array(
                    'id' => $order['id'],
                    'customer_no' => $customer_detail['customer_no'],
                    'customer_name' => $customer_detail['name'],
                    'order_no' => $order['order_no'],
                    'order_date' => date('d/m/Y', strtotime($order['order_date'])),
                    'estimate_delivery_date' => date('d/m/Y H:i', strtotime($order['delivery_date'])),
                    'total_price' => $order['total_price'],
                    'billing_address' => $order['billing_address'],
                    'delivery_address' => $order['delivery_address'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'order_details' => $order_detail_arr
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'Order list', 'data' => $data], 200);
    }

    public function create(Request $request) {
        DB::beginTransaction();

        try { 
            $order_detail_arr = array();
            $order_details = $request->order_detail;
            $order_count = Orders::count();
            
            $order = new Orders;   
            $order->order_no = config('configuration.order_prefix') . sprintf('%04d', $order_count + 1);
            $order->order_date = date('Y-m-d', strtotime($request->order_date));
            $order->delivery_date = date('Y-m-d H:i:s', strtotime($request->delivery_date));
            $order->total_price = $request->total_price;
            $order->customer_id = $request->customer_id;
            $order->billing_address = $request->billing_address;
            $order->delivery_address = $request->delivery_address;
            $order->created_at = date('Y-m-d H:i:s');
            $order->save();

            if(!empty($order_details)) {
                foreach($order_details as $order_detail) {
                    $order_detail_arr[] = array(
                        'orders_id' => $order->id,
                        'item_id' => $order_detail['item_id'],
                        'total_quantity' => $order_detail['total_quantity'],
                        'total_price' => $order_detail['total_price'],
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
            }

            Order_detail::insert($order_detail_arr);

            DB::commit(); 

            return response()->json(['success' => true, 'message' => 'Order placed successfully', 'data' => []], 200);
        } catch (\Exception $e) { 
            DB::rollback(); 

            return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again later!!', 'data' => []], 400);
        }
    }

    public function update(Request $request) {
        DB::beginTransaction();

        try { 
            $order = Orders::find($request->order_id);  
            $order->status = $request->order_status;
            $order->updated_at = date('Y-m-d H:i:s');
            $order->save();

            DB::commit(); 

            return response()->json(['success' => true, 'message' => 'Order status updated successfully', 'data' => []], 200);
        } catch (\Exception $e) { 
            DB::rollback(); 

            return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again later!!', 'data' => []], 400);
        }
    }
}
