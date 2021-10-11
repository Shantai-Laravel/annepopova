<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Payment;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payment::get();

        return view('admin::admin.payments.index', compact('payments'));
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);

        return view('admin::admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $payment->alias = $request->get('title_'.$this->lang->lang);
        $payment->save();

        $payment->translations()->delete();

        foreach ($this->langs as $lang):
            $payment->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
            ]);
        endforeach;

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $toValidate['name_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $payment = new Payment();
        $payment->alias = str_slug($request->get('name_'.$this->lang->lang));
        $payment->save();

        foreach ($this->langs as $lang):
            $payment->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('payments.index');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('payments.index');
    }
}
