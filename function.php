<?php
session_start();

//membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "dbtoko");


//menambah barang baru
if (isset($_POST["addnewbarang"])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) VALUES ('$namabarang','$deskripsi','$stock')");
    if ($addtotable) {
        header("location:index.php");
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
};


//menambah barang masuk
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];
    $namasupplier = $_POST['namasupplier'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, namasupplier, qty) VALUES ('$barangnya','$namasupplier', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstocksekarangdenganquantity' WHERE idbarang='$barangnya'");
    if ($addtomasuk && $updatestockmasuk) {
        header("location:masuk.php");
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//menambah barang keluar
if (isset($_POST['barangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $pembeli = $_POST['pembeli'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, pembeli, qty) VALUES ('$barangnya','$pembeli', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstocksekarangdenganquantity' WHERE idbarang='$barangnya'");
    if ($addtokeluar && $updatestockmasuk) {
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}

// update info barang
if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi' WHERE idbarang = '$idb'");
    if ($update) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

// menghapus barang dari stock
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang = '$idb'");
    if ($hapus) {
        header("location:index.php");
    } else {
        echo "Gagal";
        header("location:index.php");
    }
};

// mengubah data barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $namasupplier = $_POST['namasupplier'];
    $qty = $_POST['qty'];

    // mengambil stock barang saat ini
    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // qty barang masuk saat ini
    $qtyskrg = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', namasupplier='$namasupplier' WHERE idmasuk='$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', namasupplier='$namasupplier' WHERE idmasuk='$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    }
}


// Menghapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idm'");

    if ($update && $hapusdata) {
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }
}

// mengubah data barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $pembeli = $_POST['pembeli'];
    $qty = $_POST['qty'];

    // mengambil stock barang saat ini
    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // qty barang keluar saat ini
    $qtyskrg = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg - $selisih;

        if ($selisih <= $stockskrg) {

            $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
            $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', pembeli='$pembeli' WHERE idkeluar='$idk'");
            if ($kurangistocknya && $updatenya) {
                header('location:keluar.php');
            } else {
                echo 'Gagal';
                header('location:keluar.php');
            }
        } else {
            echo '
                <script>alert("Stock tidak mencukupi");
                window.location.href="keluar.php";
                </script>
                ';
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', pembeli='$pembeli' WHERE idkeluar='$idk'");
        if ($kurangistocknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}


// Menghapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

    if ($update && $hapusdata) {
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}

// menambah admin baru
if (isset($_POST['addadmin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn, "INSERT INTO login (email,password) VALUES ('$email','$password')");

    if ($queryinsert) {
        // if berhasil
        header('Location:admin.php');
    } else {
        // kalau gagal insert ke db
        header('Location:admin.php');
    }
}

// edit data admin
if (isset($_POST['updateadmin'])) {
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru', password='$passwordbaru' WHERE iduser='$idnya'");

    if ($queryupdate) {
        header('Location:admin.php');
    } else {
        header('Location:admin.php');
    }
}

// hapus admin
if (isset($_POST['hapusadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "DELETE FROM login WHERE iduser='$id'");

    if ($querydelete) {
        header('Location:admin.php');
    } else {
        header('Location:admin.php');
    }
}

// menambah supplier baru
if (isset($_POST['addsupplier'])) {
    $namasupplier = $_POST['namasupplier'];
    $notelp = $_POST['notelp'];

    $queryinsert = mysqli_query($conn, "INSERT INTO supplier (namasupplier,notelp) VALUES ('$namasupplier','$notelp')");

    if ($queryinsert) {
        // if berhasil
        header('Location:supplier.php');
    } else {
        // kalau gagal insert ke db
        header('Location:supplier.php');
    }
}

// edit data supplier
if (isset($_POST['updatesupplier'])) {
    $supplierbaru = $_POST['supplierbaru'];
    $notelpbaru = $_POST['notelpbaru'];
    $idsuppnya = $_POST['idsupp'];

    $queryupdate = mysqli_query($conn, "UPDATE supplier SET namasupplier='$supplierbaru', notelp='$notelpbaru' WHERE idsupplier='$idsuppnya'");

    if ($queryupdate) {
        header('Location:supplier.php');
    } else {
        header('Location:supplier.php');
    }
}

// hapus supplier
if (isset($_POST['hapussupplier'])) {
    $idsupp = $_POST['idsupp'];

    $querydelete = mysqli_query($conn, "DELETE FROM supplier WHERE idsupplier='$idsupp'");

    if ($querydelete) {
        header('Location:supplier.php');
    } else {
        header('Location:supplier.php');
    }
}
