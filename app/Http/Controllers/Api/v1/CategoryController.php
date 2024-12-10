<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getCategories(int $id = null): \Illuminate\Http\JsonResponse
    {
        if ($id) {
            return response()->json([
                'data' => Category::query()->find($id)->toArray(),
                'errors' => [],
            ]);
        }
        return response()->json([
            'data' => Category::all()->toArray(),
            'error' => []
        ]);
    }

    public function createCategory(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        try {
            DB::beginTransaction();

            $category = Category::query()->create([
                'name' => $request->name
            ]);

            DB::commit();

            return response()->json([
                'data' => "successfully create category",
                'success' => true,
                'status' => 200,
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['errors'=>$exception->getMessage()], 422);
        }
    }

    public function updateCategory(Request $request, int $id): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        try {
            DB::beginTransaction();

            Category::findOrFail($id)->update([
                'name' => $request->name
            ]);

            DB::commit();

            return response()->json([
                'data' => "successfully update category",
                'success' => true,
                'status' => 200,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['errors'=>$exception->getMessage()], 422);
        }
    }

    public function deleteCategory(int $id): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            DB::beginTransaction();

            Category::query()->findOrFail($id)->delete();

            DB::commit();

            return response()->json([
                'data' => "successfully delete category",
                'success' => true,
                'status' => 200,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['errors'=>$exception->getMessage()], 422);
        }
    }
}
