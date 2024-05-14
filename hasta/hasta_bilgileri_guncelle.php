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

// Veritabanı bağlantısı
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0)
{
   die("hata: ".mysqli_connect_errno());
}

// Hasta bilgilerini getirme sorgusu
$query = "SELECT * FROM tbl_hasta WHERE hastaTC = '$hastaTC'";
$result = mysqli_query($baglanti, $query);

if(mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $hastaAd = $row['hastaAd'];
    $hastaSoyad = $row['hastaSoyad'];
    $hastaTelefon = $row['hastaTelefon'];
    $hastaCinsiyet = $row['hastaCinsiyet'];
    $hastaAdres = $row['hastaAdres'];
    $hastaDogumTarihi = $row['hastaDogumTarihi'];
} else {
    echo "Hasta bilgileri bulunamadı.";
    exit;
}

// Form gönderilmiş mi kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen yeni bilgileri al
    $hastaAd = $_POST['hasta_ad'];
    $hastaSoyad = $_POST['hasta_soyad'];
    $hastaTelefon = $_POST['hasta_telefon'];
    $hastaCinsiyet = $_POST['hasta_cinsiyet'];
    $hastaAdres = $_POST['hasta_adres'];
    $hastaDogumTarihi = $_POST['hasta_dogum_tarihi'];

    // Veritabanında güncelleme işlemi
    $query_update = "UPDATE tbl_hasta SET hastaAd = '$hastaAd', hastaSoyad = '$hastaSoyad', 
                    hastaTelefon = '$hastaTelefon', hastaCinsiyet = '$hastaCinsiyet', 
                    hastaAdres = '$hastaAdres', hastaDogumTarihi = '$hastaDogumTarihi' 
                    WHERE hastaTC = '$hastaTC'";
    
    if (mysqli_query($baglanti, $query_update)) {
        echo "Hasta bilgileri başarıyla güncellendi.";
    } else {
        echo "Güncelleme işlemi sırasında bir hata oluştu: " . mysqli_error($baglanti);
    }
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Bilgileri Güncelleme</title>
</head>
<body>
    <h1>Hasta Bilgilerini Güncelle</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="ad">Ad:</label><br>
        <input type="text" id="ad" name="hasta_ad" value="<?php echo $hastaAd; ?>"><br>

        <label for="soyad">Soyad:</label><br>
        <input type="text" id="soyad" name="hasta_soyad" value="<?php echo $hastaSoyad; ?>"><br>

        <label for="telefon">Telefon:</label><br>
        <input type="text" id="telefon" name="hasta_telefon" value="<?php echo $hastaTelefon; ?>"><br>

        <label for="cinsiyet">Cinsiyet:</label><br>
        <input type="text" id="cinsiyet" name="hasta_cinsiyet" value="<?php echo $hastaCinsiyet; ?>"><br>

        <label for="adres">Adres:</label><br>
        <textarea id="adres" name="hasta_adres"><?php echo $hastaAdres; ?></textarea><br>

        <label for="dogum_tarihi">Doğum Tarihi:</label><br>
        <input type="date" id="dogum_tarihi" name="hasta_dogum_tarihi" value="<?php echo $hastaDogumTarihi; ?>"><br>

        <input type="submit" value="Güncelle">
    </form>

    <!-- Anasayfaya dön butonu -->
    <form action="http://localhost/courseapp/" method="get">
        <input type="submit" value="Anasayfaya Dön">
    </form>
</body>
</html>
