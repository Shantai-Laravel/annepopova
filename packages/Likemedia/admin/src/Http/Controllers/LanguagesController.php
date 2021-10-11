<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lang;
use App\Models\Brand;
use App\Models\Promotion;
use App\Models\Collection;
use App\Models\GalleryImage;
use App\Models\Page;
use App\Models\Parameter;
use App\Models\ParameterValue;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Set;
use App\Models\Translation;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\BlogCategory;
use App\Models\Blog;


class LanguagesController extends Controller
{
    public $lang = [];
    public $langs = [];

    public function __construct()
    {
        $this->lang = Lang::where('default', 1)->first();
        $this->langs = Lang::get();
    }

    public function index()
    {
        $languages = Lang::orderBy('default', 'desc')->get();

        return view('admin::admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin::admin.languages.create');
    }

    public function store(Request $request)
    {
        $defaultLang = Lang::where('default', '1')->first();

        $this->validate($request, [
            'name' => 'required|size:2|alpha',
            'description' => 'required|alpha'
        ]);

        $language = new Lang();
        $language->lang = $request->name;
        $language->description = $request->description;
        $language->active = 1;
        $language->save();

        $brands = Brand::get();
        $promotions = Promotion::get();
        $collections = Collection::get();
        $galleryImages = GalleryImage::get();
        $pages = Page::get();
        $parameters = Parameter::get();
        $parameterValues = ParameterValue::get();
        $products = Product::get();
        $productCategories = ProductCategory::get();
        $productImages = ProductImage::get();
        $sets = Set::get();
        $translations = Translation::get();
        $countries = Country::get();
        $currencies = Currency::get();
        $deliveries = Delivery::get();
        $payments = Payment::get();
        $blogCategories = BlogCategory::get();
        $blogs = Blog::get();

        foreach ($brands as $key => $brand) {
            $defaultBrand = $brand->translations()->where('lang_id', $defaultLang->id)->first();
            $brand->translations()->create(['lang_id' => $language->id, 'name' => $defaultBrand->name]);
        }

        foreach ($promotions as $key => $promotion) {
            $defaultPromoction = $promotion->translations()->where('lang_id', $defaultLang->id)->first();
            $promotion->translation()->create(['lang_id' => $language->id, 'name' => $defaultPromoction->name]);
        }

        foreach ($collections as $key => $collection) {
            $defaultCollection = $collection->translations()->where('lang_id', $defaultLang->id)->first();
            $collection->translation()->create(['lang_id' => $language->id, 'name' => $defaultCollection->name]);
        }

        foreach ($galleryImages as $key => $galleryImage) {
            $galleryImage->translation()->create(['lang_id' => $language->id]);
        }

        foreach ($pages as $key => $page) {
            $defaultPage = $page->translations()->where('lang_id', $defaultLang->id)->first();
            $page->translation()->create(['lang_id' => $language->id, 'title' => $defaultPage->title]);
        }

        foreach ($parameters as $key => $parameter) {
            $defaultParameter = $parameter->translations()->where('lang_id', $defaultLang->id)->first();
            $parameter->translation()->create(['lang_id' => $language->id, 'name' => $defaultParameter->name]);
        }

        foreach ($parameterValues as $key => $parameterValue) {
            $defaultParameterValue = $parameterValue->translations()->where('lang_id', $defaultLang->id)->first();
            if ($defaultParameterValue) {
                $parameterValue->translation()->create(['lang_id' => $language->id, 'name' => $defaultParameterValue->name]);
            }
        }

        foreach ($products as $key => $product) {
            $defaultProduct = $product->translations()->where('lang_id', $defaultLang->id)->first();
            $product->translation()->create(['lang_id' => $language->id, 'name' => $defaultProduct->name]);
        }

        foreach ($productCategories as $key => $productCategory) {
            $defaultCategory = $productCategory->translations()->where('lang_id', $defaultLang->id)->first();
            $productCategory->translation()->create(['lang_id' => $language->id, 'name' => $defaultCategory->name]);
        }

        foreach ($sets as $key => $set) {
            $defaultSet = $set->translations()->where('lang_id', $defaultLang->id)->first();
            $set->translation()->create(['lang_id' => $language->id, 'name' => $defaultSet->name]);
        }

        foreach ($translations as $key => $translation) {
            $defaultTranslation = $translation->translations()->where('lang_id', $defaultLang->id)->first();
            $translation->translation()->create(['lang_id' => $language->id, 'line' => $defaultTranslation->line]);
        }

        foreach ($countries as $key => $country) {
            $defaultCountry = $country->translations()->where('lang_id', $defaultLang->id)->first();
            $country->translation()->create(['lang_id' => $language->id, 'name' => $defaultCountry->name]);
        }

        foreach ($currencies as $key => $currency) {
            $defaultCurrency = $currency->translations()->where('lang_id', $defaultLang->id)->first();
            $currency->translation()->create(['lang_id' => $language->id, 'name' => $defaultCurrency->name]);
        }

        foreach ($deliveries as $key => $delivery) {
            $defaultDelivery = $delivery->translations()->where('lang_id', $defaultLang->id)->first();
            $delivery->translation()->create(['lang_id' => $language->id, 'name' => $defaultDelivery->name]);
        }

        foreach ($payments as $key => $payment) {
            $defaultPayment = $payment->translations()->where('lang_id', $defaultLang->id)->first();
            $payment->translation()->create(['lang_id' => $language->id, 'name' => $defaultPayment->name]);
        }

        foreach ($blogCategories as $key => $blogCategory) {
            $defaultBlogCategory = $blogCategory->translations()->where('lang_id', $defaultLang->id)->first();
            $blogCategory->translation()->create(['lang_id' => $language->id, 'name' => $defaultBlogCategory->name]);
        }

        foreach ($blogs as $key => $blog) {
            $defaultBlog = $blog->translations()->where('lang_id', $defaultLang->id)->first();
            $blog->translation()->create(['lang_id' => $language->id, 'name' => $defaultBlog->name]);
        }

        return redirect()->route('languages.index');
    }

