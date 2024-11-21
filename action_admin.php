<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];  

$sql_admin = "SELECT * FROM admin WHERE id = ?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("i", $admin_id);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin->num_rows > 0) {
    $admin = $result_admin->fetch_assoc();
    $nama_admin = $admin['nama_admin']; 
    $username_admin = $admin['username_admin']; 
} else {
    echo "Admin tidak ditemukan.";
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];  

    $sql_user = "SELECT * FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $nama_lengkap = $user['nama_lengkap'];
        $username = $user['username'];
        $nik = $user['nik'];
        $jenis_kelamin = $user['jenis_kelamin'];
        $universitas = $user['universitas'];
        $no_hp = $user['no_hp'];
        $tempat_lahir = $user['tempat_lahir'];
        $tanggal_lahir = $user['tanggal_lahir'];
        $agama = $user['agama'];
        $kontak_darurat = $user['kontak_darurat'];
        $ktp_prov = $user['ktp_prov'];
        $ktp_kec = $user['ktp_kec'];
        $ktp_detail = $user['ktp_detail'];
        $dom_prov = $user['dom_prov'];
        $dom_kab = $user['dom_kab'];
        $dom_detail = $user['dom_detail'];
    } else {
        echo "Data pengguna tidak ditemukan.";
        exit();
    }
} else {
    echo "ID pengguna tidak ditemukan.";
    exit();
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
        
        nav{
          z-index: 5; 
          background-color: #0077C0; 
          font-size: 14px;
          position: fixed; 
          top: 0; 
          width: 100%; 
          height: 80px; 
        }
        .navbar-brand{
          color: white;
          margin-left:80px;
          }



      .isi{ 
          margin-top: 80px;
          padding: 30px 80px;
          flex:1;
        }
        html, body {
            height: 100%; 
            margin: 0; 
        }

        body {
            display: flex;
            flex-direction: column;
        }

        footer {
            background-color: #0077C0; 
            color: white; 

            text-align: center;
            width: 100%;
        }

        .melengkapi_persyaratan{
            border: 1px solid #D9D9D9;
            border-radius:10px;
            background-color: white;
            padding:30px;
            margin-bottom: 30px;
            width:100%;

        }

        
        .list-container {
            justify-content: center;
            align-items: center;
            min-width:360px;

        }
        .card-title{
            font-size:16px;
            font-weight:bold;
        }
        .card-text{
            font-size:12px;
            color:#ABABAB;
            line-height: 1.25;
        }
        .list-item {
            display: flex;
            align-items: center;
        }
        .menu_melengkapi{
            text-decoration:none;
            color:inherit;
            display: block;
            border-radius: 2px;
            padding: 5px 0px 5px 5px;
            margin-bottom: 10px;
            
        }
        .menu_melengkapi:hover {
            background-color: #0077C0;
            color: white;
        }

        .menu_melengkapi:hover:not(.aktif) img {
            filter: brightness(0) saturate(100%) invert(100%);
        }

        .menu_melengkapi.aktif{
            background-color: #e2e2e2;
            color: #0077C0;
        }

    </style>
    <title>SBM Morut</title>
</head>
<body>
      <!-- Nav  Bar --> 
      <nav class="navbar navbar-expand-lg" style="position: fixed;">
        <div class="container-fluid" >
            
            <a class="navbar-brand" href="#" style="display:flex; padding: left 80px;">
                <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
                <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
            </a>
            <div class="collapse navbar-collapse">  
                <ul class="navbar-nav ms-auto"> 
                    <li class="nav-item dropdown" style="padding-right: 80px;">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                            <img src="foto_profil.png" alt="Profile" class="profile-pic me-2" style="border-radius: 50px;">
                            <div class="d-flex flex-column">
                              <span class="me-2"><?php echo $nama_admin ?></span>
                              <span style="font-size: 10px;"><?php echo $username_admin ?></span>
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



  <!-- Isi -->
  <div class="isi">
    <div class="melengkapi_persyaratan">
        <p style="font-size:16px; font-weight:bold;">Melengkapi Persyaratan</p>
        <hr>
        <div class="d-flex mt-4">
            <div class="list-container">
                <ul class="list-unstyled">
                    
                        <a href="action_admin.php?id=<?= $user['id'] ?>" class="menu_melengkapi aktif">
                            <li class="d-flex align-items-center">
                                <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                                <div style="padding-left:10px;">
                                    <strong>Biodata Mahasiswa</strong><br>
                                    <small>Cek kelengkapan Biodata Mahasiswa</small>
                                </div>
                            </li>
                        </a>

    
                        <a href="cek_berkas_admin.php?id=<?= $user['id'] ?>" class="menu_melengkapi">
                            <li class="d-flex align-items-center">
                                <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                                <div style="padding-left:10px;">
                                    <strong>Berkas Mahasiswa</strong><br>
                                    <small>Cek kelengkapan Berkas Mahasiswa</small>
                                </div>
                            </li>
                        </a>
    
                        <a href="admin_verifikasi.php?id=<?= $user['id'] ?>" class="menu_melengkapi">
                            <li class="d-flex align-items-center">
                                <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                                <div style="padding-left:10px;">
                                    <strong>Verifikasi</strong><br>
                                    <small>Verifikasi Tahapan</small>
                                </div>
                            </li>
                        </a>
                    </ul>
            </div>
            <div style="width: 1px; background-color: #CBCBCB; margin: 0 10px; height: auto;"></div>

            <!-- Data Diri -->
            <div class="list-container" style="margin-left:10px; width:100%;">
                <ul class="list-unstyled">
                    <li class="d-flex align-items-center mb-3">
                        <img src="icon_datadiri.png" alt="Logo" class="me-3">
                        <div style="color:#0077C0;">
                            <strong>Data Diri</strong>
                        </div>
                    </li>
                </ul>
                <div class="container">
                    <div class="list-group-container d-flex">
                        <ul class="list-group list-group-flush" style="width:100%; ">
                            <li class="list-group-item">Nama Lengkap</li>
                            <li class="list-group-item">Username</li>
                            <li class="list-group-item">NIK</li>
                            <li class="list-group-item">Agama</li>
                            <li class="list-group-item">Tempat Lahir</li>
                            <li class="list-group-item">Tanggal Lahir</li>
                            <li class="list-group-item">No. Telepon/Whatsapp</li>
                            <li class="list-group-item">Jenis Kelamin</li>
                            <li class="list-group-item">Kontak Darurat</li>
                        </ul>
                        <ul class="list-group list-group-flush" style="width:100%;">
                            <li class="list-group-item"><?=htmlspecialchars($nama_lengkap)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($username)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($nik)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($agama)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($tempat_lahir)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($tanggal_lahir)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($no_hp)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($jenis_kelamin)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($kontak_darurat)?></li>
                
                        </ul>
                    </div>
                </div>
            
            <!-- Alamat sesuai KTP -->
                <ul class="list-unstyled">
                    <li class="d-flex align-items-center mb-3 mt-3">
                        <img src="icon_alamat.png" alt="Logo" class="me-3">
                        <div style="color:#0077C0;">
                            <strong>Alamat (Sesuai KTP)</strong>
                        </div>
                    </li>
                </ul>
                <div class="container">
                    <div class="list-group-container d-flex">
                        <ul class="list-group list-group-flush" style="width:100%;">
                            <li class="list-group-item">Provinsi</li>
                            <li class="list-group-item">Kabupaten</li>
                            <li class="list-group-item">Alamat sesuai KTP</li>
                        </ul>
                        <ul class="list-group list-group-flush" style="width:100%;">
                            <li class="list-group-item"><?=htmlspecialchars($ktp_prov)?> </li>
                            <li class="list-group-item"><?=htmlspecialchars($ktp_kec)?> </li>
                            <li class="list-group-item"> <?=htmlspecialchars($ktp_detail)?></li>
                        </ul>
                    </div>
                </div>
                </ul>

                <!-- Alamat sesuai Domisili -->
                <ul class="list-unstyled">
                    <li class="d-flex align-items-center mb-3 mt-3">
                        <img src="icon_domisili.png" alt="Logo" class="me-3">
                        <div style="color:#0077C0;">
                            <strong>Alamat (Sesuai KTP)</strong>
                        </div>
                    </li>
                </ul>
                <div class="container">
                    <div class="list-group-container d-flex">
                        <ul class="list-group list-group-flush" style="width:100%;">
                            <li class="list-group-item">Provinsi</li>
                            <li class="list-group-item">Kabupaten</li>
                            <li class="list-group-item">Alamat Domisili</li>
                        </ul>
                        <ul class="list-group list-group-flush" style="width:100%;">
                            <li class="list-group-item"><?=htmlspecialchars($dom_prov)?></li>
                            <li class="list-group-item"><?=htmlspecialchars($dom_kab)?></li>
                            <li class="list-group-item"> <?=htmlspecialchars($dom_detail)?></li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>


 <!-- Footer --> 
    <footer class="text-center text-lg-start">
      <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2024 Copyright:
        <a class="text-reset fw-bold" href="#">Morowali Utara</a>
      </div>
    </footer>
    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>