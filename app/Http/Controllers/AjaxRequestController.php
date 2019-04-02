<?php


namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
                $response = '';
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
        $response = [];

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
