<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.ico" />
    <title>Philo Bot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.1/css/bootstrap.min.css"
        integrity="sha512-B3clz06N8Jv1N/4ER3q4ee4+AVa8rrv/5Q5M5tz+R5S9t8XvJyA2+7nFt2QdC8dPwZlnwyF+I1tKb/nik18Ovg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<style>
body {
        background-image: url('star.jpg'); /* Set background image */
        background-repeat: no-repeat; /* Prevent image from repeating */
        background-size: cover; /* Make image cover the entire area */
        padding-top: 30px; /* 빈 공간 추가 */
}
.container {
    width: 50% !important; /* Container의 넓이를 50%로 설정 */
    margin: 0 auto; /* 화면 중앙에 위치하도록 설정 */
}
</style>

<body>
    <div style="text-align:center;">
        <h3 style="color:#f7cac9">Marvelous Marvelab</h3><br/>
        <img src="marvel.png" alt="marvel" style="width:100%; max-width:100px;">
    </div>

    <div class="container">
        <div class="card mt-5">
            <form onsubmit="return false;">
                <div class="card-header" style="padding-top:12px;">
                   
                    <h4 style="color:#FF3300"><b>철학 대화 챗봇</b></h4>

                    <div class="form-floating">

                    </div>

                </div>
                <div class="card-body">
                    <div class="media" id="form">
                        <div class="bot-inbox inbox border rounded p-2 mb-2" style="color:#1263c2"><b>ChatBOT</b>
                            <div class="msg-header">
                                <p class="mb-0">철학자 이름과 질문을 입력하세요</p>
                            </div>
                        </div>
                    </div>
                    <div class="media" id="typing" style="display:none;">
                        <div class="bot-inbox inbox border rounded p-2 mb-2" style="color:#1263c2"><b>입력중입니다...</b></div>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="input1" name="input1"
                            placeholder="철학자 이름을 입력하세요." onclick="clearInputValue('input1')">
                        <label for="input1">철학자 이름</label>
                    </div>
                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="input2" name="input2"
                            placeholder="점수를 입력하세요." onclick="clearInputValue('input2')">
                        <label for="input2">질문</label>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="hidden" id="old_prompt" name="old_prompt" value="">
                        <input type="hidden" id="old_result" name="old_result" value="">
                        <button type="submit" id="send-btn" class="btn btn-danger mb-2 mt-2"
                            style="width:100%">보내기</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function clearInputValue(inputId) {
            document.getElementById(inputId).value = '';
        }

        $(document).ready(function () {
            $("#send-btn").on("click", function () {

                const target = document.getElementById('send-btn');
                const target2 = document.getElementById('typing');

                $input1 = document.getElementById('input1').value;
                $input2 = document.getElementById('input2').value;
                $prompt = "철학자 이름: " + $input1 + "<br/>" + "질문: " + $input2;

                $old_prompt = $("#old_prompt").val();
                $old_result = $("#old_result").val();

                $("#prompt").val("");

                target.disabled = true;
                setTimeout(function () { target2.style.display = 'block'; }, 1000);

                $msg = '<div class="user-inbox inbox border rounded p-2 mb-2"><b>Guest</b><div class="msg-header"><p class="mb-0">' + $prompt + '</p></div></div>';
                $("#form").append($msg);

                $.ajax({
                    url: 'ai.php',
                    type: 'POST',
                    data: 'text=' + $prompt + '/////' + $old_prompt + '/////' + $old_result,
                    success: function (result) {
                        const target = document.getElementById('send-btn');
                        target.disabled = false;
                        const target2 = document.getElementById('typing');
                        target2.style.display = 'none';

                        $("#old_prompt").val($prompt);
                        $("#old_result").val(result);

                        $replay = '<div class="bot-inbox inbox border rounded p-2 mb-2" style="color:#1263c2"><b>철학자 챗봇</b><div class="msg-header"><p class="mb-0">' + result + '</p></div></div>';
                        $("#form").append($replay);
                        target2.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>