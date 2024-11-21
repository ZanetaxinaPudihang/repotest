<?php
session_start();
include('db_connect.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    
    if (isset($_POST['submit'])) {
    
        $nama_lengkap = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $nik = $_POST['nik'];
        $tempat_lahir = $_POST['tempat_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $universitas = $_POST['universitas'];
        $agama = $_POST['agama'];
        $no_hp = $_POST['no_hp'];
        $kontak_darurat = $_POST['kontak_darurat'];
        $ktp_prov = $_POST['ktp_prov'];
        $ktp_kec = $_POST['ktp_kec'];
        $ktp_detail = $_POST['ktp_detail'];
        $dom_prov = $_POST['dom_prov'];
        $dom_kab = $_POST['dom_kab'];
        $dom_detail = $_POST['dom_detail'];

        $sql = "UPDATE users SET 
                nama_lengkap = ?, 
                username = ?, 
                nik = ?, 
                tempat_lahir = ?, 
                jenis_kelamin = ?, 
                tanggal_lahir = ?, 
                universitas = ?, 
                agama = ?, 
                no_hp = ?, 
                kontak_darurat = ?, 
                ktp_prov = ?, 
                ktp_kec = ?, 
                ktp_detail = ?, 
                dom_prov = ?, 
                dom_kab = ?, 
                dom_detail = ? 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssisssssssssssssi", 
            $nama_lengkap, $username, $nik, $tempat_lahir, $jenis_kelamin, 
            $tanggal_lahir, $universitas, $agama, $no_hp, $kontak_darurat,
            $ktp_prov, $ktp_kec, $ktp_detail, 
            $dom_prov, $dom_kab, $dom_detail,
            $user_id
        );
        $stmt->execute();
    }


    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $nama_lengkap = $user['nama_lengkap'];
        $username = $user['username'];
        $nik = $user['nik'];
        $tempat_lahir = $user['tempat_lahir'];
        $jenis_kelamin = $user['jenis_kelamin'];
        $tanggal_lahir = $user['tanggal_lahir'];
        $universitas = $user['universitas'];
        $agama = $user['agama'];
        $no_hp = $user['no_hp'];
        $kontak_darurat = $user['kontak_darurat'];
        $ktp_prov = $user['ktp_prov'];
        $ktp_kec = $user['ktp_kec'];
        $ktp_detail = $user['ktp_detail'];
        $dom_prov = $user['dom_prov'];
        $dom_kab = $user['dom_kab'];
        $dom_detail = $user['dom_detail'];
        $email = $user['email'];
        $profile_pictures = $user['profile_pictures'];
        if (empty($profile_pictures)) {
            $profile_pictures = 'profile.png'; 
        }
    } else {
        exit();
    }


    if (isset($_POST['submit_upload']) && isset($_FILES['foto_biodata'])) {
       
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["foto_biodata"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["foto_biodata"]["tmp_name"]) !== false &&
            in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif']) &&
            $_FILES["foto_biodata"]["size"] < 1048576) { 
            $new_file_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($_FILES["foto_biodata"]["tmp_name"], $target_file)) {

                $sql_update_picture = "UPDATE users SET profile_pictures = ? WHERE id = ?";
                $stmt_picture = $conn->prepare($sql_update_picture);
                $stmt_picture->bind_param("si", $target_file, $user_id);
                if ($stmt_picture->execute()) {
                    echo "Foto berhasil diupload.";
                    $profile_pictures = $target_file; 
                } else {
                    echo "Gagal memperbarui foto profil di database.";
                }
            } else {
                echo "Terjadi kesalahan saat meng-upload gambar.";
            }
        } else {
            echo "File tidak valid atau melebihi ukuran maksimum (1MB).";
        }
    }
} else {
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
          background-color: #e2e2e2 ;
          color: #0077C0;
      }

      .isi{
        margin-left: 300px; 
          margin-top: 80px;
          margin-bottom: 30px;
          padding: 30px;
        }
        .melengkapi_persyaratan{
            border: 1px solid #D9D9D9;
            border-radius:10px;
            background-color: white;
            padding:30px;
            margin-bottom: 30px;
        }

        
        .list-container {
            padding-right:25px;
            min-width:325px;
        }
        .card-title{
            font-size:16px;
            font-weight:bold;
        }
        .card-title2{
            font-size:14px;
            font-weight:bold;
        }
        .card-text{
            font-size:12px;
            line-height: 1.25;
        }
        .list-item {
            display: flex;
            align-items: center;
        }

        .req{
            color: red;
        }

        .form-label{
            font-size: 13px;
        }
        .btn{
            font-size: 12px;
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

        label{
          font-weight: bold;
          padding-top: 15px;
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
                        <img src="<?php echo $profile_pictures; ?>" class="picture-pic me-2" alt="Profile Picture" style="border-radius: 50px; width:40px;height:40px;">
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
      <a href="program_beasiswa.php">Informasi Beasiswa</a>
      <a href="pendaftaran_jenjang.php">Pendaftaran</a>
      <a href="lacak_pengajuan.php">Lacak Pengajuan</a>
      <a class="active" href="profil.php">Profil</a>

  </div>
  

  <!-- Isi -->
  <div class="isi">
    

  <!-- foto profile -->
  <div class="container">
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                  <div class="container">
                  <form action="profil.php" method="POST" enctype="multipart/form-data">
                    <div class="d-flex justify-content-center mt-3 mb-2 position-relative">
                        
                        <img id="selectedAvatar" src="<?php echo $profile_pictures ?: 'default.jpg'; ?>" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;" alt="foto profile" />
                        <div class="btn-upload position-absolute top-100 start-50 translate-middle">
                            <label class="btn btn-primary rounded-circle p-1" for="customFile2" style="font-size: 18px; width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; cursor: pointer;">+</label>
                            <input type="file" class="form-control d-none" id="customFile2" name="foto_biodata" onchange="displaySelectedImage(event, 'selectedAvatar')" />
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" name="submit_upload" class="btn btn-success">Save Changes</button>
                    </div>
                </form>

                <script>
                    function displaySelectedImage(event, imageElementId) {
                        var file = event.target.files[0];
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById(imageElementId).src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                </script>

                    <h5 class="card-title"> <?php echo $nama_lengkap; ?> </h5>
                    <p class="card-text"><?php echo $email; ?></p>
                  </div>
                </div>
            </div>
        </div>
    <!-- Data diri -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Data Diri</p>
                    <p class="card-text">Perubahan yang Anda lakukan pada profile akan terupdate setelah halaman di simpan.</p>
                    <hr>
                    <p class="card-title2">Profile</p>
                    <form action="profil.php" method="POST">

                    <label for="nama" class="form-label">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" class="form-control" id="nama" name="nama_lengkap" value="<?= htmlspecialchars($nama_lengkap) ?>">

                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="nik" class="form-label">NIK <span class="req">*</span></label>
                            <input type="text" class="form-control" id="nik" pattern=".{16}" maxlength="16" name="nik" value="<?= htmlspecialchars($nik) ?>">
                            <small class="eror">NIK harus terdiri dari 16</small>
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="req">*</span></label>
                            <select name="jenis_kelamin" class="form-select" id="jenis_kelamin" value="<?= htmlspecialchars($jenis_kelamin)?>">
                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?= ($jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-Laki</option>
                                 <option value="Perempuan" <?= ($jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                            <label for="univ" class="form-label">Universitas <span class="req">*</span></label>
                            <input type="text" class="form-control" id="univ" name="universitas" value="<?= htmlspecialchars($universitas) ?>">
                            <label for="WA" class="form-label">No.HP/WhatsApp <span class="req">*</span></label>
                            <input type="text" class="form-control" id="WA" name="no_hp" value="<?= htmlspecialchars($no_hp) ?>">
                            
                        </div>
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Tempat Lahir <span class="req">*</span></label>
                            <input type="text" class="form-control" id="nama" name="tempat_lahir" value="<?= htmlspecialchars($tempat_lahir) ?>">
                            <label for="nama" class="form-label">Tanggal Lahir <span class="req">*</span></label>
                            <input type="date" class="form-control" id="tanggallahir" name="tanggal_lahir" value="<?= htmlspecialchars($tanggal_lahir) ?>">
                            <label for="nama" class="form-label">Agama <span class="req">*</span></label>
                            <select name="agama" class="form-select" id="agama" value="<?= htmlspecialchars($agama)?>">
                              <option value="" selected disabled>Agama</option>
                              <option value="Kristen" <?= ($agama == 'Kristen') ? 'selected' : ''; ?>>Kristen</option>
                              <option value="Katolik" <?= ($agama == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
                              <option value="Islam" <?= ($agama == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                              <option value="Hindu" <?= ($agama == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                              <option value="Buddha" <?= ($agama == 'Buddha') ? 'selected' : ''; ?>>Buddha</option>
                              <option value="Konghucu" <?= ($agama == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                            </select>
                            <label for="nama" class="form-label">Kontak Darurat <span class="req">*</span></label>
                            <input type="text" class="form-control" id="nama" name="kontak_darurat" value="<?= htmlspecialchars($kontak_darurat) ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="card-title2" style="margin-top: 25px;">Alamat sesuai KTP</p>
                            <label for="nama" class="form-label">Provinsi <span class="req">*</span></label>
                            <input type="text" class="form-control" id="ktp_prov" name="ktp_prov" value="Sulawesi Tengah" readonly>
                            <label for="nama" class="form-label">Kecamatan <span class="req">*</span></label>
                            <select name="ktp_kec" class="form-select" id="ktp_kec" value="<?= htmlspecialchars($ktp_kec)?>">
                              <option value="" selected disabled>Kecamatan</option>
                              <option value="Mori Atas" <?= ($ktp_kec == 'Mori Atas') ? 'selected' : ''; ?>>Mori Atas</option>
                              <option value="Lembo" <?= ($ktp_kec == 'Lembo') ? 'selected' : ''; ?>>Lembo</option>
                              <option value="Lembo Raya" <?= ($ktp_kec == 'Lembo Raya') ? 'selected' : ''; ?>>Lembo Raya</option>
                              <option value="Petasia Timur" <?= ($ktp_kec == 'Petasia Timur') ? 'selected' : ''; ?>>Petasia Timur</option>
                              <option value="Petasia" <?= ($ktp_kec == 'Petasia') ? 'selected' : ''; ?>>Petasia</option>
                              <option value="Petasia Barat" <?= ($ktp_kec == 'Petasia Barat') ? 'selected' : ''; ?>>Petasia Barat</option>
                              <option value="Mori Utara" <?= ($ktp_kec == 'Mori Utara') ? 'selected' : ''; ?>>Mori Utara</option>
                              <option value="Soyo Jaya" <?= ($ktp_kec == 'Soyo Jaya') ? 'selected' : ''; ?>>Soyo Jaya</option>
                              <option value="Bungku Utara" <?= ($ktp_kec == 'Bungku Utara') ? 'selected' : ''; ?>>Bungku Utara</option>
                              <option value="Mamossalato" <?= ($ktp_kec == 'Mamossalato') ? 'selected' : ''; ?>>Mamossalato</option>
                            </select>
                            <label for="nama" class="form-label">Detail Alamat Sesuai KTP<span class="req">*</span></label>
                            <input type="text" class="form-control" id="nama" name="ktp_detail" value="<?= htmlspecialchars($ktp_detail) ?>">
                        </div>
                        <div class="col-md-6">
                            <p class="card-title2" style="margin-top: 25px;">Alamat Domisili</p>
                            <label for="nama" class="form-label">Provinsi <span class="req">*</span></label>
                            <input type="text" class="form-control" id="nama" name="dom_prov" value="<?= htmlspecialchars($dom_prov) ?>">
                            <label for="nama" class="form-label">Kabupaten <span class="req">*</span></label>
                            <input type="text" class="form-control" id="nama" name="dom_kab" value="<?= htmlspecialchars($dom_kab) ?>">
                            <label for="nama" class="form-label">Detail Alamat Sesuai Domili <span class="req">*</span></label>
                            <input type="text" class="form-control" id="nama" name="dom_detail" value="<?= htmlspecialchars($dom_detail) ?>">
                        </div>
                    </div>                  
                    </div>
                    

                    <div class="card-body text-end">
                        <input class="btn btn-primary" type="submit" name="submit" value="Save">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Ubah Password
                          </button>
                    </div>
                   </form>      
                          <!-- Modal -->
                          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Password</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <label for="pass" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="pass">

                                    <label for="pass" class="form-label">Verifikasi Password Baru</label>
                                    <input type="password" class="form-control" id="pass">
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="button" class="btn btn-primary">Save Password</button>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    </div>
    </div>


 <!-- Footer --> 
  
            
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    function displaySelectedImage(event, elementId) {
        const selectedImage = document.getElementById(elementId);
        const fileInput = event.target;

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                selectedImage.src = e.target.result;
            };

            reader.readAsDataURL(fileInput.files[0]);
        }
    }
    </script>

    
</body>
</html>