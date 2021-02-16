<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Http\Resources\BlogResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\FileUploadController;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog = Blog::orderByDesc('created_at')->paginate(10);
        return BlogResource::collection($blog);
    }

    public function search($search)
    {
        $blog = Blog::where('title', 'like', '%' . trim($search)  . '%')
            ->orWhere('name', 'like', '%' . trim($search) . '%')
            ->orWhere('text', 'like', '%' . trim($search) . '%')
            ->orWhere('description', 'like', '%' . trim($search) . '%')
            ->paginate(10);
        return BlogResource::collection($blog);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return new BlogResource($blog);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:100|unique:blogs',
            'title' => 'required|min:1|max:191|string',
            'tags' => 'string|max:191|nullable',
            'description' => 'string|nullable',
            'text' => 'string|nullable',
            'category' => 'string|nullable',
            'public' => 'required|integer'
        ]);

        $file = $request->input('thumbnail');
        if ($file) {
            $validated['thumbnail'] = FileUploadController::uf_base64($file, 'blog/');
        }

        $blog = Blog::create($validated);

        return new BlogResource($blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:100|unique:blogs,name,' . $blog->id,
            'title' => 'required|min:1|max:191|string',
            'tags' => 'string|max:191|nullable',
            'description' => 'string|nullable',
            'text' => 'string|nullable',
            'category' => 'string|nullable',
            'public' => 'required|integer'
        ]);

        $file = $request->input('thumbnail');
        if ($file) {
            if (is_array($file)) {
                Storage::disk('public')->delete('blog/' . $file[0]);
                $validated['thumbnail'] = null;
            } else {
                $validated['thumbnail'] = FileUploadController::uf_base64($file, 'blog/');
            }
        }

        $blog->update($validated);

        return new BlogResource($blog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
