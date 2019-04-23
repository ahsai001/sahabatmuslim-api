<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Ustadz extends Eloquent {

	protected $table = 'ustadz';
	protected $hidden = ['id', 'user_id', 'status', 'created_at', 'updated_at'];

	/*
	 * Relasi One-to-Many
	 * ================= 
	 * model 'Ustadz' memiliki relasi One-to-Many (belongsTo) sebagai penerima 'user_id'
	 */
	public function user() {
		return $this->belongsTo('User', 'user_id');
	}
	
}