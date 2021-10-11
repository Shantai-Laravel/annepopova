<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Set;
use App\Models\SetGallery;
use App\Models\SetProducts;
use App\Models\Collection;
use App\Models\SetPrice;
use App\Models\Currency;


class SetsController extends Controller
{
    public function index()
    {
        $sets = Set::orderBy('position', 'asc')->get();

        return view('admin::admin.sets.index', compact('sets'));
    }

    public function create()
    {
        $collections = Collection::orderBy('position', 'asc')->get();
        return view('admin::admin.sets.create', compact('collections'));
    }

    public function store(Request $request)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255|unique:sets_translation,name';
        }

        $validator = $this->validate($request, $toValidate);

        foreach ($this->langs as $lang):
            $image[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $image[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/sets', $image[$lang->lang]);
            }
        endforeach;

        $set = new Set();
        $set->collection_id = request('collection_id');
        $set->alias = str_slug(request('title_ro'));
        $set->code = request('code');
        $set->price = request('price');
        $set->discount = request('discount');
        $set->position = 1;
        $set->material_id = request('material');
        $set->color_id = request('color');
        $set->room_id = request('room');
        $set->employment_id = request('employment');
        $set->save();

        $set->save();

        foreach ($this->langs as $lang):
            $set->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'image' => $image[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_description' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        $this->addPhotosVideos($request, $set);

        Session::flash('message', 'New item has been created!');

        return redirect('/back/sets/'.$set->id.'/edit?collection='.$set->colection_id);
    }

    public function show($id)
    {
        return redirect()->route('brands.index');
    }

    public function edit($id)
    {
        $set = Set::findOrFail($id);

        if ($set->prices()->count() == 0) {
            $this->generatePrices($set);
            return redirect('back/sets/'.$set->id.'/edit');
        }

        $collections = Collection::orderBy('position', 'asc')->get();

        return view('admin::admin.sets.edit', compact('set', 'collections'));
    }

    public function update(Request $request, $id)
    {
        $set = Set::findOrFail($id);

        $exchange = 0;
        $onHome = 0;
        $active = 0;
        $loungewear = 0;
        $jewelry    = 0;

        if ($request->get('loungewear') == 'on') { $loungewear = 1; }
        if ($request->get('jewelry') == 'on') { $jewelry = 1; }

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $video = $request->file('video');

        if ($video) {
            if ($set->video) {
                $file = file_exists(public_path().'/videos/sets/'.$set->video);
                if ($file) {
                    @unlink(public_path().'/videos/sets/'.$set->video);
                }
            }
            $videoName = uniqid().$video->getClientOriginalName();
            $path = public_path().'/videos/';
            $video->move($path, $videoName);
        }else{
            $videoName = $request->video_old;
        }

        if ($request->exchange == 'on') { $exchange = 1; }

        if ($request->on_home == 'on') { $onHome = 1; }

        if ($request->active == 'on') { $active = 1; }

        if ($request->com == 'on') { $com = 1; }
        if ($request->md == 'on') { $md = 1; }

        $img = $request->img_old;
        $img_mobile = $request->img_old_mobile;

        if (!empty($request->file('img'))) {
            $img = time() . '-' . $request->img->getClientOriginalName();
            $request->img->move('images/sets', $img);
        }

        if (!empty($request->file('img_mobile'))) {
            $img_mobile = time() . '-' . $request->img_mobile->getClientOriginalName();
            $request->img_mobile->move('images/sets', $img_mobile);
        }

        $set->collection_id = request('collection_id');
        $set->alias = str_slug(request('title_ro'));
        $set->code = request('code');
        $set->dependable_price = $exchange;
        $set->video = $videoName;
        $set->discount = request('discount');
        $set->on_home = $onHome;
        $set->active = $active;
        $set->material_id = request('material');
        $set->color_id = request('color');
        $set->room_id = request('room');
        $set->employment_id = request('employment');
        $set->homewear = $loungewear;
        $set->bijoux = $jewelry;
        $set->banner_desktop = $img;
        $set->banner_mobile = $img_mobile;

        $set->save();

        $set->translations()->delete();

        foreach ($this->langs as $lang):
            $set->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'subtitle' => request('subtitle_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_description' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;


        $this->addPhotosVideos($request, $set);
        $this->savePrices($request, $set);
        $this->setStocks();

        return redirect()->back();
    }

    public function addPhotosVideos($request, $set)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        if($files = $request->get('video')){
            $image = SetGallery::create([
                'set_id' =>  $set->id,
                'src' =>  $request->get('video'),
                'type' => 'video',
            ]);
        }

        if($files = $request->file('photos')){
            foreach($files as $key => $file){
                $uniqueId = uniqid();
                $name = $uniqueId.$file->getClientOriginalName();
                $image_resize = Image::make($file->getRealPath());
                $product_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['product'];

                $image_resize->save(public_path('images/sets/og/' .$name), 75);

                $image_resize->resize(960, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save('images/sets/md/' .$name, 85);

                $image_resize->resize(480, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save('images/sets/sm/' .$name, 85);

                $image = SetGallery::create([
                    'set_id' =>  $set->id,
                    'src' =>  $name,
                    'type' => 'photo',
                ]);
            }
        }
    }

    public function deleteGalleryItem($id)
    {
        $image = SetGallery::findOrFail($id);

        if (file_exists(public_path('images/sets/og/'.$image->src))) {
            unlink(public_path('images/sets/og/'.$image->src));
        }
        if (file_exists(public_path('images/sets/sm/'.$image->src))) {
            unlink(public_path('images/sets/sm/'.$image->src));
        }

        SetGallery::where('id', $id)->delete();

        return redirect()->back();
    }

    public function setMainGalleryItem($id)
    {
        $image = SetGallery::findOrFail($id);

        SetGallery::where('set_id', $image->set_id)->update([
            'main' => 0,
        ]);

        SetGallery::where('id', $image->id)->update([
            'main' => 1,
        ]);

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
                Set::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function getByCollection($collection_id)
    {
        $sets = Set::where('collection_id', $collection_id)->orderBy('position', 'asc')->get();
        $collection = Collection::findOrFail($collection_id);

        return view('admin::admin.sets.index', compact('sets', 'collection'));
    }

    public function destroy($id)
    {
        $set = Set::findOrFail($id);
        $images = SetGallery::where('set_id', $id)->get();

        if (file_exists('/images/sets' . $set->banner)) {
            unlink('/images/sets' . $set->banner);
        }

        foreach ($this->langs as $lang):
            if (file_exists('/images/sets' . $set->translation($lang->id)->first()->image)) {
                unlink('/images/sets' . $set->translation($lang->id)->first()->image);
            }
            $set->translation($lang->id)->delete();
        endforeach;

        $set->delete();

        SetProducts::where('set_id', $set->id)->delete();

        if (count($images) > 0) {
            foreach ($images as $key => $image) {
                if (file_exists(public_path('images/sets/og/'.$image->src))) {
                    unlink(public_path('images/sets/og/'.$image->src));
                }
                if (file_exists(public_path('images/sets/sm/'.$image->src))) {
                    unlink(public_path('images/sets/sm/'.$image->src));
                }

                SetGallery::where('id', $image->id)->delete();
            }
        }

        session()->flash('message', 'Item has been deleted!');

        return redirect()->back();
    }

    public function savePrices($request, $set)
    {
        $prices = $request->get('price');
        $set = Set::findOrFail($set->id);
        $mainCurrency = Currency::where('type', 1)->first();
        $mainPrice = SetPrice::where('currency_id', $mainCurrency->id)->where('set_id', $set->id)->first();

        // $dependablePrice = $set->dependable_price;
        $dependablePrice = 1;

        // if ($dependablePrice == 1) {
        //     foreach ($prices as $key => $price) {
        //         if ($mainPrice->id == $key) {
        //             SetPrice::where('id', $key)->update([
        //                 'old_price' => $price,
        //                 'price' => $price - ($price * $set->discount / 100),
        //             ]);
        //             $this->regeneratePrices($set);
        //         }
        //     }
        // }else{
        //     foreach ($prices as $key => $price) {
        //         SetPrice::where('id', $key)->update([
        //             'old_price' => $price,
        //             'price' => $price - ($price * $set->discount / 100),
        //         ]);
        //     }
        // }

        // if ($dependablePrice == 1) {
            foreach ($prices as $key => $price) {
                $setPrice = SetPrice::find($key);
                if ($setPrice->currency->exchange_dependable == 1) {
                    if ($mainPrice->id == $key) {
                        SetPrice::where('id', $key)->update([
                            'old_price' => $price,
                            'price' => $price - ($price * $set->discount / 100),
                        ]);
                        $this->regeneratePrices($set);
                    }
                }else{
                    SetPrice::where('id', $key)->update([
                                'old_price' => $price,
                                'price' => $price - ($price * $set->discount / 100),
                            ]);
                }

            }
        // }else{
        //     foreach ($prices as $key => $price) {
        //         SetPrice::where('id', $key)->update([
        //             'old_price' => $price,
        //             'price' => $price - ($price * $set->discount / 100),
        //         ]);
        //     }
        // }

    }

    public function regeneratePrices($set)
    {
        $currencies = Currency::get();
        $mainCurrency = Currency::where('type', 1)->first();

        if ($currencies->count() > 0) {
            foreach ($currencies as $key => $currency) {
                // if ($set->dependable_price == 1) {
                    $this->countByRateProductsPrice($set, $mainCurrency, $currency);
                // }
            }
        }
    }

    private function countByRateProductsPrice($set, $mainCurrency, $currency)
    {
        if ($currency->type != 1) {
            $mainSetPrice = SetPrice::where('set_id', $set->id)->where('currency_id', $mainCurrency->id)->first();

            if (!is_null($mainSetPrice)) {
                $exchange = $mainSetPrice->old_price * $currency->rate;

                SetPrice::where('set_id', $set->id)
                            ->where('currency_id', $currency->id)
                            ->update([
                                'old_price' => $exchange,
                                'price' => $exchange - ($exchange * $set->discount / 100),
                            ]);
            }
        }
    }

    private function setStocks()
    {
        $sets = Set::get();

        foreach ($sets as $key => $set) {
            $stock = false;
            if ($set->products->count() > 0) {
                foreach ($set->products as $key => $product) {
                    if ($product->subproducts->count() > 0) {
                        foreach ($product->subproducts as $key => $subproduct) {
                            if ($stock == false || $subproduct->stoc < $stock) {
                                $stock = $subproduct->stoc;
                            }
                        }
                    }else{
                        if ($stock == false || $product->stock < $stock) {
                            $stock = $product->stock;
                        }
                    }
                }
            }
            $set->update(['stock' => $stock]);
        }
    }

    public function generatePrices($set)
    {
        $currencies = Currency::orderBy('type', 'desc')->get();
        $mainCurrency = Currency::where('type', 1)->first();

        if ($currencies->count() > 0) {
            foreach ($currencies as $key => $currency) {
                $set->prices()->create([
                    'currency_id' => $currency->id,
                    'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                ]);
            }
        }
    }

}
