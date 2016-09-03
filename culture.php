<?php

	/*
	
	제작자 : 로봇저널 (www.rbjn.kr)
	License : MIT License
	
	*/

	$setting_day = date("Ymd");
	$ServiceKey = ""; // ServiceKey
	$xml_file_url = "http://www.culture.go.kr/openapi/rest/publicperformancedisplays/period?ServiceKey=".$ServiceKey."&from={$setting_day}&to={$setting_day}&numOfRows=999&pageSize=999&pageNo=1&startPage=1&rows=20";

	$ch2 = curl_init();

	curl_setopt($ch2, CURLOPT_URL, $xml_file_url);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch2, CURLOPT_HEADER, FALSE);
	curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'GET');
	$xml_file_name = curl_exec($ch2);
	curl_close($ch2);

	$xml = simplexml_load_string($xml_file_name);

	$count = $xml->msgBody->totalCount;

	/* start */

	$today_date = date("j");

	$content = "<p>[로봇저널=Exhibition 기자] {$today_date}일, 다채로운 공연과 전시회가 개최된다. 자세한 내용을 알아보자.</p>";

	/* start */

	for($i=0; $i<$count; $i++){ // for start

		$seq = $xml->msgBody->perforList[$i]->seq;
		$title = $xml->msgBody->perforList[$i]->title;
		$place = $xml->msgBody->perforList[$i]->place;
		$area = $xml->msgBody->perforList[$i]->area;

		$title = addslashes($title);

		$thumbnail = $xml->msgBody->perforList[$i]->thumbnail; // Img Url

		$get = @getimagesize($thumbnail);
		$get_width = $get['3']; 
		
		if($get): // 이미지 정보가 있을 때만 작동

			if($get_width): $get_width_result = $get_width; else: $get_width_result = "width=200px height=200px"; endif;

			$startDate = date("n월 j일", strtotime($xml->msgBody->perforList[$i]->startDate));
			$endDate = date("n월 j일", strtotime($xml->msgBody->perforList[$i]->endDate));

			$perface = ""; $date_message = ""; $open = "개최된다."; $end_message = "다."; $e_start_message = "자세한 내용"; $e_end_message = "연락하면 된다.";

			if($i == 2): $perface = "그리고 "; $open = "관람할 수 있다."; $date_message = "에는"; $end_message = "이며, "; $e_start_message = "자세한 사항"; $e_end_message = "문의하면 된다."; endif;
			if($i == 4): $perface = "또한 "; $open = "만나볼 수 있다."; $e_start_message = "자세한 사항"; endif;
			if($i == 5): $perface = "더불어, "; $open = "열린다."; $end_message = ","; $e_start_message = "궁금한 사항"; $e_end_message = "문의하면 자세한 안내를 받을 수 있다."; endif;
			if($i == 7): $perface = "아울러 "; $open = "만나볼 수 있다."; $date_message = "에는"; $e_start_message = "궁금한 점"; $e_end_message = "문의하면 자세한 안내를 받을 수 있다."; endif;
			if($i == 9): $perface = "한편, "; $open = "관람할 수 있다."; $e_start_message = "자세한 사항"; $end_message = "이다."; endif;

			$content .= "<p><img src={$thumbnail} {$get_width_result} border=0></p><p>(▲ 자료제공 = 한국문화정보원)";

			if($startDate == $endDate){ // 시작일과 마감일이 동일할 경우 
			$content .= "<p>{$perface}"."&#039;{$title}&#039; 행사는 {$area}{$place}에서 {$open} 시작일과 마감일은 {$startDate}까지{$end_message}".""; 
			}else{ 
			$content .= "<p>{$perface}"."&#039;{$title}&#039; 행사는 {$area}{$place}에서 {$open} 시작일은 {$startDate}부터 마감일은 {$endDate}까지{$end_message}".""; 
			}

			/* 세부 설정 로드 */

			$data_file_xml_url = "http://www.culture.go.kr/openapi/rest/publicperformancedisplays/d/?seq=".$seq."&ServiceKey=".$ServiceKey;

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $data_file_xml_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$data_file_xml = curl_exec($ch);
			curl_close($ch);

			$data_xml = simplexml_load_string($data_file_xml);

			$phone = trim($data_xml->msgBody->perforInfo->phone); // 연락처
			$placeUrl = trim($data_xml->msgBody->perforInfo->url); // 홈페이지 URL

			$placeAddr = $data_xml->msgBody->perforInfo->placeAddr; // 공연장 주소

			$per_price = trim($data_xml->msgBody->perforInfo->price);

			$per_price = str_replace("티켓 :", "", $per_price); 

			if($per_price == '홈페이지 참조'){
			$content .= " 티켓 가격은 홈페이지를 참조하자.</p>"; 
			}elseif($per_price == '-'){
			$content .= "";
			}else{
			$content .= " 티켓 가격은 ".$per_price."이다.</p>"; 
			}

			if($placeUrl):
			$content .= "<p>{$e_start_message}은 (<a href={$placeUrl} target=_blank>{$placeUrl}</a>) 를 참고하거나 {$phone} 로 {$e_end_message}</p>";
			else:
			$content .= "<p>{$e_start_message}은 {$phone} 로 {$e_end_message}</p>";
			endif; 

		/* 세부 설정 로드 */ 

		endif;

	} // for end

	$content .= '<p>'."본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇저널리즘입니다.".'</p>';
	$content .= '<p>master@rbjn.kr / Exhibition 기자 / 자료제공 : 한국문화정보원</p>';

	$gisa_subject = "[로봇저널리즘] 오늘({$today_date}일) 공연·전시회 행사 … {$title} 등 개최";
	$gisa_content = addslashes($content);

	$gisa_datetime_ymdhis = date("Y-m-d H:00");
	$gisa_url_time = date("Ymdh0000").mt_rand(1,1000); 

	$gisa_subject2 = $gisa_url_time;

	/* DB 입력 시작 */

	/* DB 입력 끝 */

?>