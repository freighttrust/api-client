<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller {

	public function __construct() {

	}

	public function postSignUp(Request $request) {
		$this->validate($request, [
			'email' => 'required|unique:user',
			'password' => 'required|min:6'
		]);

		$user = User::registerUser($request);
		return $user;
	}

	public function postSignIn(Request $request) {
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required'
		]);

		$user = User::authenticateUser($request);
		if (!$user) {
			 abort('401','Email or password incorrect');
		}
		return $user;
	}

	public function postForgot(Request $request) {
		$this->validate($request, [
			'email' => 'required'
		]);
		return User::sendResetLink($request);
	}

	public function postReset(Request $request) {
		$this->validate($request, [
			'reset_token' => 'required',
			'password' => 'required|min:6'
		]);

		$user = User::where('reset_token', $request->get('reset_token'))->first();
		if ($user) {
			$user->password = $request->get('password');
			$user->reset_token = '';
			$user->save();

			return ['success' => true];
		}
		abort(400, 'Reset token i snot valid');
	}
}
