<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Kayıt</title>
</head>
<body>
    <h1>Geçmiş Olsun! Yeni Hasta Kaydı</h1>

    <h2>Hastadan Bilgiler</h2>
    <form action="hasta_ekle_yonetici.php" method="post">
        <label for="ad">Ad:</label><br>
        <input type="text" id="ad" name="hasta_ad"><br>

        <label for="soyad">Soyad:</label><br>
        <input type="text" id="soyad" name="hasta_soyad"><br>

        <label for="tc">TC Numarası:</label><br>
        <input type="text" id="tc" name="hasta_tc"><br>

        <label for="sifre">Şifre:</label><br>
        <input type="password" id="sifre" name="hasta_sifre"><br>

        <label for="dogum_tarihi">Doğum Tarihi:</label><br>
        <input type="date" id="dogum_tarihi" name="hasta_dogum_tarihi"><br>

        <label for="cinsiyet">Cinsiyet:</label><br>
        <select id="cinsiyet" name="hasta_cinsiyet">
            <option value="Erkek">Erkek</option>
            <option value="Kadın">Kadın</option>
            <option value="Diğer">Diğer</option>
        </select><br>

        <label for="telefon">Telefon Numarası:</label><br>
        <input type="text" id="telefon" name="hasta_telefon"><br>

        <label for="adres">Adres:</label><br>
        <textarea id="adres" name="hasta_adres"></textarea><br>

        <input type="submit" value="Kayıt Ol">
    </form>

    <!-- Anasayfaya dön butonu -->
<form action="http://localhost/courseapp/" method="get">
    <input type="submit" value="Anasayfaya Dön">
</form>


</body>
</html>


<?php

//veri tabanı baglantısı
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb2";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0)
{
   die("hata: ".mysqli_connect_errno());
}


// Formdan gelen verileri al
$hasta_ad = $_POST['hasta_ad'];
$hasta_soyad = $_POST['hasta_soyad'];
$hasta_tc = $_POST['hasta_tc'];
$hasta_sifre = $_POST['hasta_sifre'];
$hasta_dogum_tarihi = $_POST['hasta_dogum_tarihi'];
$hasta_cinsiyet = $_POST['hasta_cinsiyet'];
$hasta_telefon = $_POST['hasta_telefon'];
$hasta_adres = $_POST['hasta_adres'];

// Veri doğrulama
if (empty($hasta_ad) || empty($hasta_soyad) || empty($hasta_tc) || empty($hasta_sifre) || empty($hasta_dogum_tarihi) || empty($hasta_cinsiyet) || empty($hasta_telefon) || empty($hasta_adres)) {
    // Eğer herhangi bir alan boşsa
    echo "Lütfen tüm alanları doldurun.";
} else {
    // Eğer tüm alanlar doluysa, veritabanına ekleme sorgusunu yap
    $query = "INSERT INTO tbl_hasta (hastaAd, hastaSoyad, hastaTC, hastaTelefon, hastasifre, hastaCinsiyet, hastaAdres, hastaDogumTarihi)
              VALUES ('$hasta_ad', '$hasta_soyad', '$hasta_tc', '$hasta_telefon', '$hasta_sifre', '$hasta_cinsiyet', '$hasta_adres', '$hasta_dogum_tarihi')";
    
    // Veritabanına ekleme sorgusunu çalıştır
    if (mysqli_query($baglanti, $query)) {
        echo "Hasta başarıyla kaydedildi.";
    } else {
        echo "Hata oluştu: " . mysqli_error($baglanti);
    }
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);


?>
