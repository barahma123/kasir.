<?php
session_start(); // Memulai sesi PHP

include "koneksi.php"; // Menyertakan file koneksi.php yang berisi kode untuk menghubungkan ke database

// Pastikan koneksi database berhasil
if (!$db) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengecek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    // Mengambil ID barang dari parameter URL dan sanitasi input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Validasi ID untuk memastikan itu adalah angka
    if (filter_var($id, FILTER_VALIDATE_INT) !== false) {
        // Menyusun query SQL untuk menghapus data barang dari tabel 'barang' berdasarkan ID menggunakan prepared statement
        $sql = "DELETE FROM barang WHERE id_barang = ?";
        if ($stmt = mysqli_prepare($db, $sql)) {
            // Bind parameter
            mysqli_stmt_bind_param($stmt, "i", $id);

            // Menjalankan query SQL pada database
            if (mysqli_stmt_execute($stmt)) {
                // Jika berhasil, set pesan sukses dalam session dan arahkan ke halaman lihat.php
                $_SESSION['pesan'] = "Berhasil menghapus barang.";
                header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
                exit(); // Hentikan eksekusi script setelah redirect
            } else {
                // Jika gagal, set pesan error dalam session dan arahkan kembali ke halaman lihat.php
                $_SESSION['error'] = "Gagal menghapus barang. Silakan coba lagi.";
                error_log("Gagal menghapus barang: " . mysqli_stmt_error($stmt)); // Log kesalahan ke file log
                header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
                exit(); // Hentikan eksekusi script setelah redirect
            }
            mysqli_stmt_close($stmt);
        } else {
            // Jika gagal mempersiapkan statement, set pesan error dalam session dan arahkan kembali ke halaman lihat.php
            $_SESSION['error'] = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
            error_log("Gagal mempersiapkan query: " . mysqli_error($db)); // Log kesalahan ke file log
            header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
            exit(); // Hentikan eksekusi script setelah redirect
        }
    } else {
        // Jika ID tidak valid, set pesan error dalam session dan arahkan kembali ke halaman lihat.php
        $_SESSION['error'] = "ID tidak valid.";
        header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
        exit(); // Hentikan eksekusi script setelah redirect
    }
} else {
    // Jika parameter 'id' tidak ada di URL, set pesan error dalam session dan arahkan kembali ke halaman lihat.php
    $_SESSION['error'] = "ID barang tidak ditemukan.";
    header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
    exit(); // Hentikan eksekusi script setelah redirect
}

// Menutup koneksi database
mysqli_close($db);
?>
