<?php

	/* DB 접속 정보 설정하기 */

	if($conn && $db): else: exit("DB 접속 에러!!"); endif;
	
	mysql_query("set names utf8"); // DB Charset 'utf-8' 로 지정, 이 작업을 해야 데이터 입력 시 글자 깨짐(인코딩)없음.
	
	/* DB 접속 정보 설정하기 */

	/* DB 입력 함수 */
	
	function wordpress_database_insert($gisa_subject, $gisa_content, $conn){ // DB 입력을 위해 제작한 함수입니다. 현재 개발 소스 코드에서 사용하고 있습니다.
		
		if($gisa_subject && $gisa_content && $conn): // 제목, 내용, DB 연결 정보가 모두 있을 때만 작성토록 설정
		
			$gisa_datetime_ymdhis = date("Y-m-d H:00"); // 기사 작성 날짜 
			
			$gisa_url_time = date("Ymdh0000").date("H00");
			
			// DB INSERT (중요), 기본적으로 '워드프레스' 설치 시 'wp_' 입니다. 설치 과정에서 별도 조치를 할 경우 'wp_' 부분이 변경될 수 있습니다. 정상적으로 데이터 삽입이 안될 경우 참고하시기 바랍니다.
			
			mysql_query("insert into wp_posts (ID, post_author, post_name, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_title, post_status, comment_status, ping_status, guid) VALUES ('','1','$gisa_url_time','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_content','$gisa_subject','publish','closed','closed','')") or die(mysql_error());
	
			$mysql_id = mysql_insert_id(); // mysql insert_id 값 구하기
			
			$guid = "http://localhost/word/?p=".$mysql_id; // guid, ex: http://사이트주소/워드프레스 폴더/?p=mysql_id
			
			mysql_query(" update wp_posts set guid = '{$guid}' where ID = '{$mysql_id}' ");
	
			echo $gisa_subject."<HR>".$gisa_content;	// 이 부분은 기사 제목과 내용이 정상적으로 출력되는지 확인용으로 개발 소스 코드에서 '필수' 는 아닙니다. 주석(//) 처리해주셔도 됩니다.
		
		endif; // 제목, 내용, DB 연결 정보가 모두 있을 때만 작성토록 설정
		
	} // wordpress_database_insert end
	
	/* DB 입력 함수 */
	
	include_once "./Snoopy.class.php"; // snoopy class 를 include 해줍니다. (필수)
	
	$sn = new Snoopy; // $sn = new Snoopy;
	
	$serviceKey = ""; // 서비스키입니다. 서비스 키는 유지보수 편의를 위해 따로 분리하겠습니다.
	
	$sn->fetch("http://www.culture.go.kr/openapi/rest/ticketdiscounts?serviceKey=".$serviceKey."&numOfRows=999&pageSize=999&pageNo=1&startPage=1"); // Load.
	
	$xml = simplexml_load_string($sn->results); // snoopy 결과값은 $sn->results 로 load 가능합니다.
	
	// 데이터 foreach xml result.
	
		$gisa_subject = "[로봇저널 뉴스] 티켓할인서비스 정보"; // 기사 제목
	
		$gisa_content = "<p>[로봇저널=ticketdiscounts 기자] 티켓할인서비스 관련 정보를 알아보자.</p>";
				
		foreach($xml->msgBody->ticketList as $data_content){ // foreach
		
			// 기존에는 데이터를 출력하기 위해서는, $xml->msgBody->ticketList->항목(변수) 이런식으로 출력을 했어야 했습니다.
			
			// 그러나, foreach 를 사용하면 앞서 'data_content' 를 명시했기에, 간단하게 '$data_content->항목(변수)' 로 사용하시면 되겠습니다.
		
				/* 사용 변수 설정 시작 */
				
				$seq = $data_content->seq; // 티켓번호, ex: 181
				$title = $data_content->title; // 제목, ex : (2016 크리스마스특집) 점프(JUMP) - 부산
				$img = $data_content->img; // 이미지 주소, ex : http://www.culture.go.kr/upload/rdf/rdf_2016093011221800810.jpg
				$price = $data_content->price; // 티켓 요금, ex: 6만6천원
				$startDate = date("m월 d일", strtotime($data_content->startDate)); // 시작일, ex: 2016-10-05 → 00월 00일
				$endDate = date("m월 d일", strtotime($data_content->endDate)); // 마감일, ex : 2016-12-25 → 00월 00일
				
				$place = $data_content->place; // 공연장, ex: 부산시민회관
				$discountRate = $data_content->discountRate; // 할인율, ex : 50 할인(R석 6만6천→3만3천/ S석 5만천→2만7천5백)
				
				/* 사용 변수 설정 끝 */
			
				if($title && $startDate && $endDate && $price){ // 제목, 시작일, 마감일이 모두 있을 경우에만 데이터 출력. (티켓 가격도 있어야 출력되도록 변경)
			
					/* 기사 본문 시작 */
					
					$gisa_content .= "<p><img src='{$img}' width=240 border=0></p>"; // 이미지 출력
						
					$gisa_content .= "<p>{$title} 공연은 {$place}에서 열린다. 시작일은 {$startDate}부터 마감일은 {$endDate}까지다.</p>"; // 제목, 개최 공연장 위치, 시작일, 마감일 안내
					
					$gisa_content .= "<p>티켓 가격에 대해 알아보자, 티켓 요금은 {$price}이다.</p>"; // 티켓 가격에 대한 안내
					
					$gisa_content .= "<p>이와 관련해 자세한 정보는 <a href='http://www.culture.go.kr' target=_blank style='font-weight:bold; color:#333;'>문화포털(www.culture.go.kr)</a> 사이트를 참고하자.</p>";
					
					// "이와 관련해 자세한 정보" ← 이 부분은, IF 문을 통해 제어할 수 있습니다. 설정에 따라, △ 이와 관련해 자세한 정보 △ 그리고 궁금한 내용 △ 또 궁금한 점 △ {제목} 와 관련하여 궁금한 사항 △ 한편, {제목} 와 관련해 궁금한 내용 △ 아울러 궁금한 내용 △ 더불어 궁금한 점은 △ 본 {제목} 와 관련하여 궁금한 사항은 △ 끝으로 {제목} 와 관련하여 궁금한 내용이 있다면 △ 또한 자세한 내용이 알고 싶다면 등으로도 사용할 수 있으니 참고하셔서 개발하시면 될 것 같습니다.
					
					/* 기사 본문 끝 */
					
				} // title, startDate, endDate end
		
		} // foreach end
		
			$gisa_content .= '<p style="font-weight:bold;">"본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API를 이용해 제작한 로봇저널리즘입니다."</p> <p>master@rbjn.kr / ticketdiscounts 기자 / 자료제공 : 문화포털</p>';
		
			wordpress_database_insert(addslashes($gisa_subject), addslashes($gisa_content), $conn);  // 기사 제목, 기사 내용, DB 접속 정보
			
			// 사용법
			
			/*
			
				1. 'wordpress_database_insert' 는 데이터베이스(DB) 에 데이터 입력을 위해 제작한 함수입니다. (function)
				
				2. 사용법은, 'wordpress_database_insert' 를 호출할 때, △ 기사 제목(gisa_subject), △ 기사 내용 (gisa_content), DB 접속정보를 함께 넘겨주시면 됩니다.
					→ ex:) wordpress_database_insert("기사 제목","기사 내용","DB 접속 정보");
					
				3. 별도의 return 과정은 없습니다. return 이 필요할 경우 'wordpress_database_insert' 함수에서 관련 코드를 추가하시면 되겠습니다.
				
					→ ex:) return "정상적으로 처리되었습니다.";
					
				[Tip] 
				
					1. DB 입력 시 데이터 오류 등의 문제로 정상적으로 처리되지 않을 경우 오류 메세지가 출력됩니다. 이는 'mysql_query();' 시 'mysql_error' 를 입력했기 때문인데요. 출력되는 오류를 보고, 해결하시면 될 것 같습니다.
				
					2. 본 개발 소스 코드는, github 'https://github.com/Robot-Journal/rbjn' 에 업로드 하겠습니다. 
					
					3. 본 개발 소스 코드의 라이센스(License)는 'MIT 라이센스' 입니다. 자유롭게 응용 및 참고하셔서 개발하시면 되겠습니다.
					
					4. 앞서, 텍스트 파일을 통해서도 설명을 드렸지만, 한 번 더 설명 드립니다. 현재 보시는 웹 브라우저 화면에서 이미지가 모두 '엑박' 처리됩니다. 또한, '따옴표' 부분은 '\' 로 변경되어 있습니다. 개발 소스 코드를 보시면, '이미지' URL 에 '\' 가 붙어 엑박으로 나오는 것입니다. 이는 'addslashes' 라는 소스 때문으로, 'addslashes' 는 '따옴표' 가 있을 경우 '\' 처리를 하여, DB 입력 시 발생하는 오류를 방지합니다.
	
					
	
			*/
			
?>

<head><meta charset='utf-8"></head>