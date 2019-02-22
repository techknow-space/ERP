<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PartStock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Storage as Storage;
use Ramsey\Uuid\Uuid;

class ImportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('import.import');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function upload(Request $request)
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $file = false;
        $name = Uuid::uuid4()->toString();
        if ($request->hasFile('csv_upload_file')) {
            if($request->file('csv_upload_file')->isValid()){
                $file = $request->file('csv_upload_file')->storeAs('imports',$name.'.csv');
            }
        }


        $data = $this->convertFileToCSV($file);

        //return view('import.process')->with('path',$data);
        return $this->process($data);

    }

    /**
     * @param $file
     * @return array|bool
     */
    public function convertFileToCSV($file)
    {
        $data = false;

        if($file){
            $file = utf8_encode(Storage::get($file));

            $structured_data = str_getcsv($file,"\n");
            foreach ($structured_data as $row){
                $data[] = str_getcsv($row, ",");
            }
            $headers = array_shift($data);
        }

        if($data){
            $data = $this->sanitizeData($data);
        }
        return $data;
    }

    private function sanitizeData($data)
    {
        $data_new = [];
        foreach ($data as $datum){
            $datum[5] = utf8_encode(str_replace("\xc2\xa0",' ',$datum[5]));

            if($datum[6] == ''){
                $datum[6] = intval(0);
            }

            if($datum[7] == ''){
                $datum[7] = intval(0);
            }

            if($datum[8] == ''){
                $datum[8] = intval(0);
            }

            if($datum[9] == ''){
                $datum[9] = intval(0);
            }

            if($datum[6] == ''){
                $datum[6] = intval(0);
            }

            if($datum[10] == ''){
                $datum[10] = intval(0);
            }

            if($datum[11] == ''){
                $datum[11] = intval(0);
            }
            if($datum[13] == ''){
                $datum[13] = false;
            }

            if($datum[16] == ''){
                $datum[16] = false;
            }


            $data_new[] = $datum;

        }
        return $data_new;
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function process($data)
    {
        if($data){
            $data = collect($data);

            $data->each(function ($item){

                //$manufacturer = $this->findOrCreateManufacturer($item[1]);

                //$brand = $this->findOrCreateBrand($manufacturer,$item[2]);

                $brand = $this->findOrCreateBrandwithoutManufacturer($item[2]);

                $device = $this->findOrCreateModel(
                    $brand,
                    [
                        'type_id_ref' => $item[0],
                        'name' => $item[3],
                        'number' => $item[4]
                    ]
                );

                $is_child_part = $item[16];

                $part = $this->findOrCreatePart(
                    $device,
                    [
                        'sku' => $item[13],
                        'name' => $item[5],
                        'first_received' => $item[12],
                        'parent_sku' => $item[16]
                    ]
                );

                if(!$is_child_part){
                    $part_price = $this->updateOrCreatePartPrice(
                        $part,
                        [
                            'last_cost' => $item[11],
                            'selling_b2c' => $item[6]
                        ]
                    );
                    $part_stock = $this->updateOrCreatePartStock(
                        $part,
                        [
                            [
                                'code'=>'S1',
                                'stock_qty'=>$item[8],
                                'sold_all_time'=>$item[10]
                            ],
                            [
                                'code'=>'TO1',
                                'stock_qty'=>$item[7],
                                'sold_all_time'=>$item[9]
                            ]
                        ]
                    );
                }

            });

        }
        return view('import.process')->with('path',$data);
    }

    /**
     * @param string $manufacturer_name
     * @return Manufacturer
     */
    private function findOrCreateManufacturer($manufacturer_name)
    {
        try{

            $manufacturer = Manufacturer::where('manufacturer',$manufacturer_name)
                ->firstOrFail();

        }catch (ModelNotFoundException $e){
            $manufacturer = new Manufacturer();
            $manufacturer->manufacturer = $manufacturer_name;
            $manufacturer->save();
        }
        return $manufacturer;
    }

    /**
     * @param Manufacturer $manufacturer
     * @param string $brand_name
     * @return Brand
     */
    private function findOrCreateBrand(Manufacturer $manufacturer, $brand_name)
    {
        try{

            $brand = Brand::where('name',$brand_name)
                ->where('manufacturer_id',$manufacturer->id)
                ->firstOrFail();

        }catch (ModelNotFoundException $e){
            $brand = new Brand();
            $brand->name = $brand_name;
            $brand->manufacturer()->associate($manufacturer);
            $brand->save();
        }
        return $brand;
    }

    /**
     * @param Brand $brand
     * @param array $model_data
     * @return Device
     */
    private function findOrCreateModel(Brand $brand, $model_data)
    {
        $device_type = DeviceType::where('import_ref',$model_data['type_id_ref'])->firstOrFail();
        try{

            $model = Device::where('model_name',$model_data['name'])
                ->where('model_number',$model_data['number'])
                ->where('brand_id',$brand->id)
                ->firstOrFail();

        }catch (ModelNotFoundException $e){
            $model = new Device();
            $model->model_name = $model_data['name'];
            $model->model_number = $model_data['number'];
            $model->brand()->associate($brand);
            $model->devicetype()->associate($device_type);
            $model->save();
        }

        return $model;
    }

    /**
     * @param Device $device
     * @param array $part_details
     * @return Part
     */
    private function findOrCreatePart(Device $device, $part_details)
    {
        if($part_details['sku']){
            try{
                $part = Part::where('sku',$part_details['sku'])
                    ->firstOrFail();
            }catch (ModelNotFoundException $e){
                $part = new Part();
                $part->sku = $part_details['sku'];
                $part->part_name = $part_details['name'];
                $part->Devices()->associate($device);
                $part->save();
            }
        }
        else{

            try{
                $parent_part = Part::where('sku',$part_details['parent_sku'])
                    ->firstOrFail();
            }catch (ModelNotFoundException $e){
                $parent_part = false;
            }

            try{
                $part = Part::where('part_name',$part_details['name'])
                    ->where('device_id',$device->id)
                    ->firstOrFail();
                $part->is_child = true;
                $part->ParentPart()->associate($parent_part);
                $part->save();
            }catch (ModelNotFoundException $e){
                $part = new Part();
                $part->part_name = $part_details['name'];
                $part->Devices()->associate($device);
                $part->is_child = true;
                $part->ParentPart()->associate($parent_part);
                $part->save();
            }
        }


        return $part;

    }

    /**
     * @param Part $part
     * @param array $price_details
     * @return PartPrice
     */
    private function updateOrCreatePartPrice(Part $part, $price_details)
    {
        try{
            $part_price = PartPrice::where('part_id',$part->id)
                ->firstOrFail();
            $part_price->last_cost = $price_details['last_cost'];
            $part_price->selling_price_b2c = $price_details['selling_b2c'];
        }catch (ModelNotFoundException $e){
            $part_price = new PartPrice();
            $part_price->last_cost = $price_details['last_cost'];
            $part_price->selling_price_b2c = $price_details['selling_b2c'];
            $part_price->part()->associate($part);
        }
        $part_price->save();

        return $part_price;
    }

    /**
     * @param Part $part
     * @param array $stock_details
     */
    private function updateOrCreatePartStock(Part $part, $stock_details)
    {
        foreach($stock_details as $location){
            $location_obj = Location::where('location_code',$location['code'])
                ->firstOrFail();

            try{

                $part_stock = PartStock::where('part_id',$part->id)
                    ->where('location_id',$location_obj->id)
                    ->firstOrFail();
                $part_stock->stock_qty = $location['stock_qty'];
                $part_stock->sold_all_time = $location['sold_all_time'];

            }catch (ModelNotFoundException $e){

                $part_stock = new PartStock();
                $part_stock->stock_qty = $location['stock_qty'];
                $part_stock->sold_all_time = $location['sold_all_time'];
                $part_stock->location()->associate($location_obj);
                $part_stock->part()->associate($part);
            }

            $part_stock->save();
        }
    }

    private function findOrCreateBrandwithoutManufacturer($brand_name)
    {
        try{

            $brand = Brand::where('name',$brand_name)
                ->firstOrFail();

        }catch (ModelNotFoundException $e){
            $brand = new Brand();
            $brand->name = $brand_name;
            $brand->save();
        }
        return $brand;

    }
}

