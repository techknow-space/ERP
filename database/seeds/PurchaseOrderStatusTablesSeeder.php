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
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Generated'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Created'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'InReview'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Waiting for PI'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'PI Received'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'PI Updated'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Awaiting Shipment'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Shipped'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Shipment Received'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Verified'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Stock Distributed'],
                ['id'=>Uuid::uuid4()->toString(),'status'=>'Completed']
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
