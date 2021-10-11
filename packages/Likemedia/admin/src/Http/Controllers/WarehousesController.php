<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;

class WarehousesController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::orderBy('default', 'desc')->get();

        return view('admin::admin.warehouses.index', compact('warehouses'));
    }

    public function show($id)
    {
        return redirect()->route('warehouses.index');
    }

    public function create()
    {
        return redirect()->route('warehouses.index');
    }

    public function store(Request $request)
    {
        $toValidate['name'] = 'required|max:255';
        $this->validate($request, $toValidate);

        $active = request('active') == 'on' ? '1' : 0;

        $warehouse = new Warehouse();
        $warehouse->name          = request('name');
        $warehouse->active        = $active;

        $warehouse->save();

        $this->default($request, $warehouse);

        Session::flash('message', 'New item has been created!');

        return redirect()->route('warehouses.index');
    }

    public function default($request, $warehouse)
    {
        $default = request('default') == 'on' ? '1' : 0;

        if ($default == 1) {
            Warehouse::where('default', 1)->update([
                'default' => 0,
            ]);
            $warehouse->update([
                'default' => 1,
            ]);
        }
    }

    public function setDefault($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        Warehouse::where('default', 1)->where('id', '!=', $id)->update([
            'default' => 0,
        ]);

        $warehouse->update([
            'default' => 1,
        ]);

        return redirect()->back();
    }

    public function setActive($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        if ($warehouse->active == 1) {
            $warehouse->update([
                'active' => 0,
            ]);
        }else{
            $warehouse->update([
                'active' => 1,
            ]);
        }

        return redirect()->back();
    }


    public function edit($id)
    {
        return redirect()->route('warehouses.index');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('warehouses.index');
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $warehouse->delete();

        session()->flash('message', 'Item has been deleted!');
        return redirect()->route('warehouses.index');
    }

}
