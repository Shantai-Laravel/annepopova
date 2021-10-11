<?php

namespace App\Http\Controllers;


use App\Models\Lang;
use App\Models\FrontUser;

class LanguagesController extends Controller
{
    public function set($lang) {
        $lang = Lang::where('lang', $lang)->first()->lang;
        session(['applocale' => $lang]);

        return back();
    }

    public function changeLang() {
        $user = FrontUser::where('id', auth('persons')->id())->first();

        if(count($user) > 0) {
            $user->lang = request('lang');
            $user->save();
        }
    }

    public function changeLangScript()
    {
        $strings = \Cache::rememberForever('lang.js', function (){
            $lang = $this->lang->lang;

            $files   = glob(resource_path('lang/' . $lang . '/*.php'));
            $strings = [];

            foreach ($files as $file) {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }

            return $strings;
        });

        header('Content-Type: text/javascript');
        echo('window.trans = ' . json_encode($strings) . ';');
        exit();
    }

}
