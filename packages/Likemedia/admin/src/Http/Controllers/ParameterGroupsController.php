<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParameterGroup;
use App\Models\ParameterValue;
use App\Models\ParameterValueTranslation;
use App\Models\ProductCategory;
use App\Models\ParameterCategory;
use App\Models\SubProductParameter;
use App\Models\Product;
use App\Models\SubProduct;
use App\Models\TranslationGroup;
use App\Models\Translation;


class ParameterGroupsController extends Controller
{
    public function index()
    {
        $parameterGroups = ParameterGroup::orderBy('id', 'asc')->get();
        $translationKeys = TranslationGroup::get();

        return view('admin::admin.parameterGroups.index', compact('parameterGroups', 'translationKeys'));
    }

    public function create(){}

    public function store(Request $request)
    {
        $toValidate['key'] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $translation = Translation::find($request->get('key'));

        if (!is_null($translation)) {
            $parameter = ParameterGroup::create([
                'translation_group_id' => $request->get('key'),
                'key' => $translation->key,
            ]);
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $translationKeys = TranslationGroup::get();

        $parameterGroup = ParameterGroup::findOrFail($id);

        return view('admin::admin.parameterGroups.edit', compact('parameterGroup', 'translationKeys'));
    }

    public function update(Request $request, $id)
    {
        $parameterGroup = ParameterGroup::findOrFail($id);

        $translation = Translation::find($request->get('key'));

        if (!is_null($translation)) {
            $parameterGroup->update([
                'translation_group_id' => $request->get('key'),
                'key' => $request->get('key_string'),
            ]);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $parameter = ParameterGroup::findOrFail($id);
        $parameter->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->back();
    }

}
