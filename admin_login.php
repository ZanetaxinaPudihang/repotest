<?php
session_start(); 

if (isset($_POST['submit'])) {
    include('db_connect.php'); 

    $username_admin = $_POST['username_admin'];
    $password_admin = $_POST['password_admin'];

    
    if (empty($username_admin) || empty($password_admin)) {
        $error = "Username dan password tidak boleh kosong!";
    } else {
      
        $sql = "SELECT * FROM admin WHERE username_admin = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_admin);  
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            
            if ($password_admin === $user['pass_admin']) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['username_admin'] = $user['username_admin'];
                header("Location: data_pendaftar.php");
                
                exit();
            } else {
                $error = "Login gagal. Username atau password salah!";
            }
        } else {
            $error = "Login gagal. Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link ke fontawesome -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        *{
            font-family: verdana;
          }
      
          /*NAVBAR */
          .navbar{
            background-color: #0077C0;
            font-family: verdana;
            font-size: 14px;
          }
      
          .navbar-nav {
              display: flex;
              justify-content: center;
              width: 100%;
      
          }
          .nav-link {
              margin: 0 25px; 
              text-align: center; 
          }

          .nav-link {
            color: white;
          }
          .nav-link:hover {
              color: #e1e1e1;
          }
          .navbar-brand{
          color: white;
          }
          .btn-outline-success {
            color:  #0077C0;
            background-color: white; 
            border: 1px solid white; 
          }
          .btn-outline-success:hover {
              color: white;
              background-color: transparent;
              border: 1px solid white;
          }
          .login_akun{
            width: 407px;
            height: 473px;
            background-color: white;
            margin: 70px 0px 100px 80px;
            border-radius: 20px;
            padding: 50px 30px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
          }

          body{
            background-color: #f9f7f7;
          }
          

        footer{
          padding-top : 10px;
          color:white;
          background-color: #0077C0;
        }
        
        .btnkirim{
                background-color: white;
                color: black;
                padding: 0px 20px;
                border: none;
                outline: none;
              }

        .kelompok_login {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }


    </style>
    <title>SBM Morut</title>
</head>
<body>
    <nav class = "navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="display:flex; padding: left 80px;">
                <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
                <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
            </a>
                <div class="daftarbutton">
                    <form class="d-flex" role="search" style="padding-right:80px;" >
                      <button class="btn btn-outline-success" type="button" onclick="window.location.href='daftar.php';">Daftar</button>
                    </form>     
                </div>
        </div>
    </nav>

    <!-- Login Akun --> 
    <div class="kelompok_login text-center">
      <div class="login_akun text-center">
        <p style="font-size: 25px; font-weight:bold; text-align: center; margin-bottom:0px;">Login Admin</p>
        <p style="font-size: 12px; text-align: center; padding-top:5px;">Masukkan email dan password Anda untuk login sebagai admin</p>
        <form method="POST" action="admin_login.php">
        <p style="font-size: 14px; text-align:left;">Username</p>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="" name="username_admin">
        </div>

        <p style="font-size: 14px; text-align:left;">Password</p>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password_admin" placeholder="">
        </div>
        <a href="data_pendaftar.php">
        <input class="btn btn-primary" type="submit" name="submit" value="Masuk" style=" width:100px;">
        </a>
        </form>
      </div>

    </div>


      <!-- Footer --> 
    <footer class="text-center text-lg-start">
        <div class="container text-center text-md-start mt-5">
          <div class="row mt-3">
            <div class="col-sm-4">
              <a class="navbar-brand" href="#" style="display:flex; padding-left:80px;">
                <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
                <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
              </a>
              <div style="padding-left:82px; padding-top:10px;">
                <a href="#" class="me-4 text-reset"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="me-4 text-reset"><i class="fab fa-youtube"></i></a>
                <a href="#" class="me-4 text-reset"><i class="fab fa-instagram"></i></a>
              </div>
                <form id="feedback-form"style="padding-left:80px;padding-top:10px; margin-right:10px;">
                    <div class="mb-3">
                        <textarea class="form-control" id="message" rows="4" placeholder="kritik/saran"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                      <button type="submit" class="btnkirim">Kirim</button>
                    </div>
                </form>
            </div>

            <div class="col-sm-4">
              <h6 class="text-uppercase fw-bold mb-4">Link Terkait</h6>
              <p><a href="#!" class="text-reset" style="text-decoration: none;">Website Pemerintah</a></p>
              <p><a href="#!" class="text-reset" style="text-decoration: none;">Data Sekolah Kabupaten Morowali Utara</a></p>
              <p><a href="#!" class="text-reset" style="text-decoration: none;">Beasiswa Lainnya</a></p>
            </div>

            <div class="col-sm-4">
              <h6 class="text-uppercase fw-bold mb-4">Hubungi Kami</h6>
              <p><i class="fa-brands fa-whatsapp me-3"></i>(082) 2094498433993</p>
              <p><i class="fas fa-envelope me-3"></i>morowali@gmail.com</p>
              <p><i class="fas fa-phone me-3"></i>(082) 2094498433993</p>
              <h6 class="text-uppercase fw-bold mb-4">Alamat</h6>
              <p><i class="fa-brands fa-whatsapp me-3"></i>Jl.a[aaaaaahxcxvgsgvxhvxhvanbjbbkkm</p>
            </div>
          </div>
        </div>

      <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2024 Copyright:
        <a class="text-reset fw-bold" href="#">Morowali Utara</a>
      </div>
    </footer>
</body>
</html>