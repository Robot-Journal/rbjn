<?php

	// DB 접속 정보 설정
	
	 // "set names utf8" ← 이 부분을 기재하지 않으면, DB 입력 시 글자가 깨지게 됩니다.
	
	mysql_query("set names utf8"); // mysql, charset utf-8, 글자(인코딩) 깨지는 것을 방지함.
	
	if($conn && $db): // success, ← 데이터베이스 접속 계정(host, user, pass, db name)이 모두 일치하고 정상적으로 접속이 이루어지면, 아무 동작 없이 바로 다음 단계(기사 작성)로 넘어갑니다.
		
	else: // failes, 그러나 데이터베이스 접속 계정이 틀릴 경우 'DB 접속 에러' 라는 메세지가 나오면서 페이지가 더 이상 실행되지 않습니다.
	
		exit("DB 접속 에러!!"); // 'DB 접속 에러' ← 이 부분을 수정해주시면 출력 메세지를 변경하실 수 있습니다.
		
	endif; // conn, db end

	// DB INSERT 함수
	
	function wordpress_insert($gisa_subject, $gisa_content, $conn){ // 데이터베이스(DB)에 데이터(기사 제목, 내용 등) 입력 시 사용하는 함수입니다.
		
		if($gisa_subject && $gisa_content && $conn): // 기사 제목, 내용, DB 접속 정보 값 모두 있을 때 작동.
		
			$gisa_datetime_ymdhis = date("Y-m-d H:00"); // 기사 작성 날짜, 2016-01-01 03:00
			
			$gisa_url_time = date("Ymdh0000").date("H00");
			
			// 'wp_' ← 기본적으로 별도 설정 없이, 워드프레스를 설치할 경우 'wp_' 입니다. 하지만, 설치 시 별도 설정을 할 경우 'wp_' 가 아닌, 'wp2_' 가 될 수도 있습니다. 상황에 따라 수정하시면 되겠습니다.
			
			mysql_query(" insert into wp_posts (ID, post_author, post_name, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_title, post_status, comment_status, ping_status, guid) VALUES ('','1','$gisa_url_time','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_content','$gisa_subject','publish','closed','closed','')") or die(mysql_error());

			//  or die(mysql_error()); = mysql 데이터 삽입 실패 시, 오류메세지를 출력합니다. 이 오류메세지를 바탕으로 관련 정보를 찾아 오류를 해결하시면 될 것 같습니다.
			
			// ↑ DB 입력 부분 (가장 중요)
			
			$mysql_id = mysql_insert_id(); // Mysql ID
			
			$guid = "http://localhost/word/?p=".$mysql_id; // http://사이트 주소/워드프레스/?p=$mysql_id
			
			// 예컨대, 사이트 주소가 www.rbjn.kr 이고, 워드프레스 설치 경로(폴더)가 wp 일 경우, $guid 값은, "http://www.rbjn.kr/wp/?p=".$mysql_id 입니다.
			
			mysql_query("update wp_posts set guid = '{$guid}' where ID = '{$mysql_id}' "); // 이 부분도 마찬가지로, 워드프레스 설치 시 'wp_' 가 아닌, 다른 값(ex: wp2_) 으로 설정 시 해당 값을 입력해주세요.
		
		endif; // gisa_content, gisa_content, conn end
		
	} // wordpress_insert end
	
	
	/*
	
		#파싱(긁어오기) 방법에 대해 간략하게 설명드립니다.
		
		1. "Snoopy.class.php" 파일을 include_once 해주세요. (load)
	
		2. [1] 번 작업이 완료된 후, "$sn = new Snoopy;" 라고 적어주세요. (선언)

		3. $sn->fetch(" 파싱할 페이지 주소 "); // 파싱할 페이지 주소를 () 안에 넣어주세요.
		
		4. [3] 번 파싱 페이지의 결과 값은 "$sn->results"; 를 통해 확인 할 수 있습니다. 
		
		4-1) 다만, xml 형식의 파일이기에 'simplexml_load_string' 함수를 이용해 $sn->results 값을 호출하여 사용합니다. 
		
		4-2) 혹시, 'simplexml_load_string' 함수 사용 시 정상적으로 진행이 이루어지지 않고, 
		
		4-3) 오류가 발생할 경우 이용하시는 호스팅 사에서 지원하지 않는 부분으로, 호스팅 사 측에 문의하시면 정확한 답변을 전달 받으실 수 있습니다. (allow_url_fopen)
		
	*/
	
	include_once "./Snoopy.class.php"; // Snoopy Class Module Include. 파싱(긁어오기)을 위해 필수로 include 해야 합니다.
	
	$serviceKey = ""; // 서비스키, 유지보수 등을 위해 별도로 지정합니다.
	
	$sn = new Snoopy; // new Snoopy
	
	$sn->fetch("http://opendata.goseong.go.kr/openapi-data/service/tour/tourAll?serviceKey=".$serviceKey."&numOfRows=10&pageSize=10&pageNo=1&startPage=1");

	$xml = simplexml_load_string($sn->results); // result = $sn->results;
	
	// 기사 제목, 내용 설정
	
	$gisa_subject = "[뉴스] 실시간 관광정보"; // 기사 제목입니다. 이 부분을 변경하시면, 기사 제목도 변경됩니다.
	
	$gisa_content = "[로봇저널=tourAll 기자]"; // 기사 내용 start.
	
	for($i=1; $i<=10; $i++){ // for start
		
			$data_content = $xml->body->items->item[$i]; // $xml->body->items->item[$i] 으로 수동 호출(항상 입력)불편을 줄이고, 편의를 위해 $data_content 를 사용합니다.
			
			$exp1 = $data_content->exp1; // 상세정보, ex) 남부지역에 분포된 부농의 주거형으로 현 소유자의 7대조 최필관이 순조 9년
			
			$telNum = $data_content->telNum; // 전화번호, 055-673-6904 
			
			$title = $data_content->title; // 관광지명, ex:) 고성학림리 최영덕씨고가
			
				if($title && $telNum && $exp1): // 관광지명, 전화번호, 상세정보 모두 데이터 있을 때만 작동.
				
					if($i == 1): // $i 가 1일 경우, 아래 내용(gisa_content) 출력.
						$gisa_content .= "<p>{$title}, {$exp1} / T. {$telNum}</p>";
					elseif($i == 3): // $i 가 3일 경우 아래 내용 출력.
						$gisa_content .= "<p>이어 '{$title}', {$exp1} 기타 궁금한 점은 {$telNum} 으로 연락하면 된다.</p>";
					elseif($i == 5): // $i 가 5일 경우 아래 (gisa_content) 내용 출력.
						$gisa_content .= "<p>또 '{$title}'과 관련하여 자세한 내용은 다음과 같다. '{$exp1}'</p>";
					elseif($i == 7): // $가 7일 경우 아래 내용 출력
						$gisa_content .= "<p>또한, {$title}에 대한 자세한 설명은 '{$exp1}' 이다.</p>";
					elseif($i == 9): // $i 가 9일 경우 아래 내용 출력.
						$gisa_content .= "<p>아울러, {$title} 와 관련하여 자세한 사항은 아래와 같다. '{$exp1}' / ☎. {$telNum}</p>";
					endif; // $i 조건문 끝.
				
				endif; // title, telnu, exp1
			
	} // for end
	
	$gisa_subject = addslashes($gisa_subject); $gisa_content = addslashes($gisa_content); // addslashes = '따옴표' → '\' 로 자동 변경, 이 작업을 거치지 않을 경우 DB 입력 시 오류 발생할 수 있음.
	
	$gisa_content .= '<p style="font-weight:bold;">"본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇저널리즘입니다."</p> <p>master@rbjn.kr / tourAll 기자 / 자료제공 : 고성군</p>';
	
	wordpress_insert($gisa_subject, $gisa_content, $conn); // 기사제목, 기사내용, DB 접속 정보
	
?>