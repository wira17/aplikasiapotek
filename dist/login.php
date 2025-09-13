<?php
session_start();
include 'koneksi.php'; // pastikan koneksi ke DB

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nik = $_POST['nik'] ?? '';
    $password = $_POST['password'] ?? '';

    if(empty($nik) || empty($password)){
        $_SESSION['flash_error'] = "NIK dan password harus diisi!";
        header("Location: login.php");
        exit;
    }

    // Ambil user dari database
    $stmt = $conn->prepare("SELECT id, nama, password FROM pengguna WHERE nik = ?");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows === 0){
        $_SESSION['flash_error'] = "NIK tidak ditemukan!";
        header("Location: login.php");
        exit;
    }

    $stmt->bind_result($user_id, $nama_user, $hash_password);
    $stmt->fetch();

    // Cek password
if(!password_verify($password, $hash_password)){
    $_SESSION['flash_error'] = "Password salah!";
    header("Location: login.php"); // perbaiki dari transaksi_penjualan.php
    exit;
}


    // Login sukses: simpan session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['nama_user'] = $nama_user;

    // Redirect ke halaman transaksi
    header("Location: transaksi.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Apotek-Ku</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      background: url('images/back2.jpg') no-repeat center center fixed;
      background-size: cover;
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 420px;
    }

    .login-logo {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .login-logo img {
      height: 150px;
    }

    .app-title {
      text-align: center;
      margin-bottom: 20px;
    }

    .app-title h5, .app-title h6 {
      margin: 3px 0;
    }

    .footer-copy {
      text-align: center;
      margin-top: 15px;
      color: #fff;
      font-size: 13px;
    }

    .modal-content {
  border-radius: 10px;
}

.modal-header .modal-title {
  font-weight: bold;
}

.modal .form-group {
  margin-bottom: 1.2rem;
}
.modal-dialog {
  max-width: 420px;
}

.modal-content {
  background: rgba(255, 255, 255, 0.95);
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
}


  </style>
</head>

<body>

  <div class="login-box">
    <div class="login-logo">
      <img src="images/logoapp.png" alt="Logo PMJ">
    </div>
    <div class="app-title">
      <h5>SISTEM INFORMASI</h5>
      <h6><strong>PENJUALAN OBAT (APOTEK)</strong></h6>
    </div>
  <form method="POST" action="proses_login.php">
  <div class="form-group">
    <label for="nik">NIK</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
      </div>
      <input id="nik" type="text" class="form-control" name="nik" required autofocus>
    </div>
  </div>

  <div class="form-group">
    <label for="password">Password</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
      </div>
      <input id="password" type="password" class="form-control" name="password" required>
    </div>
  </div>

  <div class="form-group">
  <label for="captcha">Kode Keamanan</label>
  <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
    </div>
    <input id="captcha" type="text" class="form-control" name="captcha" required>
  </div>
  <div class="mt-2">
    <img src="captcha.php" alt="CAPTCHA" id="captcha_image">
    <a href="#" onclick="document.getElementById('captcha_image').src='captcha.php?'+Math.random(); return false;">⟳ Refresh</a>
  </div>
</div>



  
  <div class="form-group text-center mt-4">
    <button type="submit" class="btn btn-primary btn-lg btn-block">
      <i class="fas fa-sign-in-alt"></i> Login
    </button>
  </div>
</form>


    <div class="text-center mt-3">
      Belum punya akun? <a href="#" data-toggle="modal" data-target="#modalRegister">Daftar di sini</a>
    </div>

    <hr>
    <div class="text-center text-muted" style="font-size: 13px;">wira
      &copy; Apotek<br>
      Info Trouble: <strong>M. Wira</strong> - <a href="tel:+6282177856209">0821-7784-6209</a>
    </div>
  </div>


  <!-- Modal Register -->
  <div class="modal fade" id="modalRegister" tabindex="-1" role="dialog" aria-labelledby="modalRegisterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
  <form method="POST" action="proses_register.php" class="modal-content px-3 py-4">
  <div class="modal-header border-0 pb-0">
    <h5 class="modal-title"><i class="fas fa-user-plus mr-2"></i> Daftar Akun Baru</h5>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
  </div>

  <div class="modal-body pt-0">
    <div class="form-group">
      <label for="nik">NIK</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
        </div>
        <input id="nik" type="text" class="form-control" name="nik" required>
      </div>
    </div>

    <div class="form-group">
      <label for="nama">Nama Lengkap</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
        </div>
        <input id="nama" type="text" class="form-control" name="nama" required>
      </div>
    </div>

    <div class="form-group">
      <label for="no_hp">No. HP</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
        </div>
        <input id="no_hp" type="text" class="form-control" name="no_hp" required>
      </div>
    </div>

    <div class="form-group">
      <label for="email_register">Email</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        </div>
        <input id="email_register" type="email" class="form-control" name="email" required>
      </div>
    </div>

    <div class="form-group">
      <label for="password_register">Password</label>
      <div class="input-group" id="show_hide_password">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
        </div>
        <input type="password" class="form-control" id="password_register" name="password" required>
        <div class="input-group-append">
          <span class="input-group-text">
            <a href="#" onclick="togglePassword(event)"><i class="fas fa-eye" id="togglePasswordIcon"></i></a>
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer border-0">
    <button type="submit" class="btn btn-primary btn-block">
      <i class="fas fa-paper-plane"></i> Daftar
    </button>
  </div>
</form>


    </div>
  </div>

  <!-- JS Scripts -->
  <script src="assets/modules/jquery.min.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/stisla.js"></script>
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>
  <script>
    function togglePassword(e) {
      e.preventDefault();
      const input = document.getElementById("password_register");
      const icon = document.getElementById("togglePasswordIcon");
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>

</body>
</html>
