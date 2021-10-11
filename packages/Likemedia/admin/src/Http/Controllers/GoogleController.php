<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Admin\Http\Controllers\AutoUploadController;
use Admin\Http\Controllers\CurrenciesController;
use Admin\Http\Controllers\AutoMetaScriptsController;
use Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Warehouse;
use App\Models\WarehousesStock;
use App\Models\Parameter;
use App\Models\ParameterValue;
use App\Models\ParameterValueProduct;
use App\Models\Promotion;
use App\Models\ProductPrice;
use App\Models\Currency;
use App\Models\SubProduct;
use App\Models\Country;
use App\Models\Lang;
use App\Models\ProductImage;
use App\Models\ProductImageTranslation;
use App\Models\AutometaScript;
use App\Models\TranslationGroup;
use App\Models\Translation;
use App\Models\TranslationLine;
use GuzzleHttp\Client;
use Revolution\Google\Sheets\Facades\Sheets;
use Edujugon\GoogleAds\GoogleAds;

use MOIREI\GoogleMerchantApi\Facades\ProductApi;
use MOIREI\GoogleMerchantApi\Facades\OrderApi;

class GoogleController extends Controller
{
    public $issetProducts;

    public function googleAdsMain()
    {
        $ads = new GoogleAds();

        $ads->env('test')
            ->oAuth([
                'clientId'      => '867-770-3891',
                'clientSecret'  => 'VhUXtjChcGC3Ogz1xQjWXf_3',
                'refreshToken'  => '4/1gG4FYtpuOh5v5XCAk4G-RdnwF3SWBnECWfU9B75-2zEwQ1f814Le-s'
            ])
            ->session([
                'developerToken'    => 'HGnirrdw3jWZLYhC48Tl5g',
                'clientCustomerId'  => '123545481940-4mdaf48bm17k5bteu5agvn9hu93rikkj.apps.googleusercontent.com'
            ]);

        dd($ads);

        $ads->service(\AdGroupService::class);
        dd($ads);

        // $ads->oAuth([
        //     'clientId' => '105506236289725069958',
        //     'clientSecret' => 'test',
        //     'refreshToken' => 'TEST'
        //
        // ]);
    }

    public function googleApiContent()
    {
        $countries = Country::where('active', 1)->get();

        return view('admin::admin.google.apiContent.index', compact('countries'));
    }

    public function insertNewContent(Request $request)
    {
        $data = 'Content';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();

        $siteTypes = ['bijoux', 'homewear'];
        $lang = Lang::where('lang', $request->get('lang'))->first();

        foreach ($siteTypes as $key => $siteType) {

            $products = Product::where($siteType, 1)->inRandomOrder()->get();

            $details['products'] = $products;
            $details['siteType'] = $siteType;
            $request['request'] = $request;

            ProductApi::get()->then(function($data) use ($details){
                $issetProducts = [];
                if (array_key_exists('resources', $data)) {
                    foreach ($data['resources'] as $key => $item) {
                        $issetProducts[] = $item['offerId'];
                    }

                    foreach ($details['products'] as $key => $product) {
                        if (!in_array($issetProducts, $product->code)) {
                            $translation = $product->translationByLang($lang->id);
                            $attributes = [
                                'id'        => $product->code,
                                'title'     => $translation->name,
                                'description' => $translation->atributes ?? 'lorem Ipsum',
                                'condition' => 'new',
                                'availability' => 'in stock',
                                'image_link' => 'https://annepopova.com/images/products/fbq/'.$product->FBImage->src,
                                'gtin'      => $product->ean_code,
                                'mpn'       => $product->ean_code,
                                'google_product_category' => $product->category->translationByLang($lang->id)->name,
                                'product_type' => $product->category->translationByLang($lang->id)->name,
                                'Gender'    => 'female',
                                'Color'     => 'Brick-red',
                                'price'     => $product->mainPrice->price,
                                'link'      => 'https://annepopova.com/'. $request->get('lang') .'/'. $details['siteType'] .'catalog/'.$product->category->alias.'/'.$product->alias,
                                'targetCountry' => $request->get('country'),
                                'contentLanguage' => $request->get('lang')
                            ];

                            $p = ProductApi::insert(function($product) use($attributes){
                                $product->with($attributes)
                                        ->title($attributes['title'])
                                        ->description($attributes['description'])
                                        ->targetCountry($attributes['targetCountry'])
                                        ->contentLanguage($attributes['contentLanguage'])
                                        ->link($attributes['link'])
                                    	->image($attributes['image_link'])
                                    	->price($attributes['price'], 'EUR')
                                        ->gtin($attributes['gtin'])
                                        ->mpn($attributes['mpn']);
                            })->then(function($data){
                                echo '<small>Inserted '. $data['offerId']. '</small><br>';
                            })->otherwise(function(){
                                echo 'Insert failed';
                            })->catch(function($e){
                                dump($e);
                            });
                        }
                    }
                    dd($issetProducts);
                }
            });
        }
    }

