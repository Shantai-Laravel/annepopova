<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Set;
use App\Models\Promocode;
use App\Models\UserField;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Collection;

class PagesController extends Controller
{
    /**
     *  get action
     *  render home page
     */
     public function general()
     {
         $this->siteType = 'homewear';

         $promotions = Promotion::where($this->siteType, 1)->get();
         return view('front.'. $this->device .'.general', compact('seoData', 'sets', 'page', 'categories', 'promotions'));
     }
    /**
     *  get action
     *  render home page
     */
     public function index()
     {
         $this->siteType = 'homewear';

         $promotions = Promotion::where($this->siteType, 1)->get();
         return view('front.'. $this->device .'.home', compact('seoData', 'sets', 'page', 'categories', 'promotions'));
     }

    public function getHomeSubcategories(Request $request)
    {
        $subcategories = ProductCategory::with('translation')
                                    ->where('parent_id', $request->category)
                                    ->paginate(3);

        return $subcategories;
    }

    public function getHomeProducts(Request $request)
    {
        $products = Product::with('translation')
                            ->where($this->warehouse, 1)
                            ->where('category_id', $request->category)
                            ->paginate(3);

        return $products;
    }

    public function getHomeCollections(Request $request)
    {
        $collections = Collection::with('translation')->paginate(6);

        return $collections;
    }

    /**
     *  get action
     *  render dinamic pages by alias
     */
    public function getPages($slug)
    {
        $page = Page::where('alias', $slug)->first();
        if (is_null($page)) {
            return redirect()->route('404');
        }

        if (view()->exists('front/'. $this->device .'/pages/'.$slug)) {
            $seoData = $this->getSeo($page);
            return view('front.'. $this->device .'.pages.'.$slug, compact('seoData', 'page'));
        }else{
            $seoData = $this->getSeo($page);
            return view('front.'. $this->device .'.pages.default', compact('seoData', 'page'));
        }
    }

    /**
     *  get action
     *  render 404 page
     */
    public function get404()
    {
        return view('front.'. $this->device .'.404');
    }

    /**
     *  get action
     *  render wellcome page
     */
    public function wellcome()
    {
        $userfields = UserField::where('in_register', 1)->get();

        return view('front.'. $this->device .'.pages.wellcome', compact('userfields'));
    }


    public function getPromocode($promocodeId)
    {
        $promocode = Promocode::find($promocodeId);

        if(count($promocode) > 0) {
            session(['promocode' => $promocode]);
            return redirect()->route('home');
        }
    }

    /**
     *  private method
     *  get meta datas of pages
     */
    public function getSeo($page)
    {
        $seo['title'] = $page->translation($this->lang->id)->first()->meta_title ?? $page->translation($this->lang->id)->first()->title;
        $seo['keywords'] = $page->translation($this->lang->id)->first()->meta_keywords ?? $page->translation($this->lang->id)->first()->title;
        $seo['description'] = $page->translation($this->lang->id)->first()->meta_description ?? $page->translation($this->lang->id)->first()->title;

        return $seo;
    }

}
