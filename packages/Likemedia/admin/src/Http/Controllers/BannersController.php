<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Banner;


class BannersController extends Controller
{
    public function index()
    {
        $banners = Banner::get();

        $this->setBanners();

        return view('admin::admin.banners.index', compact('banners'));
    }

    public function show($id)
    {
        return redirect()->route('banners.index');
    }

    public function create()
    {
        return view('admin::admin.banners.create');
    }

    public function store(Request $request)
    {
        $bannerDesktop = "";
        $bannerMobile = "";

        $toValidate['key'] = 'required|max:255';
        $this->validate($request, $toValidate);

        if ($request->banner_desktop) {
            $bannerDesktop = uniqid() . '-' . $request->banner_desktop->getClientOriginalName();
            $request->banner_desktop->move('images/banners', $bannerDesktop);
        }

        if ($request->banner_mobile) {
            $bannerMobile = uniqid() . '-' . $request->banner_mobile->getClientOriginalName();
            $request->banner_mobile->move('images/banners', $bannerMobile);
        }

        $banner = new Banner();
        $banner->key          = request('key');
        $banner->desktop_src  = $bannerDesktop;
        $banner->mobile_src   = $bannerMobile;
        $banner->desktop_width_size   = request('desktop_width');
        $banner->desktop_height_size  = request('desktop_height');
        $banner->mobile_width_size    = request('mobile_width');
        $banner->mobile_height_size   = request('mobile_height');
        $banner->save();

        $this->setBanners();
        Session::flash('message', 'New item has been created!');

        return redirect()->route('banners.index');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);

        return view('admin::admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $toValidate['key'] = 'required|max:255';
        $this->validate($request, $toValidate);

        $bannerDesktop = $request->banner_desktop_old;
        $bannerMobile = $request->banner_mobile_old;

        if (!empty($request->file('banner_desktop'))){
            $bannerDesktop = uniqid() . '-' . $request->banner_desktop->getClientOriginalName();
            $request->banner_desktop->move('images/banners', $bannerDesktop);
            if ($banner->desktop_src) {
                @unlink(public_path('/images/banners/'.$banner->desktop_src));
            }
        }

        if (!empty($request->file('banner_mobile'))) {
            $bannerMobile = uniqid() . '-' . $request->banner_mobile->getClientOriginalName();
            $request->banner_mobile->move('images/banners', $bannerMobile);
            if ($banner->mobile_src) {
                @unlink(public_path('/images/banners/'.$banner->mobile_src));
            }
        }

        $banner->key                  = request('key');
        $banner->desktop_src          = $bannerDesktop;
        $banner->mobile_src           = $bannerMobile;
        $banner->desktop_width_size   = request('desktop_width');
        $banner->desktop_height_size  = request('desktop_height');
        $banner->mobile_width_size    = request('mobile_width');
        $banner->mobile_height_size   = request('mobile_height');
        $banner->save();

        $this->setBanners();

        return redirect()->back();
    }


    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        @unlink(public_path('/images/banners/' . $banner->desktop_src));
        @unlink(public_path('/images/banners/' . $banner->mobile_src));

        $banner->delete();

        $this->setBanners();

        session()->flash('message', 'Item has been deleted!');
        return redirect()->route('banners.index');
    }

    public function setBanners()
    {
        $array = [];
        $banners = Banner::get();

        if (count($banners) > 0) {
            foreach ($banners as $key => $banner) {
                $array[$banner->key]['desktop'] = $banner->desktop_src;
                $array[$banner->key]['mobile']  = $banner->mobile_src;
            }
        }

        $file_handle = fopen(storage_path('banners.json'), 'w');
        fwrite($file_handle, json_encode($array));
        fclose($file_handle);
    }

}
