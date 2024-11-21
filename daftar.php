<?php
include('db_connect.php'); 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $nama_lengkap = $_POST['nama_lengkap'];
  $username = $_POST['username'];
  $nik = $_POST['nik'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $universitas = $_POST['universitas'];
  $no_hp = $_POST['no_hp'];
  $tempat_lahir = $_POST['tempat_lahir'];
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $agama = $_POST['agama'];
  $kontak_darurat = $_POST['kontak_darurat'];
  $ktp_prov = $_POST['ktp_prov'];
  $ktp_kec = $_POST['ktp_kec'];
  $ktp_detail = $_POST['ktp_detail'];
  $dom_prov = $_POST['dom_prov'];
  $dom_kab = $_POST['dom_kab'];
  $dom_detail = $_POST['dom_detail'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $konf_pass = $_POST['konf_pass'];


  $sql = "INSERT INTO users (nama_lengkap, username, email, nik, tempat_lahir, jenis_kelamin, tanggal_lahir, universitas, agama, no_hp, kontak_darurat, ktp_prov, ktp_kec, ktp_detail, dom_prov, dom_kab, dom_detail, pass, konf_pass)
          VALUES ('$nama_lengkap', '$username', '$email', '$nik', '$tempat_lahir', '$jenis_kelamin', '$tanggal_lahir', '$universitas', '$agama', '$no_hp', '$kontak_darurat', '$ktp_prov', '$ktp_kec', '$ktp_detail', '$dom_prov', '$dom_kab', '$dom_detail', '$pass', '$konf_pass')";

  if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id;

    $sql_upload = "INSERT INTO upload_files (user_id) VALUES ('$user_id')";
    if ($conn->query($sql_upload) === TRUE) {

        $sql_riwayat = "INSERT INTO riwayat_status (user_id) VALUES ('$user_id')";
        if ($conn->query($sql_riwayat) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql_riwayat . "<br>" . $conn->error;
        }

    } else {
        echo "Error: " . $sql_upload . "<br>" . $conn->error;
    }
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
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
          .daftar{
            width: 407px;
            height: 725px;
            background-color: white;
            margin: 70px 0px 100px 80px;
            border-radius: 20px;
            padding: 50px 30px;
            text-align: center;
          }
          .kelompok_daftar{
            display: flex;
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
        label{
          font-size: 14px;
          font-weight: bold;
          padding-top: 15px;
        }

        input:invalid{
          border: 1px solid red;
        }

        .eror{
          color:red;
          font-size: 12px;
          display: none;
        }

        input:invalid + .eror{
          display: block;
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
                    <button class="btn btn-outline-success" type="button" onclick="window.location.href='login.php';">Login</button>
                    </form>     
                </div>
        </div>
    </nav>


   
  <div class="container">
    <div class="d-flex">
        
    <!-- Data diri -->
        
            <div class="card" style="margin: 25px 0px;">
                <div class="card-body">
                    <p class="card-title text-center fw-bold">Daftar</p>
                    <p class="card-text text-center fw-bold">Silakan daftarkan diri Anda</p>
                    <hr>
                    <form action="daftar.php" method="POST">

                      <label for="nama" class="form-label">Nama Lengkap <span class="req">*</span></label>
                      <input type="text" class="form-control" id="nama" name="nama_lengkap">

                      <label for="username" class="form-label">Username</label>
                      <input type="text" class="form-control" id="username" name="username">

                      <label for="email" class="form-label">email</label>
                      <input type="text" class="form-control" id="email" name="email">

                      

                      <div class="row">
                          <div class="col-md-6">
                              <label for="nik" class="form-label">NIK <span class="req">*</span></label>
                              <input type="text" class="form-control" id="nik" pattern=".{16}" maxlength="16" name="nik">
                              <small class="eror">NIK harus terdiri dari 16 angka</small>
                              <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="req">*</span></label>
                              <select name="jenis_kelamin" class="form-select" id="jenis_kelamin">
                              <option selected disabled>Pilih Jenis Kelamin</option>
                              <?php 
                              $jenis_kelamin = [
                                  'Laki-Laki' => 'Laki-Laki',
                                  'Perempuan' => 'Perempuan'
                              ];
                              foreach ($jenis_kelamin as $value => $label) : ?>
                                  <option value="<?= $value ?>" 
                                          <?= (isset($jenis_kelamin_db) && $jenis_kelamin_db == $value) ? 'selected' : '' ?>>
                                      <?= $label ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                              <label for="univ" class="form-label">Universitas <span class="req">*</span></label>
                              <input type="text" class="form-control" id="univ " name="universitas">
                              <label for="WA" class="form-label">No.HP/WhatsApp <span class="req">*</span></label>
                              <input type="text" class="form-control" id="WA" name="no_hp">
                              
                          </div>
                          <div class="col-md-6">
                              <label for="nama" class="form-label">Tempat Lahir <span class="req">*</span></label>
                              <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
                              <label for="nama" class="form-label">Tanggal Lahir <span class="req">*</span></label>
                              <input type="date" class="form-control" id="tanggallahir" name="tanggal_lahir">
                              <label for="nama" class="form-label">Agama <span class="req">*</span></label>
                              <select name="agama" class="form-select" id="agama">
                              <option selected disabled>Pilih Jenis Agama</option>
                              <?php 
                              
                              $agama = [
                                  'Kristen' => 'Kristen',
                                  'Katolik' => 'Katolik',
                                  'Islam' => 'Islam',
                                  'Hindu' => 'Hindu',
                                  'Buddha' => 'Buddha',
                                  'Konghucu' => 'Konghucu',
                              ];
                              
                              
                              foreach ($agama as $value => $label) : ?>
                                  <option value="<?= $value ?>" 
                                          <?= (isset($agama_db) && $agama_db == $value) ? 'selected' : '' ?>>
                                      <?= $label ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                              <label for="nama" class="form-label">Kontak Darurat <span class="req">*</span></label>
                              <input type="text" class="form-control" id="kontak_darurat" name="kontak_darurat" >
                          </div>
                      </div>
                      
                      <div class="row">
                          <div class="col-md-6">
                              <p class="card-title2" style="margin-top: 25px;">Alamat sesuai KTP</p>
                              <label for="nama" class="form-label">Provinsi <span class="req">*</span></label>
                              <input type="text" class="form-control" id="ktp_prov" name="ktp_prov" value="Sulawesi Tengah" readonly>
                              <label for="nama" class="form-label">Kecamatan <span class="req">*</span></label>
                              <select name="ktp_kec" class="form-select" id="ktp_kec">
                              <option selected disabled>Pilih Kecamatan</option>
                              <?php 
                              
                              $ktp_kec = [
                                  'Mori Atas' => 'Mori Atas',
                                  'Lembo' => 'Lembo',
                                  'Lembo Raya' => 'Lembo Raya',
                                  'Petasia Timur' => 'Petasia Timur',
                                  'Petasia' => 'Petasia',
                                  'Petasia Barat' => 'Petasia Barat',
                                  'Mori Utara' => 'Mori Utara',
                                  'Soyo Jaya' => 'Soyo Jaya',
                                  'Bungku Utara' => 'Bungku Utara',
                                  'Mamosalato' => 'Mamosalato',
                              ];
                              
                              
                              foreach ($ktp_kec as $value => $label) : ?>
                                  <option value="<?= $value ?>" 
                                          <?= (isset($ktp_kec_db) && $ktp_kec_db == $value) ? 'selected' : '' ?>>
                                      <?= $label ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                              <label for="nama" class="form-label">Detail Alamat Sesuai KTP<span class="req">*</span></label>
                              <input type="text" class="form-control" id="ktp_detail" name="ktp_detail">
                          </div>
                          <div class="col-md-6">
                              <p class="card-title2" style="margin-top: 25px;">Alamat Domisili</p>
                              <label for="nama" class="form-label">Provinsi <span class="req">*</span></label>
                              <input type="text" class="form-control" id="dom_prov" name="dom_prov" >
                              <label for="nama" class="form-label">Kabupaten <span class="req">*</span></label>
                              <input type="text" class="form-control" id="dom_kab" name="dom_kab">
                              <label for="nama" class="form-label">Detail Alamat Sesuai Domili <span class="req">*</span></label>
                              <input type="text" class="form-control" id="dom_detail" name="dom_detail">
                          </div>

                      </div>
                      
                      <label for="password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="pass" name="pass">

                      <label for="password" class="form-label">Konfirmasi Password</label>
                      <input type="password" class="form-control" id="konf_pass" name="konf_pass">
                      </div>

                      <div class="card-body text-center">
                      <input class="btn btn-primary" type="submit" name="submit" value="Daftar">
                            
                      </div>
                    </form>
                </div>
                <div>
                  <img src="gambar_login.png" alt="Login" style="height: 425px; width: auto; margin-top: 50px; margin-left: 50px;" >
                </div>
            </div>
        
    </div>
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