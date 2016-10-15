<?php

	/*
		제작자 : 로봇저널 (www.rbjn.kr)

		License : MIT License

	*/

	include_once "./Snoopy.class.php"; // snoopy class load

	$api_key = ""; // api_key 는 dart.fss.or.kr 에서 발급 받을 수 있음.

	$dart_snoopy = new Snoopy;

	$dart_snoopy->fetch("http://dart.fss.or.kr/api/search.xml?auth=".$api_key."&page_set=100");

	$dart_result = simplexml_load_string($dart_snoopy->results);

	foreach($dart_result->list as $dart_all){

		if($dart_all):

			$crp_cls = $dart_all->crp_cls; // 법인구분 : Y(유가), K(코스닥), N(코넥스), E(기타)

			if($crp_cls == 'K' || $crp_cls == 'N'):

				if($crp_cls == 'K'){
					$crp_cls_name = "코스닥";
				}elseif($crp_cls == 'N'){
					$crp_cls_name = "코넥스";
				}

				$crp_nm  = $dart_all->crp_nm; // 공시대상회사의 종목명(상장사) 또는 법인명(기타법인)

				$crp_cd  = $dart_all->crp_cd; // 공시대상회사의 종목코드(6자리) 또는 고유번호(8자리)

				$rpt_nm  = $dart_all->rpt_nm; // 공시구분+보고서명+기타정보

				$rpt_nm = str_replace("ㆍ","·",$rpt_nm);

				/*
					[기재정정] : 본 보고서명으로 이미 제출된 보고서의 기재내용이 변경되어 제출된 것임
					[첨부정정] : 본 보고서명으로 이미 제출된 보고서의 첨부내용이 변경되어 제출된 것임
					[첨부추가] : 본 보고서명으로 이미 제출된 보고서의 첨부서류가 추가되어 제출된 것임
					[변경등록] : 본 보고서명으로 이미 제출된 보고서의 유동화계획이 변경되어 제출된 것임
					[연장결정] : 본 보고서명으로 이미 제출된 보고서의 신탁계약이 연장되어 제출된 것임
					[발행조건확정] : 본 보고서명으로 이미 제출된 보고서의 유가증권 발행조건이 확정되어 제출된 것임
					[정정명령부과] : 본 보고서에 대하여 금융감독원이 정정명령을 부과한 것임
					[정정제출요구] : 본 보고서에 대하여 금융감독원이 정정제출요구을 부과한 것임

				*/

				$rcp_no = $dart_all->rcp_no; // 접수번호(공시뷰어 연결에 이용)

				/*
					- PC용 : http://dart.fss.or.kr/dsaf001/main.do?rcpNo=접수번호
					- 모바일용 : http://m.dart.fss.or.kr/html_mdart/MD1007.html?rcpNo=접수번호
				*/

				$flr_nm = $dart_all->flr_nm; // 공시 제출인명
				$rcp_dt = $dart_all->rcp_dt; // 공시 접수일자(YYYYMMDD), 20160805

				$rcp_dt_end = date("j",strtotime($rcp_dt));

				if($crp_nm && $rpt_nm){

					/* 기사 제목 */

					$gisa_subject = '['.$crp_cls_name.' 공시] '.$crp_nm.', '."'".$rpt_nm."'".' 공시';
					$gisa_subject = str_replace('ㆍ','·',$gisa_subject);

					/* 기사 제목 */

					////////////////// 기사 내용 시작

					$gisa_content = "<p>[로봇저널] 전자공시시스템에 따르면 ".$crp_nm."(".$crp_cd.")는(은) '".$rpt_nm."' 을(를) ".$rcp_dt_end."일 공시했다.</p>";

					$gisa_content .= "<p>이와 관련해 자세한 내용은 아래 링크에서 확인할 수 있다.</p>";

					$gisa_content .= "<ul>";
						$gisa_content .= '<li>PC : <a href="http://dart.fss.or.kr/dsaf001/main.do?rcpNo='.$rcp_no.'" target="_blank">http://dart.fss.or.kr/dsaf001/main.do?rcpNo='.$rcp_no.'</a></li>';
						$gisa_content .= '<li>모바일 : <a href="http://m.dart.fss.or.kr/html_mdart/MD1007.html?rcpNo='.$rcp_no.'" target="_blank">http://m.dart.fss.or.kr/html_mdart/MD1007.html?rcpNo='.$rcp_no.'</a></li>';
					$gisa_content .= "</ul>";

					$gisa_content .= '<p>"본 기사는 로봇저널리즘 전문지 \'로봇저널\' 이 전자공시시스템 OPEN API 를 이용해 작성한 기사입니다. / 출처 : 금융감독원 DART 공시정보</p>';

					////////////////// 기사 내용 끝

					/* DB 입력 시작 */

					/* DB 입력 끝 */

				} // $crp_nm && $rpt_nm end

			endif; // 코스닥 또는 코넥스 상장 법인만 다룸
		endif; // dart_all
	} // dart_all end


?>
