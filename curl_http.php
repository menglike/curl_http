<?php
# 底层使用了CURL
# 简化了初学者对CURL函数使用
# 在php单进程模式下最佳
# 可以在cli或者fpm模式下运行
/**
*  $url  string 请求url
*  $type string 请求类型 get post
*  $post  mixed post请求参数
*  $resType  mixed 返回类型
*  $https  string http https
*  $head   array  
*  $referer  string  请求来源
*/
/*
get: http://www.imooc.com
get: https://www.imooc.com
post: http://www.imooc.com  username=test
post: https://www.imooc.com username=test
*/
function curl_http($url, $type='get', $post='',$resType='json',$https='http',$head='',$referer='') {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL ,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	if($type === 'post'){
		curl_setopt($ch, CURLOPT_POST , 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS , $post);
	}	

	#支持自定义header
	if(!empty($head)){
		curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
	}
	
	#支持https
	if($https ===  'https'){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	}

	#支持自定义请求来源
	if($referer != '')
	curl_setopt($ch, CURLOPT_REFERER, $referer);   //构造来路

	$output = curl_exec( $ch );
	curl_close( $ch );
	if( curl_errno( $ch ) )  var_dump( curl_error( $ch ) );


	#支持自定义返回类型
	if($resType==='json') 
		//返回数组
		$obj = json_decode( $output ,true);
	elseif($resType === 'xml')
		//返回数组
		$obj = json_decode( json_encode(simplexml_load_string( $output )),true);
	else
		//返回html
		$obj = $output;
	return $obj;
}