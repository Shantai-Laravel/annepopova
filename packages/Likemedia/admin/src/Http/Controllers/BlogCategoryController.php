<?php

namespace Admin\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Blog;


class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::where('parent_id', 0)->get();

        return view('admin::admin.blogCategories.index', compact('categories'));
    }

    public function show()
    {
        return redirect()->route('blog-categories.index');
    }

    public function create()
    {
        return redirect()->route('blog-categories.index');
    }

    public function getCategories()
    {
        $categories = BlogCategory::with([
                            'translation',
                            'blogs',
                            'children.translation',
                            'children.blogs',
                            'children.children.translation',
                            'children.children.blogs',
                            'children.children.children.translation',
                            'children.children.children.blogs',
                            'children.children.children.children.translation',
                            'children.children.children.children.blogs',
                        ])
                        ->orderBy('position', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->where('parent_id', 0)->get();

        return $categories;
    }

    public function changePosition(Request $request)
    {
        $categories = $request->get('categories');
        $positon = 1;

        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $positon++;
                BlogCategory::where('id', $category['id'])->update(['parent_id' => 0, 'position' => $positon, 'level' => 1]);
                if (array_key_exists('children', $category)) {
                    foreach ($category['children'] as $children) {
                        $positon++;
                        BlogCategory::where('id', $children['id'])->update(['parent_id' => $category['id'], 'position' => $positon, 'level' => 2]);
                        if (array_key_exists('children', $children)) {
                            foreach ($children['children'] as $children2) {
                                $positon++;
                                BlogCategory::where('id', $children2['id'])->update(['parent_id' => $children['id'], 'position' => $positon, 'level' => 3]);
                                if (array_key_exists('children', $children2)) {
                                    foreach ($children2['children'] as $children3) {
                                        $positon++;
                                        BlogCategory::where('id', $children3['id'])->update(['parent_id' => $children2['id'], 'position' => $positon, 'level' => 4]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $data['stauts'] = 'true';
        $data['message'] = 'succes loading';
        $data['categories'] = $this->getCategories();
        return $data;
    }

    public function checkBlogs($categoryId)
    {
        $category = BlogCategory::find($categoryId);

        if (count($category->blogs) > 0) {
            return false;
        }
        return true;
    }

    public function store(Request $request)
    {
        $parentId = 0;
        $level = 1;
        $titles = array_filter($request->get('titles'), function($var){return !is_null($var);} );
        if ($request->get('categoryId')) {
            $parentId = $request->get('categoryId');
            $level = $request->get('level') + 1;
        }

        $alias = str_slug($titles[$this->lang->id]);

        $findSlug = BlogCategory::where('alias', $alias)->first();

        if (!is_null($findSlug)) $alias = $alias . rand(0, 100);

        $category = BlogCategory::create([
            'parent_id' => $parentId,
            'alias' => $alias,
            'level' => $level,
            'position' => 1,
            'succesion' => 1,
        ]);

        foreach ($titles as $key => $title) {
            $category->translations()->create([
                'lang_id' => $key,
                'name' => $title,
            ]);
        }

        $data['category'] = $category;
        $data['categories'] = $this->getCategories();

        return $data;
    }

    public function getAllCategories(Request $request)
    {
        $categories = BlogCategory::with([
                                'translation',
                                'blogs',
                                'children',
                            ])
                            ->orderBy('position', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return $categories;
    }

    public function removeWithMovingCategory(Request $request)
    {
        $categoryToMoveId = $request->get('category_to_move');
        $categoryId = $request->get('category_id');
        $parent = BlogCategory::where('id', $categoryToMoveId)->first();

        $children = BlogCategory::where('parent_id', $categoryId)->get();

        if (!is_null($parent)) {
            foreach ($children as $key => $child) {
                BlogCategory::where('id', $child->id)
                                ->update([
                                    'parent_id' => $categoryToMoveId,
                                    'level' => $parent->level + 1
                                ]);
            }

            $blogs = Blog::where('category_id', $categoryId)->pluck('id')->toArray();
            Blog::whereIn('id', $blogs)->update(['category_id' => $categoryToMoveId]);
        }

        BlogCategory::where('id', $categoryId)->delete();

        return $this->getCategories();
    }

    public function moveBlogs(Request $request)
    {
        $parent = $request->get('parent_category');
        $child = $request->get('child_category');

        $parentCategory = BlogCategory::find($parent);
        $childCategory = BlogCategory::find($child);

        if (!is_null($parentCategory) && !is_null($childCategory)) {
            $parentBlogs = Blog::where('category_id', $parentCategory->id)->update([
                'category_id' => $childCategory->id
            ]);

            BlogCategory::where('id', $child)->update([
                'parent_id' => $parent,
                'level' => $parentCategory->level + 1,
            ]);
        }

        return $this->getCategories();
    }

    public function edit($id)
    {
        $category = BlogCategory::with('translations')->findOrFail($id);

        return view('admin::admin.blogCategories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $blogCategory = BlogCategory::findOrFail($id);
        $on_home    = 0;
        $active     = 0;
        $name_image = $request->get('old_icon') ?? null;

        if ($request->get('on_home') == 'on') { $on_home = 1; }
        if ($request->get('active') == 'on') { $active = 1; }

        if($file = $request->file('icon')){
            $name_image = uniqid(). '-' .$file->getClientOriginalName();
            $image_resize = Image::make($file->getRealPath());

            $image_resize->save(public_path('images/blogCategories/og/' .$name_image), 75);

            if ($blogCategory->icon) {
                @unlink(public_path('images/blogCategories/og/'.$blogCategory->icon));
            }
        }

        // Banner Desktop
        foreach ($this->langs as $lang):
            $banner[$lang->lang] = '';
            if ($request->file('banner_desktop_'. $lang->lang)) {
              $banner[$lang->lang] = uniqid() . '-' . $request->file('banner_desktop_'. $lang->lang)->getClientOriginalName();
              $request->file('banner_desktop_'. $lang->lang)->move('images/blogCategories/og', $banner[$lang->lang]);

              if ($blogCategory->translationByLang($lang->id)->first()->banner_desktop) {
                  @unlink(public_path('images/blogCategories/og/'.$blogCategory->translationByLang($lang->id)->first()->banner_desktop));
              }
            }else{
                if ($request->get('old_banner_desktop_'. $lang->lang)) {
                    $banner[$lang->lang] = $request->get('old_banner_desktop_'. $lang->lang);
                }
            }
        endforeach;

        foreach ($this->langs as $lang):
            $banner_mob[$lang->lang] = '';
            if ($request->file('banner_mobile_'. $lang->lang)) {
              $banner_mob[$lang->lang] = uniqid() . '-' . $request->file('banner_mobile_'. $lang->lang)->getClientOriginalName();

              $request->file('banner_mobile_'. $lang->lang)->move('images/blogCategories/og', $banner_mob[$lang->lang]);

              if ($blogCategory->translationByLang($lang->id)->first()->banner_mobile) {
                  @unlink(public_path('images/blogCategories/og/'.$blogCategory->translationByLang($lang->id)->first()->banner_mobile));
              }
            }else{
                if ($request->get('old_banner_mobile_'. $lang->lang)) {
                    $banner_mob[$lang->lang] = $request->get('old_banner_mobile_'. $lang->lang);
                }
            }
        endforeach;

        $blogCategory->on_home = $on_home;
        $blogCategory->active = $active;
        $blogCategory->icon = $name_image;
        $blogCategory->alias = request('alias');
        $blogCategory->save();

        foreach ($this->langs as $lang):
            $blogCategory->translations()->where('blog_category_id', $id)->where('lang_id', $lang->id)->update([
                'name' => request('name_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'banner_desktop' => $banner[$lang->lang],
                'banner_mobile' => $banner_mob[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_description' => request('seo_description_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->back();
    }

    public function getCategoriesChilds($categoryId)
    {
        $categoriesId = [];
        $category1 = BlogCategory::find($categoryId);

        $categoriesId = array_merge($categoriesId, [$categoryId]);

        if (!is_null($category1)) {
            $categories2 = BlogCategory::where('parent_id', $category1->id)->pluck('id')->toArray();
            if (count($categories2) > 0) {
                $categoriesId = array_merge($categoriesId, $categories2);
                $categories3 = BlogCategory::whereIn('parent_id', $categories2)->pluck('id')->toArray();
                if (count($categories3) > 0) {
                    $categoriesId = array_merge($categoriesId, $categories3);
                }
            }
        }

        return $categoriesId;
    }

    public function remove(Request $request)
    {
        $category = BlogCategory::findOrFail($request->get('category_id'));

        @unlink(public_path('/images/blogCategories/og/' . $category->icon));

        foreach ($this->langs as $lang):
            @unlink(public_path('/images/blogCategories/og/' . $category->translationByLang($lang->id)->first()->banner_desktop));
            @unlink(public_path('/images/blogCategories/og/' . $category->translationByLang($lang->id)->first()->banner_mobile));
        endforeach;

        $category->delete();

        return $this->getCategories();
    }
}
