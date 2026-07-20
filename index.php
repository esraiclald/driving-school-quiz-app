<?php
    require_once "conn.php";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Sade Sürücü Kursu online deneme testleri">
<title>Testler - Sade Sürücü Kursu</title>

<link rel="icon" href="uploads/logo.png" type="image/x-icon" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body id="index-page">

<div class="page-wrapper-index">

<h1 class="title">Testler</h1>

<div class="accordion" id="accordionTests">

<?php
$query = "SELECT Tests.Test_ID, Tests.Title, Categories.Category
          FROM Tests
          INNER JOIN Categories ON Tests.Category_ID = Categories.Category_ID 
          ORDER BY Categories.Category, Tests.Test_ID";

$stmt = mysqli_prepare($conn, $query);

if (!$stmt)
{
    exit("Veritabanı sorgusu hazırlanamadı.");
}

if (!mysqli_stmt_execute($stmt))
{
    exit("Sorgu çalıştırılamadı.");
}

$result = mysqli_stmt_get_result($stmt);

$currentCategory = "";
$accordionIndex = 0;

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {

        if($currentCategory != $row["Category"])
        {

            if($currentCategory != "")
            {
                echo "</div></div></div></div>";
            }

            $accordionIndex++;
            $currentCategory = $row["Category"];

            echo '
            <div class="accordion-item">

                <h2 class="accordion-header">

                    <button class="accordion-button '.($accordionIndex==1 ? "" : "collapsed").'"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse'.$accordionIndex.'">

                       '.htmlspecialchars($currentCategory, ENT_QUOTES, 'UTF-8').'

                    </button>

                </h2>

                <div id="collapse'.$accordionIndex.'"
                     class="accordion-collapse collapse '.($accordionIndex==1 ? "show" : "").'"
                     data-bs-parent="#accordionTests">

                    <div class="accordion-body">

                        <div class="cards">
            ';
        }

        echo '
            <div class="quiz-card">

               

                <div class="card-title">
                    '.htmlspecialchars($row["Title"], ENT_QUOTES, 'UTF-8').'
                </div>

                <a class="start-btn" href="test.php?testID='.$row["Test_ID"].'">
                    Teste Başla
                </a>

            </div>
        ';
    }

    echo "</div></div></div></div>";
}
else
{

    echo '
   <div class="card shadow-sm border-0 text-center p-5">

        <h3 class="mt-3">Henüz test bulunmuyor</h3>

        <p class="text-muted mb-0">
            Yayınlanmış bir test bulunmuyor.
            Lütfen daha sonra tekrar deneyiniz.
        </p>
    </div>';
}

    mysqli_stmt_close($stmt);
?>

</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>