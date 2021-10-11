<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CropController extends Controller
{
    public function index()
    {
      $crop = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop'];

      return view('admin::admin.crop.index', compact('crop'));
    }

    public function update(Request $request)
    {
      $toValidate = [];

      foreach ($request->except('_token') as $data => $value) {
        $toValidate[$data] = "required|numeric";
      }

      $validator = $this->validate($request, $toValidate);

      $crop = json_decode(file_get_contents(storage_path('globalsettings.json')), true);

      $crop['crop']['product'][0]['bgfrom'] = $request->product_bgfrom;
      $crop['crop']['product'][0]['bgto'] = $request->product_bgto;
      $crop['crop']['product'][1]['mdfrom'] = $request->product_mdfrom;
      $crop['crop']['product'][1]['mdto'] = $request->product_mdto;
      $crop['crop']['product'][2]['smfrom'] = $request->product_smfrom;
      $crop['crop']['product'][2]['smto'] = $request->product_smto;
      $crop['crop']['gallery'][0]['bgfrom'] = $request->gallery_bgfrom;
      $crop['crop']['gallery'][0]['bgto'] = $request->gallery_bgto;
      $crop['crop']['gallery'][1]['mdfrom'] = $request->gallery_mdfrom;
      $crop['crop']['gallery'][1]['mdto'] = $request->gallery_mdto;
      $crop['crop']['gallery'][2]['smfrom'] = $request->gallery_smfrom;
      $crop['crop']['gallery'][2]['smto'] = $request->gallery_smto;

      $file_handle = fopen(storage_path('globalsettings.json'), 'w');
      fwrite($file_handle, json_encode($crop));
      fclose($file_handle);

      session()->flash('message', 'Sizes has been updated!');

      return redirect()->route('crop.index');
    }

}
