<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Lokasi extends Eloquent {

	protected $table = 'lokasi';
	protected $hidden = ['id', 'user_id', 'status', 'created_at', 'updated_at'];

	/*
	 * Relasi One-to-Many
	 * ================= 
	 * model 'Lokasi' memiliki relasi One-to-Many (belongsTo) sebagai penerima 'user_id'
	 */
	public function user() {
		return $this->belongsTo('User', 'user_id');
	}
	
}