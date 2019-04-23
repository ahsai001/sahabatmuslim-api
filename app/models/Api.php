<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Api extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'api';

	protected $primaryKey = 'key';
}