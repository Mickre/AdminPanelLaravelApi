<?php

namespace App\Http\Controllers\App;

use App\Models\Blog;
use App\Http\Resources\BlogResource;
use App\Http\Controllers\Controller;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog = Blog::where('public', 1)
            ->orderByDesc('created_at')
            ->paginate(10);
        return BlogResource::collection($blog);
    }

    public function search($search)
    {
        $blog = Blog::where('public', 1)
            ->where(
                function ($query) use ($search) {
                    return $query->where('title', 'like', '%' . trim($search)  . '%')
                        ->orWhere('name', 'like', '%' . trim($search) . '%')
                        ->orWhere('text', 'like', '%' . trim($search) . '%')
                        ->orWhere('description', 'like', '%' . trim($search) . '%');
                }
            )->paginate(10);
        return BlogResource::collection($blog);
    }

    public function show($name)
    {
        $blog = Blog::where('name', $name)->where('public', 1)->first();
        if ($blog) {
            $blog['text'] = $this->change($blog['text']);
            return new BlogResource($blog);
        }
        return response()->json(['message' => 'Error 404!'], 404);
    }

    private function change($var)
    {
        return preg_replace('/ ([A-Za-z1-9]{1}) /', " $1&nbsp;", $var);
    }
}
