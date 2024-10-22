<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\File;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create(ProductRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();
        $data['author_id'] = $user->id;
        if (isset($data['img_id'])) {

            $file = $request->file('img_id');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');


            $fileModel = new File;
            $fileModel->file_name = $filePath;
            $fileModel->save();
            $data['img_id'] = $fileModel->id;
        }

        $product = Product::create($data);

        if ($request->has('categories')) {
            $categoryIds = explode(',', $request->input('categories'));
            $product->categories()->sync($categoryIds);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $fileName = time() . '_' . $photo->getClientOriginalName();
                $filePath = $photo->storeAs('uploads', $fileName, 'public');

                $fileModel = new File;
                $fileModel->file_name = $filePath;
                $fileModel->save();

                $product->photos()->attach($fileModel->id);
            }
        }

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return view('product.products_list', compact('products'));
    }

    public function view($id)
    {
        $product = Product::with('reviews.user')->findOrFail($id);
        return view('product.view', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit_product', compact('product'));
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated();

        $product = Product::findOrFail($id);

        // Если был загружен новый файл изображения
        if ($request->hasFile('img_id')) {
            // Сохранить новый файл
            $file = $request->file('img_id');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Если у продукта уже есть изображение
            if ($product->img_id) {
                $fileModel = File::findOrFail($product->img_id);
                // Удалить старый файл
                Storage::disk('public')->delete($fileModel->file_name);
                $fileModel->file_name = $filePath;
                $fileModel->save();
                $data['img_id'] = $fileModel->id;
            } else {
                $fileModel = new File;
                $fileModel->file_name = $filePath;

                $fileModel->save();
                $data['img_id'] = $fileModel->id;
            }
        }

        $product->update($data);

        if ($request->has('categories')) {
            $categoryIds = explode(',', $request->input('categories'));
            if (empty($categoryIds[0])) {
                $product->categories()->detach();
            } else {
                $product->categories()->sync($categoryIds);
            }
        }

        return redirect()->route('product.edit', ['id' => $product->id])->with('success', 'Product updated successfully');
    }

}
