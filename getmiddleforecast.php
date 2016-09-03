<?php

	/*
	
	제작자 : 로봇저널 (www.rbjn.kr)
	License : MIT License
	
	*/
	
	global $api_key;
	 
	$api_key = ""; // utf8 encode, api key

	function getMiddleForecast($api_key, $stnId, $time){ // 기상전망
	 
	 if($api_key && $stnId && $time):
		 
		 if($time == '1'){ // time = '1' → 06:00, '2' = → 18:00
		 $today_time = date("Ymd")."0600";
		 }elseif($time == '2'){
		 $today_time = date("Ymd")."1800";
		 }else{
		 }
	 
	 $ch = curl_init();
	 $url = "http://newsky2.kma.go.kr/service/MiddleFrcstInfoService/getMiddleForecast";
	 $queryParams = '?'.urlencode('ServiceKey').'='.$api_key; /*Service Key*/
	 $queryParams .= '&'.urlencode('stnId').'='.urlencode($stnId);
	 $queryParams .= '&'.urlencode('tmFc').'='.urlencode($today_time);
	 
	 curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	 curl_setopt($ch, CURLOPT_HEADER, FALSE);
	 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	 $response = curl_exec($ch);
	 curl_close($ch);
	 
	 $load_string = simplexml_load_string($response);
	 return trim($load_string->body->items->item->wfSv);
	 
	 else:
	 
	 exit('누락된 정보가 있습니다.');
	 
	 endif;
	 
	} // function getMiddleForecast end
	 
	// 기상전망

	if(getMiddleForecast($api_key, 108, 1)): $content = "<p><b>전국 기상전망</b>에 대해 알아보면, ".getMiddleForecast($api_key, 108, 1)."</p>"; endif;
	 
	if(getMiddleForecast($api_key, 105, 1)): $content .= "<p>다음으로 <b>강원도 기상전망</b>에 대해 알아보겠습니다. ".getMiddleForecast($api_key, 105, 1)."</p>"; endif;

	if(getMiddleForecast($api_key, 109, 1)): $content .= "<p><b>서울, 인천, 경기도 기상전망</b>은 ".getMiddleForecast($api_key, 109, 1)."</p>"; endif;

	if(getMiddleForecast($api_key, 131, 1) && getMiddleForecast($api_key, 133,1)): $content .= "<p><b>충청북도 기상전망</b>은 ".getMiddleForecast($api_key, 131, 1)." <b>대전, 세종, 충청남도 기상전망</b>은 ".getMiddleForecast($api_key, 133, 1)."</p>"; endif;

	if(getMiddleForecast($api_key, 146, 1) && getMiddleForecast($api_key, 156, 1) && getMiddleForecast($api_key, 143, 1)): $content .= "<p><b>전라북도 기상전망</b>은 ".getMiddleForecast($api_key, 146, 1)." <b>광주, 전라남도 기상전망</b>은 ".getMiddleForecast($api_key, 156, 1)." <b>대구, 경상북도 기상전망</b>은 ".getMiddleForecast($api_key, 143, 1)."</p>"; endif;
	 
	if(getMiddleForecast($api_key, 159, 1)): $content .= "<p><b>부산, 울산, 경상남도 기상전망</b>은 ".getMiddleForecast($api_key, 159, 1)."</p>"; endif; 
	 
	if(getMiddleForecast($api_key, 184, 1)): $content .= "<p>또한, <b>제주도 기상전망</b>은 ".getMiddleForecast($api_key, 184, 1)."</p>"; endif;
	 
	$content .= '<p style=font-weight:bold;>"본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇 알고리즘입니다."'."</p>";
	 
	echo $content;
 
?>
