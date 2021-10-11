<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Translation;
use App\Models\TranslationLine;
use App\Models\TranslationGroup;


class TranslationsController extends Controller
{
    public function index()
    {
        return view('admin::admin.translations.index');
    }

    public function createTraslation()
    {
        dd(trans('test.ok'));
    }

    public function getGroups()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);
        
        $groups = TranslationGroup::with(['translations.lines'])->get();
        return $groups;
    }

    public function createGroup(Request $request)
    {
        TranslationGroup::create([
            'key' => $request->get('group'),
            'comment' => $request->get('comment'),
        ]);

        return $this->getGroups();
    }

    public function saveNewLine(Request $request)
    {
        $lines = array_filter($request->get('lines'), function($var){return !is_null($var);} );

        $translation = Translation::create([
            'group_id' => $request->get('groupId'),
            'key' => $request->get('key'),
            'comment' => $request->get('comment'),
        ]);

        foreach ($lines as $key => $value) {
            $translation->lines()->create([
                'lang_id' => $key,
                'line' => $value,
            ]);
        }

        return $this->getTranslationsByGroup($request->get('groupId'));
    }

    public function updateTranslations(Request $request)
    {
        $lines = array_filter($request->get('lines'), function($var){return !is_null($var);} );
        $keys = array_filter($request->get('keys'), function($var){return !is_null($var);} );

        if (count($keys) > 0) {
            foreach ($keys as $id => $key) {
                Translation::where('id', $id)->update([
                    'key' => $key,
                ]);
                $translation = Translation::find($id);

                if ($lines[$id]) {
                    $translation->lines()->delete();
                    foreach ($lines[$id] as $k => $line) {
                        $translation->lines()->create([
                            'lang_id' => $k,
                            'line' => $line,
                        ]);
                    }
                }
            }
        }
    }

    public function removeTranslations(Request $request)
    {
        Translation::where('id', $request->get('id'))->delete();

        return $this->getTranslationsByGroup($request->get('groupId'));
    }

    public function searchTranslations(Request $request)
    {
        $translationsId = $this->search($request);

        $translations = Translation::with('lines')->whereIn('id', $translationsId)
                            ->where('group_id', $request->get('groupId'))
                            ->orderBy('id', 'desc')
                            ->get();

        $findGroups = Translation::whereIn('id', $translationsId)->pluck('group_id');


        if ((count($translations) == 0) && (count($findGroups) > 0)) {
            $data['status'] = "false";
            $data['groups'] = TranslationGroup::whereIn('id', $findGroups)->where('id', '!=', $request->get('groupId'))->pluck('key');
            return $data;
        }

        $data['groups'] = TranslationGroup::whereIn('id', $findGroups)->where('id', '!=', $request->get('groupId'))->pluck('key');
        $data['status'] = "true";
        $data['trans'] = $translations;

        return $data;

    }

    public function cancelSearchTranslations(Request $request)
    {
        return $this->getTranslationsByGroup($request->get('groupId'));
    }

    private function search($request)
    {
        $translationsId = Translation::where('key', 'like', '%' . $request->get('search') . '%')->pluck('id')->toArray();
        $linesId = TranslationLine::where('line', 'like', '%' . $request->get('search') . '%')->pluck('translation_id')->toArray();

        return array_merge($translationsId, $linesId);
    }


    private function getTranslationsByGroup($groupId)
    {
        return Translation::with('lines')->where('group_id', $groupId)->orderBy('id', 'desc')->get();
    }
}
