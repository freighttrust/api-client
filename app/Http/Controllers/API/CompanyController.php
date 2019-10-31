<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Company;
use App\Http\Controllers\APIController;

class CompanyController extends APIController {

	public function __construct() {
		parent::__construct();
		$this->model = new Company();
		$this->middleware('auth', ['except' => ['getCarrierCompanies']]);
	}

	public function index(Request $request) {
		$request->merge(['user_id' => $request->user()->_id]);
		return parent::index($request);
	}

	public function store(Request $request) {
		$request->merge(['user_id' => $request->user()->_id]);
		return parent::store($request);
	}

	public function update(Request $request, $id) {
		$company = Company::find($id);
		$user = $request->user();

		if($user && Gate::allows('isSelf', $company)) {
			return parent::update($request, $id);
		}
		abort(401, 'Unauthorized');
	}

	public function destroy(Request $request, $id) {
		$company = Company::find($id);
		$user = $request->user();

		if($user && Gate::allows('isSelf', $company)) {
			return parent::destroy($request, $id);
		}
		abort(401, 'Unauthorized');
	}

	public function getCarrierCompanies(Request $request){
		if (!$request->has('ids')) {
			abort(403, 'companies ids are required');
		}

		$ids_string = $request->query('ids');
		$ids = explode(',', $ids_string);
		return $this->model->whereIn('_id', $ids)->get();
	}
}