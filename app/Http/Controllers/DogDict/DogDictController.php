<?php

namespace App\Http\Controllers\DogDict;

use App\Http\Controllers\Controller;
use App\Models\DogDict\Dog;
use App\Models\DogDict\Category;
use Illuminate\Http\Request;

class DogDictController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('sort')->get();
        $c = $request->get('c');
        $category = null;

        $query = Dog::query();

        if ($c) {
            $category = Category::where('slug', $c)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $dogs = $query->latest()->paginate(20);
		$popular = Dog::inRandomOrder()->limit(5)->pluck('name');

        return view('dogdict.index', compact('dogs', 'categories', 'category', 'popular'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $where = $request->get('where', 'search');

		$results = Dog::where('name', 'like', $query.'%')->get();


        if ($results->count() > 0) {
            Dog::where('name', 'like', '%'.$query.'%')->increment('search_count');
        }

        return view('dogdict.dict_query', compact('query', 'where', 'results'));
    }

    public function view($id)
    {
        $dog = Dog::findOrFail($id);
        $dog->increment('view_count');
        return view('dogdict.view', compact('dog'));
    }
}
