<?php
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	include_once './Request.php';

	$url = 'http://www.response.com';
	$type = 'xml';
	$key = '9278b4a8fef40d46b67837d5fc2f6ec6';	//实际应用中，在数据库中读取 key 值
	$request_data = [
		'type'	=> $type,
		'key'	=> $key,
		'data'	=> [
			'id'	=> mt_rand(1,9999),
			'code'	=> md5(mt_rand(1,9999)),
		],
		'time'	=> time(),
	];

	$Obj = new Request();
	// 发送请求
	$result = $Obj->curl($url, $request_data);

	var_dump($result);
