<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Http\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    private $url = 'https://miauketing.pl';

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
        $image = Image::orderByDesc('created_at')->paginate(6);
        return ImageResource::collection($image);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $name)
    {
        $validated = $request->validate([
            'upload' => 'required|image'
        ]);

        $path = Storage::putFile('public/' . $name, $validated['upload']);

        Image::create([
            'name' => basename($path),
            'storage' => $name
        ]);

        return response(['url' => $this->url . '/storage/' . $name . '/' . basename($path)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        Storage::delete('public/' . $image->storage . '/' . $image->name);
        $image->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
