<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Set;
use App\Models\Lang;
use Carbon\Carbon;


class SitemapController extends Controller
{

    public function index(Request $request)
    {
        $siteTypes = ['homewear', 'bijoux'];
        $url = $request->root();
        $staticPages = [''];

        $pages      = Page::orderBy('position', 'asc')->where('active', '1')->get();
        $categories = ProductCategory::orderBy('created_at', 'desc')->get();
        $products   = Product::orderBy('created_at', 'desc')->get();
        $blogs      = Blog::orderBy('created_at', 'desc')->get();
        $sets       = Set::orderBy('created_at', 'desc')->get();

        foreach ($siteTypes as $key => $siteType) {
            foreach (Lang::all() as $lang) {
                foreach ($staticPages as $staticPage) {
                    $aSiteMap[$url.'/'.$lang->lang.'/'. $siteType . '/' .$staticPage] = [
                        'added' => time(),
                        'lastmod' => Carbon::now()->toIso8601String(),
                        'priority' => 1 - substr_count($url.'/'.$lang->lang.'/'.$staticPage, '/') / 10,
                        'changefreq' => 'always'
                    ];
                }
            }
        }

        foreach (Lang::all() as $lang) {
            if(count($pages) > 0) {
                foreach ($pages as $page) {
                    $aSiteMap[$url.'/'.$lang->lang.'/'. $siteType . '/'. $page->alias] = [
                        'added' => time(),
                        'lastmod' => Carbon::now()->toIso8601String(),
                        'priority' => 1 - substr_count($url.'/'.$page->alias, '/') / 10,
                        'changefreq' => 'always'
                    ];
                }
            }
        }

        foreach ($siteTypes as $key => $siteType) {
            foreach (Lang::all() as $lang) {
                if(count($categories) > 0) {
                    foreach ($categories as $category) {
                        if ($category->{$siteType} == 1) {
                            $aSiteMap[$url.'/'.$lang->lang.'/'. $siteType . '/catalog/'.$category->alias] = [
                                'added' => time(),
                                'lastmod' => Carbon::now()->toIso8601String(),
                                'priority' => 1 - substr_count($url.'/catalog/'.$category->alias, '/') / 10,
                                'changefreq' => 'always'
                            ];
                        }
                    }
                }
            }
        }

        foreach ($siteTypes as $key => $siteType) {
            foreach (Lang::all() as $lang) {
                if(count($products) > 0) {
                    foreach ($products as $product) {
                        if ($product->{$siteType} == 1 && $product->active == 1) {
                            $aSiteMap[$url.'/'.$lang->lang.'/'. $siteType .'/catalog/'.$product->category->alias.'/'.$product->alias] = [
                                'added' => time(),
                                'lastmod' => Carbon::now()->toIso8601String(),
                                'priority' => 1 - substr_count($url.'/catalog/'.$product->category->alias.'/'.$product->alias, '/') / 10,
                                'changefreq' => 'always'
                            ];
                        }
                    }
                }
            }
        }

        // foreach ($siteTypes as $key => $siteType) {
        //     foreach (Lang::all() as $lang) {
        //         if(count($blogs) > 0) {
        //             foreach ($blogs as $blog) {
        //                 $aSiteMap[$url.'/'.$lang->lang. '/' . $siteType .'/lifestyle/'.$blog->alias] = [
        //                     'added' => time(),
        //                     'lastmod' => Carbon::now()->toIso8601String(),
        //                     'priority' => 1 - substr_count($url.'/lifestyle/'.$blog->alias, '/') / 10,
        //                     'changefreq' => 'always'
        //                 ];
        //             }
        //         }
        //     }
        // }

        foreach ($siteTypes as $key => $siteType) {
            foreach (Lang::all() as $lang) {
                if(count($sets) > 0) {
                    foreach ($sets as $set) {
                        if ($set->{$siteType} == 1) {
                            if ($set->collection) {
                                $aSiteMap[$url.'/'.$lang->lang. '/' . $siteType. '/collection/'.$set->collection->alias] = [
                                    'added' => time(),
                                    'lastmod' => Carbon::now()->toIso8601String(),
                                    'priority' => 1 - substr_count($url.'/collection/'.$set->collection->alias, '/') / 10,
                                    'changefreq' => 'always'
                                ];
                            }
                        }

                    }
                }
            }
        }

        \Cache::put('sitemap', $aSiteMap, 2880);

        return Response::view('front.sitemap')->header('Content-Type', 'application/xml');
    }
}
