<?php

namespace Admin\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\AutometaScript;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Collection;

class AutoMetaScriptsController extends Controller
{
    public function index()
    {
        $scripts = AutometaScript::get();

        return view('admin::admin.autometaScripts.index', compact('scripts'));
    }

    public function show($id)
    {
        return redirect()->route('blogs.index');
    }

    public function create()
    {
        return view('admin::admin.autometaScripts.create');
    }

    public function store(Request $request)
    {
        $script = new AutometaScript();
        $script->name = $request->get('name');
        $script->type = $request->get('type');
        $script->save();

        foreach ($this->langs as $lang):
            $script->translation()->create([
                'lang_id' => $lang->id,
                'var1' => request('var1')[$lang->id],
                'var2' => request('var2')[$lang->id],
                'var3' => request('var3')[$lang->id],
                'var4' => request('var4')[$lang->id],
                'var5' => request('var5')[$lang->id],
                'var6' => request('var6')[$lang->id],
                'var7' => request('var7')[$lang->id],
                'var8' => request('var8')[$lang->id],
                'var9' => request('var9')[$lang->id],
                'var10' => request('var10')[$lang->id],
                'var11' => request('var11')[$lang->id],
                'var12' => request('var12')[$lang->id],
                'var13' => request('var13')[$lang->id],
                'var14' => request('var14')[$lang->id],
                'var15' => request('var15')[$lang->id],
                'description' => request('description')[$lang->id],
                'meta_title' => request('meta_title')[$lang->id],
                'meta_description' => request('meta_description')[$lang->id],
                'meta_keywords' => request('meta_keywords')[$lang->id],
            ]);
        endforeach;

        if ($script->type == 'product') {
            $products = Product::get();
            if ($request->get('all_cells')) {
                $this->setScriptsToProducts($products, $script, 'all');
            }else{
                $this->setScriptsToProducts($products, $script, 'only_empty');
            }
        }elseif ($script->type == 'category') {
            $categories = ProductCategory::get();
            if ($request->get('all_cells')) {
                $this->setScriptsToCategories($categories, $script, 'all');
            }else{
                $this->setScriptsToCategories($categories, $script, 'only_empty');
            }
        }elseif ($script->type == 'collection') {
            $collections = Collection::get();
            if ($request->get('all_cells')) {
                $this->setScriptsToCollections($collections, $script, 'all');
            }else{
                $this->setScriptsToCollections($collections, $script, 'only_empty');
            }
        }

        Session::flash('message', 'New item has been created!');

        return redirect()->route('autometa-scripts.index');
    }

    public function edit($id)
    {
        $script = AutometaScript::findOrFail($id);

        return view('admin::admin.autometaScripts.edit', compact('script'));
    }

    public function update(Request $request, $id)
    {
        $script = AutometaScript::findOrFail($id);
        $script->name = request('name');
        $script->type = request('type');
        $script->save();

        $script->translations()->delete();

        foreach ($this->langs as $lang):
            $script->translation()->create([
                'lang_id' => $lang->id,
                'var1' => request('var1')[$lang->id],
                'var2' => request('var2')[$lang->id],
                'var3' => request('var3')[$lang->id],
                'var4' => request('var4')[$lang->id],
                'var5' => request('var5')[$lang->id],
                'var6' => request('var6')[$lang->id],
                'var7' => request('var7')[$lang->id],
                'var8' => request('var8')[$lang->id],
                'var9' => request('var9')[$lang->id],
                'var10' => request('var10')[$lang->id],
                'var11' => request('var11')[$lang->id],
                'var12' => request('var12')[$lang->id],
                'var13' => request('var13')[$lang->id],
                'var14' => request('var14')[$lang->id],
                'var15' => request('var15')[$lang->id],
                'description' => request('description')[$lang->id],
                'meta_title' => request('meta_title')[$lang->id],
                'meta_description' => request('meta_description')[$lang->id],
                'meta_keywords' => request('meta_keywords')[$lang->id],
            ]);
        endforeach;

        if ($script->type == 'product') {
            $products = Product::get();
            if ($request->get('all_cells')) {
                $this->setScriptsToProducts($products, $script, 'all');
            }else{
                $this->setScriptsToProducts($products, $script, 'only_empty');
            }
        }elseif ($script->type == 'category') {

            $categories = ProductCategory::get();
            if ($request->get('all_cells')) {
                $this->setScriptsToCategories($categories, $script, 'all');
            }else{
                $this->setScriptsToCategories($categories, $script, 'only_empty');
            }
        }elseif ($script->type == 'collection') {
            $collections = Collection::get();
            if ($request->get('all_cells')) {
                $this->setScriptsToCollections($collections, $script, 'all');
            }else{
                $this->setScriptsToCollections($collections, $script, 'only_empty');
            }
        }

        return redirect()->back();
    }


