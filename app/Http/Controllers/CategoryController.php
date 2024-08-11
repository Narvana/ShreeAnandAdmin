<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function AddCategory(Request $request){
        $validator=Validator::make($request->all(),[
            'category_name' => 'required|string',
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
            $category=Category::create([
                'category_name' => $request->category_name,
            ]);
    
            return response()->json([
                'success'=>1,
                'message'=>'Category Added Successfully',
                'data'=>$category],201);    
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'success'=>0, 
                    'error' =>'Internal Server Error. ' . $th->getMessage()
                ], 500);
        }
    }

    public function ShowCategory(Request $request)
    {
     try {
        //code...
        // $params=$request->query('id');
        $category=Category::get();
        if($category->isEmpty())
        {
            return response()->json(['success'=>0,'error'=>'No data Found'],404);
        }
        // if($params){
        //     $singleCategory = Category::find($params);
        //     if(!$singleCategory)
        //     {
        //         return response()->json(['success'=>0,'error'=>"No data Found, in id {$params}"],404);   
        //     }
        //     return response()->json(['success'=>1,'category'=>$singleCategory],200);
        // }
        return response()->json(['success'=>1,'Categories'=>$category],200);
     } catch (\Throwable $th) {
        //throw $th;
        return response()->json(
            [
                'success'=>0, 
                'error' =>'Something went wrong. ' . $th->getMessage()
            ], 500);
     }
    }

    public function RemoveCategory(Request $request){
        try {
            //code...
            $params=$request->query('id');
            if(!$params)
            {
                return response()->json(['success'=>0,'error'=>"Please provide id of the Category you want to remove"],400);
            }
            $category = Category::find($params);
            if(!$category)
            {
                return response()->json(['success'=>0,'error'=>"No data Found, in id {$params}"],404);
            }
            $category->delete();
            return response()->json(['success'=>1,'message'=>'Category Removed'],200);
        }catch (\Throwable $th) {
            return response()->json([
                'success'=>0,
                'details' =>'Internal Server Error. ' . $th->getMessage()], 500);
        }
    }
}
