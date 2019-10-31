<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller {
	protected $model;
	protected $blacklist_fields = [];

	public function __construct() {
	
	}

	public function index(Request $request) {
		$page_size = $request->has('limit') ? $request->get('limit') : env("PAGE_SIZE");
		$page_size = intval($page_size);

		$page = $request->has('p') ? $request->get('p') : 1;

		$query = $this->model;
		$query = $query->take($page_size);
		$query = $query->whereNull('deleted_at');

		$params = $request->all();
		foreach ($params as $key => $value) {
			switch($key){
				case 'with':
					$with = explode(',', $value);
					$query = $query->with($with);
					break;
				case 'q':
				case 'p':
				case 'limit':
				case 'order_by':
					break;
				default:
					$query = $query->where($key, $value);
			}
		}

		$count = $query->count();

		$query = $query->skip($page_size * ($page-1));
		$data = $query->get();

		return [
			'data' => $data,
			'total_count' => $count
		];
	}

	public function show(Request $request, $id) {
		$query = $this->model->where('_id', $id);

		if ($request->has('with')) {
			$with = explode(',', $request->get('with'));
			$query = $query->with($with);
		}
		$resource = $query->first();

		if(empty($resource->_id)) {
			abort(404, 'Resource does not exist.');
		}
		return $resource;
	}

	public function store(Request $request) {
		$params = $request->except($this->blacklist_fields);
		$resource = $this->model->create($params);
		if(empty($resource->_id)) {
			abort(404, "The operation request counldn't be completed.");
		}
		return $resource;
	}

	public function update(Request $request, $id) {
		$resource = $this->model->find($id);
		if(empty($resource->_id)) {
			abort(404, 'Resource does not exist.');
		}
		$resource->fill($request->except($this->blacklist_fields));
		$resource->save();
		return $resource;
	}

	public function destroy(Request $request, $id) {
		$resource = $this->model->find($id);
		if(empty($resource->_id)) {
			abort(404, 'Resource does not exist.');
		}

		$response = ['result' => false];
		$response['result'] = $resource->delete();
		return $response;
	}
}