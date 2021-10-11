<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PagesController as PageItem;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\SubProduct;
use App\Models\Page;
use App\Models\ParameterValueProduct;
use App\Models\ParameterValue;
use App\Models\ProductSimilar;
use App\Models\ProductMaterial;
use App\Models\ProductPrice;
use App\Models\SetProducts;
use App\Models\Set;
use App\Models\SetTranslation;
use App\Models\Promotion;
use App\Models\ProductsCategories;

class ProductsController extends Controller
{
    /**
     *  post action
     *  Get Categories on Home Page
     */
    public function getCategoriesOnHome()
    {
        $data['cats'] = ProductCategory::with([
                                    'translation',
                                    'products.mainImage',
                                    'products.translation',
                                    'products.mainPrice',
                                    'products.personalPrice'])
                            ->where($this->siteType, 1)
                            ->where('on_home', 1)
                            ->where('active', 1)
                            ->orderBy('position', 'asc')
                            ->paginate(1);

        $data['cols'] = Collection::with([
                                    'translation',
                                    'sets.mainPhoto',
                                    'sets.translation',
                                    'sets.personalPrice'])
                            ->where($this->siteType, 1)
                            ->where('on_home', 1)
                            ->where('active', 1)
                            ->orderBy('position', 'asc')
                            ->paginate(1);

        return $data;
    }


    public function getOtherProducts(Request $request)
    {
        return Product::with([
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'hoverImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'translation',
                            'warehouse',
                        ])
                        ->where($this->siteType, 1)
                        ->where($this->warehouse, 1)
                        ->where('active', 1)
                        ->where('id', '!=', $request->get('product_id'))
                        ->where('category_id', $request->get('category_id'))
                        ->paginate(3);
    }

    /**
     *  get action
     *  Render Category page
     */
    public function categoryRender($category)
    {
        $category = ProductCategory::with([ 'children.translation',
                                            'translation',
                                            'params.property.translation',
                                            'params.property.transData',
                                            'params.property.parameterValues.translation',
                                            'params.property.parameterValues.transData',
                                        ])
                                    ->where('alias', $category)->first();


        if (is_null($category)) {
            abort(404);
        }

        $children = null;

        if ($category->children()->count() > 0) {
            $children = $category->children;
        }else{
            if ($category->parent()->count() > 0) {
                $children = $category->parent->children;
            }
        }

        $seoData = $this->_getSeo($category);

        $products = Product::with(['translation', 'mainPrice'])->where('category_id', $category->id)->get();
        $productList = $products;
        $list = "One cat - ".$category->translation->name;

        return view('front.'. $this->device .'.products.category', compact('seoData', 'category', 'children', 'products', 'productList', 'list'));
    }

