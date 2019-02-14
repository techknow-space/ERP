<?php
/* Model object generated by: Skipper (http://www.skipper18.com) */
/* Do not modify this file manually.*/

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class StockCountItems extends Model
{
  /**  
  * Primary key type.
  * 
  * @var string
  */
  protected $keyType='uuid';
  
  /**  
  * Primary key is non-autoincrementing.
  * 
  * @var bool
  */
  public $incrementing=false;
  
  /**  
  * The attributes that should be cast to native types.
  * 
  * @var array
  */
  protected $casts=[
    'id'=>'string',
    'qty'=>'integer',
    'stockCount_id'=>'string',
    'part_id'=>'string'
  ];
  public function StockCount()
  {
    return $this->belongsTo('\App\Models\StockCount','stockCount_id','id');
  }
  public function Part()
  {
    return $this->belongsTo('\App\Models\Part','part_id','id');
  }
}