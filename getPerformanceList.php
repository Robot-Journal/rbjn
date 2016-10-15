<?php

	/*
		제작자 : 로봇저널 (www.rbjn.kr)

		License : MIT License

	*/
	
	include_once "./Snoopy.class.php"; // snoopy class load.
	
	$api_key = ""; // api_key 
	
	/* DB 연결 시작 */
	
	/* DB 연결 끝 */
	
		$sn = new Snoopy; $sn_content = new Snoopy;
		
		$sn->fetch("http://tourapi.busan.go.kr/openapi/service/BusanTourInfoService/getWalkingTourList?ServiceKey=".$api_key."&numOfRows=999&pageSize=999&pageNo=1&startPage=1");
	
		$xml = simplexml_load_string($sn->results);
		
		// 기사 제목
		
		$gisa_subject = "[로봇저널] 부산 도보여행에 대해 알아보자.";
		
			for($i=0; $i<10; $i++){ // 10개만 불러오기
			
				$data_content_m = $xml->body->items->item[$i];
				
				$dataContent = $data_content_m->dataContent; $dataContent = str_replace("ㆍ","·",$dataContent); // 내용
				
				$dataSid = $data_content_m->dataSid; // 고유번호
				
				$dataTitle = $data_content_m->dataTitle; // subject
				
					// 상세 내용 로드 시작
					
						$sn_content->fetch("http://tourapi.busan.go.kr/openapi/service/BusanTourInfoService/getWalkingTourDetail?ServiceKey=".$api_key."&numOfRows=999&pageSize=999&pageNo=1&startPage=1&data_sid=".$dataSid);
					
						$xml2 = simplexml_load_string($sn_content->results);
						
							foreach($xml2->body->item as $data_content2){
								
								$addr = $data_content2->addr.$data_content2->detail; // 주소
								
								$guide = $data_content2->guide; // 안내전화
								
								$img1thumb = $data_content2->img1thumb; // 이미지 1
								
									if($img1thumb && $dataTitle && $addr && $guide){ // 이미지, 안내전화, 주소, 제목이 있을 때만 작동합니다.
										 
										  // 기사 내용 입력 시작
										
										  $gisa_content .= "<p><img src='{$img1thumb}' width=250></p>";
										  
										  $gisa_content .= "<p>'{$dataTitle}'은(는) '{$addr}'에 위치해 있다.</p>";
										 
											if($guide):
											
												if($i == 0):
												
													$gisa_content .= "<p>자세한 내용이 궁금하면 {$guide} 으로 문의하면 안내 받을 수 있다.</p>";
												
												elseif($i == 1):
												
													$gisa_content .= "<p>그리고 궁금한 내용은 {$guide} 으로 연락하면 자세한 내용을 안내 받을 수 있다.</p>";
												
												elseif($i == 2):
												
													$gisa_content .= "<p>또 궁금한 점은 {$guide} 으로 연락하면 된다.</p>";
												
												elseif($i == 3):
												
													$gisa_content .= "<p>{$dataTitle} 와(과) 관련하여 궁금한 사항은 {$guide} 으로 문의하면 된다.</p>";
												
												elseif($i == 4):
												
													$gisa_content .= "<p>한편, {$dataTitle} 와(과) 관련해 궁금한 사항은 {$guide} 으로 연락하자. 자세한 내용을 안내 받을 수 있다.</p>";
												
												elseif($i == 5):
												
													$gisa_content .= "<p>아울러 궁금한 내용은 {$guide} 으로 연락하면 자세한 내용을 안내받을 수 있다.</p>";
												
												elseif($i == 6):
												
													$gisa_content .= "<p>더불어 궁금한 점은 {$guide} 으로 연락하면 된다.</p>";
												
												elseif($i == 7):
													
													$gisa_content .= "<p>본 {$dataTitle} 와(과) 관련하여 궁금한 사항은 {$guide} 으로 문의하면 된다.</p>";
												
												elseif($i == 8):
												
													$gisa_content .= "<p>끝으로 {$dataTitle} 와 관련해 궁금한 내용이 있다면 {$guide} 으로 연락하자. 자세한 내용을 안내 받을 수 있다.</p>";
												
												else:
												
													$gisa_content .= "<p>또한 자세한 내용을 알고 싶다면, {$guide} 으로 연락하면 된다.</p>";
												
												endif; // $i end
											
											endif; // guide end
										 
									} // img1thumb && dataTitle && addr && guide end
								
							} // foreach end
					
					// 상세 내용 로드 끝				
				
			} // for 10 end
	
			$gisa_subject = addslashes($gisa_subject);
			
			$gisa_content .= '<p style="font-weight:bold;">"본 기사는 로봇저널리즘 전문지 로봇저널이 공공 API 를 이용해 제작한 로봇저널리즘입니다.</p><p>master@rbjn.kr / 자료출처 : 부산광역시 문화관광</p>';

			/* DB 입력 시작 */

			/* DB 입력 끝 */
	

?>