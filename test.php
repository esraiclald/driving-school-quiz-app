<?php
    require_once "conn.php";

    if (!isset($_GET["testID"]) || !is_numeric($_GET["testID"])) 
    {
        header("Location: error.php");
        exit;
    }

    $testID = (int)$_GET["testID"];

    $stmt = mysqli_prepare($conn, "SELECT Title FROM Tests WHERE Test_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $testID);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $test = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

    if (!$test) 
    {
        header("Location: error.php");
        exit;
    }

    $title = $test["Title"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> - Sade Sürücü Kursu</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="uploads/logo.png" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body id="test-page">
    <div class="page-wrapper">
        <section class="quiz-container" >
            <div class="left-panel">
                <div class="title-box">
                    <p style= "font-size:40px;" id="title">
                        <?php
                            echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
                        ?>
                    </p>
                </div>
            <div class="progress-wrapper">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <br>
            <div class="question-num-text">
                <p id="question-number">Soru 1 / 50</p>
            </div>
            <div id="questionImageContainer"></div>
            <div id="questionVideoContainer"></div>
            <div class="question-text" id="questionText">
                <p>
                    Aksine bir işaret bulunmadıkça yap-işlet-devret modeliyle
                    işletilen otoyollarda, otomobiller için azami hız saatte
                    kaç kilometredir?
                </p>
            </div>
            <div class="choices-box" id="choicesBox">                
            </div>
            <div class="buttons-box" >
                <button class="btn-previous" id="btn_Previous">Geri</button>
                <button class="btn-next" id="btn_Next">İleri</button>
            </div>      
        </section>
        <div class="right-panel-box">
            <div class="mobile-top-row">
                <section class="right-panel-top">
                    <div class="score-box">
                        <center><p id="score-text">0</p></center>
                        <center><p id="score-counter-text"> 0 soruyu doğru cevapladınız.</p></center>
                    </div>
                </section>

        <section class="right-panel-middle">
            <div class="timer-box">
                <img src="uploads/stopwatch_icon.png" width="28" height="28">
                <p id="timer-box-text">45:00</p>
            </div>
        </section>

    </div>

    <section class="right-panel-bottom">
        <details class="pill-dropdown" open>
            <summary>Sorular ▼</summary>
            <div class="questions-box" id="questionsBox">
            </div>
        </details>
    </section>

    </div>
</div>
        
        <div id="resultModal" class="modal-overlay" style="display:none;">
            <div class="modal-content">
                <h1>🎉 Test Tamamlandı!</h1>
                <p id="result-score"></p>
                <p id="result-message"></p>
                <button  class="modal-btn" onclick="location.href='index.php'">Testlere Dön</button>
            </div>
        </div>
    </div>
 <?php
    $questions = [];

    $stmtQuestions = mysqli_prepare($conn, "SELECT * FROM Questions WHERE Test_ID = ?");
    mysqli_stmt_bind_param($stmtQuestions, "i", $testID);
    mysqli_stmt_execute($stmtQuestions);

    $result = mysqli_stmt_get_result($stmtQuestions);
        while ($question = mysqli_fetch_assoc($result))
        {
            $questionID = $question["Question_ID"];

            $questionObject = 
            [
                "questionText" => $question["Question_Text"],
                "questionImage" => $question["Question_Image"],
                "questionVideo" => $question["Question_Video"],
                "choices" => [],
                "answered" => false,
                "selectedChoice" => null,
                "isVisited" => false,
                "isAnsweredCorrectly" => null
            ];
           

            $stmtChoices = mysqli_prepare($conn, "SELECT * FROM Choices WHERE Question_ID = ?");
            mysqli_stmt_bind_param($stmtChoices, "i", $questionID);
            mysqli_stmt_execute($stmtChoices);

            $choicesResult = mysqli_stmt_get_result($stmtChoices);

            while ($choice = mysqli_fetch_assoc($choicesResult))
            {
                $questionObject["choices"][] = [
                                                "text" => $choice["Choice_Text"],
                                                "image" => $choice["Choice_Image"],
                                                "isCorrect" => (bool)$choice["IsCorrect"]
                                                ];             
            }
            mysqli_stmt_close($stmtChoices);
            $questions[] = $questionObject;     
        }
         mysqli_stmt_close($stmtQuestions);
    
?>
    <script> const questions = <?php echo json_encode($questions); ?>; </script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>
   
</body>
</html>