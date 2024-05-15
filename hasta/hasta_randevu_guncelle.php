<?php
// sunucu -> hastanedb
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0) {
    die("hata: ".mysqli_connect_errno());
}

echo "MySQL bağlantısı oluşturuldu";

// Oturum başlat
session_start();

// Eğer oturum yoksa veya hastaTC oturumda tanımlı değilse, kullanıcıyı yönlendir.
if (!isset($_SESSION["hastaTC"])) {
    header("Location: http://localhost/courseapp/");
    exit;
}

// Oturumda hasta TC bilgisi mevcut ise, onu al.
$hastaTC = $_SESSION["hastaTC"];

// Hasta TC'sine göre hastaID'yi bul
$sql_hastaID = "SELECT hastaID FROM tbl_hasta WHERE hastaTC = '$hastaTC'";
$result_hastaID = mysqli_query($baglanti, $sql_hastaID);

if ($result_hastaID) {
    $row_hastaID = mysqli_fetch_assoc($result_hastaID);
    $hastaID = $row_hastaID['hastaID'];

    // Doktorları ve branşlarını çek
    $sql_doktorlar = "SELECT doktorid, doktorAd, doktorBrans FROM tbl_doktor";
    $result_doktorlar = mysqli_query($baglanti, $sql_doktorlar);

    if ($result_doktorlar) {
        if (mysqli_num_rows($result_doktorlar) > 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randevu Güncelle</title>
</head>
<body>
    <h1>Randevu Güncelle</h1>
    <form action="randevu_guncelle.php" method="post">
        <label for="randevu_id">Güncellemek istediğiniz randevuyu seçiniz:</label><br>
        <select id="randevu_id" name="randevu_id">
            <?php
                while($row_doktor = mysqli_fetch_assoc($result_doktorlar)) {
                    echo "<option value='" . $row_doktor['doktorid'] . "'>" . $row_doktor['doktorAd'] . " - " . $row_doktor['doktorBrans'] . "</option>";
                }
            ?>
        </select><br><br>
        <input type="submit" value="Randevuyu Güncelle">
    </form>
</body>
</html>
<?php
        } else {
            echo "Henüz randevunuz bulunmamaktadır.";
        }
    } else {
        echo "Doktor sorgusu hatası: " . mysqli_error($baglanti);
    }
} else {
    echo "HastaID sorgusu hatası: " . mysqli_error($baglanti);
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);

echo "MySQL bağlantısı kapatıldı";
?>
