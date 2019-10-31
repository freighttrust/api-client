<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use App\Models\Member;
use App\Http\Controllers\APIController;

class MemberController extends APIController {

	public function __construct() {
		parent::__construct();
		$this->model = new Member();
		$this->middleware('auth');
	}

	public function store(Request $request) {
		$address = $request->has('address') ? strtolower($request->get('address')) : null;
		$company_id = $request->has('company_id') ? $request->get('company_id'): null;

		$this->validate($request, [
			'name'=> 'required',
			'company_id' => 'required',
			'address' => [
				'required',
				Rule::unique('member')->where('company_id', $company_id)
			]
		]);
		return parent::store($request);
	}

	public function update(Request $request, $id) {
		$address = $request->has('address') ? strtolower($request->get('address')) : null;
		$company_id = $request->has('company_id') ? $request->get('company_id'): null;

		$this->validate($request, [
			'name'=> 'required',
			'company_id' => 'required',
			'address' => [
				'required',
				Rule::unique('member')->ignore($id, '_id')->where('company_id', $company_id)
			]
		]);
		return parent::update($request, $id);
	}
}