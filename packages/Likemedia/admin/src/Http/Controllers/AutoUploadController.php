<?php

namespace Admin\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Admin\Http\Controllers\AdminController;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Promotion;
use App\Models\ParameterValueProduct;
use App\Models\ParameterValueProductTranslation;
use App\Models\ProductImage;
use App\Models\ProductImageTranslation;
use App\Models\AutoAlt;
use App\Models\SubProductProperty;
use App\Models\PropertyMultiData;
use App\Models\SubproductCombination;
use App\Models\SubProduct;
use App\Models\SubproductPrice;
use App\Models\SubProductParameter;
use App\Models\Parameter;
use App\Models\Set;
use App\Models\Collection;
use App\Models\SetProducts;
use App\Models\SetProductImage;
use App\Models\ProductSimilar;
use App\Models\ProductPrice;
use App\Models\Currency;
use App\Models\SetPrice;
use App\Models\ProductCollection;
use App\Models\ProductBrand;
use App\Models\Brand;
use App\Models\SetGallery;
use App\Models\ProductMaterial;
use App\Models\ProductsCategories;
use App\Models\Lang;
use App\Models\DillerGroup;
use App\Models\ProductDillerPrice;
use App\Models\Warehouse;
use App\Models\WarehousesStock;
use Admin\Http\Controllers\FrisboController;
use Admin\Http\Controllers\CollectionsController;

class AutoUploadController extends Controller
{
    public $lang = [];
    public $langs = [];

    public function __construct()
    {
        $this->lang = Lang::where('default', 1)->first();
        $this->langs = Lang::get();
    }

    public function index(Request $request)
    {
        $products = Product::get();
        $this->setAllPrices();

        $admin = new AdminController();
        $admin->checkProductsStocks();

        $allCategories = ProductCategory::pluck('parent_id')->toArray();
        $categories = ProductCategory::with('translation')->whereNotIn('id', $allCategories)->orderBy('position', 'asc')->get();
        $promotions = Promotion::with('translation')->get();
        $dillerGroups = DillerGroup::get();

        $currentCategory = ProductCategory::with([
                                            'translation',
                                            'params.property.translation',
                                            'params.property.parameterValues.translation',
                                            'property.property.translation',
                                            'property.property.parameterValues.translation'
                                        ])->find($request->get('category'));

        $products = Product::with([
                                'category.properties.property.parameterValues.translation',
                                'images',
                                'imagesFB',
                                'prices.currency',
                                'mainImage',
                                'category.translation',
                                'productCategories',
                                'subproducts.prices.currency',
                                'translation',
                                'translations',
                                'sets',
                                'setImages',
                                'similar',
                                'collections',
                                'brands',
                            ])
                           ->orderBy('id', 'desc')
                           ->paginate(10);

        $collections = Collection::with(['translation'])->get();
        $sets = Set::with(['translation'])->get();
        $brands = Brand::with(['translation'])->get();

        return view('admin::admin.autoupload.index', compact('categories', 'currentCategory', 'products', 'promotions', 'sets', 'collections', 'brands', 'dillerGroups'));
    }

    public function getProducts(Request $request)
    {
        $products = Product::with([
                                'category.properties.property.parameterValues.translation',
                                'images',
                                'imagesFB',
                                'prices.currency',
                                'mainImage',
                                'category.translation',
                                'productCategories',
                                'subproducts.prices.currency',
                                'translation',
                                'translations',
                                'propertyValues.translations',
                                'sets',
                                'setImages',
                                'similar',
                                'collections',
                                'brands',
                            ])
                           ->where('category_id', $request->get('category'))
                           ->orderBy('id', 'desc')
                           ->paginate(10);
        return $products;
    }

    public function edit(Request $request)
    {
        $item = $request->get('product');
        $props = array_filter($request->get('properties'), function($var){return !is_null($var);} );
        $propsText = array_filter($request->get('propertiesText'), function($var){return !is_null($var);} );
        $propsCheckbox = array_filter($request->get('propertiesCheckbox'), function($var){return !is_null($var);} );

        $findProduct = Product::find($item['id']);

        $product = Product::where('id', $item['id'])->update([
            // 'code' => $item['code'],
            // 'ean_code' => $item['ean_code'],
            'stock' => $item['stock'],
            'price' => $item['price'],
            'actual_price' => $item['price'] - ($item['price'] * $item['discount'] / 100),
            'discount' => $item['discount'],
            'promotion_id' => $item['promotion_id'],
        ]);

        $findProduct->translations()->delete();

        foreach ($item['translations'] as $key => $value) {
            $findProduct->translations()->create($value);
        }

        $this->updateSubproductCodes($item['id']);

        foreach ($props as $key => $value) {
            $findProp = ParameterValueProduct::where('product_id', $findProduct->id)->where('parameter_id', $key)->first();
            if (!is_null($findProp)) {
                ParameterValueProduct::where('product_id', $findProduct->id)->where('parameter_id', $key)->update([
                    'parameter_value_id' => $value,
                ]);
            }else{
                ParameterValueProduct::create([
                    'product_id' => $findProduct->id,
                    'parameter_id' => $key,
                    'parameter_value_id' => $value,
                ]);
            }
        }

        foreach ($propsCheckbox as $key => $values) {
            $findProp = ParameterValueProduct::where('product_id', $findProduct->id)->where('parameter_id', $key)->delete();

            if (is_array($values)) {
                foreach ($values as $value) {
                    ParameterValueProduct::create([
                        'product_id' => $findProduct->id,
                        'parameter_id' => $key,
                        'parameter_value_id' => $value,
                    ]);
                }
            }
        }

        foreach ($propsText as $key => $value) {
                $value = (array) $value;
                $findProp = ParameterValueProduct::where('product_id', $findProduct->id)->where('parameter_id', $key)->first();

                if (!is_null($findProp)) {
                    $property = ParameterValueProduct::where('product_id', $findProduct->id)->where('parameter_id', $key)->update([
                        'parameter_value_id' => 0,
                    ]);
                }else{
                    $findProp = ParameterValueProduct::create([
                        'product_id' => $findProduct->id,
                        'parameter_id' => $key,
                        'parameter_value_id' => 0,
                    ]);
                }

                $findProp->translations()->delete();
                foreach ($this->langs as $key => $languageItem) {
                    ParameterValueProductTranslation::create([
                        'lang_id' => $languageItem->id,
                        'param_val_id' => $findProp->id,
                        'value' => array_key_exists($key, $value) ? $value[$key] : $value[0]
                    ]);
                }
        }
    }

