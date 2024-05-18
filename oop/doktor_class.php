<?php
class Doktor {
    private $db;

    public function __construct($host, $username, $password, $database) {
        $this->db = new mysqli($host, $username, $password, $database);

        if ($this->db->connect_error) {
            die("Veritabanı bağlantı hatası: " . $this->db->connect_error);
        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function doktorEkle($doktorAd, $doktorSoyad, $doktorBrans, $doktorTC, $doktorSifre) {
        $sql = "INSERT INTO tbl_doktor (doktorAd, doktorSoyad, doktorBrans, doktorTC, doktorSifre) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sssss', $doktorAd, $doktorSoyad, $doktorBrans, $doktorTC, $doktorSifre);
        return $stmt->execute();
    }

    public function doktorGuncelle($doktorid, $doktorAd, $doktorSoyad, $doktorBrans, $doktorTC, $doktorSifre) {
        $sql = "UPDATE tbl_doktor SET doktorAd = ?, doktorSoyad = ?, doktorBrans = ?, doktorTC = ?, doktorSifre = ? WHERE doktorid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sssssi', $doktorAd, $doktorSoyad, $doktorBrans, $doktorTC, $doktorSifre, $doktorid);
        return $stmt->execute();
    }

    public function doktorSil($doktorid) {
        $sql = "DELETE FROM tbl_doktor WHERE doktorid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $doktorid);
        return $stmt->execute();
    }

    public function doktorlariGetir() {
        $sql = "SELECT * FROM tbl_doktor";
        $result = $this->db->query($sql);
        $doktorlar = [];
        while ($row = $result->fetch_assoc()) {
            $doktorlar[] = $row;
        }
        return $doktorlar;
    }

    public function doktorGetir($doktorid) {
        $sql = "SELECT * FROM tbl_doktor WHERE doktorid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $doktorid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
