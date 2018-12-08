<?php

class Request {

    public function curl($url,$post_Date_Array = [])
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            trigger_error("对不起，请开启curl功能模块！", E_USER_ERROR);
        }

        //对空格进行转义
        $url = str_replace(' ','+',$url);
        //初始化一个新的会话，返回一个cURL句柄
        $ch = curl_init();
        //设置选项，包括URL
        $option = [
            CURLOPT_URL              => $url,    //需要获取的URL地址
            CURLOPT_RETURNTRANSFER   => 1,        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
            CURLOPT_HEADER           => 0,        //启用时会将头文件的信息作为数据流输出
            CURLOPT_TIMEOUT          => 3,        //设置cURL允许执行的最长秒数
            CURLOPT_POST             => 1,        //启用时会发送一个常规的POST请求
            CURLOPT_POSTFIELDS       => http_build_query($this->build_sign($post_Date_Array))    //多维数组http_bulid_query()处理 (数组里面带有签名信息)
        ];
        //为cURL传输会话批量设置选项
        curl_setopt_array ($ch,$option);
        //执行给定的cURL会话
        $output = curl_exec($ch);
        //返回最后一次cURL操作的错误号
        $errorCode = curl_errno($ch);
        //释放curl句柄
        curl_close($ch);
        if(0 !== $errorCode) {
            return false;
        }
        return $output;
    }

    // 生成签名
    private function build_sign($data) {
        ksort($data);   // 按键值排序 (不对二维数组键值排序)
        $sign = md5(http_build_query($data));
        unset($data['key']);    // 去除 key 值
        $data['sign'] = $sign;  // 带上 编码后的 sign 值
        return $data;

    /* $data 类似于：
        array(4) {
          "data"    => [
            "id"    => 1,
            "name"  => "卡哇伊",
          ]
          "time"=>  1544232453,
          "type"    => "xml",
          "sign"=>  "f69d7e0d0c26b2c1079be9a4fcd12500",
        }
     */

    }

    public function jsonToArray($json) {
        return json_decode($json);
    }

    public function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }
}
