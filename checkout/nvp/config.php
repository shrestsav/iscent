<?php

  //start session in all pages
  if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
  //if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above

	// sandbox or live
	define('PPL_MODE', 'sandbox');

	if(PPL_MODE=='sandbox'){

		define('PPL_API_USER', 'fhdalikhann-facilitator_api1.gmail.com');
		define('PPL_API_PASSWORD', '4558YZFJWH887GVZ');
		define('PPL_API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AwS-U0UteULDPWEZNGc8shQ2gRyR');

		// define('PPL_API_USER', 'info-facilitator_api1.difficult.se');
		// define('PPL_API_PASSWORD', 'BYXFSNQQJVML9Q63');
		// define('PPL_API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31A2VicEdiIDn1dOG8RQha7EhEjyQx');
	}
	else{

		define('PPL_API_USER', 'info_api1.difficult.se'); 										 # LIVE CREDENTIALS
		define('PPL_API_PASSWORD', '9HALLVHNXR56TGQG'); 										 # LIVE CREDENTIALS
		define('PPL_API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31ArzocEOC7pMIPGPMAkAorp.vgPIn'); # LIVE CREDENTIALS

	}

	define('PPL_LANG', 'EN');

	// define('PPL_LOGO_IMG', 'https://www.sanwebe.com/wp-content/themes/sanwebe/img/logo.png');
	define('PPL_LOGO_IMG', 'https://sharkspeed.com/webImages/logo.png');

	$cancelUrl     = WEB_URL . '/cancel_url.php';
	$placeOrderUrl = WEB_URL . '/process.php?';

	define('PPL_RETURN_URL', $placeOrderUrl);
	define('PPL_CANCEL_URL', $cancelUrl);



	// define('PPL_CURRENCY_CODE', 'EUR');