    public function destroy($id)
    {
        $script = AutometaScript::findOrFail($id);

        $script->translations()->delete();
        $script->delete();

        session()->flash('message', 'Script has been deleted!');

        return redirect()->route('autometa-scripts.index');
    }


    // Product creating script
    public function setScriptsToProducts($products, $script, $mode)
    {
        foreach ($products as $key => $product) {
            $parsedScript = $this->parseScriptProduct($product, $script, $mode);
        }
    }

    public function parseScriptProduct($product, $script, $mode)
    {
        foreach ($this->langs as $key => $lang) {
            $data['description']        = '';
            $data['meta_title']         = '';
            $data['meta_description']   = '';
            $data['meta_keywords']      = '';
            $translation = $script->translationByLang($lang->id)->first();
            $productParams = $this->getProductParams($lang, $product);

            if (!is_null($product->translationByLang($lang->id)) && !is_null($product->category->translationByLang($lang->id))) {
                if ($translation->description) {
                    $data['description'] = str_replace('{var1}', $translation->var1, $translation->description);
                    $data['description'] = str_replace('{var2}', $translation->var2, $data['description']);
                    $data['description'] = str_replace('{var3}', $translation->var3, $data['description']);
                    $data['description'] = str_replace('{var4}', $translation->var4, $data['description']);
                    $data['description'] = str_replace('{var5}', $translation->var5, $data['description']);
                    $data['description'] = str_replace('{var6}', $translation->var6, $data['description']);
                    $data['description'] = str_replace('{var7}', $translation->var7, $data['description']);
                    $data['description'] = str_replace('{var8}', $translation->var8, $data['description']);
                    $data['description'] = str_replace('{var9}', $translation->var9, $data['description']);
                    $data['description'] = str_replace('{var10}', $translation->var10, $data['description']);
                    $data['description'] = str_replace('{var11}', $translation->var11, $data['description']);
                    $data['description'] = str_replace('{var12}', $translation->var12, $data['description']);
                    $data['description'] = str_replace('{var13}', $translation->var13, $data['description']);
                    $data['description'] = str_replace('{var14}', $translation->var14, $data['description']);
                    $data['description'] = str_replace('{var15}', $translation->var15, $data['description']);
                    $data['description'] = str_replace('{Prod_Name}', $product->translationByLang($lang->id)->name, $data['description']);
                    $data['description'] = str_replace('{Cat_Name}', $product->category->translationByLang($lang->id)->name, $data['description']);
                    $data['description'] = str_replace('{Params}', $productParams, $data['description']);
                    $data['description'] = str_replace('{Attributes}', $product->translationByLang($lang->id)->atributes, $data['description']);
                    $data['description'] = str_replace('{#}', ' ', $data['description']);

                    $data['meta_title'] = str_replace('{var1}', $translation->var1, $translation->meta_title);
                    $data['meta_title'] = str_replace('{var2}', $translation->var2, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var3}', $translation->var3, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var4}', $translation->var4, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var5}', $translation->var5, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var6}', $translation->var6, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var7}', $translation->var7, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var8}', $translation->var8, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var9}', $translation->var9, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var10}', $translation->var10, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var11}', $translation->var11, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var12}', $translation->var12, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var13}', $translation->var13, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var14}', $translation->var14, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var15}', $translation->var15, $data['meta_title']);
                    $data['meta_title'] = str_replace('{Prod_Name}', $product->translationByLang($lang->id)->name, $data['meta_title']);
                    $data['meta_title'] = str_replace('{Cat_Name}', $product->category->translationByLang($lang->id)->name, $data['meta_title']);
                    $data['meta_title'] = str_replace('{Params}', $productParams, $data['meta_title']);
                    $data['meta_title'] = str_replace('{Attributes}', $product->translationByLang($lang->id)->atributes, $data['meta_title']);
                    $data['meta_title'] = str_replace('{#}', ' ', $data['meta_title']);

                    $data['meta_description'] = str_replace('{var1}', $translation->var1, $translation->meta_description);
                    $data['meta_description'] = str_replace('{var2}', $translation->var2, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var3}', $translation->var3, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var4}', $translation->var4, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var5}', $translation->var5, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var6}', $translation->var6, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var7}', $translation->var7, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var8}', $translation->var8, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var9}', $translation->var9, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var10}', $translation->var10, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var11}', $translation->var11, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var12}', $translation->var12, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var13}', $translation->var13, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var14}', $translation->var14, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var15}', $translation->var15, $data['meta_description']);
                    $data['meta_description'] = str_replace('{Prod_Name}', $product->translationByLang($lang->id)->name, $data['meta_description']);
                    $data['meta_description'] = str_replace('{Cat_Name}', $product->category->translationByLang($lang->id)->name, $data['meta_description']);
                    $data['meta_description'] = str_replace('{Params}', $productParams, $data['meta_description']);
                    $data['meta_description'] = str_replace('{Attributes}', $product->translationByLang($lang->id)->atributes, $data['meta_description']);
                    $data['meta_description'] = str_replace('{#}', ' ', $data['meta_description']);

                    $data['meta_keywords'] = str_replace('{var1}', $translation->var1, $translation->meta_keywords);
                    $data['meta_keywords'] = str_replace('{var2}', $translation->var2, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var3}', $translation->var3, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var4}', $translation->var4, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var5}', $translation->var5, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var6}', $translation->var6, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var7}', $translation->var7, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var8}', $translation->var8, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var9}', $translation->var9, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var10}', $translation->var10, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var11}', $translation->var11, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var12}', $translation->var12, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var13}', $translation->var13, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var14}', $translation->var14, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var15}', $translation->var15, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{Prod_Name}', $product->translationByLang($lang->id)->name, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{Cat_Name}', $product->category->translationByLang($lang->id)->name, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{Params}', $productParams, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{Attributes}', $product->translationByLang($lang->id)->atributes, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{#}', ' ', $data['meta_keywords']);
                }

                if ($mode == 'only_empty') {
                    if (!$product->translationByLang($lang->id)->seo_title) {
                        $product->translationByLang($lang->id)->update([
                            'body'          => $data['description'],
                            'seo_title'    => $data['meta_title'],
                            'seo_description' => $data['meta_description'],
                            'seo_keywords' => $data['meta_keywords'],
                        ]);
                    }
                }else{
                    $product->translationByLang($lang->id)->update([
                        'body'          => $data['description'],
                        'seo_title'    => $data['meta_title'],
                        'seo_description' => $data['meta_description'],
                        'seo_keywords' => $data['meta_keywords'],
                    ]);
                }

            }
        }
    }

    public function getProductParams($lang, $product)
    {
        $ret = '';
        foreach ($product->propertyValues as $key => $propValue) {
            if ($propValue->value) {
                $ret .= ' '.$propValue->parameter->translationByLang($lang->id)->name.': '.$propValue->value->translationByLang($lang->id)->name.', ';
            }
        }
        return $ret;
    }

    // Categories creating scripts
    public function setScriptsToCategories($categories, $script, $mode)
    {
        foreach ($categories as $key => $category) {
            $parsedScript = $this->parseScriptCategory($category, $script, $mode);
        }
    }

    public function parseScriptCategory($category, $script, $mode)
    {
        foreach ($this->langs as $key => $lang) {
            $data['description']        = '';
            $data['meta_title']         = '';
            $data['meta_description']   = '';
            $data['meta_keywords']      = '';
            $translation = $script->translationByLang($lang->id)->first();

            if (!is_null($category->translationByLang($lang->id))) {
                if ($translation->description) {
                    $data['description'] = str_replace('{var1}', $translation->var1, $translation->description);
                    $data['description'] = str_replace('{var2}', $translation->var2, $data['description']);
                    $data['description'] = str_replace('{var3}', $translation->var3, $data['description']);
                    $data['description'] = str_replace('{var4}', $translation->var4, $data['description']);
                    $data['description'] = str_replace('{var5}', $translation->var5, $data['description']);
                    $data['description'] = str_replace('{var6}', $translation->var6, $data['description']);
                    $data['description'] = str_replace('{var7}', $translation->var7, $data['description']);
                    $data['description'] = str_replace('{var8}', $translation->var8, $data['description']);
                    $data['description'] = str_replace('{var9}', $translation->var9, $data['description']);
                    $data['description'] = str_replace('{var10}', $translation->var10, $data['description']);
                    $data['description'] = str_replace('{var11}', $translation->var11, $data['description']);
                    $data['description'] = str_replace('{var12}', $translation->var12, $data['description']);
                    $data['description'] = str_replace('{var13}', $translation->var13, $data['description']);
                    $data['description'] = str_replace('{var14}', $translation->var14, $data['description']);
                    $data['description'] = str_replace('{var15}', $translation->var15, $data['description']);
                    $data['description'] = str_replace('{Cat_Name}', $category->translationByLang($lang->id)->name, $data['description']);

                    $data['meta_title'] = str_replace('{var1}', $translation->var1, $translation->meta_title);
                    $data['meta_title'] = str_replace('{var2}', $translation->var2, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var3}', $translation->var3, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var4}', $translation->var4, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var5}', $translation->var5, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var6}', $translation->var6, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var7}', $translation->var7, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var8}', $translation->var8, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var9}', $translation->var9, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var10}', $translation->var10, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var11}', $translation->var11, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var12}', $translation->var12, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var13}', $translation->var13, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var14}', $translation->var14, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var15}', $translation->var15, $data['meta_title']);
                    $data['meta_title'] = str_replace('{Cat_Name}', $category->translationByLang($lang->id)->name, $data['meta_title']);

                    $data['meta_description'] = str_replace('{var1}', $translation->var1, $translation->meta_description);
                    $data['meta_description'] = str_replace('{var2}', $translation->var2, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var3}', $translation->var3, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var4}', $translation->var4, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var5}', $translation->var5, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var6}', $translation->var6, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var7}', $translation->var7, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var8}', $translation->var8, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var9}', $translation->var9, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var10}', $translation->var10, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var11}', $translation->var11, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var12}', $translation->var12, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var13}', $translation->var13, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var14}', $translation->var14, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var15}', $translation->var15, $data['meta_description']);
                    $data['meta_description'] = str_replace('{Cat_Name}', $category->translationByLang($lang->id)->name, $data['meta_description']);

                    $data['meta_keywords'] = str_replace('{var1}', $translation->var1, $translation->meta_keywords);
                    $data['meta_keywords'] = str_replace('{var2}', $translation->var2, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var3}', $translation->var3, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var4}', $translation->var4, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var5}', $translation->var5, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var6}', $translation->var6, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var7}', $translation->var7, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var8}', $translation->var8, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var9}', $translation->var9, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var10}', $translation->var10, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var11}', $translation->var11, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var12}', $translation->var12, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var13}', $translation->var13, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var14}', $translation->var14, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var15}', $translation->var15, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{Cat_Name}', $category->translationByLang($lang->id)->name, $data['meta_keywords']);
                }

                if ($mode == 'only_empty') {
                    if (!$category->translationByLang($lang->id)->seo_title) {
                        $category->translationByLang($lang->id)->update([
                            'description'  => $data['description'],
                            'seo_title'    => $data['meta_title'],
                            'seo_description' => $data['meta_description'],
                            'seo_keywords' => $data['meta_keywords'],
                        ]);
                    }
                }else{
                    $category->translationByLang($lang->id)->update([
                        'description'  => $data['description'],
                        'seo_title'    => $data['meta_title'],
                        'seo_description' => $data['meta_description'],
                        'seo_keywords' => $data['meta_keywords'],
                    ]);
                }

            }
        }
    }

    // Collections creating scripts
    public function setScriptsToCollections($collections, $script, $mode)
    {
        foreach ($collections as $key => $collection) {
            $parsedScript = $this->parseScriptCollection($collection, $script, $mode);
        }
    }

    public function parseScriptCollection($collection, $script, $mode)
    {
        foreach ($this->langs as $key => $lang) {
            $data['description']        = '';
            $data['meta_title']         = '';
            $data['meta_description']   = '';
            $data['meta_keywords']      = '';
            $translation = $script->translationByLang($lang->id)->first();

            if (!is_null($collection->translationByLang($lang->id))) {
                if ($translation->description) {
                    $data['description'] = str_replace('{var1}', $translation->var1, $translation->description);
                    $data['description'] = str_replace('{var2}', $translation->var2, $data['description']);
                    $data['description'] = str_replace('{var3}', $translation->var3, $data['description']);
                    $data['description'] = str_replace('{var4}', $translation->var4, $data['description']);
                    $data['description'] = str_replace('{var5}', $translation->var5, $data['description']);
                    $data['description'] = str_replace('{var6}', $translation->var6, $data['description']);
                    $data['description'] = str_replace('{var7}', $translation->var7, $data['description']);
                    $data['description'] = str_replace('{var8}', $translation->var8, $data['description']);
                    $data['description'] = str_replace('{var9}', $translation->var9, $data['description']);
                    $data['description'] = str_replace('{var10}', $translation->var10, $data['description']);
                    $data['description'] = str_replace('{var11}', $translation->var11, $data['description']);
                    $data['description'] = str_replace('{var12}', $translation->var12, $data['description']);
                    $data['description'] = str_replace('{var13}', $translation->var13, $data['description']);
                    $data['description'] = str_replace('{var14}', $translation->var14, $data['description']);
                    $data['description'] = str_replace('{var15}', $translation->var15, $data['description']);
                    $data['description'] = str_replace('{Col_Name}', $collection->translationByLang($lang->id)->name, $data['description']);

                    $data['meta_title'] = str_replace('{var1}', $translation->var1, $translation->meta_title);
                    $data['meta_title'] = str_replace('{var2}', $translation->var2, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var3}', $translation->var3, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var4}', $translation->var4, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var5}', $translation->var5, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var6}', $translation->var6, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var7}', $translation->var7, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var8}', $translation->var8, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var9}', $translation->var9, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var10}', $translation->var10, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var11}', $translation->var11, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var12}', $translation->var12, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var13}', $translation->var13, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var14}', $translation->var14, $data['meta_title']);
                    $data['meta_title'] = str_replace('{var15}', $translation->var15, $data['meta_title']);
                    $data['meta_title'] = str_replace('{Col_Name}', $collection->translationByLang($lang->id)->name, $data['meta_title']);

                    $data['meta_description'] = str_replace('{var1}', $translation->var1, $translation->meta_description);
                    $data['meta_description'] = str_replace('{var2}', $translation->var2, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var3}', $translation->var3, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var4}', $translation->var4, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var5}', $translation->var5, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var6}', $translation->var6, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var7}', $translation->var7, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var8}', $translation->var8, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var9}', $translation->var9, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var10}', $translation->var10, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var11}', $translation->var11, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var12}', $translation->var12, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var13}', $translation->var13, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var14}', $translation->var14, $data['meta_description']);
                    $data['meta_description'] = str_replace('{var15}', $translation->var15, $data['meta_description']);
                    $data['meta_description'] = str_replace('{Col_Name}', $collection->translationByLang($lang->id)->name, $data['meta_description']);

                    $data['meta_keywords'] = str_replace('{var1}', $translation->var1, $translation->meta_keywords);
                    $data['meta_keywords'] = str_replace('{var2}', $translation->var2, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var3}', $translation->var3, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var4}', $translation->var4, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var5}', $translation->var5, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var6}', $translation->var6, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var7}', $translation->var7, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var8}', $translation->var8, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var9}', $translation->var9, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var10}', $translation->var10, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var11}', $translation->var11, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var12}', $translation->var12, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var13}', $translation->var13, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var14}', $translation->var14, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{var15}', $translation->var15, $data['meta_keywords']);
                    $data['meta_keywords'] = str_replace('{Col_Name}', $collection->translationByLang($lang->id)->name, $data['meta_keywords']);
                }

                if ($mode == 'only_empty') {
                    if (!$collection->translationByLang($lang->id)->seo_title) {
                        $collection->translationByLang($lang->id)->update([
                            'body' => $data['description'],
                            'seo_title' => $data['meta_title'],
                            'seo_description' => $data['meta_description'],
                            'seo_keywords' => $data['meta_keywords'],
                        ]);
                    }
                }else{
                    $collection->translationByLang($lang->id)->update([
                        'body' => $data['description'],
                        'seo_title' => $data['meta_title'],
                        'seo_description' => $data['meta_description'],
                        'seo_keywords' => $data['meta_keywords'],
                    ]);
                }

            }
        }
    }
}
