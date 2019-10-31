<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Bill;
use App\Http\Controllers\APIController;

class BillController extends APIController {

    public function __construct() {
        parent::__construct();
        $this->model = new Bill();
    }
}