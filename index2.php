<?php
// Yönetici girişi için doğrulama
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yonetici_kullaniciadi = $_POST["yonetici_kullaniciadi"];
    $yonetici_sifre = $_POST["yonetici_sifre"];

    // Yönetici kullanıcı adı ve şifresi kontrolü
    if ($yonetici_kullaniciadi == "36505502848" && $yonetici_sifre == "mehmet123") {
        // Yönlendirme
        header("Location: yonetici/yonetici_giris.php");
        exit;
    } else {
        $hata_mesaji = "Yönetici kullanıcı adı veya şifre yanlış.";
    }
    
}
?>

<?php
session_start();

$hata_mesaji = ""; // Hata mesajını başlangıçta boş olarak tanımla

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["hastaTC"]) && isset($_POST["hastasifre"])) {
        $hastaTC = $_POST["hastaTC"];
        $hastasifre = $_POST["hastasifre"];
        
        // Veritabanı bağlantısı
        $baglanti = mysqli_connect("host", "username", "password", "database");

        if (mysqli_connect_errno()) {
            die("Veritabanına bağlanırken bir hata oluştu: " . mysqli_connect_error());
        }

        // Kullanıcı var mı kontrolü
        $query = "SELECT * FROM tbl_hasta WHERE hastaTC = ?";
        $stmt = mysqli_prepare($baglanti, $query);
        mysqli_stmt_bind_param($stmt, "s", $hastaTC);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Kullanıcı var, şifreyi kontrol et
            $query = "SELECT hastasifre FROM tbl_hasta WHERE hastaTC = ?";
            $stmt = mysqli_prepare($baglanti, $query);
            mysqli_stmt_bind_param($stmt, "s", $hastaTC);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $db_sifre);
            mysqli_stmt_fetch($stmt);

            if ($hastasifre === $db_sifre) {
                // Şifre doğru, oturum başlat
                $_SESSION["hastaTC"] = $hastaTC;
                header("Location: hasta/hasta_giris.php");
                exit;
            } else {
                // Şifre yanlış
                $hata_mesaji = "TC veya şifre yanlış.";
            }
        } else {
            // Kullanıcı yok
            $hata_mesaji = "Bu TC ile kayıtlı bir hasta bulunamadı.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($baglanti);
    } else {
        $hata_mesaji = "Hasta TC ve şifre alanları gereklidir.";
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

    <img src="img/resim.jpg" alt="Hastane Logosu" style="float: right; margin-top: 10px; margin-right: 10px; width: 500px; height: 500px;">

    <div>
        <h2>Hasta Girişi</h2>
        <?php if(isset($hata_mesaji)) { ?>
            <p><?php echo $hata_mesaji; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Hasta girişi için form alanları buraya gelebilir -->
            <input type="text" name="hastaTC" placeholder="Hasta TC"><br>
            <input type="password" name="hastasifre" placeholder="Şifre"><br>
            <input type="submit" value="Giriş Yap">
        </form>
    </div>

    <div>
        <h2>Hasta Kayıt</h2>
        <form action="hasta/hasta_kayit.php" method="post">
            <!-- Hasta kayıt için form alanları buraya gelebilir -->

            <input type="submit" value="Kayıt Ol">
        </form>
    </div>

    <div>
        <h2>Doktor Girişi</h2>
        <form action=" doktor/doktor_giris.php" method="post">
            <!-- Doktor girişi için form alanları buraya gelebilir -->
            <input type="text" name="doktorTC" placeholder="Doktor TC"><br>
            <input type="password" name="doktorSifre" placeholder="Şifre"><br>
            <input type="submit" value="Giriş Yap">
        </form>
    </div>

    <div>
        <h2>Yönetici Girişi</h2>
        <form action="yonetici/yonetici_giris.php" method="post">
            <!-- Yönetici girişi için form alanları buraya gelebilir -->
            <input type="text" name="yonetici_kullaniciadi" placeholder="Kullanıcı Adı"><br>
            <input type="password" name="yonetici_sifre" placeholder="Şifre"><br>
            <input type="submit" value="Giriş Yap">
        </form>
    </div>

    <div>
        <h2>Veri Girişi</h2>
        <form action="veri_ekleme.php" method="post">
            <input type="submit" value="veri ekle">
        </form>
    </div>

</body>
</html>
