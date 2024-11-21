<?php
session_start();
include('db_connect.php'); 
$user_id = $_SESSION['user_id']; 
if (isset($_SESSION['user_id'])) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $nama_lengkap = $user['nama_lengkap'];
        $username = $user['username'];
    } else {
        exit();
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
    <!-- Link ke bootstrap -->  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *{
            font-family: verdana;
          }
        
        body{
          background-color: #ededed;
        }
          
        footer{
          padding-top : 10px;
          color:white;
          background-color: #0077C0;
          z-index: 10;
          position: relative;
        }
        
        .btnkirim{
                background-color: white;
                color: black;
                padding: 0px 20px;
                border: none;
                outline: none;
              }
        
        /*Navbar*/
        nav{
          z-index: 5; 
          background-color: #0077C0; 
          font-size: 14px;
          position: fixed; 
          top: 0; 
          width: 100%; 
          height: 80px; 
        }


      /*Sidebar*/
      .sidebar {
        margin: 0;
        padding: 0;
        width: 300px;
        background-color: white;
        position: fixed;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 10;

    }
      .sidebar a {
          display: block;
          color: black;
          padding: 16px;
          text-decoration: none;
          padding-left: 80px;
      }
      .sidebar a.active {
          background-color: #0077C0;
          color: white;
      }
      .sidebar a:hover:not(.active) {
          background-color: #e2e2e2;
          color: #0077C0;
      }

      .isi{
        margin-left: 300px; 
        margin-top: 80px;
        margin-bottom: 30px;
        padding: 30px;
      }

      .welcome{
        background-color: white;
        border: 1px solid #0077C0; 
        border-radius: 20px;
        padding: 20px;
        color: #0077C0;
        
      }
      .informasi_umum{
        padding-top: 15px;
        padding-left: 10px;
        padding-right: 20px;
        max-width: 80%; 
      }

      .gambarinformasi {
        max-width: 200px; 
        margin-left: 50px; 
      }

    </style>
    <title>SBM Morut</title>
</head>
<body>
      <!-- Nav  Bar --> 
      <nav class="navbar navbar-expand-lg" style="position: fixed;">
        <div class="container-fluid" >
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto"> 
                    <li class="nav-item dropdown" style="padding-right: 80px;">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                            <img src="foto_profil.png" alt="Profile" class="profile-pic me-2" style="border-radius: 50px;">
                            <div class="d-flex flex-column">
                              <span class="me-2"><?php echo $nama_lengkap; ?></span>
                              <span style="font-size: 10px;"><?php echo $username; ?></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="Home.html">Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


      <!-- side nav bar --> 
    <!-- Sidebar -->
    <div class="sidebar">
      <a class="navbar-brand" href="#" style="display:flex;">
          <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
          <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
      </a>
      <a class="active" href="program_beasiswa.php">Informasi Beasiswa</a>
      <a href="pendaftaran_jenjang.php">Pendaftaran</a>
      <a href="lacak_pengajuan.php">Lacak Pengajuan</a>
      <a href="profil.php">Profil</a>
  </div>

  <!-- Isi -->
  <div class="isi">
    <div class="welcome">
      <p style="font-size: 25px; margin:0px; font-weight:bold;">Welcome to Beasiswa Pemerintah Kabupaten Morowali Utara</p>
      <hr style="margin: 0px; color:#0077C0; opacity: 100%;">
      <p>Perhatikan Syarat dan Ketentuan Program beasiswa berdasarkan jenjang dan persyaratan</p>
    </div>
    <div class="informasi_umum d-flex align-items-start">
      <div>
          <p style="font-size: 18px; margin:0px; font-weight:bold;">Informasi Umum</p>
          <p>Beasiswa pemerintah Kabupaten Morowali Utara ini diperuntukkan bagi mahasiswa yang berasal dari Morowali Utara, yang sedang mengemban studi di berbagai daerah. Adapun syarat-syarat pendaftaran beasiswa kabupaten morowali utara secara umum adalah sebagai berikut:</p>
          <ol>
            <li>Mahasiswa penduduk Kabupaten Morowali utara, dibuktikan dengan KTP</li>
            <li>surat pernyataan bermeterai (scan warna asli)</li>
            <li>Foto copy KTP</li>
            <li>Foto copy Kartu Keluarga</li>
            <li>Mengumpulkan Surat keterangan aktif kuliah (Scan warna asli)</li>
            <li>Mengumpulkan kartu rencana studi/KRS</li>
            <li>Foto copy buku rekening bank Sulteng/BPD</li>
            <li>Foto copy kartu mahasiswa</li>
            <li>No. Hp Mahasiswa</li>
          </ol> 
      </div>
      <div class="gambarinformasi">
          <img src="foto_program.png" alt="Gambar">
      </div>
    </div>
    <div class="Informasi_khusus">
      <p style="font-size: 18px; margin:0px; font-weight:bold;">Informasi Umum</p>
      <p>Beasiswa pemerintah Kabupaten Morowali Utara ini diperuntukkan bagi mahasiswa yang berasal dari Morowali Utara, yang sedang mengemban studi di berbagai daerah. Adapun syarat-syarat pendaftaran beasiswa kabupaten morowali utara secara umum adalah sebagai berikut </p>
      <div style="text-align: center; padding-right:25px;">
          <div class="row">
              <div class="col-md-4"> 
                  <div class="card text-bg-light mb-3">
                      <div class="card-body">
                          <h5 class="card-title">Jenjang S1</h5>
                          <hr>
                          <p class="card-text">Sedang menjalani pendidikan sebagai mahasiswa aktif, dengan menempuh paling lama 10 semester</p>
                      </div>
                  </div>
              </div>
              <div class="col-md-4"> 
                  <div class="card text-bg-light mb-3">
                      <div class="card-body">
                          <h5 class="card-title">Jenjang S2</h5>
                          <hr>
                          <p class="card-text">Sedang menjalani pendidikan sebagai mahasiswa aktif, dengan menempuh paling lama 4 semester</p>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="card text-bg-light mb-3">
                      <div class="card-body">
                          <h5 class="card-title">Jenjang S3</h5>
                          <hr>
                          <p class="card-text">Sedang menjalani pendidikan sebagai mahasiswa aktif, dengan menempuh paling lama 6 semester</p>
                      </div>
                  </div>
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
    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
</body>
</html>