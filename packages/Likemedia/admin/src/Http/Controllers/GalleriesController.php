<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\GalleryImageTranslation;
use App\Models\AutoAlt;


class GalleriesController extends Controller
{
    public function index()
    {
        $galleries = Gallery::get();

        return view('admin::admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin::admin.galleries.create');
    }

    public function store(Request $request)
    {
        $toValidate['alias'] = 'required|max:255|unique:galleries';

        $validator = $this->validate($request, $toValidate);

        $gallery = new Gallery();
        $gallery->alias = request('alias');
        $gallery->save();

        if($files = $request->file('images')){
            foreach($files as $key => $file){
                $hash = uniqid();
                $name = $hash.$file->getClientOriginalName();

                if ($file->extension() !== 'mp4') {
                    $image_resize = Image::make($file->getRealPath());
                    $gallery_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['gallery'];
                    $image_resize->save(public_path('images/galleries/og/' .$name), 75);
                    $type = "image";
                    $name = '/images/galleries/og/' . $name;
                }else{
                    $path = public_path('images/galleries/videos/');
                    $file->move($path, $name);
                    $type = "video";
                    $name = '/images/galleries/videos/' . $name;
                }

                $image = GalleryImage::create( [
                    'gallery_id' =>  $gallery->id,
                    'src' =>  $name,
                    'type' => $type,
                    'device' => 'desktop'
                ]);
            }
        }

        if($files = $request->file('images_mobile')){
            foreach($files as $key => $file){
                $hash = uniqid();
                $name = $hash.$file->getClientOriginalName();

                if ($file->extension() !== 'mp4') {
                    $image_resize = Image::make($file->getRealPath());
                    $gallery_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['gallery'];
                    $image_resize->save(public_path('images/galleries/og/' .$name), 75);
                    $type = "image";
                    $name = '/images/galleries/og/' . $name;
                }else{
                    $path = public_path('images/galleries/videos/');
                    $file->move($path, $name);
                    $type = "video";
                    $name = '/images/galleries/videos/' . $name;
                }

                $image = GalleryImage::create( [
                    'gallery_id' =>  $gallery->id,
                    'src' =>  $name,
                    'type' => $type,
                    'device' => 'mobile'
                ]);
            }
        }

        Session::flash('message', 'New item has been created!');

        return redirect()->route('galleries.index');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);

        $imagesDesktop = GalleryImage::where('gallery_id', $id)->where('device', 'desktop')->get();
        $imagesMobile = GalleryImage::where('gallery_id', $id)->where('device', 'mobile')->get();

        return view('admin::admin.galleries.edit', compact('gallery', 'imagesDesktop', 'imagesMobile'));
    }

    public function update(Request $request, $id)
    {
        $toValidate['alias'] = 'required|max:255';

        $validator = $this->validate($request, $toValidate);

        $gallery = Gallery::findOrFail($id);
        $gallery->alias = request('alias');
        $gallery->save();

        $images = array();

        if($files = $request->file('images')){
            foreach($files as $key => $file){
                $hash = uniqid();
                $name = $hash.$file->getClientOriginalName();

                if ($file->extension() !== 'mp4') {
                    $image_resize = Image::make($file->getRealPath());
                    $gallery_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['gallery'];
                    $image_resize->save(public_path('images/galleries/og/' .$name), 75);
                    $type = "image";
                    $name = '/images/galleries/og/' . $name;
                }else{
                    $path = public_path('images/galleries/videos/');
                    $file->move($path, $name);
                    $type = "video";
                    $name = '/images/galleries/videos/' . $name;
                }

                $image = GalleryImage::create( [
                    'gallery_id' =>  $gallery->id,
                    'src' =>  $name,
                    'type' => $type,
                    'device' => 'desktop'
                ]);
            }
        }

        if($files = $request->file('images_mobile')){
            foreach($files as $key => $file){
                $hash = uniqid();
                $name = $hash.$file->getClientOriginalName();

                if ($file->extension() !== 'mp4') {
                    $image_resize = Image::make($file->getRealPath());
                    $gallery_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['gallery'];
                    $image_resize->save(public_path('images/galleries/og/' .$name), 75);
                    $type = "image";
                    $name = '/images/galleries/og/' . $name;
                }else{
                    $path = public_path('images/galleries/videos/');
                    $file->move($path, $name);
                    $type = "video";
                    $name = '/images/galleries/videos/' . $name;
                }

                $image = GalleryImage::create( [
                    'gallery_id' =>  $gallery->id,
                    'src' =>  $name,
                    'type' => $type,
                    'device' => 'mobile'
                ]);
            }
        }

        return redirect()->back();
    }


    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if (file_exists('/images/galleries' . $gallery->image)) {
            unlink('/images/galleries' . $gallery->image);
        }

        $gallery->delete();

        $images = GalleryImage::where('gallery_id', $gallery->id)->get();

        if (!empty($images)) {
            foreach ($images as $key => $image) {
                GalleryImage::where('id', $image->id)->delete();
                $imagesTrans = GalleryImageTranslation::where('gallery_image_id', $image->id)->delete();

                if (file_exists('/images/galleries/og/'.$image->src)) {
                    unlink('/images/galleries/og/'.$image->src);
                }
                if (file_exists('/images/galleries/sm/'.$image->src)) {
                    unlink('/images/galleries/sm/'.$image->src);
                }
            }
        }

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('galleries.index');
    }

    public function deleteGalleryImages(Request $request)
    {
        $image = GalleryImage::where('gallery_id', $request->get('galleryId'))->where('id', $request->get('id'))->first();
        GalleryImage::where('gallery_id', $request->get('galleryId'))->where('id', $request->get('id'))->delete();
        $images = GalleryImageTranslation::where('gallery_image_id', $request->get('id'))->get();

        if (!is_null($images)) {
            if (file_exists('/images/galleries/og/'.$image->src)) {
                unlink('/images/galleries/og/'.$image->src);
            }
            if (file_exists('/images/galleries/sm/'.$image->src)) {
                unlink('/images/galleries/sm/'.$image->src);
            }
        }

        if (!empty($images)) {
            foreach ($images as $key => $image) {
               GalleryImage::where('id', $image->id)->delete();
            }
        }

        return "true";
    }

}
