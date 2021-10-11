<?php

use Illuminate\Database\Seeder;

class ContactsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contacts')->delete();
        DB::table('contacts_translation')->delete();

        $langs = DB::table('langs')->get();

        $contacts = array(
      		array('title' => 'address', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'magazins', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'footertext', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'footertextdesc', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'weekend', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'company', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'companyAddress', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'bankname', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
        );

        DB::table('contacts')->insert($contacts);

        $contacts = DB::table('contacts')->get();

        foreach ($contacts as $key => $contact) {
            foreach ($langs as $key => $lang) {
                DB::table('contacts_translation')->insert([
                        'contact_id' => $contact->id,
                        'lang_id' => $lang->id,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
            }
        }

        $contactsWithoutLang = array(
      		array('title' => 'phone', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'emailfront', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'adminname', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'site', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'emailadmin', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'skype', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'whatsapp', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'viber', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'facebook', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'instagram', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'twitter', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'google', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'youtube', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'linkedin', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'pinterest', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'social', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'workWeekdays', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'workWeekends', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'fisc', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'nds', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'iban', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'codbank', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'phonePayment', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'sign', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'delivery', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('title' => 'treshold', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
        );

        DB::table('contacts')->insert($contactsWithoutLang);

        $contactsWithoutLang = DB::table('contacts')->whereNotIn('id', $contacts->pluck('id'))->get();

        foreach ($contactsWithoutLang as $key => $contact) {
          DB::table('contacts_translation')->insert([
                  'contact_id' => $contact->id,
                  // 'lang_id' => 1,
                  'created_at' => date("Y-m-d H:i:s"),
                  'updated_at' => date("Y-m-d H:i:s"),
              ]);
        }
    }
}
