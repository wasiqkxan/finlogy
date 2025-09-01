<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Auth::user()->categories;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $category = Auth::user()->categories()->create($validatedData);

        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return $category;
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'type' => 'string|max:255',
        ]);

        $category->update($validatedData);

        return response()->json($category, 200);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json(null, 204);
    }
}
