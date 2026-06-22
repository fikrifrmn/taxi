<?php
include "config.php";

// TAMBAH MOBIL
if (isset($_POST['add'])) {
    $merk = mysqli_real_escape_string($config, $_POST['merk']);
    $plat = mysqli_real_escape_string($config, $_POST['plat']);
    $id_kelas = $_POST['id_kelas']; // Ambil ID Kelas dari form (Premium/Economy/dll)

    // Tambahkan id_kelas ke dalam Query agar tidak melanggar Constraint Foreign Key
    $query = "INSERT INTO mobil (merk_mobil, plat, id_kelas) VALUES ('$merk', '$plat', '$id_kelas')";
    
    if (mysqli_query($config, $query)) {
        header("Location: ../admin/armada.php?msg=success");
        exit();
    } else {
        echo "Error: " . mysqli_error($config);
    }
}

// UPDATE MOBIL
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $merk = mysqli_real_escape_string($config, $_POST['merk']);
    $plat = mysqli_real_escape_string($config, $_POST['plat']);
    $id_kelas = $_POST['id_kelas']; // Ambil ID Kelas dari form

    $query = "UPDATE mobil SET 
                merk_mobil = '$merk', 
                plat = '$plat', 
                id_kelas = '$id_kelas' 
              WHERE id = '$id'";
              
    if (mysqli_query($config, $query)) {
        header("Location: ../admin/armada.php?msg=updated");
        exit();
    }
}

// HAPUS MOBIL (Tetap Sama)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM mobil WHERE id = '$id'";
    if (mysqli_query($config, $query)) {
        header("Location: ../admin/armada.php?msg=deleted");
        exit();
    }
}
?>