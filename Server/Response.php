<?php

class Response
{
	public function arrayFormat($data = [], $type = 'json') {
		if($type == 'json') {
			return $this->arrayToJson($data);
		} else {
			return $this->arrayToXml($data);
		}
	}

	private function arrayToJson($data) {
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	private function arrayToXml($data, $encoding = 'utf-8')  {
		$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
		$xml .= "<xml>";
	    $xml .= $this->xml_encode($data);
	    $xml .="</xml>";
	    return $xml;
	}

	private function xml_encode($data) {
		$xml = '';
		foreach ($data as $key=>$val){
		    if(is_array($val) || is_object($val)){
		    	$xml .="<" . $key . ">" . $this->xml_encode($val) . "</" . $key . ">";
		    }else{
		    	$xml .="<" . $key . ">" . $val . "</" . $key . ">";
		    }
	    }
	    return $xml;
	}


	// 签名验证
	public function validate_sign($data, $key) {
		// 提取请求的 sign
		$sign = $data['sign'];
		// 删除键值 sign
		unset($data['sign']);
		// 添加键值 key
		$data['key'] = $key;
		ksort($data);
		if ($sign != md5(http_build_query($data))) {
			return false;
		} else {
			return true;
		}
	}

	// 返回响应数据
	public function response_data($code, $msg, $data) {
		return [
			'code'	=> $code,
			'msg'	=> $msg,
			'data'	=> $data,
		];
	}

	public function validate_type($type) {
		$allow_type = ['json', 'xml'];
		if (!in_array($type, $allow_type) || $type == '' || $type == NULL) {
			return false;
		} else{
			return true;
		}

	}

}