    /**
     *  get action
     *  Render Product page
     */
    public function productRender(Request $request, $category, $product)
    {
        $product = Product::with([
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'hoverImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'warehouse',
                            'translation',
                          ])->where('alias', $product)
                          ->where($this->siteType, 1)
                          ->where($this->warehouse, 1)
                          ->where('active', 1)
                          ->orderBy('position', 'asc')
                          ->first();

        $category = ProductCategory::where('alias', $category)->first();

        if (is_null($product) || is_null($category)) {
            abort(404);
        }

        $productMaterial = ProductMaterial::where('material_id', $product->id)->pluck('product_id')->toArray();

        $similarProductsArray = ProductSimilar::where('product_id', $product->id)->pluck('category_id')->toArray();
        $similarProducts      = Product::with([
                                    'category.properties.property.parameterValues.translation',
                                    'category.translation',
                                    'images',
                                    'mainImage',
                                    'hoverImage',
                                    'mainPrice',
                                    'personalPrice',
                                    'subproducts.parameterValue.translation',
                                    'subproducts.parameter.translation',
                                    'subproducts.warehouse',
                                    'translation',
                                    'warehouse',
                                ])
                        ->whereIn('category_id', $similarProductsArray)
                        ->where('id', '!=', $product->id)
                        ->where($this->siteType, 1)
                        ->where($this->warehouse, 1)
                        ->where('active', 1)
                        ->get();

        if ($similarProducts->count() == 0) {
            $similarProducts = Product::with([
                                        'category.properties.property.parameterValues.translation',
                                        'category.translation',
                                        'images',
                                        'mainImage',
                                        'hoverImage',
                                        'mainPrice',
                                        'personalPrice',
                                        'subproducts.parameterValue.translation',
                                        'subproducts.parameter.translation',
                                        'subproducts.warehouse',
                                        'translation',
                                        'warehouse',
                                    ])
                                    ->where('category_id', $category->id)
                                    ->where('id', '!=', $product->id)
                                    ->where($this->siteType, 1)
                                    ->where($this->warehouse, 1)
                                    ->where('active', 1)
                                    ->inRandomOrder()
                                    ->limit(3)
                                    ->get();
        }

        $seoData = $this->_getSeo($product);

        $productsSets = $product->sets()->pluck('set_id')->toArray();

        if (!@$_COOKIE['view_recently']) {
            $prods[] = $product->id;
            setcookie('view_recently', json_encode($prods), time() + 10000000, '/');
        }else{
            $prods = json_decode(@$_COOKIE['view_recently']);
            if (!in_array($product->id, $prods)) {
                if (count($prods) == 4) {
                    array_splice($prods, 1, 1);
                }
                array_push($prods, $product->id);
            }
            setcookie('view_recently', json_encode($prods), time() + 10000000, '/');
        }

        $otherProducts = Product::with([
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'hoverImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'translation',
                            'warehouse',
                        ])
                        ->where($this->siteType, 1)
                        ->where($this->warehouse, 1)
                        ->where('active', 1)
                        ->where('id', '!=', $product->id)
                        ->where('category_id', $category->id)
                        ->get();

        if ($request->get('mode') == 'dev') {
            return view('front.'. $this->device .'.dev.productDev', compact('seoData', 'category', 'product', 'otherProducts', 'similarProducts', 'wearWith'));
        }else{
            return view('front.'. $this->device .'.products.product', compact('seoData', 'category', 'product', 'otherProducts', 'similarProducts', 'wearWith'));
        }
    }

    public function productRenderHammer($category, $product)
    {
        $product = Product::with([
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'hoverImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'warehouse',
                            'translation',
                          ])->where('alias', $product)
                          ->where($this->siteType, 1)
                          ->where($this->warehouse, 1)
                          ->where('active', 1)
                          ->orderBy('position', 'asc')
                          ->first();

        $category = ProductCategory::where('alias', $category)->first();

        if (is_null($product) || is_null($category)) {
            abort(404);
        }

        $productMaterial = ProductMaterial::where('material_id', $product->id)->pluck('product_id')->toArray();

        $similarProductsArray = ProductSimilar::where('product_id', $product->id)->pluck('category_id')->toArray();
        $similarProducts = Product::whereIn('category_id', $similarProductsArray)->where('id', '!=', $product->id)->get();

        if ($similarProducts->count() == 0) {
            $similarProducts = Product::where('category_id', $category->id)
                                    ->where('id', '!=', $product->id)
                                    ->where($this->siteType, 1)
                                    ->where($this->warehouse, 1)
                                    ->where('active', 1)
                                    ->inRandomOrder()
                                    ->limit(3)
                                    ->get();
        }

        $seoData = $this->_getSeo($product);

        $productsSets = $product->sets()->pluck('set_id')->toArray();

        if (!@$_COOKIE['view_recently']) {
            $prods[] = $product->id;
            setcookie('view_recently', json_encode($prods), time() + 10000000, '/');
        }else{
            $prods = json_decode(@$_COOKIE['view_recently']);
            if (!in_array($product->id, $prods)) {
                if (count($prods) == 4) {
                    array_splice($prods, 1, 1);
                }
                array_push($prods, $product->id);
            }
            setcookie('view_recently', json_encode($prods), time() + 10000000, '/');
        }

        $otherProducts = Product::with([
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'hoverImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'translation',
                            'warehouse',
                        ])
                        ->where($this->siteType, 1)
                        ->where($this->warehouse, 1)
                        ->where('active', 1)
                        ->where('id', '!=', $product->id)
                        ->where('category_id', $category->id)
                        ->get();

        return view('front.'. $this->device .'.products.hammer', compact('seoData', 'category', 'product', 'otherProducts', 'similarProducts', 'wearWith'));
    }

