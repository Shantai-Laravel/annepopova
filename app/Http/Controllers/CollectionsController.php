<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\SubProduct;
use App\Models\Page;
use App\Models\Collection;
use App\Models\Set;

class CollectionsController extends Controller
{

    public function collectionRender(Request $request, $alias)
    {
        $mainSet = Set::with([
                            'translation',
                            'personalPrice',
                            'photos',
                            'products.category',
                            'products.translation',
                            'products.warehouse',
                            'products.mainPrice',
                            'products.personalPrice',
                            'products.mainImage',
                            'products.setImages',
                            'products.setImage',
                            'products.subproducts.parameterValue.translation',
                            'products.subproducts.parameter',
                            'products.subproducts.warehouse',
                            'collection.translation',
                        ])
                        ->where($this->siteType, 1)
                        ->where('id', $request->get('order'))->first();


        $collection = Collection::with([
                            'sets.translation',
                            'sets.personalPrice',
                            'sets.photos',
                            'sets.products.category',
                            'sets.products.translation',
                            'sets.products.warehouse',
                            'sets.products.mainPrice',
                            'sets.products.personalPrice',
                            'sets.products.mainImage',
                            'sets.products.setImages',
                            'sets.products.setImage',
                            'sets.products.subproducts.parameterValue.translation',
                            'sets.products.subproducts.parameter',
                            'sets.products.subproducts.warehouse',
                            'sets.collection.translation',
                        ])
                        ->where($this->siteType, 1)
                        ->where('alias', $alias)->first();


        if (is_null($collection)) {
            abort(404);
        }

        if (!is_null($mainSet)) {
            // dd($mainSet->id);
            $otherSets = Set::with([
                                'translation',
                                'personalPrice',
                                'photos',
                                'products.category',
                                'products.translation',
                                'products.warehouse',
                                'products.mainPrice',
                                'products.personalPrice',
                                'products.mainImage',
                                'products.setImages',
                                'products.setImage',
                                'products.subproducts.parameterValue.translation',
                                'products.subproducts.parameter',
                                'products.subproducts.warehouse',
                                'collection.translation',
                            ])
                            ->where('collection_id', $collection->id)
                            ->where('id', '!=', $mainSet->id)
                            ->where($this->siteType, 1)
                            ->get();
        }else{
            $otherSets = 'all';
        }

        $productsId = [];
        foreach ($collection->sets as $key => $set) {
            foreach ($set->products as $key => $prod) {
                $productsId[] = $prod->id;
            }
        }

        $products = Product::with(['translation', 'mainPrice'])
                            ->whereIn('id', $productsId)
                            ->orderBy('created_at', 'desc')
                            ->where($this->siteType, 1)
                            ->where($this->warehouse, 1)
                            ->where('active', 1)
                            ->get();

        $productList = $products;
        $list = "One Cat - ". $collection->translation->name;

        $seoData = $this->_getSeo($collection);

        $similars = Set::with([
                        'translation',
                        'personalPrice',
                        'photos',
                        'products.category',
                        'products.translation',
                        'products.warehouse',
                        'products.mainPrice',
                        'products.personalPrice',
                        'products.mainImage',
                        'products.setImages',
                        'products.setImage',
                        'products.subproducts.parameterValue.translation',
                        'products.subproducts.parameter',
                        'products.subproducts.warehouse',
                        'collection.translation',
                        'mainPhoto',
                    ])
                    ->where($this->siteType, 1)->whereNotIn('id', $collection->sets()->pluck('id')->toArray())->limit(5)->get();

        if ($request->get('mode') == 'dev') {
            return view('front.'. $this->device .'.dev.collectionDev', compact('seoData', 'collection', 'mainSet', 'productList', 'list', 'otherSets', 'similars'));
        }else{
            return view('front.'. $this->device .'.collections.collections', compact('seoData', 'collection', 'mainSet', 'productList', 'list', 'otherSets', 'similars'));
        }
    }

    public function collectionRenderAll()
    {
        $collection = Collection::get();

        $sets = Set::with(['translation', 'products.translation', 'products.warehouse', 'collection', 'mainPhoto'])->get();

        return view('front.'. $this->device .'.collections.all', compact('seoData', 'sets'));
    }

    public function setItemRender($id)
    {
        $set = Set::where('id', $id)->first();

        return view('front.'. $this->device .'.collections.set', compact('seoData', 'set'));
    }

    public function getSets(Request $request)
    {
        $sets = Set::with(['translation', 'products.translation', 'products.warehouse', 'collection', 'mainPhoto'])
                    ->where('collection_id', $request->get('collection_id'))
                    ->where(env('APP_SLUG'), 1)
                    ->paginate(3);

        return $sets;
    }

    public function setRender($collectionAlias, $setAlias)
    {
        $collection = Collection::where('alias', $collectionAlias)->first();

        if (is_null($collection)) {
            abort(404);
        }

        $set = Set::where('alias', $setAlias)->first();

        if (is_null($set)) {
            abort(404);
        }

        $similarSets = Set::where('id', '!=', $set->id)->limit(3)->inRandomOrder()->get();

        return view('front.'. $this->device .'.collections.set', compact('collection', 'set', 'similarSets'));
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
