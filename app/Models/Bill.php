<?php
namespace App\Models;

use App\Helpers\NotificationHelper;
class Bill extends Model {
  
    protected $collection = 'bill';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function company() {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function setStatusAttribute($status) {
        $this->attributes['status'] = $status;
        if ($status == 'shipper_signed') {
            NotificationHelper::sendShipperSignNotification($this);
        }
    }
}