<?php
$code = $_GET["code"] ?? "";

switch ($code) {
    case "404":
        $title = "Test Bulunamadı";
        $message = "Aradığınız test mevcut değil.";
        break;

    case "db":
        $title = "Veritabanı Hatası";
        $message = "Lütfen daha sonra tekrar deneyiniz.";
        break;

    default:
        $title = "Bir Hata Oluştu";
        $message = "Beklenmeyen bir hata meydana geldi.";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hata - Sade Sürücü Kursu</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="uploads/logo.png" type="image/x-icon">
</head>
<body>

<div class="error-container">

    <div class="error-card">

        <h1>🚫 <?php echo $title; ?></h1>

        <p>
            <?php echo $message;?>
        </p>
        <button onclick="location.href='index.php'">
            Testlere Dön
        </button>

    </div>

</div>

</body>
</html>