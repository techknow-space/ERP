<?php


namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Storage as Storage;
use Ramsey\Uuid\Uuid;

class ImportUpdatedStockAndNameController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('import.update.nameandstock');
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
        app('debugbar')->disable();

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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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

            if($datum[1] == ''){
                $datum[1] = intval(0);
            }

            if($datum[2] == ''){
                $datum[2] = intval(0);
            }

            if($datum[3] == ''){
                $datum[3] = intval(0);
            }

            if($datum[4] == ''){
                $datum[4] = intval(0);
            }

            if($datum[6] == ''){
                $datum[6] = false;
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

                try{
                    $is_child_part = $item[6];
                    $part = Part::where('sku',$item[5])->firstOrFail();

                    $part->part_name = $item[0];
                    $part->save();

                    if(!$is_child_part){
                        $part_stock = $this->updateOrCreatePartStock(
                            $part,
                            [
                                [
                                    'code'=>'S1',
                                    'stock_qty'=>$item[2],
                                    'sold_all_time'=>$item[4]
                                ],
                                [
                                    'code'=>'TO1',
                                    'stock_qty'=>$item[1],
                                    'sold_all_time'=>$item[3]
                                ]
                            ]
                        );
                    }


                }catch (ModelNotFoundException $e){
                    //
                }



            });

        }
        return view('import.process')->with('path',$data);
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

                $part_stock->stock_qty = intval(
                    trim(
                        str_replace('Â', '',
                            $location['stock_qty']
                        )
                    )
                );
                $part_stock->sold_all_time = intval(
                    trim(
                        str_replace('Â', '',
                            $location['sold_all_time']
                        )
                    )
                );


            }catch (ModelNotFoundException $e){

                $part_stock = new PartStock();
                $part_stock->stock_qty = intval(
                    trim(
                        str_replace('Â', '',
                            $location['stock_qty']
                        )
                    )
                );
                $part_stock->sold_all_time = intval(
                    trim(
                        str_replace('Â', '',
                            $location['sold_all_time']
                        )
                    )
                );
                $part_stock->location()->associate($location_obj);
                $part_stock->part()->associate($part);
            }

            $part_stock->save();
        }
    }

}