    public function updateSubproductCodes($id)
    {
        $product = Product::find($id);

        if ($product->subproducts->count() > 0) {
            $x = 1;

            foreach ($product->subproducts as $key => $subproduct) {
                $subproduct->update([
                    'code' => $product->code.$x,
                ]);
                $x++;
            }
        }
    }

    public function generateCode($product)
    {
        $code = substr($product->category->translation->name, 0, 3);
        $code .= substr($product->category->translation->name, -3);
        $code .= '-'.$product->category_id;
        $code .= $product->id;

        return $code;
    }

    public function create(Request $request)
    {
        $item = $request->get('product');
        $props = array_filter($request->get('properties'), function($var){return !is_null($var);} );
        $propsText = array_filter($request->get('propertiesText'), function($var){return !is_null($var);} );
        $propsCheckbox = array_filter($request->get('propertiesCheckbox'), function($var){return !is_null($var);} );

        $product = Product::create([
            'alias' => str_slug($item['name'][$this->lang->id]),
            'active' => 1,
            'position' => 0,
            'category_id' => $request->get('category_id'),
            // 'code' => $item['code'],
            // 'code' => $item['ean_code'],
            'stock' => $item['stoc'],
            'price' => $item['price'],
            'actual_price' => $item['price'] - ($item['price'] * $item['discount'] / 100),
            'discount' => $item['discount'],
            'promotion_id' => $item['promotion'],
        ]);

        foreach ($this->langs as $key => $lang) {
            $product->translation()->create([
                'lang_id' => $lang->id,
                'name' => $item['name'][$lang->id],
                'description' => $item['description'][$lang->id],
                'body' => $item['body'][$lang->id],
                'atributes' => @$item['atributes'][$lang->id],
            ]);
        }

        $code = $this->generateCode($product);
        $product->update([
            'code' => $code,
        ]);

        if (count($props) > 0) {
            foreach ($props as $key => $value) {
                ParameterValueProduct::create([
                    'product_id' => $product->id,
                    'parameter_id' => $key,
                    'parameter_value_id' => $value,
                ]);
            }
        }

        foreach ($propsCheckbox as $key => $values) {
            if (is_array($values)) {
                foreach ($values as $value) {
                    ParameterValueProduct::create([
                        'product_id' => $product->id,
                        'parameter_id' => $key,
                        'parameter_value_id' => $value,
                    ]);
                }
            }
        }

        foreach ($propsText as $key => $value) {
            $value = (array) $value;
            $findProp = ParameterValueProduct::create([
                'product_id' => $product->id,
                'parameter_id' => $key,
                'parameter_value_id' => 0,
            ]);

            foreach ($this->langs as $key => $lang) {
                ParameterValueProductTranslation::create([
                    'lang_id' => $lang->id,
                    'param_val_id' => $findProp->id,
                    'value' => array_key_exists($key, $value) ? $value[$key] : $value[0],
                ]);
            }
        }

        $this->generateSubprodusesForProduct($product);
        $this->generatePrices($product);

        $warehouses = Warehouse::get();
        if ($product->subproducts()->count() > 0) {
            foreach ($product->subproducts as $key => $subproduct) {
                foreach ($warehouses as $key => $warehouse) {
                    WarehousesStock::create([
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id,
                        'subproduct_id' => $subproduct->id,
                        'stock' => 0,
                    ]);
                }
            }
        }else{
            foreach ($warehouses as $key => $warehouse) {
                WarehousesStock::create([
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'subproduct_id' => null,
                    'stock' => 0,
                ]);
            }
        }

        // frisbo functional

        // if ($product->subproducts()->count() > 0) {
        //     $frisbo = new FrisboController();
        //     foreach ($product->subproducts as $key => $subproduct) {
        //         $frisbo->addProduct($subproduct);
        //     }
        // }
    }

    public function getImages(Request $request)
    {
        $images = ProductImage::where('product_id', $request->get('product_id'))->where('type', null)->orderBy('id', 'desc')->get();
        return $images;
    }

    public function getImagesFB(Request $request)
    {
        $images = ProductImage::where('product_id', $request->get('product_id'))->where('type', 'fb')->orderBy('id', 'desc')->get();
        return $images;
    }