    /**
     *  get action
     *  Render new page
     */
    public function newRender()
    {
        $page = Page::where('alias', 'new')->first();

        if (is_null($page)) {
            abort(404);
        }

        $pageItem = new PageItem;
        $seoData = $pageItem->getSeo($page);

        $products = Product::with(['translation', 'mainPrice'])
                            ->where('discount', 0)
                            ->orderBy('created_at', 'desc')
                            ->where($this->siteType, 1)
                            ->where($this->warehouse, 1)
                            ->where('active', 1)
                            ->get();

        $productList = $products;
        $list = "New";

        return view('front.'. $this->device .'.products.new', compact('seoData', 'products', 'productList', 'list'));
    }

    /**
     *  post action (vuejs)
     *  return new products collection
     */
    public function getNewProducts(Request $request)
    {
        $products = Product::with(['category.properties.property.parameterValues.translation',
                                    'imagesBegin',
                                    'imagesLast',
                                    'images',
                                    'mainImage',
                                    'hoverImage',
                                    'mainPrice',
                                    'personalPrice',
                                    'category.translation',
                                    'subproducts.parameterValue.translation',
                                    'subproducts.parameter.translation',
                                    'subproducts.warehouse',
                                    'translation',
                                    'warehouse',

                                ])
                            // ->where('created_at', '>=', date('Y-m-d', strtotime('-15 days')))
                            ->where('discount', 0)
                            ->orderBy('created_at', 'desc')
                            ->where($this->siteType, 1)
                            ->where($this->warehouse, 1)
                            ->where('active', 1)
                            ->paginate(12);

        return $products;
    }

    /**
     *  get action
     *  Render Sale page
     */
    public function saleRender()
    {
        $page = Page::where('alias', 'sale')->first();

        if (is_null($page)) {
            abort(404);
        }

        $pageItem = new PageItem;
        $seoData = $pageItem->getSeo($page);

        $products = Product::with(['translation', 'mainPrice'])
                            ->where('discount', '>', '0')
                            ->orderBy('discount_update', 'desc')
                            ->where($this->siteType, 1)
                            ->where($this->warehouse, 1)
                            ->where('active', 1)
                            ->get();

        $productList = $products;
        $list = "Outlet";

        return view('front.'. $this->device .'.products.sale', compact('seoData', 'products', 'productList', 'list'));
    }

    /**
     *  post action (vuejs)
     *  return sale products collection
     */
    public function getSaleProducts(Request $request)
    {
        $products = Product::with(['category.properties.property.parameterValues.translation',
                                    'imagesBegin',
                                    'imagesLast',
                                    'images',
                                    'mainImage',
                                    'hoverImage',
                                    'category.translation',
                                    'subproducts.parameterValue.translation',
                                    'subproducts.parameter.translation',
                                    'subproducts.warehouse',
                                    'translation',
                                    'warehouse',
                                    'mainPrice',
                                    'personalPrice'])
                            ->where('discount', '>', '0')
                            ->orderBy('discount_update', 'desc')
                            ->where($this->siteType, 1)
                            ->where($this->warehouse, 1)
                            ->where('active', 1)
                            ->paginate(12);

        return $products;
    }

