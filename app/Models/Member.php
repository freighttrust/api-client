<?php
namespace App\Models;

class Member extends Model {
	
	protected $collection = 'member';
	protected $fillables = ['address', 'name'];
}

Member::saving(function($member){
	$member->address = strtolower($member->address);
});