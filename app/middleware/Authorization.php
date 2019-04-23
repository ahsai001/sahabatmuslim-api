<?php

require 'vendor/autoload.php';

use auth\Authenticator;

class Authorization extends \Slim\Middleware
{

	/**
     * Deny Access
     *
     */   
    public function deny_access() {

        # Inisialisasi chris kacerguis
        $random = new \chriskacerguis\Randomstring\Randomstring();

    	$app = $this->app;
        $app->response->setStatus(401);
		$app->response->headers->set('Content-Type', 'application/json');
        echo json_encode(array(
            'seq_id' => $random->generate(8) . time(),
            'status' => 3,
            'message' => 'Invalid API Key'
        ));
    }

    public function call()
    {
        $app = $this->app;

        // authorized api key
        $authorizedAPIKey = function () use ($app) {

        	$auth = new Authenticator($app->request->post('key'));
        	return $auth->execute();
        };

        if ($authorizedAPIKey()) {

        	// next to application
        	$this->next->call();
        } else {

        	$this->deny_access();
        }

    }

}