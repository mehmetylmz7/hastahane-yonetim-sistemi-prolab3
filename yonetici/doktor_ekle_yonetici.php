<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doktor Kayıt</title>
</head>
<body>
    <h1>Yeni Doktor Kaydı</h1>

    <h2>Doktordan Bilgiler</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="ad">Ad:</label><br>
        <input type="text" id="ad" name="doktor_ad"><br>

        <label for="soyad">Soyad:</label><br>
        <input type="text" id="soyad" name="doktor_soyad"><br>

        <label for="brans">Branş:</label><br>
        <input type="text" id="brans" name="doktor_brans"><br>

        <label for="tc">TC Numarası:</label><br>
        <input type="text" id="tc" name="doktor_tc"><br>

        <label for="sifre">Şifre:</label><br>
        <input type="password" id="sifre" name="doktor_sifre"><br>

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
const database="hastanedb";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0)
{
   die("hata: ".mysqli_connect_errno());
}

// Formdan gelen verileri al
$doktor_ad = $_POST['doktor_ad'];
$doktor_soyad = $_POST['doktor_soyad'];
$doktor_brans = $_POST['doktor_brans'];
$doktor_tc = $_POST['doktor_tc'];
$doktor_sifre = $_POST['doktor_sifre'];

// Veri doğrulama
if (empty($doktor_ad) || empty($doktor_soyad) || empty($doktor_brans) || empty($doktor_tc) || empty($doktor_sifre)) {
    // Eğer herhangi bir alan boşsa
    echo "Lütfen tüm alanları doldurun.";
} else {
    // Eğer tüm alanlar doluysa, veritabanına ekleme sorgusunu yap
    $query = "INSERT INTO tbl_doktor (doktorAd, doktorSoyad, doktorBrans, doktorTC, doktorSifre)
              VALUES ('$doktor_ad', '$doktor_soyad', '$doktor_brans', '$doktor_tc', '$doktor_sifre')";
    
    // Veritabanına ekleme sorgusunu çalıştır
    if (mysqli_query($baglanti, $query)) {
        echo "Doktor başarıyla kaydedildi.";
    } else {
        echo "Hata oluştu: " . mysqli_error($baglanti);
    }
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>
