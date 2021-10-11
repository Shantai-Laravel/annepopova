<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use Admin\Http\Controllers\AutoUploadController;
use App\Base as Model;
use App\Models\Product;
use App\Models\Parameter;
use App\Models\ParameterValueProduct;
use App\Models\TranslationLine;
use App\Models\Translation;
use App\Models\TranslationGroup;
use App\Models\Lang;
use App\Models\Promotion;
use App\Models\ProductPrice;
use App\Models\ProductDillerPrice;
use App\Models\DillerGroup;
use App\Models\SubProduct;
use App\Models\WarehousesStock;
use App\Models\Warehouse;
use App\Models\ProductImage;
use ImageOptimizer;
use GuzzleHttp\Client;


class AdminController extends Controller
{
    public function index()
    {
        $langs = Lang::pluck('id')->toArray();

        // $translations = TranslationLine::whereNotIn('lang_id', $langs)->delete();

        $products = Product::get();

        if ($products->count() > 0) {
            foreach ($products as $key => $product) {
                $mainPrice = $product->mainPrice->price;
                $product->update([
                    'price' => $mainPrice,
                    'actual_price' => $mainPrice,
                ]);
            }
        }

        return view('admin::admin.dashbord');
    }

    public function cleanTranslations()
    {
        $langs = Lang::pluck('id')->toArray();

        $translations = TranslationLine::whereNotIn('lang_id', $langs)->delete();
        dd($translations);
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;

            $request->file('upload')->move(public_path('images/ckeditor'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/ckeditor/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function optimizeAllImages()
    {
        // $productsImagesOG = \File::files(public_path('images/products/og/'));
        // $productsImagesSM = \File::files(public_path('images/products/sm/'));
        //
        // $productsImagesSet = \File::files(public_path('images/products/set/'));
        // $productsImagesFBQ = \File::files(public_path('images/products/fbq/'));
        //
        // $setsImagesOG = \File::files(public_path('images/sets/og/'));
        // $setsImagesSM = \File::files(public_path('images/sets/sm/'));
        // $setsImages = \File::files(public_path('images/sets/'));
        //
        // $categoryImagesOG = \File::files(public_path('images/categories/og/'));
        // $categoryImagesSM = \File::files(public_path('images/categories/sm/'));
        //
        // $collectionImages = \File::files(public_path('images/collections/'));

        $backgrounds = \File::files(public_path('fronts/img/backgrounds/'));
        $about = \File::files(public_path('fronts/img/about'));

        // foreach ($productsImagesOG as $key => $productsImageSrc) {
        //     ImageOptimizer::optimize($productsImageSrc, $productsImageSrc);
        //     $this->convert($productsImageSrc, $productsImageSrc);
        // }
        //
        // foreach ($productsImagesSM as $key => $productsImageSrc) {
        //     ImageOptimizer::optimize($productsImageSrc, $productsImageSrc);
        //     $this->convert($productsImageSrc, $productsImageSrc);
        // }
        //
        // foreach ($productsImagesSet as $key => $image) {
        //     ImageOptimizer::optimize($image, $image);
        //     $this->convert($image, $image);
        // }
        //
        // foreach ($productsImagesFBQ as $key => $image) {
        //     ImageOptimizer::optimize($image, $image);
        //     $this->convert($image, $image);
        // }
        //
        // foreach ($setsImagesOG as $key => $setsImageSrc) {
        //     ImageOptimizer::optimize($setsImageSrc, $setsImageSrc);
        //     $this->convert($setsImageSrc, $setsImageSrc);
        // }
        //
        // foreach ($setsImagesSM as $key => $setsImageSrc) {
        //     ImageOptimizer::optimize($setsImageSrc, $setsImageSrc);
        //     $this->convert($setsImageSrc, $setsImageSrc);
        // }
        //
        // foreach ($setsImages as $key => $setsImageSrc) {
        //     ImageOptimizer::optimize($setsImageSrc, $setsImageSrc);
        //     $this->convert($setsImageSrc, $setsImageSrc);
        // }
        //
        // foreach ($categoryImagesOG as $key => $image) {
        //     ImageOptimizer::optimize($image, $image);
        //     $this->convert($image, $image);
        // }
        //
        // foreach ($categoryImagesSM as $key => $image) {
        //     ImageOptimizer::optimize($image, $image);
        //     $this->convert($image, $image);
        // }
        //
        // foreach ($collectionImages as $key => $image) {
        //     ImageOptimizer::optimize($image, $image);
        //     $this->convert($image, $image);
        // }

        foreach ($backgrounds as $key => $image) {
            ImageOptimizer::optimize($image, $image);
            $this->convert($image, $image);
        }

        foreach ($about as $key => $image) {
            ImageOptimizer::optimize($image, $image);
            $this->convert($image, $image);
        }

        echo "Success....";

        return redirect()->back();
    }

    private function convert($from, $to)
    {
        $command = 'convert '
            . $from
            .' '
            . '-sampling-factor 4:2:0 -strip -quality 65'
            .' '
            . $to;
        return `$command`;
    }

    public function getTranslations()
    {
        $this->cleanTranslation();
        $langs = Lang::get();
        $url = "https://juliaallert.com";
        $i = 0;

        $endpoint = $url."/api/get/translations/all";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint);

        $contentArr = $response->getBody()->getContents();

        foreach (json_decode($contentArr)->groups as $key => $group) {
            $issetGroup = TranslationGroup::where('key', $group->key)->first();
            if (is_null($issetGroup)) {
                TranslationGroup::create([
                    'id' => $group->id,
                    'key' => $group->key,
                    'comment' => $group->comment,
                ]);
            }
        }

        foreach (json_decode($contentArr)->translations as $key => $translation) {
            $issetTranslation = Translation::where('key', $translation->key)->first();
            if (is_null($issetTranslation)) {

                $trans = Translation::create([
                    'id' => $translation->id,
                    'group_id' => $translation->group_id,
                    'key' => $translation->key,
                    'comment' => $translation->comment,
                ]);
                foreach ($langs as $key => $lang) {
                    TranslationLine::create([
                        'translation_id' => $trans->id,
                        'lang_id' => $lang->id,
                        'line' => '',
                    ]);
                }
            }
        }

        foreach ($langs as $key => $lang) {
            $endpoint = $url."/api/get/translations/".$lang->lang;
            $client = new \GuzzleHttp\Client();

            $response = $client->request('GET', $endpoint);

            $statusCode = $response->getStatusCode();
            $content = $response->getBody()->getContents();

            foreach (json_decode($content) as $key => $trans) {
                $translation = Translation::where('key', $trans->trans->key)->first();

                if (!is_null($translation)) {
                    $line = TranslationLine::where('lang_id', $lang->id)->where('translation_id', $translation->id)->update([
                        'line' => $trans->line,
                    ]);
                    $i++;
                }
            }
        }

        echo "Success, was synchronize ". $i ." translations from ".$url;
    }

    public function cleanTranslation()
    {
        $langIds = Lang::pluck('id')->toArray();

        TranslationLine::whereNotIn('lang_id', $langIds)->delete();
    }

    public function updatePrices()
    {
        $promotions = Promotion::get();
        $products = Product::get();
        $productPrices = ProductPrice::get();
        $dillerGroups = DillerGroup::get();
        $productDillerPrices = ProductDillerPrice::get();

        // update product discount
        foreach ($promotions as $key => $promotion) {
            foreach ($products as $key => $product) {
                if ($product->promotion_id == $promotion->id) {
                    if ($product->discount < $promotion->discount) {
                        $product->update([
                            'discount' => $promotion->discount,
                        ]);
                    }
                }
            }
        }

        // update retail and b2b prices
        foreach ($productPrices as $key => $productPrice) {
            if (!is_null($productPrice->product)) {
                $productPrice->update([
                    'price' => $productPrice->old_price - ($productPrice->old_price * $productPrice->product->discount / 100),
                    'b2b_price' => $productPrice->b2b_old_price - ($productPrice->b2b_old_price * $productPrice->product->discount / 100),
                ]);
            }else{
                $productPrice->delete();
            }
        }

        // update diller's Prices
        foreach ($productDillerPrices as $key => $productDillerPrice) {
            if (!is_null($productDillerPrice->product)) {
                if (!is_null($productDillerPrice->dillerGroup)) {
                    if ($productDillerPrice->dillerGroup->discount > $productDillerPrice->product->discount) {
                        $discount = $productDillerPrice->dillerGroup->discount;
                    }else{
                        $discount = $productDillerPrice->product->discount;
                    }
                    $productDillerPrice->update([
                        'price' => $productDillerPrice->old_price - ($productDillerPrice->old_price * $discount / 100),
                    ]);
                }else{
                    $productDillerPrice->delete();
                }
            }else{
                $productDillerPrice->delete();
            }
        }

        return redirect()->back();
    }

    public function updateStocks()
    {
        $subproducts = SubProduct::get();
        $products = Product::get();
        $warehouses = Warehouse::get();

        // dd($warehouses);

        foreach ($products as $key => $product) {
            if ($product->subproducts->count() > 0) {
                foreach ($product->subproducts as $key => $subproduct) {
                    foreach ($warehouses as $key => $warehouse) {
                        $findSubproductWarehouse = WarehousesStock::where('product_id', $subproduct->product_id)
                                                            ->where('subproduct_id', $subproduct->id)
                                                            ->where('warehouse_id', $warehouse->id)
                                                            ->first();
                        if (is_null($findSubproductWarehouse)) {
                            WarehousesStock::create([
                                'product_id' => $subproduct->product_id,
                                'subproduct_id' => $subproduct->id,
                                'warehouse_id' => $warehouse->id,
                                'stock' => 0,
                            ]);
                        }
                    }
                }
            }else{
                foreach ($warehouses as $key => $warehouse) {

                    $findProductWarehouse = WarehousesStock::where('product_id', $product->id)
                                                        ->where('subproduct_id', null)
                                                        ->where('warehouse_id', $warehouse->id)
                                                        ->first();
                    if (is_null($findProductWarehouse)) {
                        WarehousesStock::create([
                            'product_id' => $product->id,
                            'warehouse_id' => $warehouse->id,
                            'stock' => 0,
                        ]);
                    }
                }
            }
        }

        // foreach ($subproducts as $key => $subproduct) {
        //     if ($subproduct->warehouses) {
        //         foreach ($subproduct->warehouses as $key => $subproductWarehouse) {
        //             if ($subproductWarehouse->stock == 0) {
        //                 $productStock = WarehousesStock::where('product_id', $subproduct->product_id)->where('subproduct_id', null)->where('warehouse_id', $subproductWarehouse->warehouse_id)->first();
        //                 $subproductWarehouse->update([
        //                     'stock' => $productStock->stock,
        //                 ]);
        //             }
        //         }
        //     }
        // }
    }

    public function checkProductsStocks()
    {
        return true;
        $products = Product::get();

        foreach ($products as $key => $product) {
            $active = 0;
            $frisbo = 0;
            $swagger = 0;

            if ($product->subproducts()->count() > 0) {
                foreach ($product->subproducts as $key => $subproduct) {
                    foreach ($subproduct->warehouses as $key => $warehouse) {
                        $warehouseName = strtolower($warehouse->warehouse->name);
                        if ($warehouse->stock > 0) {
                            $$warehouseName = 1;
                        }
                    }
                }
            }else{
                foreach ($product->warehouses as $key => $warehouse) {
                    $warehouseName = strtolower($warehouse->warehouse->name);
                    if ($warehouse->stock > 0) {
                        $$warehouseName = 1;
                    }
                }
            }
            $product->update(['frisbo' => $frisbo, 'swagger' => $swagger]);
        }
    }

    public function handleProductsImagesShowAll()
    {
        $products = Product::get();

        foreach ($products as $key => $product) {
            $product->update([
                'frisbo' => 1,
                'swagger' => 1,
            ]);
        }
    }

    public function handleProductsImages1()
    {
        $images = \File::files(public_path('images/collections'));

        foreach ($images as $key => $file) {

            $fileName = $file->getFilename();

            $image_resize = Image::make($file->getRealPath());

            $image_resize->save(public_path('images/collections/og/' . $fileName));

            $image_resize->resize(480, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save('images/collections/sm/' . $fileName);

            echo $fileName.'<br>';
        }
    }

    public function handleProductsImages()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        // return $this->handleProductsImages1();

        $images = \File::files(public_path('images/products/uploads'));

        echo "<h5>Success!<h5>";
        echo "<a href='/back'>go back<a><hr>";

        foreach ($images as $key => $file) {

            $fileName = $file->getFilename();

            $image_resize = Image::make($file->getRealPath());
            $image_resize->save(public_path('images/products/og/' . $fileName));

            $image_resize->resize(960, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save('images/products/md/' . $fileName);

            $image_resize->resize(480, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save('images/products/sm/' . $fileName);

            echo $fileName.'<br>';
        }

        $this->resetImagsesCache();
    }

    public function resetImagsesCache()
    {
        $images = ProductImage::get();

        $uniqid = uniqid();
        foreach ($images as $key => $image) {
            if ($image->href) {
                $image->update(['src' => $image->href.'?'.$uniqid]);
            }
        }
    }

    public function regenerateSubproducts()
    {
        $products = Product::get();
        $autoupload = new AutoUploadController();
        $warehouses = Warehouse::get();

        foreach ($products as $key => $product) {
            $autoupload->generateSubprodusesForProduct($product);

            if ($product->subproducts) {
                foreach ($product->subproducts as $key => $subproduct) {
                    foreach ($warehouses as $key => $warehouse) {
                        $checkWareHouse = WarehousesStock::where('warehouse_id', $warehouse->id)->where('product_id', $product->id)->where('subproduct_id', $subproduct->id)->first();
                        if (is_null($checkWareHouse)) {
                            WarehousesStock::create([
                                'warehouse_id' => $warehouse->id,
                                'product_id' => $product->id,
                                'subproduct_id' => $subproduct->id,
                                'stock' => 0,
                            ]);
                        }
                    }
                }
            }else{
                foreach ($warehouses as $key => $warehouse) {
                    $checkWareHouse = WarehousesStock::where('warehouse_id', $warehouse->id)->where('product_id', $product->id)->where('subproduct_id', null)->first();
                    if (is_null($checkWareHouse)) {
                        WarehousesStock::create([
                            'warehouse_id' => $warehouse->id,
                            'product_id' => $product->id,
                            'subproduct_id' => null,
                            'stock' => 0,
                        ]);
                    }
                }
            }
        }
    }

    public function generateAditionallsForProducts()
    {
        $products = Product::get();
        $data = [];

        foreach ($products as $key => $product) {
            foreach ($product->category->translations as $key => $trans) {
                $data[$trans->lang_id]['category'] = $trans->name;
            }

            if ($product->setProds) {
                foreach ($product->setProds as $key => $set) {
                    foreach ($set->set->translations as $key => $trans) {
                        $data[$trans->lang_id]['set'] = $trans->name;
                    }
                }
            }

            if ($product->setProds) {
                foreach ($product->setProds as $key => $set) {
                    if ($set->set->collection) {
                        foreach ($set->set->collection->translations as $key => $trans) {
                            $data[$trans->lang_id]['collection'] = $trans->name;
                        }
                    }
                }
            }

            $color = getProductColor($product->id, 2);
            foreach ($this->langs as $key => $lang) {
                $data[$lang->id]['color'] = $color;
            }

            $this->insertDataToProduct($product, $data);
        }

    }

    public function insertDataToProduct($product, $data)
    {
        foreach ($data as $key => $value) {
            $product->translations()->where('lang_id', $key)->update([
                'aditionall' => json_encode($value),
            ]);
        }
    }

}
