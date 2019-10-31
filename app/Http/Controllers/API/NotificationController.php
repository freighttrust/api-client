<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Notification;
use App\Http\Controllers\APIController;

class NotificationController extends APIController {

    public function __construct() {
        parent::__construct();
        $this->model = new Notification();
    }

    public function store(Request $request) {
        $this->validate($request, [
            'address'=> 'required',
            'token' => 'required'
        ]);

        $params = $request->all();
        return Notification::newOrUpdate($params);
    }
}