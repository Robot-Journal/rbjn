<?php

	// DB 연결을 해줍니다.
	
	/*
	
		DB Host = localhost
		
		DB User(ID) = test
		
		DB Pass(Password) = 1234
		
		DB Name = test_board
		
		라고 할 때, 
		
		$conn = mysql_connect("localhost","test","1234");
		$db = mysql_select_db("test_board",$conn);
		
		이런식으로 입력해주시면 됩니다.
	
	*/
	// mysql DB Charset Setting.
	
	// 글자가 깨질 경우(인코딩) 아래 코드를 입력해주세요. mysql_query(" set names utf8 ");
	
	mysql_query(" set names utf8 "); // 이 코드를 입력하지 않을 경우, 프로그램(로봇기자) 실행 시 글자가 깨질 수 있습니다.
	
	if($conn && $db): // db 연결 성공 시 아무 메세지 출력 없이 다음 단계(기사 작성) 진행.
		
		// DB 연결 성공 시 특정 메세지를 출력하고 싶다면, 여기에 적어주세요.
		
		// (여기)
		
	else: // db 연결 실패할 경우 페이지 즉시 종료 및 메세지 출력
	
		// DB 연결(접속)이 실패할 경우 기사 작성 단계로 넘어가지 않고, 바로 프로그램(로봇기자) 작동이 중단됩니다.
		
		// 이는 불필요한 작업을 막기 위함인데요. 기사를 작성한다고 하더라도, DB 연결이 실패할 경우 작성된 기사가 DB 에 입력되지 않습니다. 이를 방지하기 위해 DB 연결 실패 시 바로 프로그램을 종료합니다.
		
		exit("DB 접속 에러");
	
	endif;
	
	// 사용법은 wordpress_database_insert($기사 제목, $기사 내용[본문], $DB 접속정보); 라고 적어주시면 사용할 수 있습니다.
	
	// 다만, 앞에서 DB 접속 여부를 체크하기에 별도로 DB 연결과정을 체크하지는 않습니다. 그리고, 중복 게시물(기사) 역시 체크하지 않습니다. 중복 체크가 필요할 경우, 데이터 INSERT(삽입) 시 특정 값을 삽입하고, 해당 값을 바탕으로 Select 하시면 될 것 같습니다.
	
	// select 후 존재 여부에 따라 pass 또는 insert 을 통해 기사를 DB 에 삽입하여 주시면 되겠습니다.
	
	function wordpress_database_insert($gisa_subject, $gisa_content, $conn){ // 사용법
		
			if($gisa_subject && $gisa_content && $conn): // 기사 제목, 기사 내용, DB 접속 정보가 모두 있을 때만 작동. 1개라도 데이터가 없으면 작동 안함
				
				$gisa_datetime_ymdhis = date("Y-m-d H:00"); // 기사 작성 날짜(일자), Ex:) 2016-01-01 03:00 
				
				$gisa_url_time = date("Ymdh0000").date("H00");
				
				// DB INSERT (중요), 아래 INSERT 소스 코드 입력 시 오탈자 여부 주의하여 입력해주세요. 아래 내용과 100% 동일하지 않을 경우 작동을 하지 않거나 오류가 발생할 수 있습니다.
				
				mysql_query(" insert into wp_posts (ID, post_author, post_name, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_title, post_status, comment_status, ping_status, guid) VALUES ('','1','$gisa_url_time','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_content','$gisa_subject','publish','closed','closed','')") or die(mysql_error()); 
				
				// ↑ 소스 끝에 보시면, mysql_error(); 부분이 있는데요. 
				
				// 이는 mysql_query(), 즉 쿼리 시도 시 정상적으로 처리되지 않고 에러가 발생했을 경우 해당 에러의 에러 메세지를 보여주는 기능입니다. 
				
				// 출력되는 에러메세지를 기반으로 원인을 분석하셔서 해결하시면 될 것 같습니다.
				
				// 하나 더 추가합니다. (부연 설명) 소스를 보시면, "insert into wp_posts" 라는 부분이 있습니다. 여기서 'wp_' 관련입니다.
				
				// 워드프레스 설치 시 별도 설정을 했을 경우 'wp_' 가 아닐 수도 있습니다. 그 땐 설정하신 값을 입력해주셔야 합니다. ex: 'wp2_'
				
				// 그러나 별도 설정 없이 진행 했을 경우, 그냥 'wp_' 로 입력해주시면 되겠습니다.
				
				$mysql_id = mysql_insert_id();
				
				$guid = "http://localhost/word/?p=".$mysql_id;
				
				// 만약, 운영 중인 사이트 주소가 'http://rbjn.kr' 이고, 워드프레스 설치 폴더(경로)가 'wordpress' 라면, guid 는 다음과 같이 입력하시면 됩니다.
				
				// $guid = "http://rbjn.kr/wordpress/?p=".$mysql_id;
				
				mysql_query(" update wp_posts set guid = '{$guid}' where ID = '{$mysql_id}' ");
				
				// ↑ 이 부분도 마찬가지입니다. 워드프레스 설치 시 별도 설정을 하셨다면, 해당 값을 입력하여 주시고, 별도 설정 없이 진행했을 경우 'wp_' 를 (상기 소스와 같이) 그대로 입력해주세요.
				
			endif;
		
	} // wordpress_database_insert end
	
	/*
	
		#파싱(긁어오기) 순서
		
		1. "Snoopy.class.php" 파일을 include 해줍니다. 이 파일을 include 반드시 해주셔야 합니다.
		
		2. [1] 번 작업이 끝났으면, "$sn = new Snoopy;" 를 입력해주세요.
	
		3. "$sn->fetch()" ← 이 부분은 파싱할, 즉 긁어올 페이지의 주소를 입력해주세요. ex:) http://openapi.arko.or.kr:8080/openapi/service/KoreaPac/getList, 입력하실 때 $_GET 으로 넘기실 값이 있다면 (ex: servicekey 등) 함께 입력해주세요.
	
		4. [3] 번까지 모든 작업이 끝났으면, "$sn->results" ← 를 입력하시면 결과 값을 얻으실 수 있습니다. 이번 강의에서는 해당 API 가 제공하는 데이터 형식이 'XML' 이기에 simplexml_load_string($sn->results); 로 사용했습니다.
		
	*/
	
	include_once "./Snoopy.class.php"; // Snoopy Class  Load.
	
	$sn = new Snoopy; // new Snoopy
	
	$serviceKey = ""; // serviceKey
	
	// ↑ 이 서비스 키는, 공공데이터포털 → 일반 인증키(UTF-8) 입니다. 기본적으로, UTF-8 로 인코딩을 해줘야 합니다만, 포털 측에서 해당 인증키 제공 시 '자동으로' UTF-8 로 인코딩을 해서 제공하기에 별도 설정할 필요 없이 그대로 붙이시면 됩니다.
	
	$sn->fetch("http://openapi.arko.or.kr:8080/openapi/service/KoreaPac/getList?serviceKey=".$serviceKey."&numOfRows=10&pageSize=10&pageNo=1&startPage=1");

	$xml = simplexml_load_string($sn->results); // result = $sn->results;
	
	$gisa_subject = "[뉴스] 공연예술센터 공연 정보"; // ← 기사 제목을 변경하고자 하시면, 이 부분을 변경해주세요. 변경해주시면 기사 제목도 함께 변경됩니다.
	
	$gisa_content = "[로봇저널=KoreaPac 기자] 공연예술센터 공연 정보에 대해 알아보자."; // 기사 본문 start.
	
	for($i=1; $i<=10; $i++){ // for start
	
	// ↑ 본 강의에서는 '속도 최적화' 를 위해 검색건수는 10건만 설정해 검색하였기에 상기와 같이 기재하였습니다.($i=1; $i<=10') 
	
	// 이 부분은, 개발 상황을 고려하여 개발해주시면 됩니다.
		
		/*
			idx		ID
			stext1	연도
			stext2	극장
			stext3	공연기간
			stext4	순서
			title		단체명
			text1	대표
			text2	공연명
			stext5	분야
			stext6   공연일수			
			stext7   공연횟수
		*/
	
		$data_content = $xml->body->items->item[$i]; // 원칙적으로, '연도' 값을 호출하기 위해서는 $xml->body->items->item[$i]->stext1; 이런식으로 입력해줘야 합니다. 
		
		// 하지만 이 방식은 개발 속도 지연 뿐만 아니라 많이 번거로운 작업입니다. 이러한 문제점을 해결하고자, $data_content = $xml->body->items->item[$i]; 를 입력했습니다.
		
		// 이에 따라, (수동) 으로 입력 없이, 그냥 $data_content->stext1; 이런식으로만 입력해주시면 됩니다.
		
		$idx = $data_content->idx; // ID
		
		$stext1 = $data_content->stext1; // 연도 
		
		$stext2 = $data_content->stext2; // 극장
		
		$stext3 = $data_content->stext3; // 공연기간
		
		$stext4 = $data_content->stext4; // 순서
		
		$title = $data_content->title; // 단체명
		
		$text1 = $data_content->text1;  // 대표
		
		$text2 = $data_content->text2; // 공연명
		
		$stext5 = $data_content->stext5; // 분야
		
		$stext6 = $data_content->stext6; // 공연일수
		
		$stext7 = $data_content->stext7; // 공연횟수
		
		/*
		
			↓ 아래 작업은, 공연기간을 가독성 등 여러 측면을 고려해 프로그램 소스 단에서 수정하는 작업입니다.
			   
			   기존에는, '20160514-0514' 라고 값이 출력되는데, 이를 그대로 기사에 적용하기에는 다소 무리가 있기에
			   
			   m월 d일 형식으로 변환합니다. 예컨대, '2016년 05월 14일' 이면, '05월 14일' 로 변경처리합니다.
		
		*/
		
		$stext3_0 = explode("-", $stext3); 
		
		$stext3_ymd = date("m월 d일", strtotime($stext3_0[0])); // 01월 01일, front.
		
		if($title && $stext1 && $stext2 && $stext3 && $text1 && $text2 && $stext5): 
		
		// ↑ △ 단체명 △ 연도 △ 극장 △ 공연기간 △ 대표 △ 공연명 △ 분야에 대한 데이터 값이 모두 있을 경우 작동하도록 설정. 1개라도 값이 없으면 작동하지 않습니다.
				
				// 공연횟수 별로 멘트 설정
				
				if($i == 1): // $i 가 1일 경우 아래 (gisa_content) 내용을 출력합니다.
					$gisa_content .= "<p>먼저, {$stext2}에서 {$stext3_ymd}부터 '({$stext5}) {$text2}'이 개최된다. 공연 단체는 '{$title}(대표 : {$text1})'이다.</p>";
				elseif($i == 3): // $i 가 3일 경우 아래 내용 출력.
					$gisa_content .= "<p>이어 '{$title}(대표 : {$text1})' 가 공연하는 '({$stext5}) {$text2}'는 {$stext3_ymd}부터 '{$stext2}'에서 열린다. ";
				elseif($i == 5): // $i 가 5일 경우 아래 내용 출력합니다.
					$gisa_content .= "<p>또한 '({$stext5}) {$text2}' 이(가) {$stext3_ymd}부터 '{$stext2}' 에서 열릴 예정이다.";
				elseif($i == 7): // 마찬가지로, $i 가 7일 경우 아래 내용 출력.
					$gisa_content .= "<p>아울러 {$stext3_ymd}부터 {$stext2}에서 열리는 '({$stext5}) {$text2}' 공연은 '{$title}(대표 : {$text1})' 가(이) 공연을 한다. ";
				elseif($i == 9): // $i 가 9일 경우 아래 (한편..) 내용 출력
					$gisa_content .= "<p>한편, {$stext3_ymd}부터 '({$stext5}) {$text2}' 이(가) {$stext2}에서 열린다. </p>";			
				elseif($i == 11): // 마지막으로 $i 가 11 = 더불어 .. 내용 출력합니다. 
					$gisa_content .= "<p>더불어, {$stext3_ymd}부터 '({$stext5}) {$text2}' 이(가) {$stext2}에서 열린다. </p>";			
				else: // 이 부분은 $i 값이 '1','3','5','7','9','11' 이 아닐 경우 아래 메세지를 출력시키는 부분입니다. (예외)
					$gisa_content .= "<p>{$stext3_ymd}부터 '({$stext5}) {$text2}' 이(가) {$stext2}에서 열린다. </p>";				
				endif;				
				
		endif;
		
	} // for end
	
	// ↓ 이 부분은 copyright 입니다. 기사 맨 마지막에 삽입되는 부분입니다.
	
	$gisa_content .= '<p style="font-weight:bold;">"본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇저널리즘입니다."</p> <p>master@rbjn.kr / KoreaPac 기자</p>';
	
	// addslashes = ' ' → '\' 로 변경합니다. 따옴표가 있을 경우 '\' 로 변경처리하여 DB 에 데이터 삽입 시 발생할 수 있는 오류를 방지하는 역할을 합니다.
	
	$gisa_subject = addslashes($gisa_subject); $gisa_content = addslashes($gisa_content);
	
	wordpress_database_insert($gisa_subject, $gisa_content, $conn); // 기사 제목, 기사 본문(내용), DB 접속 정보
	
?>

<head><meta charset='utf-8'></head>