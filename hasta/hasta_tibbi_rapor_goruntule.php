<?php
session_start();

const host = "localhost";
const username = "root";
const password = "";
const database = "hastanedb2";

$hata_mesaji = ""; // Hata mesajını başlangıçta boş olarak tanımla
$tibbi_raporlar = []; // Tıbbi raporları tutmak için bir dizi
$hasta_bilgileri = []; // Hasta bilgilerini tutmak için bir dizi
$gecmis_randevular = []; // Geçmiş randevuları tutmak için bir dizi

if (!isset($_SESSION["hastaTC"])) {
    header("Location: hasta_giris.php");
    exit;
}

$hastaTC = $_SESSION["hastaTC"];

// Veritabanı bağlantısı
$baglanti = mysqli_connect(host, username, password, database);

if (mysqli_connect_errno()) {
    die("Veritabanına bağlanırken bir hata oluştu: " . mysqli_connect_error());
}

// Hasta bilgilerini almak için sorgu
$query = "SELECT * FROM tbl_hasta WHERE hastaTC = ?";
$stmt = mysqli_prepare($baglanti, $query);
mysqli_stmt_bind_param($stmt, "s", $hastaTC);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$hasta_bilgileri = mysqli_fetch_assoc($result);
$hastaid = $hasta_bilgileri['hastaid'];
mysqli_stmt_close($stmt);

// Tıbbi raporları almak için sorgu
$query = "SELECT raporid, raporTarihi, raporIcerigi FROM tbl_tibbirapor WHERE hastaid = ?";
$stmt = mysqli_prepare($baglanti, $query);
mysqli_stmt_bind_param($stmt, "i", $hastaid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $tibbi_raporlar[] = $row;
}

mysqli_stmt_close($stmt);

// Geçmiş randevuları ve doktor bilgilerini almak için sorgu
$query = "SELECT r.randevuid, r.randevuTarih, r.randevuSaati, r.randevuDurum, d.doktorAd, d.doktorSoyad, d.doktorBrans
          FROM tbl_randevu r
          JOIN tbl_doktor d ON r.doktorid = d.doktorid
          WHERE r.hastaid = ?";
$stmt = mysqli_prepare($baglanti, $query);
mysqli_stmt_bind_param($stmt, "i", $hastaid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $gecmis_randevular[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($baglanti);

// JSON verisi oluşturma
$hasta_verileri = [
    'hasta_bilgileri' => $hasta_bilgileri,
    'tibbi_raporlar' => $tibbi_raporlar,
    'gecmis_randevular' => $gecmis_randevular
];

$json_dosyasi_yolu = 'C:/xampp/htdocs/courseapp/tibbi_raporlar/' . $hastaTC . '_raporlar.json';

if (!file_exists(dirname($json_dosyasi_yolu))) {
    mkdir(dirname($json_dosyasi_yolu), 0777, true);
}

file_put_contents($json_dosyasi_yolu, json_encode($hasta_verileri, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tıbbi Raporlar</title>
</head>
<body>
    <h1>Tıbbi Raporlar</h1>

    <?php if(!empty($hata_mesaji)) { ?>
        <p><?php echo $hata_mesaji; ?></p>
    <?php } ?>

    <h2>Hasta T.C: <?php echo htmlspecialchars($hastaTC); ?></h2>

    <?php if(!empty($tibbi_raporlar)) { ?>
        <h3>Tıbbi Raporlar</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Rapor ID</th>
                    <th>Rapor Tarihi</th>
                    <th>Rapor İçeriği</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tibbi_raporlar as $rapor) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rapor['raporid']); ?></td>
                        <td><?php echo htmlspecialchars($rapor['raporTarihi']); ?></td>
                        <td><?php echo htmlspecialchars($rapor['raporIcerigi']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Bu T.C. kimlik numarası ile kayıtlı tıbbi rapor bulunamadı.</p>
    <?php } ?>

    <?php if(!empty($gecmis_randevular)) { ?>
        <h3>Geçmiş Randevular</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Randevu ID</th>
                    <th>Randevu Tarihi</th>
                    <th>Randevu Saati</th>
                    <th>Doktor Adı</th>
                    <th>Doktor Soyadı</th>
                    <th>Branş</th>
                    <th>Randevu Durumu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($gecmis_randevular as $randevu) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($randevu['randevuid']); ?></td>
                        <td><?php echo htmlspecialchars($randevu['randevuTarih']); ?></td>
                        <td><?php echo htmlspecialchars($randevu['randevuSaati']); ?></td>
                        <td><?php echo htmlspecialchars($randevu['doktorAd']); ?></td>
                        <td><?php echo htmlspecialchars($randevu['doktorSoyad']); ?></td>
                        <td><?php echo htmlspecialchars($randevu['doktorBrans']); ?></td>
                        <td><?php echo htmlspecialchars($randevu['randevuDurum']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Bu T.C. kimlik numarası ile kayıtlı geçmiş randevu bulunamadı.</p>
    <?php } ?>

    <p>JSON dosyası oluşturuldu: <?php echo htmlspecialchars($json_dosyasi_yolu); ?></p>

    <!-- Anasayfaya dön butonu -->
    <form action="http://localhost/courseapp/" method="get">
        <input type="submit" value="Anasayfaya Dön">
    </form>
    <form action="hasta_pdf_olustur.php" method="post">
    <input type="submit" name="pdf_olustur" value="PDF Oluştur">
</form>


</body>
</html>
