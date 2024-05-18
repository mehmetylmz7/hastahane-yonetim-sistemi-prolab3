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
const username = "root";
const password = "";
const database = "hastanedb2";

$baglanti = mysqli_connect(host, username, password, database);

if(mysqli_connect_errno() > 0) {
    die("hata: " . mysqli_connect_errno());
}

// Hastanın mevcut ve bugünden sonraki randevularını al
$current_date = date('Y-m-d');
$sql_randevular = "SELECT r.randevuid, r.randevuTarih, r.randevuSaati, d.doktorAd, d.doktorSoyad FROM tbl_randevu r
                   INNER JOIN tbl_doktor d ON r.doktorid = d.doktorid
                   WHERE r.hastaid = (SELECT hastaid FROM tbl_hasta WHERE hastaTC = '$hastaTC')
                   AND r.randevuTarih >= '$current_date'";
$result_randevular = mysqli_query($baglanti, $sql_randevular);

// Hata kontrolü
if (!$result_randevular) {
    die("Randevu sorgusu hatası: " . mysqli_error($baglanti));
}

// Randevu iptal işlemi
if(isset($_POST['iptal'])){
    $randevu_id = $_POST['randevu_id'];
    $sql_iptal = "DELETE FROM tbl_randevu WHERE randevuid = $randevu_id";
    $result_iptal = mysqli_query($baglanti, $sql_iptal);
    if($result_iptal){
        // İptal başarılı
        header("Location: http://localhost/courseapp/randevu_iptal.php");
        exit;
    } else {
        // İptal hatası
        echo "Randevu iptali sırasında bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Randevu İptal</title>
</head>
<body>
<div class="container">
    <h2>Hoş Geldiniz <?php echo $hastaTC; ?></h2>
    <h3>Randevu İptal</h3>
    <form action="" method="POST">
        <?php
        while ($row_randevu = mysqli_fetch_assoc($result_randevular)) {
            $randevu_id = $row_randevu['randevuid'];
            $randevu_tarihi = $row_randevu['randevuTarih'];
            $randevu_saat = $row_randevu['randevuSaati'];
            $doktor_ad = $row_randevu['doktorAd'];
            $doktor_soyad = $row_randevu['doktorSoyad'];
            echo "<div>Randevu Tarihi: $randevu_tarihi, Randevu Saati: $randevu_saat, Doktor: $doktor_ad $doktor_soyad</div>";
            echo "<button type='submit' name='iptal' value='iptal'>İptal</button>";
            echo "<input type='hidden' name='randevu_id' value='$randevu_id'>";
        }
        ?>
    </form>
</div>
</body>
</html>
