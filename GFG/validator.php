<?php

include('lib/lib.php');

$json = file_get_contents('php://input');

$validate = validateJsonFields($json);

print_r($validate);



?>