    public function uploadImages(Request $request)
    {
        $product = $request->get('product_id');

        if($files = $request->file('attachments')){
            foreach($files as $key => $file){
                $uniqueId = uniqid();
                $clientOriginalName = str_replace(' ', '', $file->getClientOriginalName());
                $name = pathinfo($clientOriginalName, PATHINFO_FILENAME).sha1($uniqueId).$clientOriginalName;
                $image_resize = Image::make($file->getRealPath());
                $product_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['product'];
                $image_resize->save(public_path('images/products/og/' .$name));
                // $image_resize->resize(1000, 1000)->save(public_path('images/products/fbq/' .$name), 85);

                // $nameFB = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).$uniqueId.$file->getClientOriginalName();
                // $crop = $image_resize->crop(1200, 1200, 25, 25)->save(public_path('images/products/fbq/' .$nameFB), 85);

                // $image_resize->resize(null, 1080, function ($constraint) {
                //                 $constraint->aspectRatio();
                //             })->save('images/products/og/' .$name, 85);

                $image_resize->resize(960, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save('images/products/md/' .$name);

                $image_resize->resize(480, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save('images/products/sm/' .$name);

               ini_set('memory_limit', '-1');

               // $img = Image::make(public_path('images/background.png'));
               // $img->insert(public_path('images/products/og/'.$name), 'center');
               // $nameFB = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).$uniqueId.$file->getClientOriginalName();
               // $img->save(public_path('images/products/fbq/' .$nameFB), 75);
               //
               // $img = Image::make(public_path('images/products/fbq/' .$nameFB));
               // $img->insert(public_path('images/ramka-1.png'), 'center');
               // $nameFB = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).$uniqueId.$file->getClientOriginalName();
               // $img->save(public_path('images/products/fbq/' .$nameFB), 75);

               $images[] = $name;

                $image = ProductImage::create( [
                    'product_id' =>  $product,
                    'src' =>  $name,
                    'main' => 0,
                ]);

                // $imageFB = ProductImage::create( [
                //     'product_id' =>  $product,
                //     'src' =>  $nameFB,
                //     'main' => 0,
                //     'type' => 'fb',
                // ]);
                //
                // foreach ($this->langs as $lang){
                //     ProductImageTranslation::create( [
                //         'product_image_id' => $imageFB->id,
                //         'lang_id' =>  $lang->id,
                //     ]);
                // }

                foreach ($this->langs as $lang){
                  $category_id = Product::where('id', $product)->pluck('category_id');
                  $autoAlt = AutoAlt::where('cat_id', $category_id)->where('lang_id', $lang->id)->pluck('keywords')->toArray();

                  if(count($autoAlt) == 0) {
                    ProductImageTranslation::create( [
                        'product_image_id' => $image->id,
                        'lang_id' =>  $lang->id,
                        'alt' => $request->text[$lang->id][$key],
                        'title' => $request->text[$lang->id][$key],
                    ]);
                  }

                  if(count($autoAlt) > 0) {
                    if (count($autoAlt) == 1) {
                        ProductImageTranslation::create( [
                            'product_image_id' => $image->id,
                            'lang_id' =>  $lang->id,
                            'alt' => $autoAlt[0],
                            'title' => $autoAlt[0],
                        ]);
                    } else {
                      ProductImageTranslation::create( [
                          'product_image_id' => $image->id,
                          'lang_id' =>  $lang->id,
                          'alt' => $autoAlt[array_rand($autoAlt)],
                          'title' => $autoAlt[array_rand($autoAlt)],
                      ]);
                    }
                  }
                }
            }
        }
    }

    public function uploadImagesFB(Request $request)
    {
        $product = $request->get('product_id');

        if($files = $request->file('attachments')){
            foreach($files as $key => $file){
                $uniqueId = uniqid();
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).$uniqueId.$file->getClientOriginalName();
                $image_resize = Image::make($file->getRealPath());
                $product_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['product'];

                $image_resize->save(public_path('images/products/fbq/' .$name), 75);

                $images[] = $name;

                $image = ProductImage::create([
                    'product_id' =>  $product,
                    'src' =>  $name,
                    'main' => 0,
                    'type' => 'fb'
                ]);

                foreach ($this->langs as $lang){
                  $category_id = Product::where('id', $product)->pluck('category_id');
                  $autoAlt = AutoAlt::where('cat_id', $category_id)->where('lang_id', $lang->id)->pluck('keywords')->toArray();

                  if(count($autoAlt) == 0) {
                    ProductImageTranslation::create( [
                        'product_image_id' => $image->id,
                        'lang_id' =>  $lang->id,
                        'alt' => $request->text[$lang->id][$key],
                        'title' => $request->text[$lang->id][$key],
                    ]);
                  }

                  if(count($autoAlt) > 0) {
                    if (count($autoAlt) == 1) {
                        ProductImageTranslation::create( [
                            'product_image_id' => $image->id,
                            'lang_id' =>  $lang->id,
                            'alt' => $autoAlt[0],
                            'title' => $autoAlt[0],
                        ]);
                    } else {
                      ProductImageTranslation::create( [
                          'product_image_id' => $image->id,
                          'lang_id' =>  $lang->id,
                          'alt' => $autoAlt[array_rand($autoAlt)],
                          'title' => $autoAlt[array_rand($autoAlt)],
                      ]);
                    }
                  }
                }
            }
        }
    }

    public function remove(Request $request)
    {
        $item = $request->get('product');

        $findProduct = Product::find($item['id']);

        $findProduct->translations()->delete();
        $findProduct->delete();
    }

    public function removeImage(Request $request)
    {
        $image = ProductImage::find($request->get('id'));

        ProductImage::where('id', $request->get('id'))->delete();
        ProductImageTranslation::where('product_image_id', $request->get('id'))->get();

        @unlink(public_path('images/products/og/'.$image->src));
        @unlink(public_path('images/products/sm/'.$image->src));

        $images = ProductImage::where('product_id', $request->get('product_id'))->where('type', null)->orderBy('id', 'desc')->get();
        return $images;
    }

    public function removeImageFB(Request $request)
    {
        $image = ProductImage::find($request->get('id'));

        ProductImage::where('id', $request->get('id'))->delete();
        ProductImageTranslation::where('product_image_id', $request->get('id'))->get();

        @unlink(public_path('images/products/fbq/'.$image->src));

        $images = ProductImage::where('product_id', $request->get('product_id'))->where('type', 'fb')->orderBy('id', 'desc')->get();
        return $images;
    }

    public function mainImage(Request $request)
    {
        $image = ProductImage::find($request->get('id'));

        if (!is_null($image)) {
            ProductImage::where('product_id', $image->product_id)->update([
                'main' => 0,
            ]);

            ProductImage::where('id', $image->id)->update([
                'main' => 1,
                'first' => 0,
            ]);
        }

        $images = ProductImage::where('product_id', $request->get('product_id'))->where('type', null)->orderBy('id', 'desc')->orderBy('main', 'asc')->get();
        return $images;
    }

    public function firstImage(Request $request)
    {
        $image = ProductImage::find($request->get('id'));

        if (!is_null($image)) {
            ProductImage::where('product_id', $request->get('product_id'))->where('first', $request->get('sort'))->update([
                'first' => 0,
            ]);

            ProductImage::where('id', $image->id)->update([
                'first' => $request->get('sort'),
                'main' => 0
            ]);
        }

        $data['images'] = ProductImage::where('product_id', $request->get('product_id'))->where('type', null)->orderBy('id', 'desc')->orderBy('main', 'asc')->get();
        $data['imagesFB'] = ProductImage::where('product_id', $request->get('product_id'))->where('type', 'fb')->orderBy('id', 'desc')->get();
        return $data;
    }

    public function generateSubprodusesForProduct($product)
    {
        $category = ProductCategory::find($product->category_id);
        $subProductParameter = SubProductParameter::where('category_id', $category->id)->first();
        if (!is_null($subProductParameter)) {

        $parameter = Parameter::find($subProductParameter->parameter_id);
        $x = 'A';

        if (!is_null($parameter)) {
            if (count($parameter->parameterValues()->get())) {
                foreach ($parameter->parameterValues()->get() as $key => $value) {
                    $subproduct = SubProduct::where('product_id', $product->id)->where('parameter_id', $parameter->id)->where('value_id', $value->id)->first();
                    $combinationJSON = [ $parameter->id => $value->id ];
                    $x = $value->suffix;

                    if (is_null($subproduct)) {
                        SubProduct::create([
                            'product_id' => $product->id,
                            'parameter_id' => $parameter->id,
                            'value_id' => $value->id,
                            'code' => $product->code.'-'.$x,
                            'combination' => json_encode($combinationJSON),
                            'price' => $product->price,
                            'actual_price' => $product->actual_price,
                            'discount' =>  $product->discount,
                            'stoc' =>  $product->stock,
                            'active' =>  1,
                        ]);
                    }else{
                        SubProduct::where('id', $subproduct->id)->update([
                            'product_id' => $product->id,
                            'parameter_id' => $parameter->id,
                            'value_id' => $value->id,
                            'code' => $product->code.'-'.$x,
                            'combination' => json_encode($combinationJSON),
                        ]);
                    }
                    $x++;
                }
            }
        }

            $parameterValuesId = $parameter->parameterValues()->get()->pluck('id')->toArray();
            SubProduct::where('product_id', $product->id)->whereNotIn('value_id', $parameterValuesId)->delete();
            SubProduct::where('product_id', $product->id)->where('parameter_id', '!=', $parameter->id)->delete();
        }
    }

    public function editSubproducts(Request $request)
    {
        $active = array_filter($request->get('subproducts')['active'], function($var){return !is_null($var);} );
        $price = array_filter($request->get('subproducts')['price'], function($var){return !is_null($var);} );
        $prices = array_filter($request->get('subproductPrices'), function($var){return !is_null($var);} );
        $stocks = array_filter($request->get('subproducts')['stoc'], function($var){return !is_null($var);} );
        $ean_code = array_filter($request->get('subproducts')['ean_code'], function($var){return !is_null($var);} );
        $discount = array_filter($request->get('subproducts')['discount'], function($var){return !is_null($var);} );

        $productCode = $request->get('code');
        $productEanCode = $request->get('ean_code');
        $productStocks = array_filter($request->get('stocks'), function($var){return !is_null($var);} );

        $product = Product::find($request->get('product_id'));

        $product->update([
            'code' => $productCode,
            'ean_code' => $productEanCode,
        ]);

        foreach ($productStocks as $key => $productStock) {
            WarehousesStock::where('id', $key)->update([
                'stock' => $productStock,
            ]);
        }

        foreach ($active as $key => $activeItem) {
            Subproduct::where('id', $key)->update([
                'active' => @$active[$key],
                'price' => @$price[$key],
                'actual_price' => @$price[$key] - (@$price[$key] * @$discount[$key] / 100),
                'discount' => @$discount[$key],
                'ean_code' => @$ean_code[$key],
            ]);
        }

        foreach ($stocks as $key => $stock) {
            WarehousesStock::where('id', $key)->update([
                'stock' => $stock,
            ]);
        }

        $this->setSubproductsPrices($prices, $request->get('product_id'));

        $subproducts = Subproduct::with('prices.currency')->where('product_id', $request->get('product_id'))->get();

        $admin = new AdminController();
        $admin->checkProductsStocks();

        return $subproducts;
    }

    private function setSubproductsPrices($prices, $productId)
    {
        if (count($prices) > 0) {
            foreach ($prices as $key => $price) {
                SubproductPrice::where('id', $key)->update([
                    'old_price' => $price,
                    'price' => $price,
                ]);
            }
        }

        $this->generateSubproductsPrices($productId);
    }

    public function inheritSubproducts(Request $request)
    {
        $findSubproducts = Subproduct::with('prices.currency')->where('product_id', $request->get('product_id'))->get();
        $product = Product::find($request->get('product_id'));
        $warehouses = Warehouse::get();

        if (count($findSubproducts) > 0) {
            foreach ($findSubproducts as $key => $subproduct) {
                foreach ($warehouses as $key => $warehouse) {
                    $productStock = WarehousesStock::where('warehouse_id', $warehouse->id)->where('product_id', $product->id)->where('subproduct_id', null)->first();
                    WarehousesStock::where('product_id', $product->id)
                        ->where('subproduct_id', $subproduct->id)
                        ->where('warehouse_id', $warehouse->id)
                        ->update([
                            'stock' => $productStock->stock,
                        ]);
                }


                // Subproduct::where('id', $subproduct->id)->update([
                //     'price' => $product->price,
                //     'actual_price' => $product->actual_price,
                //     'discount' => $product->discount,
                //     'stoc' => $product->stock,
                // ]);

                // if ($product->prices()->get()) {
                //     foreach ($product->subproducts()->get() as $key => $subproduct) {
                //         foreach ($product->prices()->get() as $key => $productPrice) {
                //             SubProductPrice::where('subproduct_id', $subproduct->id)
                //                             ->where('currency_id', $productPrice->currency_id)
                //                             ->update([
                //                                 'price' => $productPrice->price,
                //                                 'old_price' => $productPrice->old_price,
                //                             ]);
                //
                //         }
                //     }
                // }
            }
        }

        $data['warehouses']         = Warehouse::where('active', 1)->get();
        $data['warehousesStocks']   = WarehousesStock::with(['warehouse'])
                                                    ->where('product_id', $request->get('product_id'))
                                                    ->where('subproduct_id', '!=', null)
                                                    ->get();

        $data['subproducts'] =  Subproduct::with('prices.currency')->where('product_id', $request->get('product_id'))->get();

        return $data;
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $finds = ProductTranslation::where('name', 'like', '%' . $search . '%')->pluck('product_id')->toArray();

        $products = Product::with([
                                'category.properties.property.parameterValues.translation',
                                'images',
                                'imagesFB',
                                'prices.currency',
                                'mainImage',
                                'category.translation',
                                'productCategories',
                                'subproducts.prices.currency',
                                'translation',
                                'translations',
                                'propertyValues',
                                'sets',
                                'setImages',
                                'similar',
                                'collections',
                                'brands',
                            ])->whereIn('id', array_unique($finds))->get();

        return $products;
    }

    public function saveSets(Request $request)
    {
        $setProduct = SetProducts::where('product_id', $request->get('product_id'))
                                    ->where('set_id', $request->get('set_id'))
                                    ->first();

        if (!is_null($setProduct)) {
            $setProduct->delete();
        }else{
            SetProducts::create([
                    'set_id' => $request->get('set_id'),
                    'product_id' => $request->get('product_id'),
                ]);
        }

        $collections = new CollectionsController();
        $collections->discountSetPrices();
    }

    public function saveCategs(Request $request)
    {
        $categProduct = ProductsCategories::where('product_id', $request->get('product_id'))
                                    ->where('category_id', $request->get('categ_id'))
                                    ->first();

        if (!is_null($categProduct)) {
            $categProduct->delete();
        }else{
            ProductsCategories::create([
                    'category_id' => $request->get('categ_id'),
                    'product_id' => $request->get('product_id'),
                ]);
        }
    }

    public function uploadSetProductImage(Request $request)
    {
        $product = $request->get('product_id');
        $set = $request->get('set_id');

        if ($product){

            if($files = $request->file('attachments')){
                $issetImage = SetProductImage::where('product_id', $product)->where('set_id', $set)->first();
                if (!is_null($issetImage)) {
                    @unlink(public_path('/images/products/set/' . $issetImage->image));
                    $issetImage->delete();
                }

                foreach($files as $key => $file){
                    $uniqueId = uniqid();
                    $name = $uniqueId.$file->getClientOriginalName();
                    $image_resize = Image::make($file->getRealPath());
                    $image_resize->save(public_path('images/products/set/' .$name), 75);

                    $image = SetProductImage::create( [
                        'product_id' =>  $product,
                        'set_id' =>  $set,
                        'image' =>  $name,
                        'main' => 0,
                    ]);

                }
            }

            $data['product'] = Product::with([
                                    'category.properties.property.parameterValues.translation',
                                    'images',
                                    'imagesFB',
                                    'prices.currency',
                                    'mainImage',
                                    'category.translation',
                                    'productCategories',
                                    'subproducts.prices.currency',
                                    'translation',
                                    'translations',
                                    'propertyValues',
                                    'sets',
                                    'setImages',
                                    'similar',
                                    'collections',
                                    'brands',
                                ])->where('id', $product)->first();

            $data['sets'] = Set::with(['translation'])->get();

            return $data;
        }

        return false;
    }

    public function generateNewSet(Request $request)
    {
        $product = Product::where('id', $request->get('product_id'))->first();
        $collectionId = $request->get('collection_id');
        $issetCollection = $product->collections()->where('collection_id', $collectionId)->first();

        if (is_null($issetCollection)){
            ProductCollection::create(['collection_id' => $collectionId, 'product_id' => $product->id]);

            $set = new Set();
            $set->collection_id = $collectionId;
            $set->alias = $product->alias;
            $set->price = $product->price;
            $set->position = 0;
            $set->active = 1;
            $set->save();

            $set->code = 'Set-'.$set->id;
            $set->save();

            foreach ($product->translations as $productRow):
                $set->translations()->create([
                    'lang_id' => $productRow->lang_id,
                    'name' => $productRow->name,
                    'description' => $productRow->description,
                ]);
            endforeach;

            SetProducts::create([
                'set_id' => $set->id,
                'product_id' => $product->id,
            ]);

            $productImages = ProductImage::where('product_id', $product->id)->where('type', null)->orderBy('id', 'desc')->get();

            if (count($productImages) > 0) {
                foreach ($productImages as $key => $productImage) {
                    SetGallery::create([
                        'set_id' => $set->id,
                        'type' => 'photo',
                        'src' => $productImage->src
                    ]);
                    $ogFile = copy(public_path('images/products/og/' .$productImage->src), public_path('images/sets/og/' .$productImage->src));
                    $smFile = copy(public_path('images/products/sm/' .$productImage->src), public_path('images/sets/sm/' .$productImage->src));
                }
            }

            foreach ($product->prices()->get() as $key => $price) {
                SetPrice::create([
                    'set_id' => $set->id,
                    'currency_id' => $price->currency_id,
                    'old_price' => $price->old_price,
                    'price' => $price->price,
                    'dependable' => $price->dependable,
                ]);
            }
        }else{
            Set::where('alias', $product->alias)->delete();
            ProductCollection::where('collection_id', $collectionId)->where('product_id', $product->id)->delete();
        }

    }

    public function setSimilarProducts(Request $request)
    {
        // return $request->get('category_id');
        $similar = ProductSimilar::where('product_id', $request->get('product_id'))->where('category_id', $request->get('category_id'))->first();

        if (!is_null($similar)) {
            $similar->delete();
        }else{
            ProductSimilar::create([
                'product_id' => $request->get('product_id'),
                'category_id' => $request->get('category_id'),
            ]);
        }
    }

    public function setSimilarAllProducts(Request $request)
    {
        $arr = [];
        foreach ($request->get('categories_id') as $key => $category) {
            $arr[] = $category['id'];
        }

        ProductSimilar::where('product_id', $request->get('product_id'))->delete();

        foreach ($arr as $key => $category) {
            ProductSimilar::create([
                'product_id' => $request->get('product_id'),
                'category_id' => $category,
            ]);
        }
    }

    public function uploadVideo(Request $request)
    {
        $product = Product::where('id', $request->get('product_id'))->first();
        $videoName = "";

        if($files = $request->file('attachments')){
            if (!is_null($product->video)) {
                @unlink(public_path('/videos/' . $product->video));
            }

            foreach($files as $key => $file){
                $videoName = uniqid().$file->getClientOriginalName();
                $path = public_path().'/videos/';
                $file->move($path, $videoName);

                Product::where('id', $product->id)->update([
                    'video' => $videoName,
                ]);
            }
        }

        return $videoName;
    }

    public function setHitProduct(Request $request)
    {
        $product = Product::where('id', $request->get('product_id'))->where('hit', 1)->first();

        if (is_null($product)) {
            Product::where('id', $request->get('product_id'))->update([
                'hit' => 1
            ]);
        }else{
            Product::where('id', $request->get('product_id'))->update([
                'hit' => 0
            ]);
        }
    }

    public function setRecomandedProduct(Request $request)
    {
        $product = Product::where('id', $request->get('product_id'))->where('recomended', 1)->first();

        if (is_null($product)) {
            Product::where('id', $request->get('product_id'))->update([
                'recomended' => 1
            ]);
        }else{
            Product::where('id', $request->get('product_id'))->update([
                'recomended' => 0
            ]);
        }
    }

    public function removeVideo(Request $request)
    {
        $product = Product::where('id', $request->get('product_id'))->first();

        if (!is_null($product->video)) {
            @unlink(public_path('/videos/' . $product->video));
        }

        Product::where('id', $request->get('product_id'))->update([
            'video' => null
        ]);
    }

    public function removeSetProductImage(Request $request)
    {
        $product = $request->get('product_id');
        $set = $request->get('set_id');

        if ($product){
            $issetImage = SetProductImage::where('product_id', $product)->where('set_id', $set)->first();

            if (!is_null($issetImage)) {
                @unlink(public_path('/images/products/set/' . $issetImage->image));
            }

            $issetImage->delete();

            $data['product'] = Product::with([
                                    'category.properties.property.parameterValues.translation',
                                    'images',
                                    'imagesFB',
                                    'prices.currency',
                                    'mainImage',
                                    'category.translation',
                                    'productCategories',
                                    'subproducts.prices.currency',
                                    'translation',
                                    'translations',
                                    'propertyValues',
                                    'sets',
                                    'setImages',
                                    'similar',
                                    'collections',
                                    'brands',
                                ])->where('id', $product)->first();

            $data['sets'] = Set::with(['translation'])->get();

            return $data;
        }

        return false;
    }

    public function addBrandToProduct(Request $request)
    {
        $product = Product::where('id', $request->get('product_id'))->first();
        $brand = $product->brands()->where('brand_id',  $request->get('brand_id'))->first();

        if (is_null($brand)) {
            ProductBrand::create([
                'brand_id' => $request->get('brand_id'),
                'product_id' => $request->get('product_id'),
            ]);
        }else{
            ProductBrand::where('product_id', $request->get('product_id'))
                        ->where('brand_id', $request->get('brand_id'))
                        ->delete();
        }
    }

    public function changeDependeblePrice(Request $request)
    {
        $product = Product::findOrFail($request->get('product_id'));

        if ($product->dependable_price == 1) {
            $product->update([
                'dependable_price' => 0
            ]);
        }else{
            $product->update([
                'dependable_price' => 1
            ]);
        }

        $this->regeneratePricesStep1($request);

        $product = Product::with(['prices.currency'])->findOrFail($request->get('product_id'));

        return $product;
    }

    public function changeDependeblePriceSubproduct(Request $request)
    {
        $subProduct = SubProduct::findOrFail($request->get('subproduct_id'));
        $prices = array_filter($request->get('subproductPrices'), function($var){return !is_null($var);} );

        if ($subProduct->dependable_price == 1) {
            $subProduct->update([
                'dependable_price' => 0
            ]);
            $this->setSubproductsPrices($prices, $request->get('product_id'));
        }else{
            $subProduct->update([
                'dependable_price' => 1
            ]);
            $this->regenerateSubproductsPrices($subProduct);
        }

        return Subproduct::with('prices.currency')->where('product_id', $request->get('product_id'))->get();
    }

    public function savePrices(Request $request)
    {
        Product::where('id', $request->get('product_id'))->update([
            'discount' => $request->get('discount')
        ]);

        $this->regeneratePricesStep1($request);

        $data['diller_prices'] = $this->generateDillerPrices($request->get('product_id'));
        $data['product_prices'] = Product::with(['prices.currency'])->findOrFail($request->get('product_id'));

        return $data;
    }

    public function generateDillerPrices($productId)
    {
        $product = Product::findOrFail($productId);
        $dillerGroups = DillerGroup::get();
        $retPrices = [];

        ProductDillerPrice::where('product_id', $product->id)->delete();

        foreach ($dillerGroups as $key => $dillerGroup) {
            foreach ($product->prices as $key => $price) {
                if ($dillerGroup->applied_on == 'b2b') {
                    ProductDillerPrice::create([
                        'product_id' => $product->id,
                        'diller_group_id' => $dillerGroup->id,
                        'currency_id' => $price->currency->id,
                        'price' => $price->b2b_old_price - ($price->b2b_price * $dillerGroup->discount / 100),
                        'old_price' => $price->b2b_old_price - ($price->b2b_price * $dillerGroup->discount / 100),
                        // 'old_price' => $price->b2b_old_price - ($price->b2b_old_price * $dillerGroup->discount / 100),
                        // 'price' => $price->b2b_price - ($price->b2b_price * $dillerGroup->discount / 100),
                    ]);
                    $retPrices[$dillerGroup->id][$price->currency->abbr] = $price->b2b_old_price - ($price->b2b_old_price * $dillerGroup->discount / 100);
                }else{
                    ProductDillerPrice::create([
                        'product_id' => $product->id,
                        'diller_group_id' => $dillerGroup->id,
                        'currency_id' => $price->currency->id,
                        'price' => $price->old_price - ($price->price * $dillerGroup->discount / 100),
                        'old_price' => $price->old_price - ($price->price * $dillerGroup->discount / 100),
                        // 'old_price' => $price->old_price - ($price->old_price * $dillerGroup->discount / 100),
                        // 'price' => $price->price - ($price->price * $dillerGroup->discount / 100),
                    ]);
                    $retPrices[$dillerGroup->id][$price->currency->abbr] = $price->old_price - ($price->old_price * $dillerGroup->discount / 100);
                }
            }
        }

        return $retPrices;
    }

    // Generate prices on product create
    public function generatePrices($product)
    {
        $currencies = Currency::orderBy('type', 'desc')->get();
        $mainCurrency = Currency::where('type', 1)->first();

        if ($currencies->count() > 0) {
            foreach ($currencies as $key => $currency) {
                $checkProductPrice = $product->prices()->where('currency_id', $currency->id)->first();
                if (!$checkProductPrice) {
                    $product->prices()->create([
                        'currency_id' => $currency->id,
                        'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                    ]);
                }

                $this->countByRateProductsPrice($product, $mainCurrency, $currency);

                // generate subproducts prices
                if ($product->subproducts->count() > 0) {
                    foreach ($product->subproducts as $key => $subproduct) {
                        $checkSubproductPrice = $subproduct->prices()->where('currency_id', $currency->id)->first();
                        if (!$checkSubproductPrice) {
                            $subproduct->prices()->create([
                                'currency_id' => $currency->id,
                                'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                            ]);
                        }

                        $this->countByRateSubProductsPrice($subproduct, $mainCurrency, $currency);
                    }
                }
            }
        }
    }

    // Product Generate exchange Prices
    public function regeneratePricesStep1($request)
    {
        $prices = array_filter($request->get('prices'), function($var){return !is_null($var);} );
        $pricesB2B = array_filter($request->get('b2bPrices'), function($var){return !is_null($var);} );
        $product = Product::findOrFail($request->get('product_id'));
        $mainCurrency = Currency::where('type', 1)->first();
        $mainPrice = ProductPrice::where('currency_id', $mainCurrency->id)->where('product_id', $product->id)->first();


        // if ($product->dependable_price == 1) {
        //     foreach ($prices as $key => $price) {
        //         if ($mainPrice->id == $key) {
        //             ProductPrice::where('id', $key)->update([
        //                 'old_price' => $price,
        //                 'price' => $price - ($price * $product->discount / 100),
        //             ]);
        //             $this->regeneratePricesStep2($product);
        //         }
        //     }
        // }else{
        //     foreach ($prices as $key => $price) {
        //         ProductPrice::where('id', $key)->update([
        //             'old_price' => $price,
        //             'price' => $price - ($price * $product->discount / 100),
        //         ]);
        //     }
        // }

        foreach ($prices as $key => $price) {
            $prodPrice = ProductPrice::find($key);
            if ($prodPrice->currency->exchange_dependable == 1) {
                if ($mainPrice->id == $key) {
                    ProductPrice::where('id', $key)->update([
                        'old_price' => $price,
                        'price' => (int)$price - ((int)$price * $product->discount / 100),
                        'b2b_price' => $pricesB2B[$key],
                        'b2b_old_price' => $pricesB2B[$key],
                    ]);
                    $this->regeneratePricesStep2($product);
                }
            }else{
                ProductPrice::where('id', $key)->update([
                           'old_price' => $price,
                           'price' => (int)$price - ((int)$price * $product->discount / 100),
                           'b2b_price' => $pricesB2B[$key],
                           'b2b_old_price' => $pricesB2B[$key],
                       ]);
            }

        }
    }

    // Product Generate exchange Prices
    public function regeneratePricesStep2($product)
    {
        $currencies = Currency::get();
        $mainCurrency = Currency::where('type', 1)->first();

        if ($currencies->count() > 0) {
            foreach ($currencies as $key => $currency) {
                if ($product->dependable_price == 1) {
                    $this->countByRateProductsPrice($product, $mainCurrency, $currency);
                }
            }
        }
    }

    // Product Generate exchange Prices
    private function countByRateProductsPrice($product, $mainCurrency, $currency)
    {
        if ($currency->type != 1) {
            $mainProductPrice = ProductPrice::where('product_id', $product->id)->where('currency_id', $mainCurrency->id)->first();
                if (!is_null($mainProductPrice)) {
                    $exchange = (int)$mainProductPrice->old_price * (int)$currency->rate * (int)$currency->correction_factor;
                    $exchangeB2B = (int)$mainProductPrice->b2b_old_price * (int)$currency->rate * (int)$currency->correction_factor;

                    ProductPrice::where('product_id', $product->id)
                                ->where('currency_id', $currency->id)
                                ->update([
                                    'old_price' => $exchange,
                                    'price' => (int)$exchange - ((int)$exchange * $product->discount / 100),
                                    'b2b_price' => $exchangeB2B,
                                    'b2b_old_price' => (int)$exchangeB2B - ((int)$exchangeB2B * $product->discount / 100)
                                ]);
                }
        }
    }

    // Subproduct Generate exchange Prices
    private function generateSubproductsPrices($productId)
    {
        $product = Product::findOrFail($productId);

        if (count($product->subproducts()->get()) > 0) {
            foreach ($product->subproducts()->get() as $key => $subproduct) {
                $this->regenerateSubproductsPrices($subproduct);
            }
        }
    }

    // Subproduct Generate exchange Prices
    public function regenerateSubproductsPrices($subproduct)
    {
        $currencies = Currency::get();
        $mainCurrency = Currency::where('type', 1)->first();

        if ($currencies->count() > 0) {
            foreach ($currencies as $key => $currency) {
                // if ($subproduct->dependable_price == 0) {
                    if ($currency->exchange_dependable == 1) {
                    $this->countByRateSubProductsPrice($subproduct, $mainCurrency, $currency);
                }
            }
        }
    }

    // Subproduct Generate exchange Prices
    private function countByRateSubProductsPrice($subproduct, $mainCurrency, $currency)
    {
        if ($currency->type != 1) {
            $mainProductPrice = SubproductPrice::where('subproduct_id', $subproduct->id)->where('currency_id', $mainCurrency->id)->first();

            if (!is_null($mainProductPrice)) {
                $exchange = (int)$mainProductPrice->old_price * (int)$currency->rate;

                SubproductPrice::where('subproduct_id', $subproduct->id)
                            ->where('currency_id', $currency->id)
                            ->update([
                                'old_price' => $exchange,
                                'price' => (int)$exchange - ((int)$exchange * (int)$subproduct->discount / 100),
                            ]);
            }
        }
    }

    public function setAllPrices()
    {
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
    }

    public function getMaterials(Request $request)
    {
        $productId = $request->get('product_id');

        // $productMaterialValue = ParameterValueProduct::where('parameter_id', '32')->where('product_id', $productId)->first();
        $needleCategoryChildren = ProductCategory::where('parent_id', '2')->pluck('id');


        // if (!is_null($productMaterialValue)) {
            // $value = $productMaterialValue->parameter_value_id;
            // $materialsId = ParameterValueProduct::where('parameter_id', '32')->where('parameter_value_id', $value)->pluck('product_id')->toArray();
            // $materialsId = ParameterValueProduct::where('parameter_id', '32')->where('parameter_value_id', $value)->pluck('product_id')->toArray();
            $data['materials'] = Product::with('translation')->where('category_id', 2)->where('id', '!=', $productId)->get();

            $data['checkedMaterials'] = ProductMaterial::where('product_id', $productId)->pluck('material_id')->toArray();

            return $data;
        // }
        // return $productMaterialValue;

    }

    public function addMaterials(Request $request)
    {
        $check = ProductMaterial::where('product_id', $request->get('product_id'))
                                ->where('material_id', $request->get('material_id'))
                                ->first();

        if (!is_null($check)) {
            $check->delete();
        }else{
            ProductMaterial::create([
                'product_id' => $request->get('product_id'),
                'material_id' => $request->get('material_id'),
            ]);
        }
    }

    public function changeComStatus(Request $request)
    {
        $product = Product::find($request->get('product_id'));

        $loungewear = $product->homewear ? 0 : 1;

        if (!is_null($product)) {
            $product->update([
                'homewear' => $loungewear,
            ]);
        }
    }

    public function changeMdStatus(Request $request)
    {
        $product = Product::find($request->get('product_id'));

        $jewelry = $product->bijoux ? 0 : 1;

        if (!is_null($product)) {
            $product->update([
                'bijoux' => $jewelry,
            ]);
        }
    }

    public function changeActiveStatus(Request $request)
    {
        $product = Product::find($request->get('product_id'));

        // dd($product->active);

        $active = $product->active ? 0 : 1;

        // dd($active);

        if (!is_null($product)) {
            $product->update([
                'active' => $active,
            ]);
        }
    }

    public function updateSubproducts(Request $request)
    {
        $data['product'] = Product::with(['subproducts.prices.currency'])
                           ->where('id', $request->get('product_id'))
                           ->first();

        $data['warehouses']         = Warehouse::where('active', 1)->get();
        $data['warehousesStocks']   = WarehousesStock::with(['warehouse'])
                                                        ->where('product_id', $request->get('product_id'))
                                                        ->where('subproduct_id', '!=', null)
                                                        ->get();

        $data['warehousesProductStocks'] = WarehousesStock::with(['warehouse'])
                                                        ->where('product_id', $request->get('product_id'))
                                                        ->where('subproduct_id', null)
                                                        ->get();

        return $data;
    }

    public function changeCategory(Request $request)
    {
        Product::where('id', $request->get('product_id'))->update([
            'category_id' => $request->get('category_id'),
        ]);
    }

    public function getDillersPrices(Request $request)
    {
        $prices = ProductDillerPrice::where('product_id', $request->get('productId'))->get();
        $currencies = Currency::where('active', 1)->get();
        $dillerGroups = DillerGroup::get();
        $retPrices = [];

        foreach ($dillerGroups as $key => $dillerGroup) {
            if ($prices->count() > 0) {
                foreach ($prices as $key => $price) {
                    if ($dillerGroup->id == $price->diller_group_id) {
                        $retPrices[$dillerGroup->id][$price->currency->abbr] = $price->old_price;
                    }
                }
            }else{
                foreach ($currencies as $key => $currency) {
                    $retPrices[$dillerGroup->id][$currency->abbr] =  0;
                }
            }
        }

        return $retPrices;
    }
}
