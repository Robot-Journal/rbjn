<?php

	/*
		제작자 : 로봇저널 (www.rbjn.kr)

		License : MIT License

	*/

	$api_key = ""; // api_key
	
	include_once "./Snoopy.class.php"; // new Snoopy
	
	$sn = new Snoopy;
	
	$sn->fetch("http://api.visitkorea.or.kr/openapi/service/rest/KorService/searchFestival?ServiceKey=".$api_key."&numOfRows=10&pageSize=10&pageNo=1&startPage=1&arrange=A&listYN=Y&eventStartDate=".date("Y-m-d")."&MobileOS=ETC&MobileApp=%EA%B3%B5%EC%9C%A0%EC%9E%90%EC%9B%90%ED%8F%AC%ED%84%B8");

	$xml = simplexml_load_string($sn->results);
	
	$today_date = date("d"); // 오늘 '일' 자
	
	/// 기사 제목
	
	$gisa_subject = "[로봇기자] 오늘(".$today_date.")일 개최되는 '행사/공연/축제' 는? …";

	// 기사 내용
	
	$gisa_content = "<p>[로봇저널=Exhibition2 기자] ".$today_date."일, 어떤 '행사/공연/축제' 가 개최될지 알아보자.</p>";
	
		for($i=0; $i<10; $i++){ // for start
			
			$no_v = $xml->body->items->item[$i];
			
				$title = $no_v->title; // 콘텐츠 제목
				
				$firstimage = $no_v->firstimage; // 원본대표이미지 (약 500x333 size)
				
				$firstimage2 = $no_v->firstimage2; // 썸네일 대표이미지 (약 150x100 size) URL 응답
				
				$eventstartdate = date("m월 d일", strtotime($no_v->eventstartdate)); // 행사시작일 ( 형식 : YYYYMMDD )
				
				$eventenddate = date("m월 d일", strtotime($no_v->eventenddate)); // 행사종료일 ( 형식 : YYYYMMDD ), 20160103 -> 2016년 03월 01일
				
				$tel = $no_v->tel; // tel
				
				$addr = $no_v->addr1.$no_v->addr2; // 주소
				
					// 썸네일 이미지, 콘텐츠 제목, 주소, 행사시작일과 종료일, 전화번호가 모두 있을 때만 작동합니다.
								
					if($firstimage2 && $title && $addr && $eventstartdate && $eventenddate && $tel){
						
							$gisa_content .= "<p><img src='{$firstimage}' width=250 alt='{$title}' title='{$title}' border=0></p>";
							
							// '가맥축제 2016' 은(는) 전라북도 전주시 완산구 현무1길 20(경원동3가) 에서 개최되고, 행사시작일은 08월 04일부터 마감일은 08월 06일까지다.
							
							$gisa_content .= "<p>'{$title}' 은(는) {$addr} 에서 개최되고, 행사시작일은 {$eventstartdate}부터 마감일은 {$eventenddate}까지다.</p>";
	
								if($i == 0):
								
									 $gisa_content .= "<p>이와 관련하여 자세한 사항은 ".strip_tags($tel)." 으로 연락하면 된다.</p>";
								
								elseif($i == 1):
									
									$gisa_content .= "<p>그리고 궁금한 내용은 ".strip_tags($tel)." 으로 연락하면 자세한 내용을 안내 받을 수 있다.</p>";
								
								elseif($i == 2):
								
									$gisa_content .= "<p>또 궁금한 점은 ".strip_tags($tel)." 으로 연락하면 된다.<p>";
								
								elseif($i == 3):
								
									$gisa_content .= "<p>{$title} 와 (과) 관련하여 궁금한 사항은 ".strip_tags($tel)." 으로 문의하면 된다.</p>";
								
								elseif($i == 4):
									
									$gisa_content .= "<p>한편, {$title} 와 관련해 궁금한 내용이 있다면 ".strip_tags($tel)." 으로 연락하자. 자세한 내용을 안내 받을 수 있다.</p>";
								
								elseif($i == 5):
								
									$gisa_content .= "<p>아울러 궁금한 내용은 ".strip_tags($tel)." 으로 연락하면 자세한 내용을 안내 받을 수 있다.</p>";
									
								elseif($i == 6):
								
									$gisa_content .= "<p>더불어 궁금한 점은 ".strip_tags($tel)." 으로 연락하면 된다</p>";
									
								elseif($i == 7):
								
									$gisa_content .= "<p>본 {$title} 와(과) 관련하여 궁금한 사항은 ".strip_tags($tel)." 으로 문의하면 된다.</p>";
								
								elseif($i == 8):
								
									$gisa_content .= "<p>끝으로 {$title} 와 관련해 궁금한 내용이 있다면 ".strip_tags($tel)." 으로 연락하자. 자세한 내용을 안내 받을 수 있다.</p>";
								
								else: // no
								
									$gisa_content .= "<p>또한 자세한 내용을 알고 싶다면, {$title} 으로 연락하면 된다.</p>";
								
								endif; // $i end
							
					} // if end
			
		} // for end
							
		$gisa_subject .= $title." 등 개최";
	
		$gisa_subject = addslashes($gisa_subject);
	
		$gisa_content .= '<p style="font-weight:bold;">"본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇저널리즘입니다."</p> <p>master@rbjn.kr / Exhibition2 기자 / 자료제공 : 한국관광공사</p>';
	
		/* DB 입력 시작 */

		/* DB 입력 끝 */
	
?>















