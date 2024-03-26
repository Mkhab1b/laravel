<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        try {
            $this->validateCategory($request);

            $category = Category::create($request->all());
            $this->updateParentCategory($category->parent_id);

            return response()->json($category, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()->all()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => [$e->getMessage()]], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validateCategory($request);

            $category = Category::findOrFail($id);
            $category->update($request->all());
            $this->updateParentCategory($category->parent_id);

            return response()->json($category, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()->all()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => [$e->getMessage()]], 422);
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $this->updateParentCategory($category->parent_id);
        $category->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function paginate()
    {
        $categories = Category::paginate(7);
        return response()->json($categories);
    }

    private function validateCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
    }

    private function updateParentCategory($parentId)
    {
        if ($parentId) {
            $category = Category::find($parentId);
            $childrenIds = $category->children()->pluck('id')->toArray();
            $category->childrenArr = $childrenIds;
            $category->save();
        }
    }


}
