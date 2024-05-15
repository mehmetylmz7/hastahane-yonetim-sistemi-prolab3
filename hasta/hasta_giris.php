<?php
session_start();

if(isset($_SESSION["hastaTC"])) {
    $hastaTC = $_SESSION["hastaTC"];
} else {
    // Oturumda hasta TC bilgisi yoksa, uygun bir işlem yapabilirsiniz.
    // Örneğin, ana sayfaya yönlendirme yapabilirsiniz.
    header("Location: http://localhost/courseapp/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yönetici Paneli</title>
<style>
    .menu {
        display: flex;
        justify-content: space-around;
        background-color: #f2f2f2;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 10px;
    }

    .item {
        padding: 5px 10px;
    }

    .item a {
        font-size: 20px;
        text-decoration: none;
        color: #333;
    }

    .item a:hover {
        color: #555;
    }
</style>
</head>
<body>
<h1>Geçmiş Olsun <?php echo $hastaTC; ?></h1>

<div class="menu">
    <div class="item">
        <a href="hasta_randevu_goruntule.php">Radevularımı Goruntule</a>
    </div>
    <div class="item">
        <a href="hasta_tibbi_rapor_goruntule.php">Tıbbi Raporlarımı Goruntule</a>
    </div>
    <div class="item">
        <a href="hasta_bilgileri_guncelle.php">Bilgilerimi Güncelle</a>
    </div>
    <div class="item">
        <a href="hasta_randevu_al.php">Randevu Al</a>
    </div>
    <div class="item">
        <a href="hasta_randevu_iptal.php">Randevu İptal Et</a>
    </div>
    <div class="item">
        <a href="hasta_randevu_guncelle.php">Randevu Randevu Guncelle</a>
    </div>
    
</div>
</body>
</html>
