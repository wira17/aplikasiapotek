<?php
session_start();
include 'koneksi.php'; // koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form login
    $nik           = mysqli_real_escape_string($conn, $_POST['nik']);
    $password      = mysqli_real_escape_string($conn, $_POST['password']);
    $input_captcha = $_POST['captcha'] ?? '';

    // Validasi CAPTCHA
    if (empty($input_captcha) || $input_captcha !== ($_SESSION['captcha'] ?? '')) {
        echo "<script>alert('Kode keamanan salah!'); window.location.href='login.php';</script>";
        exit;
    }
    // Hapus CAPTCHA dari session agar tidak bisa dipakai ulang
    unset($_SESSION['captcha']);

    // Cek user berdasarkan NIK
    $sql   = "SELECT * FROM users WHERE nik='$nik' LIMIT 1";
    $query = mysqli_query($conn, $sql);

    if ($query && mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        // Cek status akun
        if ($user['status'] !== 'aktif') {
            echo "<script>alert('Maaf akun anda belum aktif, silahkan hubungi admin'); window.location.href='login.php';</script>";
            exit;
        }

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Login sukses: buat session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['nama_user'] = $user['nama'];  
            $_SESSION['nik']       = $user['nik'];  
            $_SESSION['email']     = $user['email'];

            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Password salah
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
            exit;
        }
    } else {
        // NIK tidak ditemukan
        echo "<script>alert('NIK tidak ditemukan!'); window.location.href='login.php';</script>";
        exit;
    }
} else {
    // Jika bukan POST, redirect ke login
    header("Location: login.php");
    exit;
}
?>
