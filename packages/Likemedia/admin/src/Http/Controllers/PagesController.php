<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Page;
use App\Models\Gallery;


class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('position', 'asc')->orderBy('created_at', 'desc')->get();

        return view('admin::admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $galleries = Gallery::get();

        return view('admin::admin.pages.create', compact('galleries'));
    }

    public function store(Request $request)
    {
        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);
        $banner = "";

        $request->on_header == 'on' ? $on_header = 1 : $on_header = 0;
        $request->on_drop_down == 'on' ? $on_drop_down = 1 : $on_drop_down = 0;
        $request->on_footer == 'on' ? $on_footer = 1 : $on_footer = 0;

        if ($request->banner) {
            $banner = uniqid() . '-' . $request->banner->getClientOriginalName();
            $request->banner->move('images/pages', $banner);
        }

        foreach ($this->langs as $lang){
            $image[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $image[$lang->lang] = uniqid() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/pages', $image[$lang->lang]);
            }
        }

        $page = new Page();
        $page->alias = str_slug(request('title_'.$this->lang->lang));
        $page->image = $banner;
        $page->active = 1;
        $page->position = 1;
        $page->on_header = $on_header;
        $page->on_drop_down = $on_drop_down;
        $page->on_footer = $on_footer;
        $page->gallery_id = request('gallery_id');
        $page->save();

        foreach ($this->langs as $lang):
            $page->translations()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'image' => $image[$lang->lang],
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('pages.index');
    }

    public function edit($id)
    {
        $page = Page::with('translations')->findOrFail($id);
        $galleries = Gallery::get();

        return view('admin::admin.pages.edit', compact('page', 'translations', 'galleries'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $request->on_header == 'on' ? $on_header = 1 : $on_header = 0;
        $request->on_drop_down == 'on' ? $on_drop_down = 1 : $on_drop_down = 0;
        $request->on_footer == 'on' ? $on_footer = 1 : $on_footer = 0;

        $banner = $request->banner_old;

        if ($request->banner) {
            $banner = uniqid() . '-' . $request->banner->getClientOriginalName();
            $request->banner->move('images/pages', $banner);
            if ($page->image) {
                @unlink(public_path('images/pages/'.$page->image));
            }
        }

        foreach ($this->langs as $lang){
            $image[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $image[$lang->lang] = uniqid() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/pages', $image[$lang->lang]);
            }else{
                if ($request->get('old_image_'. $lang->lang)) {
                    $image[$lang->lang] = $request->get('old_image_'. $lang->lang);
                }
            }
        }

        // $page->alias = str_slug(request('title_'.$this->lang->lang));
        $page->image = $banner;
        $page->on_header = $on_header;
        $page->on_drop_down = $on_drop_down;
        $page->on_footer = $on_footer;
        $page->gallery_id = request('gallery_id');
        $page->save();

        $page->translations()->delete();

        foreach ($this->langs as $lang):
            $page->translations()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'image' => $image[$lang->lang],
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang)
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
                Page::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function status($id)
    {
        $page = Page::findOrFail($id);

        if ($page->active == 1) {
            $page->active = 0;
        } else {
            $page->active = 1;
        }

        $page->save();

        return redirect()->route('pages.index');
    }


    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        @unlink('/images/pages/' . $page->image);

        $page->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('pages.index');
    }

}
