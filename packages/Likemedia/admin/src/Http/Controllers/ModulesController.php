<?php

namespace Admin\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\Lang;
use App\Models\Module;


class ModulesController extends Controller
{
    public function index()
    {
        $modules = Module::orderBy('position', 'asc')->where('parent_id', 0)->get();

        return view('admin::admin.modules.index', compact('modules'));
    }

    public function changePosition()
    {
        $neworder = Input::get('neworder');
        $i = 1;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {
            $id = str_replace("tablelistsorter[]=", "", $v);
            if (!empty($id)) {
                Module::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function create()
    {
        $allModules = Module::where('parent_id', 0)->get();

        return view('admin::admin.modules.create', compact('allModules'));
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);
        $allModules = Module::where('parent_id', 0)->get();

        return view('admin::admin.modules.edit', compact('module', 'allModules'));
    }

    public function store(Request $request)
    {
        $toValidate = [];
        $toValidate['name'] = 'required';
        $toValidate['src'] = 'required';
        $toValidate['table_name'] = 'required';
        $toValidate['parent_id'] = 'required';

        $validator = $this->validate($request, $toValidate);

        $module = new Module();
        $module->name = $request->name;
        $module->description = $request->description;
        $module->src = $request->src;
        $module->position = 1;
        $module->table_name = $request->table_name;
        $module->icon = $request->icon;
        $module->parent_id = $request->parent_id;
        $module->save();

        Session::flash('message', 'New item has been created!');

        return redirect()->route('modules.index')->withInput();
    }

    public function update(Request $request, $id)
    {
        $toValidate = [];
        $toValidate['name'] = 'required';
        $toValidate['src'] = 'required';
        $toValidate['table_name'] = 'required';
        $toValidate['parent_id'] = 'required';

        $validator = $this->validate($request, $toValidate);

        $module = Module::findOrFail($id);
        $module->name = $request->name;
        $module->description = $request->description;
        $module->src = $request->src;
        $module->table_name = $request->table_name;
        $module->icon = $request->icon;
        $module->parent_id = $request->parent_id;
        $module->save();

        Session::flash('message', 'New item has been created!');

        return redirect()->route('modules.index');
    }


    public function destroy($id)
    {
        Module::findOrFail($id)->delete();

        Session::flash('message', 'Item was successful deleted! ');

        return redirect()->route('modules.index');
    }

}
