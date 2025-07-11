<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load the 'todos' relationship to avoid null errors
        //$categories = Category::with('todos')  // Ensure you're using 'todos' in the relation
        //    ->where('user_id', auth()->user()->id)
        //    ->get();
    
        //return view('category.index', compact('categories'));

        $categories = Category::with('todos')
        ->where('user_id', Auth::id())
        ->get();
        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        Category::create([
            'user_id' => auth()->user()->id,
            'title'   => $request->title,
        ]);

        return redirect()->route('category.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Not implemented
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        if (auth()->user()->id === $category->user_id) {
            return view('category.edit', compact('category'));
        }

        return redirect()->route('category.index')
            ->with('danger', 'You are not authorized to edit this category!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $category->update([
            'title' => ucfirst($request->title),
        ]);

        return redirect()->route('category.index')
            ->with('success', 'Todo category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (auth()->user()->id === $category->user_id) {
            $category->delete();

            return redirect()->route('category.index')
                ->with('success', 'Category deleted successfully!');
        }

        return redirect()->route('category.index')
            ->with('danger', 'You are not authorized to delete this category!');
    }
}
