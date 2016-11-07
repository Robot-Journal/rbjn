<?php

	// DB 접속 정보
	
	if($conn && $db): // success, DB 접속이 성공했을 때. 별도 메세지 출력 없이 다음 단계(데이터 로드, 기사 작성 등) 진행.
	
	else: // failes, DB 접속이 실패했을 경우 메세지 출력.
	
		exit("DB 접속 에러!!");
	
	endif; // if conn && db end
	
	mysql_query("set names utf8"); // mysql charset, DB 데이터 삽입 시 글자 깨짐(인코딩) 방지.
		
	// DB INSERT 함수
	
	function wordpress_rbjn_insert($gisa_subject, $gisa_content, $conn){ // wordpress_rbjn_insert function start
 		
		if($gisa_subject && $gisa_content && $conn): // 기사 제목, 내용, DB 접속 정보 모두 있을 때만 작동.
		
			$gisa_datetime_ymdhis = date("Y-m-d H:00"); // 기사 작성 날짜
			
			$gisa_url_time = date("Ymdh0000").date("H00");
			
			// 'wp_' ← 기본적으로, 워드프레스 설치 시 'wp_' 입니다만, 워드프레스 설치 시 별도 설정을 하셨을 경우 해당 값을 입력해주세요.
			
			mysql_query("insert into wp_posts (ID, post_author, post_name, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_title, post_status, comment_status, ping_status, guid) VALUES ('','1','$gisa_url_time','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_datetime_ymdhis','$gisa_content','$gisa_subject','publish','closed','closed','')") or die(mysql_error());
			
			$mysql_id = mysql_insert_id();
			
			$guid = "http://localhost/word/?p=".$mysql_id; // 이 부분에 대해 부연 설명을 드리면, "http://사이트 주소/워드프레스 경로/?p=$mysql_id" 를 입력해주세요.
			
			mysql_query("update wp_posts set guid = '{$guid}' where ID = '{$mysql_id}'"); // 'wp_' ← 앞서 설명드린 것처럼, 워드프레스 설치 시 별도 설정을 하셨다면 해당 설정 값을 입력해주세요.
		
		endif; // gisa_subject, gisa_content, conn end
		
	} // wordpress_rbjn_insert end
	
	// snoopy class 호출
	
	include_once "./Snoopy.class.php"; // snoopy class module load (필수) include.
	
	$sn = new Snoopy; // new Snoopy
	
	$serviceKey = ""; // 유지보수 등 편의를 위해 서비스키는 따로 분리합니다.
	
	$sn->fetch("http://openapi.mpm.go.kr/openapi/service/RetrievePblinsttEmpmnInfoService/getList?serviceKey=".$serviceKey."&numOfRows=10&pageSize=10&pageNo=1&startPage=1s"); // 데이터 로드(읽어오기)
	
	$xml = simplexml_load_string($sn->results); // $sn->results → snoopy data results
	
	// 정상적으로 데이터가 출력되는지 확인해보겠습니다.
	
		// 기사 제목
		
		$gisa_subject = "[단신] 실시간 공공취업정보 - 로봇저널"; // 기사 제목 ← 이 부분을 변경해주시면 기사 제목이 변경됩니다.
		
		$gisa_content = "<p>[로봇저널=RetrievePblinsttEmpmnInfoService 기자] 공공취업정보와 관련해 알아보자.</p>"; // 기사 본문 start
	
		for($i=1; $i<=10; $i++){ // numOfRows = 한 페이지 결과 수, 
		
//		↑  본 강의에서는 10건만 검색하므로, 수동으로 '10' 을 입력했지만, 실제 개발 시에는 'numOfRows' 를 이용해 for 문을 사용해주시면 될 것 같습니다.
	
					$data_content = $xml->body->items->item[$i]; // $xml->body->items->item[$i], '[$i]' → $i
	
					// 사용 변수
					
					$deptName = $data_content->deptName; // 기관명, ex: 송파우체국
					
					$regdate = date("d일", strtotime($data_content->regdate)); // 등록일, ex: 2016-11-21
					
					$enddate = date("d일", strtotime($data_content->enddate)); // 마감일, ex: 2016-11-21
					
					$title = $data_content->title; // 제목, 기간제채용계획공고
					
					// 제목 : / 기관명 : / 등록일 : / 마감일 / 
					
					if($deptName && $title && $regdate && $enddate){ // 기관명, 제목(공고), 등록일, 마감일 데이터가 모두 있을 경우에만 작동합니다.
						
						if($i == 1): // $i 가 1일 땐, 아래 $gisa_content 내용 출력
							$gisa_content .= "<p>먼저, {$deptName}, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						elseif($i == 2): // $i 가 2일 때, 아래 $gisa_content 내용 출력
							$gisa_content .= "<p>{$deptName}은(는) {$regdate} '{$title}' 를 냈다. 마감일은 {$enddate}까지다.</p>";
						elseif($i == 4): // $i 가 4일 때, 아래 $gisa_content 내용 출력
							$gisa_content .= "<p>그리고 {$deptName}도, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						elseif($i == 6): // $i 가 6일 때, 아래 $gisa_content 내용 출력
							$gisa_content .= "<p>또 {$deptName}, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						elseif($i == 8): // $i 가 8일 때, 아래 $gisa_content 내용 출력
							$gisa_content .= "<p>또한 {$deptName}, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						elseif($i == 10): // $i 가 10일 때, 아래 $gisa_content 내용 출력.
							$gisa_content .= "<p>한편, {$deptName}, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						elseif($i == 12): // $i 가 12일 때, 아래 $gisa_content 내용 출력.
							$gisa_content .= "<p>아울러, {$deptName}, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						elseif($i == 14): // $i 가 14일 때, 아래 $gisa_content 내용 출력.
							$gisa_content .= "<p>더불어, {$deptName}, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						else: // 상기 조건에 모두 해당 안 될 경우 아래 $gisa_content 출력
							$gisa_content .= "<p>{$deptName}, {$regdate} 공고를 냈다. 이 공고는 '{$title}'로 마감일은 {$enddate}까지다.</p>";
						endif; // if 문 endif;
							
					} // title, regdate, enddate
					
		} // for end
		
			// 기사 마지막, copyright
		
			$gisa_content .= '<p style="font-weight:bold;">"본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇저널리즘(로봇기자)입니다."</p> <p>master@rbjn.kr / RetrievePblinsttEmpmnInfoService 기자</p>';
		
			// 기사 제목, 기사 내용 데이터 DB 에 삽입, 함수 ↓
		
			wordpress_rbjn_insert(addslashes($gisa_subject), addslashes($gisa_content), $conn); // db insert
			
			/*
				[Tip] 
				
					■ wordpress_rbjn_insert 사용법 : wordpress_rbjn_insert(기사 제목, 기사 내용, DB 접속 정보);
					
					1. 본 개발 소스 코드는 github (https://github.com/Robot-Journal/rbjn) 에 업로드 하겠습니다.
					2. 본 개발 소스 코드 라이센스(License)는 'MIT License' 입니다. 자유롭게 수정 및 개발하셔서 사용하시면 됩니다.
					3. 본 개발 소스 코드와 관련하여 궁금한 점이 있을 경우, 'master@rbjn.kr' 으로 연락주시기 바랍니다. 확인 후 답변드리겠습니다.
			*/
			
?>

<head><meta charset='utf-8'></head>