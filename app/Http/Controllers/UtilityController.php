<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilityController extends Controller {

	public function __construct() {

	}

	public function postUpload(Request $request) {
		$id = $request->user()->_id;
		if (!$request->hasFile('file')) {
			abort(400, 'No file specified');
		}

		$file = $request->file('file');
		$originalName = $file->getClientOriginalName();
		$newName = md5(time() + rand(0, 100000)) . urlencode($originalName);
		$newFile = 'uploads' . DIRECTORY_SEPARATOR . $id;

		try {
			$file->move($newFile, $newName);
			$newFile = $newFile . DIRECTORY_SEPARATOR . $newName;

			return array(
              'url' => env('API_URL'). DIRECTORY_SEPARATOR . $newFile ,  
              'file_path' => DIRECTORY_SEPARATOR . $newFile
            ); 
		}
		catch(\Exception $e) {
			abort('500' , 'Something went wrong while storing the file');
		}
	}
}