    // Post insert content
    public function insertContent(Request $request)
    {
        $data = 'Content';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();

        $siteTypes = ['bijoux', 'homewear'];
        $lang = Lang::where('lang', $request->get('lang'))->first();

        foreach ($siteTypes as $key => $siteType) {

            $products = Product::where($siteType, 1)->inRandomOrder()->get();
                foreach ($products as $key => $product) {
                    $translation = $product->translationByLang($lang->id);
                    $attributes = [
                        'id'        => $product->code,
                        'title'     => $translation->name,
                        'description' => $translation->atributes ?? 'lorem Ipsum',
                        'condition' => 'new',
                        'availability' => 'in stock',
                        'image_link' => 'https://annepopova.com/images/products/og/'.$product->mainImage->src,
                        'gtin'      => $product->ean_code,
                        'mpn'       => $product->ean_code,
                        'google_product_category' => $product->category->translationByLang($lang->id)->name,
                        'product_type' => $product->category->translationByLang($lang->id)->name,
                        'Gender'    => 'female',
                        'Color'     => 'Brick-red',
                        'price'     => $product->mainPrice->price,
                        'link'      => 'https://annepopova.com/'. $request->get('lang') .'/'. $siteType .'catalog/'.$product->category->alias.'/'.$product->alias,
                        'targetCountry' => $request->get('country'),
                        'contentLanguage' => $request->get('lang')
                    ];

                    $p = ProductApi::insert(function($product) use($attributes){
                        $product->with($attributes)
                                ->title($attributes['title'])
                                ->description($attributes['description'])
                                ->targetCountry($attributes['targetCountry'])
                                ->contentLanguage($attributes['contentLanguage'])
                                ->link($attributes['link'])
                            	->image($attributes['image_link'])
                            	->price($attributes['price'], 'EUR')
                                ->gtin($attributes['gtin'])
                                ->mpn($attributes['mpn']);
                    })->then(function($data){
                        echo '<small>Inserted '. $data['offerId']. '</small><br>';
                    })->otherwise(function(){
                        echo 'Insert failed';
                    })->catch(function($e){
                        dump($e);
                    });

                }
        }

    }

    public function googleMerchantApi()
    {
        $product = Product::where('bijoux', 1)->inRandomOrder()->find(41);

        $translation = $product->translationByLang(38);

        $attributes = [
            'id' => $product->code,
            'title' => $translation->name,
            'description' => $translation->atributes,
            'condition' => 'new',
            'availability' => 'in stock',
            'imagelink' => 'https://annepopova.com/images/products/og/'.$product->mainImage->src,
            'gtin' => $product->ean_code,
            'mpn' => $product->ean_code,
            'google_product_category' => $product->category->translationByLang(38)->name,
            'product_type' => $product->category->translationByLang(38)->name,
            'Gender' => 'female',
            'Color' => 'Brick-red',
            'price' => $product->mainPrice->price,
            'link' => 'https://annepopova.com/ro'. $details['siteType'] .'catalog/'.$product->alias,
        ];

        $p = ProductApi::insert(function($product) use($attributes){
            $product->with($attributes)
                	->link($attributes['link'])
                	->price($attributes['price'], 'EUR');
        })->then(function($data){
            dd($data);
            echo 'Product inserted';
        })->otherwise(function(){
            echo 'Insert failed';
        })->catch(function($e){
            dump($e);
        });

        dd($p);
    }

    public function index()
    {
        return view('admin::admin.google.index');
    }

    public function getCategoriesId()
    {
        $categories = ProductCategory::orderBy('position', 'asc')->get();
        return view('admin::admin.google.categoriesIdList', compact('categories'));
    }

