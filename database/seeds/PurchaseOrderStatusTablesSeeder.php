<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PurchaseOrderStatusTablesSeeder extends Seeder
{

    /**
     * @throws Exception
     */
    public function run()
    {
        DB::table('purchase_order_statuses')->insert(
            [
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Generated','seq_id'=>0],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Created','seq_id'=>1],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'InReview','seq_id'=>2],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Sent','seq_id'=>3],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'PI Received','seq_id'=>4],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'PI Updated','seq_id'=>5],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Awaiting Shipment','seq_id'=>6],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Shipped','seq_id'=>7],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Shipment Received','seq_id'=>8],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Verified','seq_id'=>9],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Stock Distributed','seq_id'=>10],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Completed','seq_id'=>11]
            ]
        );

        DB::table('purchase_order_cdstatuses')->insert(
            [
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Calculated'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Supplier Notified'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Acknowledged by Supplier'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Adjusted']
            ]
        );

        DB::table('purchase_order_payment_statuses')->insert(
            [
                ['id'=>Uuid::uuid4()->toString(),'status'=>'UnPaid'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'In Queue'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Paid'],
            ]
        );
    }
}
