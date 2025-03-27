<?php

namespace App\Http\Controllers;

use App\Models\Product; // ← ⭐️忘れないでね
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); // productsテーブルの全データを取得
        return view('products', compact('products'));
    }

    // フォームを表示
    public function create()
    {
        return view('products.create');
    }

    // データを受け取って登録
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
        ]);

        // データを保存
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // 登録後、リダイレクト
        return redirect('/products')->with('success', '商品が登録されました！');
    }
}
