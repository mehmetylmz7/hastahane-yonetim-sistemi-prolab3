<?php
session_start();

if(isset($_SESSION["doktorTC"])) {
    $doktorTC = $_SESSION["doktorTC"];
} else {
    // Oturumda doktor TC bilgisi yoksa, uygun bir işlem yapabilirsiniz.
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

// Doktor bilgilerini getirme sorgusu
$query = "SELECT * FROM tbl_doktor WHERE doktorTC = '$doktorTC'";
$result = mysqli_query($baglanti, $query);

if(mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $doktorAd = $row['doktorAd'];
    $doktorSoyad = $row['doktorSoyad'];
    $doktorBrans = $row['doktorBrans'];
} else {
    echo "Doktor bilgileri bulunamadı.";
    exit;
}

// Form gönderilmiş mi kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen yeni bilgileri al
    $doktorAd = $_POST['doktor_ad'];
    $doktorSoyad = $_POST['doktor_soyad'];
    $doktorBrans = $_POST['doktor_brans'];

    // Veritabanında güncelleme işlemi
    $query_update = "UPDATE tbl_doktor SET doktorAd = '$doktorAd', doktorSoyad = '$doktorSoyad', 
                    doktorBrans = '$doktorBrans' WHERE doktorTC = '$doktorTC'";
    
    if (mysqli_query($baglanti, $query_update)) {
        echo "Doktor bilgileri başarıyla güncellendi.";
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
    <title>Doktor Bilgileri Güncelleme</title>
</head>
<body>
    <h1>Doktor Bilgilerini Güncelle</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="ad">Ad:</label><br>
        <input type="text" id="ad" name="doktor_ad" value="<?php echo $doktorAd; ?>"><br>

        <label for="soyad">Soyad:</label><br>
        <input type="text" id="soyad" name="doktor_soyad" value="<?php echo $doktorSoyad; ?>"><br>

        <label for="brans">Branş:</label><br>
        <select id="brans" name="doktor_brans">
            <option value="Kardiyoloji" <?php if($doktorBrans == "Kardiyoloji") echo "selected"; ?>>Kardiyoloji</option>
            <option value="Ortopedi" <?php if($doktorBrans == "Ortopedi") echo "selected"; ?>>Ortopedi</option>
            <option value="Göz Hastalıkları" <?php if($doktorBrans == "Göz Hastalıkları") echo "selected"; ?>>Göz Hastalıkları</option>
            <option value="Dahiliye" <?php if($doktorBrans == "Dahiliye") echo "selected"; ?>>Dahiliye</option>
            <option value="Nöroloji" <?php if($doktorBrans == "Nöroloji") echo "selected"; ?>>Nöroloji</option>
            <option value="Üroloji" <?php if($doktorBrans == "Üroloji") echo "selected"; ?>>Üroloji</option>
            <option value="Kulak Burun Boğaz" <?php if($doktorBrans == "Kulak Burun Boğaz") echo "selected"; ?>>Kulak Burun Boğaz</option>
            <option value="Kadın Hastalıkları ve Doğum" <?php if($doktorBrans == "Kadın Hastalıkları ve Doğum") echo "selected"; ?>>Kadın Hastalıkları ve Doğum</option>
            <option value="Psikiyatri" <?php if($doktorBrans == "Psikiyatri") echo "selected"; ?>>Psikiyatri</option>
            <option value="Dermatoloji" <?php if($doktorBrans == "Dermatoloji") echo "selected"; ?>>Dermatoloji</option>
        </select><br>

        <input type="submit" value="Güncelle">
    </form>

    <!-- Anasayfaya dön butonu -->
    <form action="http://localhost/courseapp/" method="get">
        <input type="submit" value="Anasayfaya Dön">
    </form>
</body>
</html>
