<?php
session_start(); // Memulai session PHP agar bisa menggunakan variabel session

include "koneksi.php"; // Menyertakan file koneksi.php yang berisi kode untuk menghubungkan ke database

// Mengecek apakah form telah disubmit
if (isset($_POST['submit'])) {
    // Mengambil data dari form dan melakukan sanitasi
    $nama = trim($_POST['nama_barang']);
    $harga = trim($_POST['harga_barang']);
    $stok = trim($_POST['stok_barang']);

    // Validasi input
    if (empty($nama) || !is_numeric($harga) || !is_numeric($stok) || $harga < 0 || $stok < 0) {
        die("Input tidak valid. Pastikan semua data diisi dengan benar.");
    }

    // Menyusun query SQL untuk memasukkan data barang ke tabel 'barang'
    $sql = "INSERT INTO barang (nama_barang, harga_barang, stok_barang) VALUES (?, ?, ?)";

    // Menyiapkan statement
    if ($stmt = $db->prepare($sql)) {
        // Mengikat parameter
        $stmt->bind_param("sii", $nama, $harga, $stok);

        // Menjalankan statement
        if ($stmt->execute()) {
            // Jika berhasil, set pesan sukses dalam session dan arahkan ke halaman lihat.php
            $_SESSION['pesan'] = "Berhasil menambah barang";
            header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
            exit(); // Hentikan eksekusi script setelah redirect
        } else {
            // Jika gagal, tampilkan pesan error
            die("Gagal menambah barang: " . $stmt->error);
        }

        // Menutup statement
        $stmt->close();
    } else {
        die("Gagal menyiapkan statement: " . $db->error);
    }

    // Menutup koneksi database
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
</head>
<body>
    <h2>Form Tambah Barang</h2>
    <!-- Form untuk menambah barang -->
    <form action="tambah.php" method="post">
        <label for="nama_barang">Nama Barang:</label><br>
        <!-- Input untuk nama barang -->
        <input type="text" id="nama_barang" name="nama_barang" required><br><br>
        <label for="harga_barang">Harga Barang:</label><br>
        <!-- Input untuk harga barang -->
        <input type="number" id="harga_barang" name="harga_barang" required min="0"><br><br>
        <label for="stok_barang">Stok Barang:</label><br>
        <!-- Input untuk stok barang -->
        <input type="number" id="stok_barang" name="stok_barang" required min="0"><br><br>
        <!-- Tombol submit untuk mengirim data ke server -->
        <input type="submit" value="Tambah Barang" name="submit">
        <!-- Link untuk kembali ke halaman lihat.php -->
        <a href="lihat.php">Kembali</a>
    </form>
</body>
</html>

