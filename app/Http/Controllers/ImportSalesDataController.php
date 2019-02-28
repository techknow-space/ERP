<?php


namespace App\Http\Controllers;


use App\Models\WODevicePart;
use App\Models\WorkOrder;
use App\Models\WorkOrderDevice;
use App\Models\Part;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class ImportSalesDataController extends Controller
{

    public function index()
    {
        return view('import.sales.index');
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

        //return view('import.sales.process')->with('path',$data);
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
        return $data;
    }

    public function process($data)
    {
        $part_not_found = [];
        if($data){
            foreach ($data as $datum){
                $part = $this->getPartwithSKU($datum['1']);
                if($part){
                    $wo = new WorkOrder();
                    $wo->save();

                    $wo_device = new WorkOrderDevice();
                    $wo_device->WorkOrder()->associate($wo);
                    $wo_device->Device()->associate($part->devices);
                    $wo_device->DeviceType()->associate($part->devices->devicetype);
                    $wo_device->save();

                    $wo_device_part = new WODevicePart();
                    $wo_device_part->WorkOrderDevice()->associate($wo_device);
                    $wo_device_part->Part()->associate($part);
                    $date = date('Y-m-d H:i:s',strtotime($datum[0]));
                    $wo_device_part->setCreatedAt($date);
                    $wo_device_part->setUpdatedAt($date);
                    $wo_device_part->save();

                }
                else{

                    $part_not_found[] = $datum;
                }
            }
        }

        $part_not_found = collect($part_not_found)->unique('1');

        return view('import.sales.process')->with('not_found',$part_not_found);
    }

    public function getPartwithSKU($sku)
    {
        try{
            $part = Part::where('sku',$sku)->with('devices.devicetype')->firstOrFail();
        }catch(ModelNotFoundException $e){
            $part = false;
        }

        return $part;
    }
}
