<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $categories = Category::all();
        return view('admin', ['categories' => $categories]);
    }

    public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|integer|max:255',
            'description' => 'string|max:255|nullable',
        ]);

        if ($validator->fails()) {
            return view('admin', ['errors' => $validator->errors(), 'categories' => Category::all()]);
        }

        try {
            DB::beginTransaction();

            Product::query()->create([
                'name' => $request->get('name'),
                'category_id' => $request->get('category'),
                'description' => $request->get('description'),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            Log::error($e);
        }

        return redirect('/admin');
    }
}
