<?php

namespace Auth;

require 'vendor/autoload.php';

/**
 * Authenticates key.
 */
class Authenticator
{

    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function execute()
    {
    	$key = \Api::where('key', $this->key)->count();
    	
    	if ($key > 0) {

    		return true;
    	} else {

            return false;
        }
    }
}