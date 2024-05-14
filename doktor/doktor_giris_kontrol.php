<?php
session_start();

const host = "localhost";
const username= "root";
const password="";
const database="hastanedb";

$hata_mesaji = ""; // Hata mesajını başlangıçta boş olarak tanımla

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["doktorTC"]) && isset($_POST["doktorSifre"])) {
        $doktorTC = $_POST["doktorTC"];
        $doktorSifre = $_POST["doktorSifre"];
        
       
     
        // Veritabanı bağlantısı
        $baglanti=mysqli_connect(host, username, password, database);

        if (mysqli_connect_errno()) {
            die("Veritabanına bağlanırken bir hata oluştu: " . mysqli_connect_error());
        }

        // Kullanıcı var mı kontrolü
        $query = "SELECT * FROM tbl_doktor WHERE doktorTC = ?";
        $stmt = mysqli_prepare($baglanti, $query);
        mysqli_stmt_bind_param($stmt, "s", $doktorTC);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Kullanıcı var, şifreyi kontrol et
            $query = "SELECT doktorSifre FROM tbl_doktor WHERE doktorTC = ?";
            $stmt = mysqli_prepare($baglanti, $query);
            mysqli_stmt_bind_param($stmt, "s", $doktorTC);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $db_sifre);
            mysqli_stmt_fetch($stmt);

            if ($doktorSifre === $db_sifre) {
                // Şifre doğru, oturum başlat
                $_SESSION["doktorTC"] = $doktorTC;
                header("Location: doktor_giris.php");
                exit;
            } else {
                // Şifre yanlış
                $hata_mesaji = "TC veya şifre yanlış.";
            }
        } else {
            // Kullanıcı yok
            $hata_mesaji = "Bu TC ile kayıtlı bir doktor bulunamadı.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($baglanti);
    } else {
        $hata_mesaji = "Doktor TC ve şifre alanları gereklidir.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MHRS SİSTEMİ</title>
</head>
<body>
    <h1>MHRS SİSTEMİ</h1>

    
    <div>
        <h2>Doktor Girişi</h2>
        <?php if(isset($hata_mesaji)) { ?>
            <p><?php echo $hata_mesaji; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- doktor girişi için form alanları buraya gelebilir -->
            <input type="text" name="doktorTC" placeholder="Doktor TC"><br>
            <input type="password" name="doktorSifre" placeholder="Şifre"><br>
            <input type="submit" value="Giriş Yap">
        </form>
        <!-- Anasayfaya dön butonu -->
<form action="http://localhost/courseapp/" method="get">
    <input type="submit" value="Anasayfaya Dön">
</form>
    </div>


 

</body>
</html>