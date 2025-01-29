<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //
    public function addOrder(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'OrderString' => 'required|string',
            'OrderDate' => 'required|date',
        ]);

        if($validator->fails())
        {
            $errors=$validator->errors()->all();
            return response()->json([
                'success'=> 0,
                'error'=> $errors[0]
            ],400);
        }

        try {
            // $orderDate = Carbon::createFromFormat('d/m/Y', $request->OrderDate)->format('Y-m-d');

            $add = Order::create([
                'OrderString' => $request->OrderString,
                'OrderDate' => $request->OrderDate
            ]);

            return response()->json([
                'success' => 1,
                'data' => $add,
                'message'=> 'Order Added'
            ],200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'success'=>0, 
                    'error' =>'Internal Server Error. ' . $th->getMessage()
                ], 500);
        }
    }

    public function showOrder(Request $request)
    {
        $orders=Order::get();
        if(Empty($orders))
        {
            return response()->json(
                [
                    'success'=>0, 
                    'error' => 'No Products'
                ], 400);
        }
        return response()->json(
            [
                'success'=>1, 
                'message' => 'Order List',
                'data' => $orders,
            ], 200);
    }
}