    public function getRecentlyProducts(Request $request)
    {
        $recently = [];
        if (@$_COOKIE['view_recently']) {
            $recently = json_decode(@$_COOKIE['view_recently']);
        }

        $products = Product::with(['category.properties.property.parameterValues.translation',
                                    'imagesBegin',
                                    'imagesLast',
                                    'images',
                                    'mainImage',
                                    'hoverImage',
                                    'category.translation',
                                    'subproducts.parameterValue.translation',
                                    'subproducts.parameter.translation',
                                    'subproducts.warehouse',
                                    'translation',
                                    'warehouse',
                                    'mainPrice',
                                    'personalPrice'])
                            ->whereIn('id', $recently)
                            ->limit(4)
                            ->where($this->siteType, 1)
                            ->where($this->warehouse, 1)
                            ->where('active', 1)
                            ->get();

        return $products;
    }

    /**
     *  post action (vuejs)
     *  return search products collection
     */
    public function searchProducts(Request $request)
    {
        $findProducts = ProductTranslation::where('name', 'like',  '%'.$request->get('search').'%')
                                    ->orWhere('body', 'like',  '%'.$request->get('search').'%')
                                    ->pluck('product_id')->toArray();

        $data['products'] = Product::with(['category.properties.property.parameterValues.translation', 'imagesBegin', 'imagesLast', 'images', 'mainImage', 'hoverImage', 'category.translation', 'subproducts', 'translation'])
                                    ->whereIn('id', $findProducts)
                                    // ->where(env('APP_SLUG'), 1)
                                    ->get();

        $findSets = SetTranslation::where('name', 'like',  '%'.$request->get('search').'%')
                                    ->pluck('set_id')->toArray();

        $data['sets'] = Set::with(['collection', 'mainPhoto', 'translation'])
                                    ->whereIn('id', $findSets)
                                    // ->where(env('APP_SLUG'), 1)
                                    ->get();

        return $data;
    }

    /**
     *  post action (vuejs)
     *  return simple category products
     */
    public function getProductsAllSimple(Request $request)
    {
        $category = ProductCategory::find($request->get('category_id'));

        $categoryChilds = ProductCategory::where('parent_id', $category->id)->pluck('id')->toArray();
        $categoryChilds2 = ProductCategory::whereIn('parent_id', $categoryChilds)->pluck('id')->toArray();

        $categoriesId = array_merge($categoryChilds, $categoryChilds2);

        array_push($categoryChilds, $category->id);

        $productCategories = ProductsCategories::whereIn('category_id', $categoryChilds)->pluck('product_id')->toArray();

        $prductsList = Product::whereIn('category_id', $categoryChilds)->pluck('id')->toArray();
        $prductsList2 = Product::whereIn('id', $productCategories)->pluck('id')->toArray();

        $productsIds = array_merge($prductsList, $prductsList2);

        $products = Product::with(['category.properties.property.parameterValues.translation',
                                    'imagesBegin',
                                    'imagesLast',
                                    'images',
                                    'mainImage',
                                    'hoverImage',
                                    'mainPrice', 'personalPrice',
                                    'category.translation',
                                    'subproducts.parameterValue.translation',
                                    'subproducts.parameter.translation',
                                    'subproducts.warehouse',
                                    'translation',
                                    'warehouse'
                                ])
                           ->whereIn('id', $productsIds)
                           ->paginate(6);

        return $products;
    }


