<?php
session_start();
include('db_connect.php');

// Memastikan admin sudah login
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

    // Ambil data pengguna
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
    
    // Ambil file yang di-upload
    $sql_file = "SELECT * FROM upload_files WHERE user_id = ? ORDER BY id DESC LIMIT 1";  
    $stmt_file = $conn->prepare($sql_file);
    $stmt_file->bind_param("i", $user_id);
    $stmt_file->execute();
    $result_file = $stmt_file->get_result();

    if ($result_file->num_rows > 0) {
        $file_row = $result_file->fetch_assoc();
        $files = [
            'file_pernyataan' => $file_row['file_pernyataan'],
            'file_ktp' => $file_row['file_ktp'],
            'file_kk' => $file_row['file_kk'],
            'file_aktif_kuliah' => $file_row['file_aktif_kuliah'],
            'file_ktm' => $file_row['file_ktm'],
            'file_krs' => $file_row['file_krs'],
            'file_akreditasi' => $file_row['file_akreditasi'],
            'file_spp' => $file_row['file_spp'],
            'file_rekening' => $file_row['file_rekening']
        ];
        $status_files = [
            'status_file_pernyataan' => $file_row['status_file_pernyataan'],
            'status_file_ktp' => $file_row['status_file_ktp'],
            'status_file_kk' => $file_row['status_file_kk'],
            'status_file_aktif_kuliah' => $file_row['status_file_aktif_kuliah'],
            'status_file_ktm' => $file_row['status_file_ktm'],
            'status_file_krs' => $file_row['status_file_krs'],
            'status_file_akreditasi' => $file_row['status_file_akreditasi'],
            'status_file_spp' => $file_row['status_file_spp'],
            'status_file_rekening' => $file_row['status_file_rekening']
        ];
    } else {
        $files = [];
        $status_files = [];
    }

    if (isset($_POST['status']) && isset($_POST['file_column'])) {
        var_dump($_POST); // Debug: Melihat data POST yang dikirimkan
       
        $status = $_POST['status']; // 'diterima' atau 'ditolak'
        $file_column = $_POST['file_column']; // kolom yang ingin diupdate, seperti 'status_file_ktp'
    
        // Pastikan status yang dikirim adalah salah satu nilai ENUM yang valid
        $valid_statuses = ['diterima', 'ditolak', 'menunggu'];

        $valid_columns = [
            'status_file_pernyataan',
            'status_file_ktp',
            'status_file_kk',
            'status_file_aktif_kuliah',
            'status_file_ktm',
            'status_file_krs',
            'status_file_akreditasi',
            'status_file_spp',
            'status_file_rekening'
        ];

        if (in_array($file_column, $valid_columns) && in_array($status, $valid_statuses)) {
            // Query untuk update status
            $update_query = "UPDATE upload_files SET $file_column = ? WHERE user_id = ?";
            
            // Debugging: Cetak query untuk melihat apakah sudah sesuai
            echo "Query: " . $update_query; 
        
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("si", $status, $user_id);
        
            // Debugging: Menjalankan query
            if ($stmt_update->execute()) {
                echo "Status berhasil diperbarui menjadi: " . $status;
            } else {
                echo "Gagal memperbarui status. Error: " . $stmt_update->error;
            }
        } else {
            echo "Status atau kolom file tidak valid.";
        }        
    }

    
   if (isset($_POST['send_note']) && isset($_POST['notes_berkas'])) {
        $note = $_POST['notes_berkas'];

        // Cek apakah sudah ada catatan sebelumnya
        $sql_current_notes = "SELECT notes_berkas FROM upload_files WHERE user_id = ?";
        $stmt_current_notes = $conn->prepare($sql_current_notes);
        $stmt_current_notes->bind_param("i", $user_id);
        $stmt_current_notes->execute();
        $result_notes = $stmt_current_notes->get_result();

        if ($result_notes->num_rows > 0) {
            $user_data = $result_notes->fetch_assoc();
            // Jika ada catatan sebelumnya, update dengan catatan baru
            $current_notes = $user_data['notes_berkas'];
            $update_notes_query = "UPDATE upload_files SET notes_berkas = ? WHERE user_id = ?";
        } else {
            // Jika tidak ada catatan, langsung buat catatan baru
            $update_notes_query = "UPDATE upload_files SET notes_berkas = ? WHERE user_id = ?";
        }

        // Update atau buat catatan baru
        $stmt_update_notes = $conn->prepare($update_notes_query);
        $stmt_update_notes->bind_param("si", $note, $user_id);

        if ($stmt_update_notes->execute()) {
            echo "Note berhasil dikirim dan catatan lama telah digantikan.";
        } else {
            echo "Gagal mengirim note.";
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
        }

        
        .list-container {
            padding-right:25px;
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

        .btn-approve {
            background-color: green; 
            color:white;
            width:40px;
        }

        .btn-reject {
            background-color: red; 
            color:white;
            width:40px;
        }


        .btn-approve:hover {
            background-color: #d4edda;
        }

        .btn-reject:hover {
            background-color: #f5c6cb;
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
                <a href="action_admin.php?id=<?= $user['id'] ?>" class="menu_melengkapi">
                            <li class="d-flex align-items-center">
                                <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                                <div style="padding-left:10px;">
                                    <strong>Biodata Mahasiswa</strong><br>
                                    <small>Cek kelengkapan Biodata Mahasiswa</small>
                                </div>
                            </li>
                        </a>
    
                        <a href="cek_berkas_admin.php?id=<?= $user['id'] ?>" class="menu_melengkapi aktif">
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
           <div class="container ">
                <h4>Berkas Pengajuan</h4>
                <table class="table table-bordered" style="margin-top: 20px; table-layout:auto;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Preview</th>
                            <th>Aksi</th>
                            <th>Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    if (!empty($files)) {
                        foreach ($files as $key => $file) {
                            if (!empty($file)) {
                                
                                $status_column = "status_" . $key;  
                                $status = $status_files[$status_column]; 
                                if ($status == 'diterima') {
                                    $btn_class_accepted = 'btn-success';   
                                    $btn_class_rejected = 'btn-outline-danger';  
                                } elseif ($status == 'ditolak') {
                                    $btn_class_accepted = 'btn-outline-success';
                                    $btn_class_rejected = 'btn-danger';
                                } else {
                                    $btn_class_accepted = 'btn-outline-success';
                                    $btn_class_rejected = 'btn-outline-danger';
                                }
                            
                                echo "<tr>
                                    <td>" . $no++ . "</td>
                                    <td>" . ucfirst(str_replace('_', ' ', $key)) . "</td>
                                    <td>
                                        <!-- Modal untuk Preview File -->
                                        <button class='btn btn-link' data-bs-toggle='modal' data-bs-target='#modal" . ucfirst($key) . "' style='text-decoration:none;'>Lihat file</button>
                                        <a href='" . $file . "' class='btn btn-download' download>
                                            <i class='fas fa-download'></i> Download
                                        </a>
                                    </td>
                                    <td>
                                        <!-- Form untuk Mengubah Status -->
                                        <form method='POST' action='cek_berkas_admin.php?id=" . $user['id'] . "'>
                                            <input type='hidden' name='file_column' value='status_" . $key . "'> 
                                            <button type='submit' name='status' value='diterima' class='btn $btn_class_accepted' style='width:120px;'>
                                                <i class='fas fa-check'></i> Diterima
                                            </button>
                                            <button type='submit' name='status' value='ditolak' class='btn $btn_class_rejected' style='width:120px;'>
                                                <i class='fas fa-times'></i> Ditolak
                                            </button>
                                        </form>
                                    </td>
                                </tr>";

                                echo "
                                <div class='modal fade' id='modal" . ucfirst($key) . "' tabindex='-1' aria-labelledby='modal" . ucfirst($key) . "Label' aria-hidden='true'>
                                    <div class='modal-dialog'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='modal" . ucfirst($key) . "Label'>" . ucfirst(str_replace('_', ' ', $key)) . "</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <embed src='" . $file . "' width='100%' height='400px'>
                                            </div>
                                        </div>
                                    </div>
                                </div>";


                            }
                        }
                  
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada file yang ditemukan untuk pengguna ini.</td></tr>";
                    }
                    ?>

                    </tbody>
                </table>
                <form method="POST" action="">
                    <h5 class="mt-4">Kirim Catatan untuk User</h5>
                    <textarea name="notes_berkas" placeholder="Masukkan catatan di sini" style="width:100%;"></textarea><br>
                    <button class="btn btn-primary" type="submit" name="send_note" style="align-item:right;">Send Note</button>
                </form>
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