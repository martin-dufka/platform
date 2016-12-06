<?php

namespace Orchid\Foundation\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Orchid\Foundation\Core\Models\File;
use Orchid\Foundation\Http\Controllers\Controller;

class FileController extends Controller
{
    /**
     * @param Request $request
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('images')) {
            $file = $this->saveImage($request->file('images'));

            return response()->json($file);
        } else {
            dd('Изображение не пришло');
        }
    }

    /**
     * @param UploadedFile $image
     * @return static
     */
    protected function saveImage(UploadedFile $image)
    {
        Storage::disk('public')->makeDirectory(date('Y/m/d'));

        $name = sha1(time().$image->getClientOriginalName());
        $path = '/'.date('Y/m/d').'/'.$name;

        $full_path = storage_path('app/public/'.'/'.date('Y/m/d').'/'.$name);
        Image::make($image)->save($full_path.$name, 75);

        $file = File::create([
            'name' => $name,
            'original_name' => $image->getClientOriginalName(),
            'mime' => $image->getMimeType(),
            'path' => $path,
            'user_id' => Auth::user()->id,
        ]);

        return $file;
    }

    protected function saveImageDataBase()
    {
    }
}
