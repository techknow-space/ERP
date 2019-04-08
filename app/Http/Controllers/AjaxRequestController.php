<?php


namespace App\Http\Controllers;


use App\Models\PartPrice;
use App\Models\StockTransferItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class AjaxRequestController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function processRequest(Request $request): JsonResponse
    {
        $entity = $request->input('entity');
        $entity_id = $request->input('entity_id');

        $attributes = $request->input('attributes');

        return response()->json(
            $this->sendRequestToEntity($entity,$entity_id,$attributes)
        );
    }

    public function sendRequestToEntity(string $entity, string $entity_id, array $attributes)
    {
        $response = [
            'error'=>true,
            'message'=> 'Unknown Error!!! MisMatched Entity Types'
        ];

        switch ($entity){
            case 'Part':
                $response = $this->updatePartDetails($entity_id,$attributes);
                break;
            case 'PartPrice':
                $response = $this->updatePartPrice($entity_id,$attributes);
                break;
            case 'PartStock':
                $response = $this->updatePartStock($entity_id,$attributes);
                break;
            case 'PurchaseOrder':
                $response = $this->updatePurchaseOrder($entity_id,$attributes);
                break;
            case 'PurchaseOrderItem':
                $response = $this->updatePurchaseOrderItem($entity_id,$attributes);
                break;
            case 'StockTransfer':
                $response = $this->updateStockTransfer($entity_id,$attributes);
                break;
            case 'StockTransferItem':
                $response = $this->updateStockTransferItem($entity_id,$attributes);
                break;
            case 'PurchaseOrderPayment':
                $response = $this->updatePurchaseOrderPayment($entity_id,$attributes);
                break;
            case 'PurchaseOrderDistributionItem':
                $response = $this->updatePurchaseOrderDistributionItem($entity_id,$attributes);
                break;
        }

        return $response;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updatePartDetails(string $id, array $attributes): array
    {

    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updatePartStock(string $id, array $attributes): array
    {

    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updatePartPrice(string $id, array $attributes): array
    {
        $response = [
            'error'=>true,
            'message'=> 'Sorry there was an unknown error updating this record.'
        ];

        try{
            DB::beginTransaction();

            $partPrice = PartPrice::where('part_id',$id)->firstOrFail();

            foreach ($attributes as $key=>$value){
                $partPrice->$key = $value;
                $partPrice->save();
            }
            
            DB::commit();
            $response = [
                'error'=>false,
                'message'=> 'This record has been updated.'
            ];

        }catch (Exception $exception){

            DB::rollBack();
            $response['error'] = true;
            $response['message'] = $exception->getMessage();

        }

        return $response;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updatePurchaseOrder(string $id, array $attributes): array
    {
        $response = [];

        return $response;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updatePurchaseOrderItem(string $id, array $attributes): array
    {
        $response = [];

        return $response;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updatePurchaseOrderPayment(string $id, array $attributes): array
    {
        $response = [];

        return $response;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updateStockTransfer(string $id, array $attributes): array
    {
        $response = [];

        return $response;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updateStockTransferItem(string $id, array $attributes): array
    {
        $response = [
            'error'=>true,
            'message'=> 'Unknown Error!!!'
        ];

        try{
            DB::beginTransaction();

            $stockTransferItem = StockTransferItem::findOrFail($id);

            foreach ($attributes as $key=>$value){
                $stockTransferItem->$key = $value;
            }

            $stockTransferItem->save();

            DB::commit();
            $response['error'] = true;
            $response['message'] = 'Updated Successfully';

        }catch (Exception $exception){

            DB::rollBack();

            $response['error'] = true;
            $response['message'] = $exception->getMessage();
        }

        return $response;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function updatePurchaseOrderDistributionItem(string $id, array $attributes): array
    {
        $response = [];

        return $response;
    }
}
