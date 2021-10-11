<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Brand;


class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('position', 'asc')->get();

        return view('admin::admin.brands.index', compact('brands'));
    }

    public function show($id)
    {
        return redirect()->route('brands.index');
    }

    public function create()
    {
        return view('admin::admin.brands.create');
    }

    public function store(Request $request)
    {
        $logo = "";
        $picture = "";

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        if ($request->logo) {
            $logo = uniqid() . '-' . $request->logo->getClientOriginalName();
            $request->logo->move('images/brands', $logo);
        }

        if ( $request->picture) {
            $picture = uniqid() . '-' . $request->picture->getClientOriginalName();
            $request->logo->move('images/brands', $picture);
        }

        foreach ($this->langs as $lang){
            $banner[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = uniqid() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/brands', $banner[$lang->lang]);
            }
        }

        $brand = new Brand();
        $brand->alias = str_slug(request('title_'.$this->lang));
        $brand->active = 1;
        $brand->position = 1;
        $brand->image = $picture;
        $brand->logo  = $logo;
        $brand->save();

        foreach ($this->langs as $lang):
            $brand->translation()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'banner' => $banner[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_descr' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('brands.index');
    }

    public function edit($id)
    {
        $brand = Brand::with('translations')->findOrFail($id);

        return view('admin::admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);
        $logo = $request->logo_old;
        $picture = $request->picture_old;

        if (!empty($request->file('logo'))) {
            $logo = uniqid() . '-' . $request->logo->getClientOriginalName();
            $request->logo->move('images/brands', $logo);
            if ($brand->logo) {
                @unlink(public_path('images/brands/'.$brand->logo));
            }
        }

        if (!empty($request->file('picture'))) {
            $picture = uniqid() . '-' . $request->picture->getClientOriginalName();
            $request->picture->move('images/brands', $picture);
            if ($brand->image) {
                @unlink(public_path('images/brands/'.$brand->image));
            }
        }

        foreach ($this->langs as $lang):
            $banner[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = uniqid() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/brands', $banner[$lang->lang]);
            }else{
                if ($request->get('old_image_'. $lang->lang)) {
                    $banner[$lang->lang] = $request->get('old_image_'. $lang->lang);
                }
            }
        endforeach;

        $brand->alias = str_slug(request('title_ro'));
        $brand->active = 1;
        $brand->position = 1;
        $brand->image = $picture;
        $brand->logo = $logo;
        $brand->save();

        $brand->translations()->delete();

        foreach ($this->langs as $lang):
            $brand->translation()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'banner' => $banner[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_descr' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        return redirect()->back();
    }


    public function changePosition()
    {
        $neworder = Input::get('neworder');
        $i = 1;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {
            $id = str_replace("tablelistsorter[]=", "", $v);
            if (!empty($id)) {
                Brand::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function status($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->active == 1)  $brand->active = 0;
        else  $brand->active = 1;

        $brand->save();

        return redirect()->route('brands.index');
    }


    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        @unlink('/images/brands/' . $brand->image);

        $brand->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('brands.index');
    }

}
