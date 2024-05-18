<?php
// Sunucu -> hastanedb2
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb2";

$baglanti = mysqli_connect(host, username, password, database);

if (mysqli_connect_errno() > 0) {
    die("Hata: " . mysqli_connect_error());
}

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
$sql_hastaID = "SELECT hastaid FROM tbl_hasta WHERE hastaTC = '$hastaTC'";
$result_hastaID = mysqli_query($baglanti, $sql_hastaID);

if ($result_hastaID) {
    $row_hastaID = mysqli_fetch_assoc($result_hastaID);
    $hastaID = $row_hastaID['hastaid'];

    // Hastanın mevcut ve bugünden sonraki randevularını çek
    $bugun = date('Y-m-d');
    $sql_randevular = "SELECT randevuid, randevuTarih, randevuSaati, doktorid FROM tbl_randevu WHERE hastaid = '$hastaID' AND randevuTarih > '$bugun'";
    $result_randevular = mysqli_query($baglanti, $sql_randevular);

    if ($result_randevular) {
        if (mysqli_num_rows($result_randevular) > 0) {
?>
<!DOCTYPE html>
<html lang="tr">
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
                while ($row_randevu = mysqli_fetch_assoc($result_randevular)) {
                    // Doktor adını ve branşını almak için doktorid kullanarak sorgu yap
                    $doktorid = $row_randevu['doktorid'];
                    $sql_doktor = "SELECT doktorAd, doktorBrans FROM tbl_doktor WHERE doktorid = '$doktorid'";
                    $result_doktor = mysqli_query($baglanti, $sql_doktor);
                    $row_doktor = mysqli_fetch_assoc($result_doktor);

                    echo "<option value='" . $row_randevu['randevuid'] . "'>" 
                        . "Randevu Tarihi: " . $row_randevu['randevuTarih'] . ", "
                        . "Randevu Saati: " . $row_randevu['randevuSaati'] . ", "
                        . "Doktor: " . $row_doktor['doktorAd'] . " - " . $row_doktor['doktorBrans'] 
                        . "</option>";
                }
            ?>
        </select><br><br>
        <label for="yeni_tarih">Yeni Randevu Tarihi:</label>
        <input type="date" id="yeni_tarih" name="yeni_tarih" required><br><br>
        <label for="yeni_saat">Yeni Randevu Saati:</label>
        <input type="time" id="yeni_saat" name="yeni_saat" required><br><br>
        <input type="submit" value="Randevuyu Güncelle">
    </form>
</body>
</html>
<?php
        } else {
            echo "Bugünden sonraki randevunuz bulunmamaktadır.";
        }
    } else {
        echo "Randevu sorgusu hatası: " . mysqli_error($baglanti);
    }
} else {
    echo "HastaID sorgusu hatası: " . mysqli_error($baglanti);
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>
