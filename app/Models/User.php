<?php

namespace App\Models;

use App\Helpers\MongoDate;
use App\Helpers\EmailHelper;
use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $collection = 'user'; 
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];

    public function companies() {
        return $this->hasMany('App\Http\Company');
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }

    public static function registerUser(Request $request) {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);
        return $user;
    }

    public static function authenticateUser(Request $request) {
        $user = User::where('email', $request->get('email'))->first();
        if ($user && Hash::check($request->get('password'), $user->password)) {
            $user->refreshToken();
            return $user;
        }
        return false;
    }

    public static function sendResetLink(Request $request) {
        $user = User::where('email', $request->get('email'))->first();
        $response = [
            'success' => false,
            'message' => ''
        ];

        if ($user) {
            $user->reset_token = md5($request->get('email') . rand(10000, 99999));
            $user->save();

            EmailHelper::sendResetPasswordEmail($user);

            $response['success'] = true;
            $response['message'] = 'Password reset link sent to email';

            return $response;
        } else {
            $response['success'] = false;
            $response['message'] = 'Email is not registered';
        }

        return $response;
    }

    public function refreshToken() {
        $this->access_token = md5($this->email . $this->password . time());
        $this->access_token_expired = MongoDate::getFromTimestamp(strtotime('+1 month', time()));
        $this->save();
    }
}

User::created(function($user) {
    $user->refreshToken();
});