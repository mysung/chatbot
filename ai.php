<?php
// 성미영의 챗봇 개발 예제 소스입니다.
// https://promptengineer.kr / 유메타랩(주)
// 문의: seo@seowan.net / edu@yumeta.kr

$ch = curl_init();
$url = 'https://api.openai.com/v1/chat/completions';
$api_key = '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';

list($prompt, $old_prompt, $old_result) = explode("/////", $_POST['text']);

$post_data = [
    "model" => "gpt-3.5-turbo-16k",
    "messages" => [
        [
            "role" => "system",
            //"content" => "안녕하세요. 당신의 이름은 '성미영 봇'이며, 당신은 성미영이 개발했습니다. 당신의 이름은 '성미영 봇'임을 명심하세요."
            //"content" => "# 삼행시는 주어진 이름의 각 글자로 시작하는 문장을 만드는 것입니다\n삼행시: 성미영\n성: 성스러운\n미: 미인의\n영: 영광을 위하여\n#입력\n이름\n#출력형식\n이름: 입력\n삼행시\n첫 글자: 첫 글자로 시작하는 문장\n둘째 글자: 둘째 글자로 시작하는 문장\n서째 글자: 세째 글자로 시작하는 문장\n"
            //"content" => "#음식 리뷰 댓글 작성\n#지시문\n#입력\n음식, 점수, 코멘트\n#출력\n1점(불만족)~5점(만족) 점수에 따라 친절한 답변과 이모티콘 추가합니다\n"
            "content" => "#지시문\n당신은 입력으로 설정된 고대로부터 현대까지의 모든 철학자의 역할을 하는 페르소나가 됩니다.\n
            설정된 페르소나의 철학자의 철학적 사고와 논리에 의거해 사용자와 철학적 사고에 대한 대화를 할 수 있는 챗봇입니다.\n
            #제약조건\n답변은 해당 철학자의 그주요 업적에 대한 요약을 먼저 제시합니다.\n
            그런 다음 질문에 대해 해당 페르소나의 어록과 사실에 근거해서  답변합니다.\n
            질문과 관련된 논문이 있다면  내용도 요약합니다.\n
            #입력\n
            철학자 이름#철학 대화 챗봇\n
            #지시문\n
            당신은 입력으로 설정된 고대로부터 현대까지의 모든 철학자의 역할을 하는 페르소나가 됩니다.\n
            설정된 페르소나의 철학자의 철학적 사고와 논리에 의거해 사용자와 철학적 사고에 대한 대화를 할 수 있는 챗봇입니다.\n
            #제약조건\n
            답변은 해당 철학자의 업적에 대해 요약합니다.\n
            질문\n
            #답변\n
            철학자 이름\n
            답변"
        ],
        [
            "role" => "user",
            "content" => $old_prompt
        ],
        [
            "role" => "assistant",
            "content" => $old_result
        ],
        [
            "role" => "user",
            "content" => $prompt
        ]
    ],
    "max_tokens" => 2000,
    "temperature" => 0.
];

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

$response = json_decode($result);
$message_content = var_export($response->choices[0]->message->content, true);
$message = trim($message_content, "'");

$message = str_replace("\n", "<br/>", $message);

if (strpos($message, "NULL") !== false) {
    echo "<font color=red>서버에 오류가 발생했습니다. 페이지를 새로고침해주세요. $result </font>";
} else {
    echo stripslashes($message);
}
?>
