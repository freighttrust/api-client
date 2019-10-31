<?php
namespace App\Models;

class Notification extends Model {
  
    protected $collection = 'notification';

    public function company() {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public static function newOrUpdate($params = []) {
        $notification = self::where('address', $params['address'])->first();
        if ($notification) {
            $tokens = $notification->tokens ? $notification->tokens : [];
            if (!in_array($params['token'], $tokens)) {
                $tokens[] = $params['token'];
            }
            $notification->tokens = $tokens;
            $notification->save();
        } else {
            $notification = self::create([
                'address' => $params['address'],
                'tokens' => [$params['token']]
            ]);
        }
        
        return $notification;
    }
}