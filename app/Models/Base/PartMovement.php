<?php
/* Model object generated by: Skipper (http://www.skipper18.com) */
/* Do not modify this file manually.*/

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class PartMovement extends Model
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
    'movement_at'=>'datetime',
    'part_id'=>'string',
    'part_name'=>'string'
  ];
  public function Part()
  {
    return $this->belongsTo('\App\Models\Part','part_id','id');
  }
  public function PartMvmntFrom()
  {
    return $this->hasOne('\App\Models\PartMvmntFrom','partMovement_id','id');
  }
  public function PartMvmntTos()
  {
    return $this->hasOne('\App\Models\PartMvmntTo','partMovement_id','id');
  }
}
