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
const database = "hastanedb";

$baglanti = mysqli_connect(host, username, password, database);

if(mysqli_connect_errno() > 0) {
    die("hata: " . mysqli_connect_errno());
}

// Doktor seçeneklerini veritabanından al
$sql_doktorlar = "SELECT doktorAd, doktorSoyad, doktorBrans FROM tbl_doktor";
$result_doktorlar = mysqli_query($baglanti, $sql_doktorlar);

// Hata kontrolü
if (!$result_doktorlar) {
    die("Doktor sorgusu hatası: " . mysqli_error($baglanti));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Randevu Al</title>
<style>
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 2px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"], input[type="date"], select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Hoş Geldiniz <?php echo $hastaTC; ?></h2>
    <h3>Randevu Al</h3>
    <form action="randevu_kaydet.php" method="POST">
        <div class="form-group">
            <label for="randevu_tarihi">Randevu Tarihi:</label>
            <input type="date" id="randevu_tarihi" name="randevu_tarihi" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
        </div>
        <div class="form-group">
            <label for="randevu_saat">Randevu Saati:</label>
            <input type="time" id="randevu_saat" name="randevu_saat" required>
        </div>
        <div class="form-group">
            <label for="doktor">Doktor Seçimi:</label>
            <select id="doktor" name="doktor" required>
                <option value="">Doktor Seçin</option>
                <?php
                // Doktor seçeneklerini ekrana yazdır
                while ($row_doktor = mysqli_fetch_assoc($result_doktorlar)) {
                    $doktorAd = $row_doktor['doktorAd'];
                    $doktorSoyad = $row_doktor['doktorSoyad'];
                    $doktorBrans = $row_doktor['doktorBrans'];
                    echo "<option value='$doktorAd $doktorSoyad'>$doktorAd $doktorSoyad ($doktorBrans)</option>";
                }
                ?>
            </select>
        </div>
        <input type="submit" value="Randevu Al">
    </form>
</div>
</body>
</html>