    /**
     *  post action (vuejs)
     *  return category products
     */
    public function getProductsAll(Request $request)
    {
        $id = 0;
        $data = [];
        $product = Product::find($request->get('mainProductId'));
        $category = ProductCategory::find($request->get('category_id'));
        $categoryChilds = ProductCategory::where('parent_id', $category->id)->pluck('id')->toArray();
        $categoryChilds2 = ProductCategory::whereIn('parent_id', $categoryChilds)->pluck('id')->toArray();

        $categoriesId = array_merge($categoryChilds, $categoryChilds2);

        array_push($categoryChilds, $category->id);

        if (!is_null($product)) {
            $id = $product->id;
        }

        $allProducts = Product::whereIn('category_id', $categoryChilds)->get(); // without pagination

        $products = Product::with(['category.properties.property.parameterValues.translation',
                                    'imagesBegin',
                                    'imagesLast',
                                    'images',
                                    'mainImage',
                                    'hoverImage',
                                    'mainPrice', 'personalPrice',
                                    'category.translation',
                                    'subproducts.parameterValue.translation',
                                    'subproducts.parameter.translation',
                                    'subproducts.warehouse',
                                    'translation',
                                    'warehouse'
                                ])
                           ->orderByRaw("id = $id DESC")
                           ->whereIn('category_id', $categoriesId)
                           ->where($this->siteType, 1)
                           ->where($this->warehouse, 1)
                           ->where('active', 1)
                           ->paginate(3);

       // $data['filter']['properties'] = $this->getPropertiesList($allProducts);
       // $data['filter']['prices']['min'] = $allProducts->min('actual_price');
       // $data['filter']['prices']['max'] = $allProducts->max('actual_price');

       $maxPrice = ProductPrice::where('currency_id', $this->currency->id)
                               ->whereIn('product_id', $products->pluck('id')->toArray())
                               ->max('price');

       $data['prices']['min'] = 0;
       $data['prices']['max'] = $maxPrice;
       $data['products'] = $products;

       return json_encode($data);
    }

    public function setDefaultFilter(Request $request)
    {
        $categoryChilds = ProductCategory::where('parent_id', $request->get('category'))->pluck('id')->toArray();
        $categoryChilds = array_merge($categoryChilds, [$request->get('category')]);
        $allProducts = Product::whereIn('category_id', $categoryChilds)->get(); // without pagination

        $data['parameters'] = $this->getPropertiesList($allProducts, $request->get('category'));

        $maxPrice = ProductPrice::where('currency_id', $this->currency->id)
                                ->whereIn('product_id', $allProducts->pluck('id')->toArray())
                                ->max('price');

        $data['prices']['min'] = 0;
        $data['prices']['max'] = $maxPrice;

        return $data;
    }

    protected function getPropertiesList($allProducts, $categoryId)
    {
        $dependable = 0;
        $parametersId = ParameterValueProduct::whereIn('product_id', array_filter($allProducts->pluck('id')->toArray()))->pluck('parameter_value_id')->toArray();
        $dependableCategory = ProductCategory::where('id', $categoryId)->first();

        if (!is_null($dependableCategory)) {
            if (!is_null($dependableCategory->subproductParameter)) {
                $dependable = $dependableCategory->subproductParameter->parameter_id;
            }
        }

        $dependebleValues = ParameterValue::where('parameter_id', $dependable)->pluck('id')->toArray();

        $parametersId = array_merge($parametersId, $dependebleValues);
        return json_encode(array_filter($parametersId));
    }

    /**
     *  post action (vuejs)
     *  return subproduct on change size
     */
    public function getSubproductVue(Request $request)
    {
        $subproduct = SubProduct::where('product_id', $request->get('productId'))
                                ->where('parameter_id', $request->get('propertyId'))
                                ->where('value_id', $request->get('valueId'))
                                ->where('active', 1)
                                ->where('stoc', '>', 0)
                                ->first();
       return $subproduct;
    }

