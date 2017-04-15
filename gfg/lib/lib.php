<?php


////$r = \Httpful\Request::get('localhost/validator.php')->sendsJson()->body($json)->send();

//verifica se eh json
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}


function logError($err_id = 0,$err_desc = ''){
	return ('	{
			"err_code" : "'.$err_id.'"
			"err_desc": "'.$err_desc.'"
		}');
}

function logErrorValidate($err_id,$field_name,$sku,$desc){
	$error = new stdClass;
	$error->code = $err_id;
	$error->field_name = $field_name;
	$error->sku = $sku;
	$error->desc = $desc;
	return $error;
}

function imgRule($url){
	$allowed =  array('jpg', 'jpeg', 'png', 'gif');
	$url = addhttp($url);
	if (!filter_var($url, FILTER_VALIDATE_URL) ) {
		return false;
	}
	$ext = pathinfo($url, PATHINFO_EXTENSION);
	if(!in_array($ext,$allowed) ) {
    	return false;
	}
	return true;
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

/**
function validateJsonFields
return {
    "valid": 1|0,
    "errors": [0..n] [	
    	{
		   	"code": int required,
		    "field_name": string optional,
		    "sku": string optional,
		    "desc": string required
		}
    ]
}
**/

function validateJsonFields($json) {
	$json_arr =  json_decode($json);
	$erros = [];
	$valid = 1;

	if (empty($json)){
		$error = logErrorValidate(2,'','', 'Empty input');
		$erros[] = $error;
		$valid = 0;
		$ret = new stdClass;
		$ret->valid = $valid;
		$ret->errors = $erros;
		$jsonRet = json_encode($ret);
		print_r($jsonRet);
		return;
	}

	//verifica se o formato json é valido
	if (!isJson($json)){
		$error = logErrorValidate(2,'','', 'Invalid json format');
		$erros[] = $error;
		$valid = 0;
		$ret = new stdClass;
		$ret->valid = $valid;
		$ret->errors = $erros;
		$jsonRet = json_encode($ret);
		print_r($jsonRet);
		return;
	}

	foreach ($json_arr as $item) {	
		//aqui começam todas as regras de validação
		if (!is_string($item->sku) || empty($item->sku)) {
			$error = logErrorValidate(1,'sku',$item->sku, 'This field must be a non empty string');
			$erros[] = $error;
			$valid = 0;
		}
		//fim da primeira regra de validacao  (campo sku deve ser uma string nao vazia).
		
		if (!is_numeric($item->price) || empty($item->price)) {
			$error = logErrorValidate(1,'price',$item->sku, 'This field must to be a number');
			$erros[] = $error;
			$valid = 0;
		}
		if (!is_string($item->name) || empty($item->name)) {
			$error = logErrorValidate(1,'name',$item->sku, 'This field must be a non empty string');
			$erros[] = $error;
			$valid = 0;
		}
		if (!is_string($item->description) || empty($item->description)) {
			$error = logErrorValidate(1,'description',$item->sku, 'This field must be a non empty string');
			$erros[] = $error;
			$valid = 0;
		}
		if ((!is_string($item->size) && !is_array($item->size)) || empty($item->size))   {
			$error = logErrorValidate(1,'size',$item->sku, 'This field must be a non empty string or an array');
			$erros[] = $error;
			$valid = false;
		}
		if (!is_string($item->brand) || empty($item->brand)) {
			$error = logErrorValidate(1,'brand',$item->sku, 'This field must be a non empty string');
			$erros[] = $error;
			$valid = 0;
		}
		if ( !is_array($item->categories) || empty($item->categories) )  {
			$error = logErrorValidate(1,'categories',$item->sku, 'This fied must be a non empty array');
			$erros[] = $error;
			$valid = 0;
		}
		if (!is_numeric($item->special_price) && !empty($item->special_price)) {
			$error = logErrorValidate(1,'special_price',$item->sku, 'This field must be a number');
			$erros[] = $error;
			$valid = 0;
		}
		if (!imgRule($item->product_image_url)) {
			$error = logErrorValidate(1,'product_image_url',$item->sku, 'This field must be an url with image extension');
			$erros[] = $error;
			$valid = 0;
		}
	}
	$ret = new stdClass;
	$ret->valid = $valid;
	$ret->errors = $erros;
	$jsonRet = json_encode($ret);
	print_r($jsonRet);
}




/**
function filterJsonFields
return {
    "valid": 1,
    "errors": [0..n] [	
    	{
		   	"code": 0 required,
		    "field_name": string optional,
		    "sku": string optional,
		    "desc": string required
		}
    ]
    "newJson" : json
}
**/

function filterJsonFields($json){
	$erros = [];
	$valid = true;
	$json_arr =  json_decode($json);
	foreach ($json_arr as $key => $item) {
		$val = $item->price;
		//$item->price+0  casting para converter string para (int|float) dependendo do conteudo da string
		if (!is_float($item->price+0)){
			$old = $item->price;
			$json_arr[$key]->price = number_format($old,2,'.','');
			$error = logErrorValidate(0,'price',$item->sku, 'Field converted to double (was "'.$old.'" and now its "'.$json_arr[$key]->price.'")');
			$erros[] = $error;
		}
		if ( ( !is_float($item->special_price+0) && !empty($item->special_price) ) ){
			$old = $item->special_price;
			$json_arr[$key]->special_price = number_format($old,2,'.','');
			$error = logErrorValidate(0,'special_price',$item->sku, 'Field converted to double (was "'.$old.'" and now its "'.$json_arr[$key]->special_price.'")');
			$erros[] = $error;
		}
		if (!preg_match("~^(?:f|ht)tps?://~i", $item->product_image_url)) {
			$old = $item->product_image_url;
        	$json_arr[$key]->product_image_url = "http://" . $item->product_image_url;
        	$error = logErrorValidate(0,'special_price',$item->sku, 'Added http protocol to url (was "'.$old.'" and now its "'.$json_arr[$key]->product_image_url.'")');
			$erros[] = $error;
    	}		
	}

	$newJson = json_encode($json_arr);
	$ret = new stdClass;
	$ret->valid = $valid;
	$ret->errors = $erros;
	$ret->newJson = $newJson;
	$jsonRet = json_encode($ret);
	print_r($jsonRet);	
}
