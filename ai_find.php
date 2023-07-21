<?php
// 교육을 위해 제공되는 유메타랩(프롬프트 엔지니어 코리아)의 챗봇 개발 예제 소스입니다.
// https://promptengineer.kr / 유메타랩(주)
// 문의: seo@seowan.net / edu@yumeta.kr
function findAndCombineText($text, $keyword) {
    $result = '';
    $positions = array();
    $keywordLength = mb_strlen($keyword, 'UTF-8');
    $textLength = mb_strlen($text, 'UTF-8');
    
    $pos = mb_stripos($text, $keyword, 0, 'UTF-8');
    
    while ($pos !== false) {
        $positions[] = $pos;
        $pos = mb_stripos($text, $keyword, $pos + 1, 'UTF-8');
    }
    
    $previousEnd = -1; // 이전 텍스트 영역의 끝 위치
    foreach ($positions as $pos) {
        $start = max(0, $pos - 50);
        $end = min($textLength, $pos + $keywordLength + 50);
        
        // 중복되는 영역인지 확인
        if ($start <= $previousEnd) {
            continue; // 중복되면 건너뛰기
        }
        
        $result .= mb_substr($text, $start, $end - $start, 'UTF-8') . " ";
        $previousEnd = $end; // 이전 텍스트 영역의 끝 위치 업데이트
    }
    
    return $result;
}


$ch = curl_init();
$url = 'https://api.openai.com/v1/chat/completions';
$api_key = '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';

$split = explode("/////", $_POST['text']);
$prompt = $split[0];
$old_prompt = $split[1];
$old_result = $split[2];

// 첫 번째 요청 보내기
$post_fields_1 = array(
    "model" => "gpt-3.5-turbo",
    "messages" => array(
        array(
            "role" => "system",
            "content" => "특정 문장이 들어오면, 문장에 있는 핵심 단어들만 출력해주세요. 예를들어 '마치 성문을 눈 앞에서 본 것처럼 성문의 생김새를 묘사해줘'라는 문장이 있다면, '성문, 생김새, 묘사'라고 출력하는거지. 혹은 '새로 오신 교수님이 누구지?'라는 문장이 있으면, '신임, 교수'와 같은 식으로 단어를 알아듣기 쉽게 명사형으로 바꾸어도 괜찮아."
        ),
        array(
            "role" => "user",
            "content" => "자, 그럼 너에게 문장을 줄게. 문장은 '최재목 교수님의 전화번호가 뭐지?'야. 출력은 단어만을 출력하고, 단어들 끼리는 쉼표로 구분하길 바란다. 다른 설명은 필요 없어."
        ),
        array(
            "role" => "assistant",
            "content" => "최재목, 교수, 전화번호"
        ),
        array(
            "role" => "user",
            "content" => "자, 그럼 너에게 문장을 줄게. 문장은 '총장님의 약력에 대해서 알려줘'야. 출력은 단어만을 출력하고, 단어들 끼리는 쉼표로 구분하길 바란다. 다른 설명은 필요 없어."
        ),
        array(
            "role" => "assistant",
            "content" => "총장, 약력"
        ),
        array(
            "role" => "user",
            "content" => "자, 그럼 너에게 문장을 줄게. 문장은 'yumc에 새로 들어온 부원에 대해서 알고 싶어'야. 출력은 단어만을 출력하고, 단어들 끼리는 쉼표로 구분하길 바란다. 다른 설명은 필요 없어."
        ),
        array(
            "role" => "assistant",
            "content" => "yumc, 신입, 부원"
        ),
        array(
            "role" => "user",
            "content" => "자, 그럼 너에게 문장을 줄게. 문장은 '$prompt'야. 출력은 단어만을 출력하고, 단어들 끼리는 쉼표로 구분하길 바란다. 다른 설명은 필요 없어."
        )
    ),
    "max_tokens" => 500,
    "temperature" => 0.5
);

$header = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
];

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields_1));
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

$result_1 = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

$response_1 = json_decode($result_1);
$first_response_content = $response_1->choices[0]->message->content;

$filename = 'fulltext.txt';
$fullText = file_get_contents($filename);

//$fullText = "안녕하세요? 유메타랩(Yumeta lab) 대표 서승완입니다. 청년의 눈높이에서 철학과 기술을 재해석하는 작업에 몰두하고 있어요. AI와 메타버스가 우리 사회와 삶을 바꿀 수 있는 잠재력을 가지고 있다고 믿습니다. 인문학적 바탕 위에서, 이러한 기술들이 어떻게 인간의 창의성, 생산성, 그리고 행복에 기여할 수 있을지 늘 고민하고 있어요. 그 고민을 해결해가는 여정이 쉽지는 않을거예요. 그 여정에 함께 동참해주시겠어요? 유년 시절부터 코딩을 공부해 한국정보올림피아드(KOI)에 입상하는 등 IT에 많은 관심을 보였습니다. 대학에서는 철학을 전공하며 기술과 인문학의 융합을 위한 이론적 토대를 쌓았지요. 동양철학 석사과정 중 전국 최초 메타버스 캠퍼스인 YUMC를 구축하였고, LG전자를 비롯한 다양한 기업 및 기관과의 협업을 진행했습니다. 2022년, 창업 이후로 인공지능 TOSSII를 개발하며 인공지능 프롬프트 관련 사업을 진행하고 있어요. 그 외에도 보드게임 <독도마루>를 기획/제작하고, AI기반 소설 제작 사이트 '야옹소설'을 운영한 경험을 가지고 있답니다. '프롬프트 엔지니어(챗GPT)'와 '메타버스', '인문학'을 주제로 3권의 저서를 쓰고, 1권의 역서(공동 일역)를 옮겼습니다. 모두 인문학적 관점에서 기술을 이해하려는 노력을 담았습니다. 일본 양명학에 관련된 4편의 논문도 저술한 바 있습니다.";

$keywords = explode(',', $first_response_content);
$info_result = '';

foreach ($keywords as $keyword) {
    $info = findAndCombineText($fullText, $keyword);
    $info_result .= $info . ' ';
}

$info_result = trim($info_result);





// 두 번째 요청 보내기
$post_fields_2 = array(
    "model" => "gpt-3.5-turbo-16k",
    "messages" => array(
        array(
            "role" => "system",
            "content" => "안녕하세요. 당신의 이름은 '테스트봇'이며, 당신은 유메타랩이 개발했습니다. 당신의 이름은 '테스트봇'이며, 서승완에 대한 정보를 출력합니다. 당신은 서승완에 대해서 매우 잘 알고 있습니다.\n\n#관련 정보\n$info_result"
        ),
        array(
            "role" => "user",
            "content" => "$old_prompt"
        ),
        array(
            "role" => "assistant",
            "content" => "$old_result"
        ),
        array(
            "role" => "user",
            "content" => "$prompt"
        )
    ),
    "max_tokens" => 1000,
    "temperature" => 0.7
);

$header = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields_2));
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

$result_2 = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

$response_2 = json_decode($result_2);
$second_response_content = $response_2->choices[0]->message->content;

$second_response_content = str_replace("\n", "<br/>", $second_response_content);

if (strpos($second_response_content,"NULL") !== false) { 
    echo "<font color=red>서버에 뭔가 오류가 있습니다. 새로고침 해주세요. $second_response_content </font>";
}else{
    echo stripslashes($second_response_content);
}

?>