    // Filter products
    public function filter(Request $request)
    {
        $propsProducts =    [];
        $propsSubprods =    [];
        $params =           [];
        $subproducts =      [];
        $dependable = 0;
        $categoriesId = array_filter($request->get('categories'));
        $dependableCategory = ProductCategory::where('id', $request->get('category'))->first();

        $childCategories = ProductCategory::whereIn('parent_id', $categoriesId)->pluck('id')->toArray();

        $allCategoriesId = array_merge($categoriesId, $childCategories);


        if (!is_null($dependableCategory)) {
            if (!is_null($dependableCategory->subproductParameter)) {
                $dependable = $dependableCategory->subproductParameter->parameter_id;
            }
        }

        foreach ($request->get('properties') as $key => $param) {
            if ($param['name'] != $dependable) {
                $params[$param['name']][] = $param['value'];
            }else{
                $subproducts[] = $param;
            }
        }

        foreach ($params as $param => $values) {
            $propIds = [];
            foreach ($values as $key => $value) {
                $row = ParameterValueProduct::select('product_id')
                                ->where('parameter_value_id', $value)
                                ->where('parameter_id', $param)
                                ->when(count($propsProducts) > 0, function($query) use ($propsProducts){
                                    return $query->whereIn('product_id', $propsProducts);
                                })
                                ->pluck('product_id')->toArray();

                $propIds = array_merge($propIds, $row);
            }
            $propsProducts = $propIds;
        }

        foreach ($subproducts as $key => $value) {
            $row = Subproduct::select('product_id')
                                ->whereRaw('json_contains(combination, \'{"'.$value['name'].'": '.$value['value'].'}\')')
                                ->where('active', 1)
                                ->where('stoc', '>', 0)
                                ->when(count($propsProducts) > 0, function($query) use ($propsProducts){
                                   return $query->whereIn('product_id', $propsProducts);
                                })
                                ->pluck('product_id')->toArray();

            $propsSubprods = array_merge($propsSubprods, $row);
        }


        if ((count($request->get('properties')) > 0) && (count($propsProducts) == 0) && (count($propsSubprods) == 0)) {
            $propsProducts = [0];
        }

        $priceMax = $request->get('priceMax') ??  1000000;
        $priceMin = $request->get('priceMin') ?? 0;

        $products = Product::with(['category.properties.property.parameterValues.translation',
                                    'imagesBegin',
                                    'imagesLast',
                                    'images',
                                    'mainImage',
                                    'hoverImage',
                                    'category.translation',
                                    'subproducts.parameterValue.translation',
                                    'subproducts.parameter.translation',
                                    'subproducts.warehouse',
                                    'translation',
                                    'warehouse'
                                ])
                           ->orderBy('position', 'asc')
                           ->where($this->siteType, 1)
                           ->where($this->warehouse, 1)
                           ->where('active', 1)
                           ->when(count($allCategoriesId) > 0, function($query) use ($allCategoriesId){
                               return $query->whereIn('category_id', $allCategoriesId);
                           })
                           ->when(count($propsProducts) > 0, function($query) use ($propsProducts){
                              return $query->whereIn('id', $propsProducts);
                          })
                          ->when(count($propsSubprods) > 0, function($query) use ($propsSubprods){
                             return $query->whereIn('id', $propsSubprods);
                           })
                           ->whereHas('personalPrice', function($query) use ($priceMin, $priceMax){
                               $query->where('price', '>=', $priceMin);
                               $query->where('price', '<=', $priceMax);
                           })
                           ->with(['category.properties.property.parameterValues.translation', 'imagesBegin', 'imagesLast', 'images', 'mainImage', 'hoverImage', 'mainPrice', 'personalPrice', 'category.translation', 'subproducts', 'translation', 'warehouse'])
                           // ->where(env('APP_SLUG'), 1)
                           ->paginate(3);

        return $products;
    }

    public function renderPromotions()
    {
        $promotions = Promotion::with(['products.category.properties.property.parameterValues.translation',
                                        'products.images',
                                        'products.mainImage',
                                        'products.hoverImage',
                                        'products.category.translation',
                                        'products.subproducts.parameterValue.translation',
                                        'products.subproducts.parameter.translation',
                                        'products.translation',
                                        'products.mainPrice',
                                        'products.personalPrice'])
                                    ->where($this->siteType, 1)
                                    ->get();

        return view('front.'. $this->device .'.products.promo', compact('promotions'));
    }

    /**
     *  private method
     *  return meta datas of categories and products
     */
    private function _getSeo($item)
    {
        $seo['title']       = $item->translation->seo_title ?? $item->translation->name;
        $seo['keywords']    = $item->translation->seo_keywords ?? $item->translation->name;
        $seo['description'] = $item->translation->seo_description ?? $item->translation->name;

        return $seo;
    }

}
