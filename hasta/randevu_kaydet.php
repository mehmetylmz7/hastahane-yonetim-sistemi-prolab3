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

// Formdan gönderilen verileri al
$randevu_tarihi = $_POST['randevu_tarihi'];
$randevu_saat = $_POST['randevu_saat'];
$doktor = $_POST['doktor'];

// Veritabanı bağlantısı
const host = "localhost";
const username = "root";
const password = "";
const database = "hastanedb";

$baglanti = mysqli_connect(host, username, password, database);

if(mysqli_connect_errno() > 0) {
    die("hata: " . mysqli_connect_errno());
}

// Doktor adını ve soyadını ayırmak için boşluğa göre parçala
$doktor_parts = explode(" ", $doktor);
$doktorAd = $doktor_parts[0];
$doktorSoyad = $doktor_parts[1];

// Doktorun ID'sini bul
$sql_doktorID = "SELECT doktorid FROM tbl_doktor WHERE doktorAd = '$doktorAd' AND doktorSoyad = '$doktorSoyad'";
$result_doktorID = mysqli_query($baglanti, $sql_doktorID);

if (!$result_doktorID) {
    die("Doktor ID sorgusu hatası: " . mysqli_error($baglanti));
}

$row_doktorID = mysqli_fetch_assoc($result_doktorID);
$doktorID = $row_doktorID['doktorid'];

// Hasta ID'sini bul
$sql_hastaID = "SELECT hastaID FROM tbl_hasta WHERE hastaTC = '$hastaTC'";
$result_hastaID = mysqli_query($baglanti, $sql_hastaID);

if (!$result_hastaID) {
    die("Hasta ID sorgusu hatası: " . mysqli_error($baglanti));
}

$row_hastaID = mysqli_fetch_assoc($result_hastaID);
$hastaID = $row_hastaID['hastaID'];

// Yeni randevuID oluşturmak için en yüksek randevuID'yi bul
$sql_max_randevuID = "SELECT MAX(randevuid) AS max_randevuid FROM tbl_randevu";
$result_max_randevuID = mysqli_query($baglanti, $sql_max_randevuID);

if (!$result_max_randevuID) {
    die("En yüksek randevuID sorgusu hatası: " . mysqli_error($baglanti));
}

$row_max_randevuID = mysqli_fetch_assoc($result_max_randevuID);
$max_randevuID = $row_max_randevuID['max_randevuid'];
$new_randevuID = $max_randevuID + 1;

// Yeni randevu eklemek için SQL INSERT INTO
$sql_insert_randevu = "INSERT INTO tbl_randevu (randevuid, randevuTarih, randevuSaati, doktorid, randevuDurum, hastaid) 
VALUES ('$new_randevuID', '$randevu_tarihi', '$randevu_saat', '$doktorID', 'Aldi', '$hastaID')";

if (mysqli_query($baglanti, $sql_insert_randevu)) {
    echo "Yeni randevu başarıyla eklendi.";
} else {
    echo "Randevu eklerken hata oluştu: " . mysqli_error($baglanti);
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>
