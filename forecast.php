<?php

	/* Setting */
	$api_key = ""; // api_key
	/* Setting */

	function conventnumber($number){

	if(substr_count($number, "0") == 4):
	$number = str_replace("0","",$number);
	$unit = "만";
	endif;

	return $number.$unit;

	}

	function forecast_start($api_key, $type, $by_line){

	if($api_key && $type && $by_line): else: exit('누락된 정보가 있습니다.'); endif; // api_key && type end

	$url = "http://data.ex.co.kr/exopenapi/safeDriving/forecast?serviceKey=".$api_key."&type=".$type;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);

	$xml = simplexml_load_string($response);

		foreach($xml->list as $data_content){ // foreach start

		// 교통량

		$cjunkook = conventnumber($data_content->cjunkook); // 전국교통량
		$cjibangDir = conventnumber($data_content->cjibangDir); // 지방방향 교통량
		$cseoulDir = conventnumber($data_content->cseoulDir); // 서울방향 교통량

		// 서울 ~ 지방

		$csudj = $data_content->csudj; $csudj_1 = str_replace(":","",$csudj); $csudj_2 = str_replace(":","시간 ", $csudj)."분"; // 서울~대전
		$csudg = $data_content->csudg; $csudg_1 = str_replace(":","",$csudg); $csudg_2 = str_replace(":","시간 ", $csudg)."분";  // 서울~대구
		$csuus = $data_content->csuus; $csuus_1 = str_replace(":","",$csuus); $csuus_2 = str_replace(":","시간 ", $csuus)."분";  // 서울~울산
		$csubs = $data_content->csubs; $csubs_1 = str_replace(":","",$csubs); $csubs_2 = str_replace(":","시간 ", $csubs)."분";  // 서울~부산
		$csugj = $data_content->csugj; $csugj_1 = str_replace(":","",$csugj); $csugj_2 = str_replace(":","시간 ", $csugj)."분";  // 서울~광주
		$csump = $data_content->csump; $csump_1 = str_replace(":","",$csump); $csump_2 = str_replace(":","시간 ", $csump)."분";  // 서울~목포
		$csukr = $data_content->csukr; $csukr_1 = str_replace(":","",$csukr); $csukr_2 = str_replace(":","시간 ", $csukr)."분";  // 서울~강릉

		// 지방 ~ 서울

		$cdjsu = $data_content->cdjsu; $cdjsu_1 = str_replace(":","",$cdjsu); $cdjsu_2 = str_replace(":","시간 ", $cdjsu)."분";  // 대전~서울
		$cdgsu = $data_content->cdgsu; $cdgsu_1 = str_replace(":","",$cdgsu); $cdgsu_2 = str_replace(":","시간 ", $cdgsu)."분";  // 대구~서울
		$cussu = $data_content->cussu; $cussu_1 = str_replace(":","",$cussu); $cussu_2 = str_replace(":","시간 ", $cussu)."분";  // 울산~서울
		$cbssu = $data_content->cbssu; $cbssu_1 = str_replace(":","",$cbssu); $cbssu_2 = str_replace(":","시간 ", $cbssu)."분";  // 부산~서울
		$cgjsu = $data_content->cgjsu; $cgjsu_1 = str_replace(":","",$cgjsu); $cgjsu_2 = str_replace(":","시간 ", $cgjsu)."분";  // 광주~서울
		$cmpsu = $data_content->cmpsu; $cmpsu_1 = str_replace(":","",$cmpsu); $cmpsu_2 = str_replace(":","시간 ", $cmpsu)."분";  // 목포~서울
		$ckrsu = $data_content->ckrsu; $ckrsu_1 = str_replace(":","",$ckrsu); $ckrsu_2 = str_replace(":","시간 ", $ckrsu)."분";  // 강릉~서울

		$gisa_subject_array_seoul = array(
		array($csudj_1."_a"), array($csudg_1."_b"), array($csuus_1."_c"), array($csubs_1."_d"),
		array($csugj_1."_e"), array($csump_1."_f"), array($csukr_1."_g")
		);

		rsort($gisa_subject_array_seoul); // a = 서울~대전, b = 서울~대구, c = 서울~울산, d = 서울~부산, e = 서울~광주, f = 서울~목포, g = 서울~강릉

		if(strstr($gisa_subject_array_seoul[0][0],"a")){
		$gisa_subject_data = "서울→대전 ".$csudj_2;
		}elseif(strstr($gisa_subject_array_seoul[0][0],"b")){
		$gisa_subject_data = "서울→대구 ".$csudg_2;
		}elseif(strstr($gisa_subject_array_seoul[0][0],"c")){
		$gisa_subject_data = "서울→울산 ".$csuus_2;
		}elseif(strstr($gisa_subject_array_seoul[0][0],"d")){
		$gisa_subject_data = "서울→부산 ".$csubs_2;
		}elseif(strstr($gisa_subject_array_seoul[0][0],"e")){
		$gisa_subject_data = "서울→광주 ".$csugj_2;
		}elseif(strstr($gisa_subject_array_seoul[0][0],"f")){
		$gisa_subject_data = "서울→목포 ".$csump_2;
		}elseif(strstr($gisa_subject_array_seoul[0][0],"g")){
		$gisa_subject_data = "서울→강릉 ".$csukr_2;
		}

		########################

		$gisa_subject_array_locality = array(
		array($cdjsu_1."_a"), array($cdgsu_1."_b"), array($cussu_1."_c"), array($cbssu_1."_d"),
		array($cgjsu_1."_e"), array($cmpsu_1."_f"), array($ckrsu_1."_g")
		);

		rsort($gisa_subject_array_locality); // a = 대전~서울, b = 대구~서울, c = 울산~서울, d = 부산~서울, e = 광주~서울, f = 목포~서울, g = 강릉~서울

		if(strstr($gisa_subject_array_locality[0][0],"a")){
		$gisa_subject_data_2 = "대전→서울 ".$cdjsu_2;
		}elseif(strstr($gisa_subject_array_locality[0][0],"b")){
		$gisa_subject_data_2 = "대구→서울 ".$cdgsu_2;
		}elseif(strstr($gisa_subject_array_locality[0][0],"c")){
		$gisa_subject_data_2 = "울산→서울 ".$cussu_2;
		}elseif(strstr($gisa_subject_array_locality[0][0],"d")){
		$gisa_subject_data_2 = "부산→서울 ".$cbssu_2;
		}elseif(strstr($gisa_subject_array_locality[0][0],"e")){
		$gisa_subject_data_2 = "광주→서울 ".$cgjsu_2;
		}elseif(strstr($gisa_subject_array_locality[0][0],"f")){
		$gisa_subject_data_2 = "목포→서울 ".$cmpsu_2;
		}elseif(strstr($gisa_subject_array_locality[0][0],"g")){
		$gisa_subject_data_2 = "강릉→서울 ".$ckrsu_2;
		}

		$date = date("Y-m-d H:i:s"); $today = date("j"); $today_time = date("A"); $today_t_time = date("g");
		if($today_time == 'AM'): $today_time_text = "오전"; elseif($today_time == 'PM'): $today_time_text = "오후"; endif;

		/* 기사 제목 */

		$gisa_subject = "[로봇저널리즘, {$today_time} {$today_t_time}시 고속도로] 전국교통량 {$cjunkook}대 … {$gisa_subject_data}, {$gisa_subject_data_2} 소요";

		/* 기사 제목 */

		/* 기사 내용 */

		$gisa_content .= "<p>[로봇저널={$by_line} 기자] {$today}일 {$today_time_text} {$today_t_time}시 기준, 서울에서 대전까지 {$csudj_2}, △ 서울~대구 {$csudg_2}, △ 서울~울산 {$csuus_2}, △ 서울~부산 {$csubs_2}이 소요될 것으로 보인다.</p>";
		$gisa_content .= "<p>또한 서울에서 광주까지 소요시간은 {$csugj_2}, △ 서울~목포 {$csump_2}, △ 서울~강릉 {$csukr_2}으로 예상된다.</p>";
		$gisa_content .= "<p>반면, 대전에서 서울까지 {$cdjsu_2}, △ 대구~서울 {$cdgsu_2}, △ 울산~서울 {$cussu_2}, △ 부산~서울 {$cbssu_2}, △ 광주~서울 {$cgjsu_2}, △ 목포~서울 {$cmpsu_2}, △ 강릉~서울 {$ckrsu_2}이 소요될 것으로 전망됐다.</p>";
		$gisa_content .= "<p>한편, {$today}일 {$today_time_text} {$today_t_time}시 현재 전국교통량은 {$cjunkook}대이며 서울방향 교통량은 {$cseoulDir}대, 지방방향 교통량은 {$cjibangDir}대다.</p>";
		$gisa_content .= '<p>“본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇저널리즘입니다.”</p>';
		$gisa_content .= "<p>master@rbjn.kr / {$by_line} 기자</p>";

		/* 기사 내용 */

		/* DB 입력 시작 */


		/* DB 입력 끝 */


		} // foreach end

	} // forecast_start end

	/* Start */

	echo forecast_start($api_key, "xml", "Forecast"); // api_key, type


?>