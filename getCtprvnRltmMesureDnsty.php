<?php
	
	function getCtprvnRltmMesureDnsty($api_key, $sidoName, $numOfRows){

	if($api_key && $sidoName && $numOfRows): else: exit('누락된 정보가 있습니다.'); endif;

	$url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty";

	$queryParams = "?sidoName=".urlencode($sidoName);
	$queryParams .= "&pageNo=1&numOfRows=".$numOfRows;
	$queryParams .= "&ServiceKey=".$api_key;
	$queryParams .= "&ver=1.2";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);

	$xml = simplexml_load_string($response);

	/* Table Source */

	$content .= "

	<table style='border: none; max-width:100% !important; border-collapse: collapse;'>";
	$content .= "<tbody>";
	$content .= "<tr>";
	$content .= "<td style='color: rgb(204, 61, 61); height:30px;border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>측정소 명</td>";
	$content .= "<td style='color: rgb(204, 61, 61); height:30px;border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>측정망 정보</td>";
	$content .= "<td style='color: rgb(204, 61, 61); height:30px;border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>측정일시</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>아황산가스 농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>일산화탄소 농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>오존 농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>이산화질소 농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>미세먼지(PM10) 농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>미세먼지(PM10) 24시간예측이동농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>미세먼지(PM2.5) 농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>미세먼지(PM2.5) 24시간예측이동농도</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>통합대기환경수치</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>통합대기환경지수</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>아황산가스 지수</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>일산화탄소 지수</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>오존 지수</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>이산화질소 지수</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>미세먼지(PM10) 지수</td>";
	$content .= "<td style='height:30px; color: rgb(204, 61, 61);border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>미세먼지(PM2.5) 지수</td>";
	$content .= "</tr>";

	/* Table Source */

	foreach($xml->body->items->item as $getCtprvnRltmMesureDnsty_data){ // getCtprvnRltmMesureDnsty_data start

	$gisa_data_d = date("ymdhis",strtotime($getCtprvnRltmMesureDnsty_data->dataTime.$getCtprvnRltmMesureDnsty_data->stationName)).mt_rand(1,1000);

	$stationName_gisa_data_d = $getCtprvnRltmMesureDnsty_data->stationName; // 측정소명
	if($stationName_gisa_data_d): $stationName_result = $stationName_gisa_data_d; endif;
	$mangName_gisa_data_d = $getCtprvnRltmMesureDnsty_data->mangName; // 측정망 정보
	if($mangName_gisa_data_d): $mangName_result = $mangName_gisa_data_d; endif;
	$dataTime_gisa_data_d = $getCtprvnRltmMesureDnsty_data->dataTime; // 측정일시
	if($dataTime_gisa_data_d): $dataTime_result = $dataTime_gisa_data_d; endif;
	$so2Value_gisa_data_d = $getCtprvnRltmMesureDnsty_data->so2Value; // 아황산가스 농도
	if($so2Value_gisa_data_d): $so2Value_result = $so2Value_gisa_data_d; endif;
	$coValue_gisa_data_d = $getCtprvnRltmMesureDnsty_data->coValue; // 일산화탄소 농도
	if($coValue_gisa_data_d): $coValue_result = $coValue_gisa_data_d; endif;
	$o3Value_gisa_data_d = $getCtprvnRltmMesureDnsty_data->o3Value; // 오존 농도
	if($o3Value_gisa_data_d): $o3Value_result = $o3Value_gisa_data_d; endif;
	$no2Value_gisa_data_d = $getCtprvnRltmMesureDnsty_data->no2Value; // 이산화질소 농도
	if($no2Value_gisa_data_d): $no2Value_result = $no2Value_gisa_data_d; endif;
	$pm10Value_gisa_data_d = $getCtprvnRltmMesureDnsty_data->pm10Value; // 미세먼지(PM10) 농도
	if($pm10Value_gisa_data_d): $pm10Value_result_gisa_data_d = $pm10Value_gisa_data_d; endif;
	$pm10Value24_gisa_data_d = $getCtprvnRltmMesureDnsty_data->pm10Value24; // 미세먼지(PM10) 24시간예측이동농도
	if($pm10Value24_gisa_data_d): $pm10Value24_result = $pm10Value24_gisa_data_d; endif;
	$pm25Value_gisa_data_d = $getCtprvnRltmMesureDnsty_data->pm25Value; // 미세먼지(PM2.5) 농도
	if($pm25Value_gisa_data_d): $pm25Value_result = $pm25Value_gisa_data_d; endif;
	$pm25Value24_gisa_data_d = $getCtprvnRltmMesureDnsty_data->pm25Value24; // 미세먼지(PM2.5) 24시간예측이동농도
	if($pm25Value24_gisa_data_d): $pm25Value24_result = $pm25Value24_gisa_data_d; endif;
	$khaiValue_gisa_data_d = $getCtprvnRltmMesureDnsty_data->khaiValue; // 통합대기환경수치
	if($khaiValue_gisa_data_d): $khaiValue_result = $khaiValue_gisa_data_d; endif;
	$khaiGrade_gisa_data_d = $getCtprvnRltmMesureDnsty_data->khaiGrade; // 통합대기환경지수
	if($khaiGrade_gisa_data_d): $khaiGrade_result = $khaiGrade_gisa_data_d; endif;
	$so2Grade_gisa_data_d = $getCtprvnRltmMesureDnsty_data->so2Grade; // 아황산가스 지수
	if($so2Grade_gisa_data_d): $so2Grade_result = $so2Grade_gisa_data_d; endif;
	$coGrade_gisa_data_d = $getCtprvnRltmMesureDnsty_data->coGrade; // 일산화탄소 지수
	if($coGrade_gisa_data_d): $coGrade_result = $coGrade_gisa_data_d; endif;
	$o3Grade_gisa_data_d = $getCtprvnRltmMesureDnsty_data->o3Grade; // 오존 지수
	if($o3Grade_gisa_data_d): $o3Grade_result = $o3Grade_gisa_data_d; endif;
	$no2Grade_gisa_data_d = $getCtprvnRltmMesureDnsty_data->no2Grade; // 이산화질소 지수
	if($no2Grade_gisa_data_d): $no2Grade_result = $no2Grade_gisa_data_d; endif;
	$pm10Grade_gisa_data_d = $getCtprvnRltmMesureDnsty_data->pm10Grade; // 미세먼지(PM10) 지수
	if($pm10Grade_gisa_data_d): $pm10Grade_result = $pm10Grade_gisa_data_d; endif;
	$pm25Grade = $getCtprvnRltmMesureDnsty_data->pm25Grade; // 미세먼지(PM2.5) 지수
	if($pm25Grade): $pm25Grade_result = $pm25Grade; endif;

	$content .= "<tr>";
	
	$content .= "<td style='height:30px;border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc; border-top:1px solid #ccc; text-align:center;'>".$stationName_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$mangName_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$dataTime_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$so2Value_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$coValue_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$o3Value_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$no2Value_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$pm10Value_result_gisa_data_d."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$pm10Value24_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$pm25Value_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$pm25Value24_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$khaiValue_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$khaiGrade_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$so2Grade_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$coGrade_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$o3Grade_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$no2Grade_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$pm10Grade_result."</td>";
	$content .= "<td style='border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:center;'>".$pm25Grade_result."</td>";
	$content .= "</tr>";

		if($stationName_result && $mangName_result && $dataTime_result):

		if(strstr($so2Value_result,'-')){ $so2Value_result_chk = 0; }elseif($so2Value_result){ $so2Value_result_chk = 1; }else{ $so2Value_result_chk = 0; }
		if(strstr($coValue_result,'-')){ $coValue_result_chk = 0; }elseif($coValue_result){ $coValue_result_chk = 1;  }else{ $coValue_result_chk = 0; }
		if(strstr($o3Value_result,'-')){ $o3Value_result_chk = 0; }elseif($o3Value_result){ $o3Value_result_chk = 1;  }else{ $o3Value_result_chk = 0; }
		if(strstr($no2Value_result,'-')){ $no2Value_result_chk = 0; }elseif($no2Value_result){ $no2Value_result_chk = 1; }else{ $no2Value_result_chk = 0; }
		if(strstr($pm10Value_result_gisa_data_d,'-')){ $pm10Value_result_chk = 0; }elseif($pm10Value_result_gisa_data_d){ $pm10Value_result_chk = 1; }else{ $pm10Value_result_chk = 0; }
		if(strstr($pm10Value24_result,'-')){ $pm10Value24_result_chk = 0; }elseif($pm10Value24_result){ $pm10Value24_result_chk = 1; }else{ $pm10Value24_result_chk = 0; }
		if(strstr($pm25Value_result,'-')){ $pm25Value_result_chk = 0; }elseif($pm25Value_result){ $pm25Value_result_chk = 1; }else{ $pm25Value_result_chk = 0; }
		if(strstr($pm25Value24_result,'-')){ $pm25Value24_result_chk = 0; }elseif($pm25Value24_result){ $pm25Value24_result_chk = 1; }else{ $pm25Value24_result_chk = 0; }
		if(strstr($khaiValue_result,'-')){ $khaiValue_result_chk = 0; }elseif($khaiValue_result){ $khaiValue_result_chk = 1; }else{ $khaiValue_result_chk = 0; }
		if(strstr($khaiGrade_result,'-')){ $khaiGrade_result_chk = 0; }elseif($khaiGrade_result){ $khaiGrade_result_chk = 1; }else{ $khaiGrade_result_chk = 0; }
		if(strstr($so2Grade_result,'-')){ $so2Grade_result_chk = 0; }elseif($so2Grade_result){ $so2Grade_result_chk = 1; }else{ $so2Grade_result_chk = 0; }
		if(strstr($coGrade_result,'-')){ $coGrade_result_chk = 0; }elseif($coGrade_result){ $coGrade_result_chk = 1; }else{ $coGrade_result_chk = 0; }
		if(strstr($o3Grade_result,'-')){ $o3Grade_result_chk = 0; }elseif($o3Grade_result){ $o3Grade_result_chk = 1; }else{ $o3Grade_result_chk = 0; }
		if(strstr($no2Grade_result,'-')){ $no2Grade_result_chk = 0; }elseif($no2Grade_result){ $no2Grade_result_chk = 1; }else{ $no2Grade_result_chk = 0; }
		if(strstr($pm10Grade_result,'-')){ $pm10Grade_result_chk = 0; }elseif($pm10Grade_result){ $pm10Grade_result_chk = 1; }else{ $pm10Grade_result_chk = 0; }
		if(strstr($pm25Grade_result,'-')){ $pm25Grade_result_chk = 0; }elseif($pm25Grade_result){ $pm25Grade_result_chk = 1; }else{ $pm25Grade_result_chk = 0; }

	endif; // $stationName_result && $mangName_result && $dataTime_result

		if($stationName_result && $mangName_result && $dataTime_result && $so2Value_result_chk == 1){
			$content_text .= "{$stationName_result} 측정망 정보는 '{$mangName_result}' 이며,";
		}

		switch($so2Value_result){ case($so2Value_result_chk == 1): $content_text .= ' ▲ 아황산가스 농도 '.$so2Value_result."ppm"; break; case($so2Value_result_chk == 0): $content_text .= ''; break; default: $content_text .= ''; }
		switch($coValue_result){ case($coValue_result_chk == 1): $content_text .= ' ▲ 일산화탄소 농도 '.$coValue_result."ppm"; break; case($coValue_result_chk == 0): $content_text .= ''; break; default: $content_text .= ''; }
		switch($o3Value_result){ case($o3Value_result_chk == 1): $content_text .= ' ▲ 오존 농도 '.$o3Value_result."ppm"; break; case($o3Value_result_chk == 0): $content_text .= ''; break; default: $content_text .= ''; }
		switch($no2Value_result){ case($no2Value_result_chk == 1): $content_text .= ' ▲ 이산화질소 농도 '.$no2Value_result."ppm"; break; case($no2Value_result_chk == 0): $content_text .= ''; break; default: $content_text .= ''; }
		switch($pm10Value_result_gisa_data_d){ case($pm10Value_result_chk == 1): $content_text .= ' ▲ 미세먼지(PM10) 농도 '.$pm10Value_result_gisa_data_d."㎍/㎥"; break; case($pm10Value_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($pm10Value24_result){ case($pm10Value24_result_chk == 1): $content_text .= ' ▲ 미세먼지(PM10) 24시간예측이동농도 '.$pm10Value24_result."㎍/㎥"; break; case($pm10Value24_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($pm25Value_result){ case($pm25Value_result_chk == 1): $content_text .= ' ▲ 미세먼지(PM2.5) 농도 '.$pm25Value_result."㎍/㎥"; break; case($pm25Value_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($pm25Value24_result){ case($pm25Value24_result_chk == 1): $content_text .= ' ▲ 미세먼지(PM2.5) 24시간 예측이동농도 '.$pm25Value24_result."㎍/㎥"; break; case($pm25Value24_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }

		switch($khaiValue_result){ case($khaiValue_result_chk == 1): $content_text .= ' ▲ 통합대기 환경수치 '.$khaiValue_result."㎍/㎥"; break; case($khaiValue_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($khaiGrade_result){ case($khaiGrade_result_chk == 1): $content_text .= ' ▲ 통합대기 환경지수 '.$khaiGrade_result."㎍/㎥"; break; case($khaiGrade_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }

		switch($so2Grade_result){ case($so2Grade_result_chk == 1): $content_text .= ' ▲ 아황산가스 지수 '.$so2Grade_result."㎍/㎥"; break; case($so2Grade_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($coGrade_result){ case($coGrade_result_chk == 1): $content_text .= ' ▲ 일산화탄소 지수 '.$coGrade_result."㎍/㎥"; break; case($coGrade_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($o3Grade_result){ case($o3Grade_result_chk == 1): $content_text .= ' ▲ 오존 지수 '.$o3Grade_result."㎍/㎥"; break; case($o3Grade_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($no2Grade_result){ case($no2Grade_result_chk == 1): $content_text .= ' ▲ 이산화질소 지수 '.$no2Grade_result."㎍/㎥"; break; case($no2Grade_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }

		switch($pm10Grade_result){ case($pm10Grade_result_chk == 1): $content_text .= ' ▲ 미세먼지(PM10) 지수 '.$pm10Grade_result."㎍/㎥"; break; case($pm10Grade_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }
		switch($pm25Grade_result){ case($pm25Grade_result_chk == 1): $content_text .= ' ▲ 미세먼지(PM2.5) 지수 '.$pm25Grade_result."㎍/㎥"; break; case($pm25Grade_result_chk == 0): $content_text .= ''; break;default: $content_text .= ''; }

		if($stationName_result && $mangName_result && $dataTime_result && $so2Value_result_chk == 1){
		$content_text .= ' 다.';
		}

	} // foreach end

	$content .= "<tr>";

	$content .= "<td style='height:30px; color: rgb(0, 85, 255); border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc; text-align:right;' colspan=19>"."데이터 출처 : 한국환경공단&nbsp;"."</td>";
	$content .= "</tr>";
	$content .= "</tbody></table>";

	return $content.$content_text;

	} // getCtprvnRltmMesureDnsty end

	
	function start_function($gisa_byline, $number, $gisa_byline_end){ // 로봇저널리즘 작동 명령 소스 코드

		if($gisa_byline && $number && $gisa_byline_end){

		// 1 = 호남권, 2 = 영남권, 3 = 충청권, 4 = 수도권, 5 = 강원·제주

			if($number == 1){ // 호남권
				
				$content2 .= "{$gisa_byline} 먼저, '광주' 지역 실시간 측정정보(대기오염정보)는 다음과 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','광주',10);
				$content2 .= "이어 '전북' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','전북',17);
				$content2 .= "또한 '전남' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','전남',18);
				
				@database_insert($content2, $gisa_byline, $number); // db insert
			
			}elseif($number == 2){ // 영남권
				
				$content2 .= "{$gisa_byline} '대구' 지역 실시간 측정정보는 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','대구',14);
				$content2 .= "다음은 '부산' 지역 실시간 측정정보를 알아보자.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','부산',22);
				$content2 .= "'울산' 지역 실시간 측정정보는 다음과 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','울산',15);
				$content2 .= "또한 '경북' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','경북',19);
				$content2 .= "끝으로 '경남' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','경남',24);
				
				@database_insert($content2, $gisa_byline, $number); // db insert
			
			}elseif($number == 3){ // 충청권
			
				$content2 .= "{$gisa_byline} '대전' 지역 실시간 측정정보는 다음과 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','대전',10);
				$content2 .= "'충북' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','충북',12);
				$content2 .= "'충남' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','충남',11);
			
				@database_insert($content2, $gisa_byline, $number); // db insert
			
			}elseif($number == 4){ // 수도권
		
				$content2 = "{$gisa_byline} 먼저, '서울' 지역 실시간 측정정보를 알아보자.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','서울',40);
				$content2 .= "다음은 '인천' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','인천',22);
				$content2 .= "또한 '경기' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','경기',82);
			
				@database_insert($content2, $gisa_byline, $number); // db insert
			
			}elseif($number == 5){ // 강원·제주
			
				$content2 .= "{$gisa_byline} '강원' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','강원',11);
				$content2 .= "'제주' 지역 실시간 측정정보에 대해 알아보면 아래 표와 같다.";
				$content2 .= getCtprvnRltmMesureDnsty('API_KEY','제주',5);
				
				@database_insert($content2, $gisa_byline, $number); // db insert
			
			}else{ // numberelse
				exit('데이터가 누락되었습니다.');
			} // number end

		}else{ // $gisa_byline && $gisa_byline_end && $number else
			exit('데이터가 누락되었습니다.');
		} // $gisa_byline && $gisa_byline_end && $number end

	}
	
	
	
	function database_insert($content2, $gisa_byline, $number){ // 데이터베이스 입력 소스 코드

		if($content2 && $gisa_byline && $number){ // $content2 && $gisa_byline && $number

		// 기사 내용 = content, 기사 바이라인 = gisa_byline, number = 1→호남권, 2→영남권, 3→충청권, 4→수도권, 5→강원·제주

		$gisa_datetime_ymdhis = date("Y-m-d H:00");
		$number_date = date("H:00");

		$gisa_url_time = date("Ymdh0000").$number;

		if($number == 1):
			$gisa_subject = "[로봇저널리즘] 실시간 대기오염정보 {$number_date} 호남권 - 로봇저널";
			elseif($number == 2):
			$gisa_subject = "[로봇저널리즘] 실시간 대기오염정보 {$number_date} 영남권 - 로봇저널";
			elseif($number == 3):
			$gisa_subject = "[로봇저널리즘] 실시간 대기오염정보 {$number_date} 충청권 - 로봇저널";
			elseif($number == 4):
			$gisa_subject = "[로봇저널리즘] 실시간 대기오염정보 {$number_date} 수도권 - 로봇저널";
			elseif($number == 5):
			$gisa_subject = "[로봇저널리즘] 실시간 대기오염정보 {$number_date} 강원·제주 - 로봇저널";
		endif;

		$gisa_subject2 = $gisa_url_time;
		/* DB 입력 부분 넣으면 됨 */

		$content2 = addslashes($content2);

		/* DB 입력 부분 넣으면 됨 */

		}else{
		exit('데이터가 누락되었습니다.');
		}

	} // database_insert end 

	 $gisa_byline = "[로봇저널=AirBot 기자] "; $gisa_byline_end = "AirBot 기자"; // 기사 바이라인 

	 start_function($gisa_byline, 1, $gisa_byline_end); // [작동 방법] ▲ 기사 바이라인, 지역, 기자명 1 = 호남권 2 = 영남권 3 = 충청권 4 = 수도권 5 = 강원·제주
	 
?>