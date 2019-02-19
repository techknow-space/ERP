<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DeviceTypesTableSeeder extends Seeder
{

    /**
     * @throws Exception
     */
    public function run()
    {
        DB::table('device_types')->insert(
            [
                ['id'=>Uuid::uuid4()->toString(),'type'=>'Phones','import_ref'=>1],
                ['id'=>Uuid::uuid4()->toString(),'type'=>'Tablets','import_ref'=>2],
                ['id'=>Uuid::uuid4()->toString(),'type'=>'Laptops','import_ref'=>3],
                ['id'=>Uuid::uuid4()->toString(),'type'=>'Macbooks','import_ref'=>4],
                ['id'=>Uuid::uuid4()->toString(),'type'=>'Wearables','import_ref'=>5],
                ['id'=>Uuid::uuid4()->toString(),'type'=>'Headphones','import_ref'=>6],
                ['id'=>Uuid::uuid4()->toString(),'type'=>'Gaming','import_ref'=>7]
            ]
        );
    }
}
