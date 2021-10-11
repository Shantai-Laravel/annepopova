<?php

namespace Admin\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\Gallery;


class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('position', 'asc')->get();
        $category = null;

        return view('admin::admin.blogs.index', compact('blogs', 'category'));
    }

    public function getByCategory($id)
    {
        $blogs = Blog::orderBy('position', 'asc')->where('category_id', $id)->get();
        $category = BlogCategory::findOrFail($id);

        return view('admin::admin.blogs.index', compact('blogs', 'category'));
    }

    public function show($id)
    {
        return redirect()->route('blogs.index');
    }

    public function create()
    {
        $allCategories = BlogCategory::pluck('parent_id')->toArray();
        $categories = BlogCategory::whereNotIn('id', $allCategories)->orderBy('position', 'asc')->get();
        $galleries = Gallery::get();

        return view('admin::admin.blogs.create', compact('categories', 'galleries'));
    }

    public function store(Request $request)
    {
        $picture = "";
        $picture_mob= "";

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        if ($request->picture) {
            $picture = uniqid() . '-' . $request->picture->getClientOriginalName();
            $request->picture->move('images/blogs', $picture);
        }

        if ($request->picture_mob) {
            $picture_mob = uniqid() . '-' . $request->picture_mob->getClientOriginalName();
            $request->picture_mob->move('images/blogs', $picture_mob);
        }

        foreach ($this->langs as $lang){
            $banner[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = uniqid() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/blogs', $banner[$lang->lang]);
            }
        }

        if (request('first')) {
            Blog::where('first', 1)->update([ 'first' => 0 ]);
        }

        $blog = new Blog();
        $blog->alias = str_slug(request('title_'.$this->lang->lang));
        $blog->category_id = request('category_id');
        $blog->gallery_id = request('gallery_id');
        $blog->first = request('first') ? 1 : 0;
        $blog->active = 1;
        $blog->position = 1;
        $blog->image = $picture;
        $blog->image_mob = $picture_mob;
        $blog->date_time = request('date');
        $blog->save();

        foreach ($this->langs as $lang):
            $blog->translation()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'banner' => $banner[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_descr' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('blogs.index');
    }

    public function edit($id)
    {
        $blog = Blog::with('translations')->findOrFail($id);
        $allCategories = BlogCategory::pluck('parent_id')->toArray();
        $categories = BlogCategory::whereNotIn('id', $allCategories)->orderBy('position', 'asc')->get();
        $galleries = Gallery::get();

        return view('admin::admin.blogs.edit', compact('blog', 'categories', 'galleries'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);
        $picture = $request->picture_old;
        $picture_mob = $request->picture_old_mob;

        if (!empty($request->file('picture'))) {
            $picture = uniqid() . '-' . $request->picture->getClientOriginalName();
            $request->picture->move('images/blogs', $picture);
            if ($blog->image) {
                @unlink(public_path('images/blogs/'.$blog->image));
            }
        }

        if (!empty($request->file('picture_mob'))) {
            $picture_mob = uniqid() . '-' . $request->picture_mob->getClientOriginalName();
            $request->picture_mob->move('images/blogs', $picture_mob);
            if ($blog->image_mob) {
                @unlink(public_path('images/blogs/'.$blog->image_mob));
            }
        }

        foreach ($this->langs as $lang):
            $banner[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = uniqid() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/blogs', $banner[$lang->lang]);
            }else{
                if ($request->get('old_image_'. $lang->lang)) {
                    $banner[$lang->lang] = $request->get('old_image_'. $lang->lang);
                }
            }
        endforeach;

        if ($request->get('first') == 'on') {
            Blog::where('first', 1)->where('id', '!==', $blog->id)->update([ 'first' => 0 ]);
            $first = 1;
        }else{
            $first = $blog->first;
        }

        $blog->alias = str_slug(request('title_'.$this->lang->lang));
        $blog->category_id = request('category_id');
        $blog->gallery_id = request('gallery_id');
        $blog->first = $first;
        $blog->active = 1;
        $blog->position = 1;
        $blog->image = $picture;
        $blog->image_mob = $picture_mob;
        $blog->date_time = request('date');
        $blog->save();

        $blog->translations()->delete();

        foreach ($this->langs as $lang):
            $blog->translation()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
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
                Blog::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function status($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->active == 1)  $blog->active = 0;
        else  $blog->active = 1;

        $blog->save();

        return redirect()->route('blogs.index');
    }


    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        @unlink('/images/blogs/' . $blog->image);

        $blog->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('blogs.index');
    }

}
