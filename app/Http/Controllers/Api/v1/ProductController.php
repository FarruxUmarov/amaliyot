<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getProducts(int $id = null): \Illuminate\Http\JsonResponse
    {
        if ($id)
        {
            return response()->json(Product::query()->findOrFail($id)->toArray());
        }

        return response()->json(Product::all()->toArray());
    }

    public function createProduct(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'string|max:255|nullable',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $product = Product::query()->create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description ?? null,
            ]);

            DB::commit();

            return response()->json([
                'data' => 'Successfully created product',
                'success' => true
            ]);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'data' => $th->getMessage(),
                'success' => false
            ]);
        }
    }

    public function updateProduct(int $id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'string|max:255|nullable',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            Product::findOrFail($id)->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description ?? null,
            ]);

            DB::commit();

            return response()->json([
                'data' => 'Successfully updated product',
                'success' => true
            ]);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'data' => $th->getMessage(),
                'success' => false
            ]);
        }
    }

    public function deleteProduct(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();

            Product::query()->findOrFail($id)->delete();

            DB::commit();

            return response()->json([
                'data' => 'Successfully deleted product',
                'success' => true
            ]);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'data' => $th->getMessage(),
                'success' => false
            ]);
        }
    }
}
