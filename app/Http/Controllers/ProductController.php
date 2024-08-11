<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;

class ProductController extends Controller
{
    //
    public function AddProduct(Request $request){

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string',
            'product_name' => 'required|string',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all(); // Get all error messages
            $formattedErrors = [];
            foreach ($errors as $error) {
                $formattedErrors[] = $error;
            }
            return response()->json([
                'success' => 0,
                'error' => $formattedErrors[0]
            ], 422);
        }

        try {
            //code...

            $data=$validator->validate();

            if ($request->hasFile('product_image')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('product_image')->getRealPath())->getSecurePath();
                $data['product_image'] = $uploadedFileUrl;
            }
            // else{
            //     return response()->json([
            //         'success' => 0,
            //         'error' => ''
            //     ], 422);
            // }

            $createProduct=Product::create($data);
            return response()->json(['success'=>1,'message' => 'Product Added','product'=>$createProduct], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'success'=>0, 
                    'error' =>'Internal Server Error. ' . $th->getMessage()
                ], 500);
        }
    } 

    public function ShowProduct(Request $request)
    {
        try {
            //code...
            // $params=$request->query('id');
            $product=Product::get();
            if($product->isEmpty())
            {
                return response()->json(['success'=>0,'error'=>'No data Found'],404);
            }
            // if($params){
            //     $singleProduct = Product::find($params);
            //     if(!$singleProduct)
            //     {
            //         return response()->json(['success'=>0,'error'=>"No data Found, in id {$params}"],404);   
            //     }
            //     return response()->json(['success'=>1,'Product'=>$singleProduct],200);
            // }
            return response()->json(['success'=>1,'Products'=>$product],200);
         } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'success'=>0, 
                    'error' =>'Something went wrong. ' . $th->getMessage()
                ], 500);
         }
    }

    public function RemoveProduct(Request $request){
        try {
            //code...
            $params=$request->query('id');
            if(!$params)
            {
                return response()->json(['success'=>0,'error'=>"Please provide id of the product you want to remove"],400);
            }
            $product = Product::find($params);
            if(!$product)
            {
                return response()->json(['success'=>0,'error'=>"No data Found, in id {$params}"],404);
            }
            $product->delete();
            return response()->json(['success'=>1,'message'=>'Product Removed'],200);
        }catch (\Throwable $th) {
            return response()->json([
                'success'=>0,
                'details' =>'Internal Server Error. ' . $th->getMessage()], 500);
        }
    }

}
