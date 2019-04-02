<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PurchaseOrderPayment extends Base\PurchaseOrderPayment
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }

    /**
     * @param string $attr
     * @return bool
     */
    public function hasAttribute(string $attr): bool
    {
        return array_key_exists($attr, $this->attributes);
    }
}

