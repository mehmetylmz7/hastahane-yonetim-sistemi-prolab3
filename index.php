



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MHRS SİSTEMİ</title>
</head>
<body>
    <h1>MHRS SİSTEMİ</h1>

    <img src="img/resim.jpg" alt="Hastane Logosu" style="float: right; margin-top: 10px; margin-right: 10px; width: 500px; height: 500px;">

    <div>
        <h2>Hasta Girişi</h2>
        <form action=" hasta/hasta_giris_kontrol.php" method="post">
            <!-- Hasta girişi için form alanları buraya gelebilir -->
            <input type="submit" value="Giriş Yap">
        </form>
    </div>

    <div>
        <h2>Hasta Kayıt</h2>
        <form action="hasta/hasta_kayit.php" method="post">
            <!-- Hasta kayıt için form alanları buraya gelebilir -->

            <input type="submit" value="Kayıt Ol">
        </form>
    </div>

    <div>
        <h2>Doktor Girişi</h2>
        <form action=" doktor/doktor_giris_kontrol.php" method="post">
            <!-- Doktor girişi için form alanları buraya gelebilir -->
            <input type="submit" value="Giriş Yap">
        </form>
    </div>

    <div>
        <h2>Yönetici Girişi</h2>
        <form action="yonetici/yonetici_giris_kontrol.php" method="post">
            <!-- Yönetici girişi için form alanları buraya gelebilir -->
            <input type="submit" value="Giriş Yap">
        </form>
    </div>

    <div>
        <h2>Veri Girişi</h2>
        <form action="veri_ekleme.php" method="post">
            <input type="submit" value="veri ekle">
        </form>
    </div>

    

</body>
</html>
