<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function showAll()
    {
        $categories = Category::all();

        return view('pages.categories', compact('categories'));
    }
    public function storeAPI(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->save();
        return response()->json([
            "message" => "Category Created",
            "data" => $category
        ], 201);
    }
    public function store(Request $request)
    {
        $category = new Category;
        $request->validate([
            'name' => 'required|unique:categories,name'
        ]);
        if ($request->hasFile('banner_image')) {
            $request->validate([
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            $imageName = time() . '.' . $request->banner_image->extension();
            $request->banner_image->move(public_path('images/categories'), $imageName);
            $category->banner_image = $imageName;
        }
        if ($request->hasFile('badge')) {
            $request->validate([
                'badge' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ]);
            $imageName = time() . '.' . $request->badge->extension();
            $request->badge->move(public_path('images/categories'), $imageName);
            $category->badge = $imageName;
        }
        $category->name = $request->name;
        $category->save();
        //        return response()->json([
        //            "message" => "Category Created"
        //        ], 201);

        return redirect()->back()
            ->withMessage('Category Created successfully');
    }

    public function show($id): JsonResponse
    {
        $category = Category::find($id);
        if (!empty($category)) {
            return response()->json($category);
        } else {
            return response()->json([
                "message" => "Category not Found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->name = is_null($request->name) ? $category->name : $request->name;
            if ($request->hasFile('banner_image')) {
                $request->validate([
                    'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);
                if ($category->banner_image) {
                    if (file_exists(public_path('images/categories' . $category->banner_image))) {
                        unlink(public_path('images/categories' . $category->banner_image));
                    }
                }
                $imageName = time() . '.' . $request->banner_image->extension();
                $request->banner_image->move(public_path('images/categories'), $imageName);
                $category->banner_image = $imageName;
            }
            if ($request->hasFile('badge')) {
                $request->validate([
                    'badge' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
                ]);
                if ($category->badge != "") {
                    if (file_exists(public_path('images/categories' . $category->badge))) {
                        unlink(public_path('images/categories' . $category->badge));
                    }
                }
                $imageName = time() . '.' . $request->badge->extension();
                $request->badge->move(public_path('images/categories'), $imageName);
                $category->badge = $imageName;
            }
            $category->save();

            return redirect()->back()->withMessage('Category Successfully updated');
        } else {
            return response()->json([
                "message" => "Category Not Found"
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->delete();


            return response()->json([
                "message" => "Category Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Category Not Found"
            ], 404);
        }
    }
    public function destroyWeb($id)
    {
        $category = Category::find($id);
        $category->delete();


        //            $user = User::where('id', $id)->firstorfail()->delete();
        echo ("User Record deleted successfully.");
        //            return redirect()->route('admin.users.index');
        return redirect()->back()
            ->withMessage('Category deleted successfully');
    }
}
