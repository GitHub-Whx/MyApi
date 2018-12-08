<?php
include_once './Response.php';

$request_data = $_POST;
$key = '9278b4a8fef40d46b67837d5fc2f6ec6';

$Obj = new Response();

// 编码验证
if (!$Obj->validate_type($request_data['type'])) {
	$code = 404;
	$msg = 'encode type error';
	$data = $request_data;

	$response_data = $Obj->response_data($code, $msg, $data);
	// 默认以 json 格式返回原数据
	$result = $Obj->arrayFormat($response_data);
	echo $result;
	exit();
} else {
	// 签名验证
	if ($Obj->validate_sign($request_data, $key)) {
		$code = 200;
		$msg = 'success';
		$data = [		// 实际情况从数据库获取数据
				'id'	=> mt_rand(1,9999),
				'rand_code'	=> mt_rand(1, 9999),
			];
	} else{
		$code = 401;
		$msg = 'sign error';
		$data = [];
	}
}
$response_data = $Obj->response_data($code, $msg, $data);

$result = $Obj->arrayFormat($response_data,$request_data['type']);
echo $result;
