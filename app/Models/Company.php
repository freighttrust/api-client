<?php
namespace App\Models;

class Company extends Model {
	
	protected $collection = 'company';
	protected $fillables = ['_id', 'name', 'website', 'logo', 'email', 'user_id', 'phone', 'license_id', 'license_valid'];

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

	public function members() {
		return $this->hasMany('App\Models\Member');
	}
}