    public function getTransData()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);
        $translationGroups = TranslationGroup::get();
        return view('admin::admin.google.transData', compact('translationGroups'));
    }

    public function setSiteType($siteType)
    {
        $data['homewear'] = 0;
        $data['bijoux'] = 0;

        if ($siteType == 'homewear') { $data['homewear'] = 1; }
        if ($siteType == 'bijoux') { $data['bijoux'] = 1; }

        return $data;
    }

    public function uploadProducts()
    {
        $data = 'Products';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(config('sheets.post_sheet_id'))
                       ->all();

        $sheets = $this->parseSheet($sheets);
        $productsID = [];

        if (!empty($sheets)) {
            foreach ($sheets as $key => $item) {

                $siteType = $this->setSiteType($item['Type']);
                $checkProduct = Product::where('alias', $item['alias'])->where('code', $item['code_prod'])->first();

                if (is_null($checkProduct)) {
                    $product = Product::create([
                        'category_id'   => $item['category_id'],
                        'alias'         => $item['alias'],
                        'position'      => $item['position'],
                        'code'          => $item['code_prod'],
                        'homewear'      => $siteType['homewear'],
                        'bijoux'        => $siteType['bijoux'],
                        'active'        => $item['Active']
                    ]);

                    foreach ($this->langs as $key => $oneLang) {
                        $product->translation()->create([
                            'lang_id'   => $oneLang->id,
                            'name'      => $item['prodName_'.$oneLang->lang],
                            'atributes' => $item['Attributes_'.$oneLang->lang],
                        ]);
                    }

                    $productsID[] = $product->id;
                }else{
                    $checkProduct->update([
                        'category_id'   => $item['category_id'],
                        'alias'         => $item['alias'],
                        'position'      => $item['position'],
                        'code'          => $item['code_prod'],
                        'homewear'      => $siteType['homewear'],
                        'bijoux'        => $siteType['bijoux'],
                        'active'        => $item['Active']
                    ]);

                    $checkProduct->translations()->delete();

                    foreach ($this->langs as $key => $oneLang) {
                        $checkProduct->translation()->create([
                            'lang_id'   => $oneLang->id,
                            'name'      => $item['prodName_'.$oneLang->lang],
                            'atributes' => $item['Attributes_'.$oneLang->lang],
                        ]);
                    }
                }
            }
        }

        $products = Product::whereIn('id', $productsID)->get();

        $script = AutometaScript::find(1);
        if (!is_null($script)) {
            $productAll = Product::get();
            $autometa = new AutoMetaScriptsController();
            $autometa->setScriptsToProducts($productAll, $script, 'only_empty');
        }

        $autoupload = new AutoUploadController();

        foreach ($products as $key => $product) {
            // generate subproducts
            $autoupload->generateSubprodusesForProduct($product);

            // generate prices
            $autoupload->generatePrices($product);

            $warehouses = Warehouse::get();
            // generate stocks
            if ($product->subproducts) {
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
        }

        $admin = new AdminController();
        $admin->checkProductsStocks();
    }

    public function uploadParameters()
    {
        $data = 'Parameters';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(1312499570)
                       ->all();

        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $items) {
            foreach ($items as $key => $item) {
                $parameter = Parameter::where('key', $key)->first();
                if (!is_null($parameter)) {
                    if ($parameter->type !== 'text') {

                    $parameterProductValue = ParameterValueProduct::where('product_id', $items['Prod_ID'])->where('parameter_id', $parameter->id)->first();

                    if (is_null($parameterProductValue)) {
                         ParameterValueProduct::create([
                             'product_id' => $items['Prod_ID'],
                             'parameter_id' => $parameter->id,
                             'parameter_value_id' => $item,
                         ]);
                    }else{
                        $parameterProductValue->update([
                            'parameter_value_id' => $item,
                        ]);
                    }
                }else{
                    $parameterProductValue = ParameterValueProduct::where('product_id', $items['Prod_ID'])->where('parameter_id', $parameter->id)->first();

                    if (is_null($parameterProductValue)) {
                         $param = ParameterValueProduct::create([
                             'product_id' => $items['Prod_ID'],
                             'parameter_id' => $parameter->id,
                             'parameter_value_id' => 0,
                         ]);

                         foreach ($this->langs as $key => $lang) {
                             $param->translations()->create([
                                 'value' => $item,
                                 'lang_id' => $lang->id
                             ]);
                         }
                    }else{
                        $parameterProductValue->update([
                            'parameter_value_id' => 0,
                        ]);
                        $parameterProductValue->translations()->delete();
                        foreach ($this->langs as $key => $lang) {
                            $parameterProductValue->translations()->create([
                                'value' => $item,
                                'lang_id' => $lang->id
                            ]);
                        }
                    }
                }
            }

                }
            }
        // }
    }

    public function uploadPrices()
    {
        $data = 'Prices';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(1448833316)
                       ->all();

        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $items) {
            $product = Product::where('id', $items['Prod_ID'])->first();

            if (!is_null($product)) {
                $product->update([
                    'discount' => $items['Discount'],
                    'promotion_id' => $items['Promo'],
                ]);
                $this->setProductPrices($product, $items);
            }
        }
    }

    public function setProductPrices($product, $items)
    {
        $currencies = Currency::get();
        $mainCurrency = Currency::where('type', 1)->first();
        $mainPrice = ProductPrice::where('currency_id', $mainCurrency->id)->where('product_id', $product->id)->first();

        foreach ($currencies as $key => $currency) {
            $prodPrice = ProductPrice::where('product_id', $product->id)->where('currency_id', $currency->id)->first();
            if ($currency->abbr == 'EUR') {
                $prodPrice->update([
                        'old_price' => $items["Price Retail (Eur)"],
                        'price' => (int)$items["Price Retail (Eur)"] - ((int)$items["Price Retail (Eur)"] * $product->discount / 100),
                        'b2b_price' => $items["Price B2B (Eur)"],
                        'b2b_old_price' => $items["Price B2B (Eur)"],
                ]);
            }elseif ($currency->abbr == 'MDL') {
                $prodPrice->update([
                            'old_price' => $items["Price Retail (MDL)"],
                            'price' => (int)$items["Price Retail (MDL)"] - ((int)$items["Price Retail (MDL)"] * $product->discount / 100),
                            'b2b_price' => 0,
                            'b2b_old_price' => 0,
                        ]);
            }
            $currencies = new CurrenciesController();
            $autoupload = new AutoUploadController();

            $currencies->countByRateProductsPrice($product, $mainCurrency, $currency);
            $autoupload->generateDillerPrices($product->id);
        }
    }

    public function uploadStocks()
    {
        $data = 'Stocks';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(1395080368)
                       ->all();

        $sheets = $this->parseSheet($sheets);
        $warehouses = Warehouse::get();

        foreach ($sheets as $key => $item) {
            if ($item['Product ID'] !== '---') {
                Product::where('id', $item['Product ID'])->update([
                    'ean_code' => $item['Ean_Code'],
                ]);
                WarehousesStock::where('product_id', $item['Product ID'])->where('subproduct_id', 'null')->where('warehouse_id', 1)->update([
                    'stock' => $item['Stock_Frisbo'],
                ]);
                WarehousesStock::where('product_id', $item['Product ID'])->where('subproduct_id', 'null')->where('warehouse_id', 2)->update([
                    'stock' => $item['Stock_Swagger'],
                ]);
            }else{
                SubProduct::where('id', $item['Subroduct ID'])->update([
                    'ean_code' => $item['Ean_Code'],
                ]);
                WarehousesStock::where('subproduct_id', $item['Subroduct ID'])->where('warehouse_id', 1)->update([
                    'stock' => $item['Stock_Frisbo'],
                ]);
                WarehousesStock::where('subproduct_id', $item['Subroduct ID'])->where('warehouse_id', 2)->update([
                    'stock' => $item['Stock_Swagger'],
                ]);
            }
        }
    }

    public function uploadTranslations()
    {
        $data = 'Translations';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();


        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(953820667)
                       ->all();

        $sheets = $this->parseSheetWithLangs($sheets);

        foreach ($sheets as $key => $sheet) {
            $checkTransGroup = TranslationGroup::where('key', $sheet['group key'])->first();

            if (!is_null($checkTransGroup)) {
                $checkTrans = Translation::where('key', $sheet['trans'])->first();
                if (!is_null($checkTrans)) {
                    foreach ($this->langs as $key => $lang) {
                        TranslationLine::where('translation_id', $checkTrans->id)
                                        ->where('lang_id', $lang->id)
                                        ->update([ 'line' => $sheet[$lang->id], ]);
                    }
                }else{
                    $trans = Translation::create([
                        'group_id' => $checkTransGroup->id,
                        'key' => $sheet['trans'],
                    ]);

                    foreach ($this->langs as $key => $lang) {
                        TranslationLine::create([
                            'translation_id' => $trans->id,
                            'lang_id' => $lang->id,
                            'line' => $sheet[$lang->id],
                        ]);
                    }
                }
            }
        }
    }


    public function uploadImages()
    {
        $data = 'Images';
        $view = view('admin::admin.google.progressBar', compact('data'));
        echo $view->render();

        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                        // ->sheetList();
                       ->sheetById(903481932)
                       ->all();
        $sheets = $this->parseSheet($sheets);

        foreach ($sheets as $key => $sheet) {
            $checkImage = ProductImage::where('href', $sheet['Site-im-1'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-1']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'], 'src' => $sheet['Site-im-1'], 'href' => $sheet['Site-im-1'], 'main' => 1]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-2'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-2']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-2'], 'href' => $sheet['Site-im-2'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-3'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-3']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-3'], 'href' => $sheet['Site-im-3'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-4'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-4']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-4'], 'href' => $sheet['Site-im-4'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImage = ProductImage::where('href', $sheet['Site-im-5'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFile = file_exists('images/products/og/'.$sheet['Site-im-5']);

            if (is_null($checkImage) && $checkFile) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['Site-im-5'], 'href' => $sheet['Site-im-5'],'main' => 0]);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-1'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-1']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-1'], 'href' => $sheet['FB-im-1'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-2'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-2']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-2'], 'href' => $sheet['FB-im-2'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-3'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-3']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-3'], 'href' => $sheet['FB-im-3'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-4'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-4']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-4'], 'href' => $sheet['FB-im-4'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-5'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-5']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-5'], 'href' => $sheet['FB-im-5'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-6'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-6']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-6'], 'href' => $sheet['FB-im-6'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-7'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-7']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-7'], 'href' => $sheet['FB-im-7'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-8'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-8']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-8'], 'href' => $sheet['FB-im-8'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-9'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-9']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-9'], 'href' => $sheet['FB-im-9'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            $checkImageFB = ProductImage::where('href', $sheet['FB-im-10'])->where('product_id', $sheet['Prod-ID'])->first();
            $checkFileFB = file_exists('images/products/fbq/'.$sheet['FB-im-10']);

            if (is_null($checkImageFB) &&  $checkFileFB) {
                $image = ProductImage::create(['product_id' => $sheet['Prod-ID'],'src' => $sheet['FB-im-10'], 'href' => $sheet['FB-im-10'], 'type' => 'fb']);
                $this->setTranslation($image, $sheet['Prod-ID']);
            }

            Product::where('id', $sheet['Prod-ID'])->update(['video' => $sheet['Video']]);
        }
    }

    public function setTranslation($image, $productId)
    {
        foreach ($this->langs as $lang){
            ProductImageTranslation::create( [
                'product_image_id' => $image->id,
                'lang_id' =>  $lang->id,
            ]);
        }
    }

    public function getParametersId()
    {
        $products = Product::get();
        $parameters = Parameter::orderBy('type', 'asc')->get();
        $promotions = Promotion::get();

        return view('admin::admin.google.parametersIdList', compact('products', 'parameters', 'parameterValues', 'promotions'));
    }

    public function getSubproductsId()
    {
        $products = Product::get();

        return view('admin::admin.google.subproductsIdList', compact('products'));
    }

    public function parseSheet($sheets)
    {
        $keys = $sheets[0];
        $arr = [];

        foreach ($sheets as $key => $sheet) {
            if ($key !== 0) {
                for ($i=0; $i < count($keys); $i++) {
                    if (array_key_exists($i, $sheet)) {
                        $arr[$key][$keys[$i]] = $sheet[$i];
                    }else{
                        $arr[$key][$keys[$i]] = 0;
                    }
                }
            }
        }
        return $arr;
    }

    public function parseSheetWithLangs($sheets)
    {
        $keys = $sheets[0];
        $arr = [];

        foreach ($sheets as $key => $sheet) {
            if ($key !== 0) {
                for ($i=0; $i < count($keys); $i++) {
                    $langKey = $this->getLangId($keys[$i]);
                    if ($langKey > 0) {
                        $keys[$i] = $langKey;
                    }
                    if (array_key_exists($i, $sheet)) {
                        $arr[$key][$keys[$i]] = $sheet[$i];
                    }else{
                        $arr[$key][$keys[$i]] = 0;
                    }
                }
            }
        }
        return $arr;
    }

    public function getLangId($langAbbr)
    {
        $ret = 0;
        foreach ($this->langs as $key => $lang) {
            if ($langAbbr == $lang->lang) {
                $ret = $lang->id;
            }
        }

        return $ret;
    }


    public function getProducts()
    {
        $sheets = Sheets::spreadsheet(config('sheets.post_spreadsheet_id'))
                       ->sheetById(config('sheets.post_sheet_id'))
                       ->all();

        return view('admin::admin.google.progressBar');
    }
}
