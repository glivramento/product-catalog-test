<?php

include('lib/httpful.phar');
include('lib/lib.php');

$json = file_get_contents('php://input');

//como a funcao é dentro do servidor, seria mais eficiente simplesmente chmamar a função, mas aproveitei aqui pra testar a chamada ao ws
$r = \Httpful\Request::post('pousadaadanegri.com.br/gfg/validator.php')->sendsJson()->body($json)->send();
$ret = json_decode($r->body);
if ($ret->valid == 1) {
	$filter = filterJsonFields($json);

	print_r($filter);
}
else {
	print_r($r->body);
}