    public function destroy($id)
    {
        $lang = Lang::findOrFail($id);
        $countries = $lang->countries()->get();
        $defaultLang = Lang::where('default', 1)->first();

        if (!is_null($defaultLang)) {
            if ($countries->count() > 0) {
                foreach ($countries as $key => $country) {
                    Country::where('id', $country->id)->update([
                        'lang_id' => $defaultLang->id,
                        // 'active' => 0,
                    ]);
                }
            }

            if ($lang->default == 1) {
                session('flash', "Can't delete default language!");
                return back();
            }
            $lang->delete();
        }

        session('flash', "Item was deleted!");
        return back();
    }

    public function setDefault($id)
    {
        $current = Lang::where('default', '1')->first();
        $current->default = 0;
        $current->save();

        $new = Lang::findOrFail($id);
        $new->default = 1;
        $new->save();

        $countries = Country::where('active', 0)->get();

        foreach ($countries as $key => $country) {
            Country::where('id', $country->id)->update([
                'lang_id' => $new->id,
            ]);
        }

        return back();
    }

    public function setActive($id)
    {
        $new = Lang::findOrFail($id);

        if ($new->active == 1) {
            $this->changeActiveCountries($new);
            $new->active = 0;
        }else {
            $new->active = 1;
        }

        $new->save();

        return back();
    }

    public function changeActiveCountries($lang)
    {
        $countries = $lang->countries()->get();
        $defaultLang = Lang::where('default', 1)->first();

        if ($lang->count() > 0) {
            foreach ($countries as $key => $country) {
                Country::where('id', $country->id)->update([
                    'lang_id' => $defaultLang->id,
                ]);
            }
        }
    }

}
