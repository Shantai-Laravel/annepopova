<?php

use Illuminate\Database\Seeder;

class GeneralTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('generals')->delete();
        DB::table('generals_translation')->delete();

        $langs = DB::table('langs')->get();

        $generals = array(
      		array('name' => 'order', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('name' => 'order', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('name' => 'order', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('name' => 'order', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('name' => 'checkbox', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('name' => 'checkbox', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('name' => 'shippingReturns', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
          array('name' => 'description', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), ),
        );

        DB::table('generals')->insert($generals);

        $generals = DB::table('generals')->get();

        foreach ($generals as $key => $general) {
            foreach ($langs as $key => $lang) {
                DB::table('generals_translation')->insert([
                        'general_id' => $general->id,
                        'lang_id' => $lang->id,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
            }
        }
    }
}
