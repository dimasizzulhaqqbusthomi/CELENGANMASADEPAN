<?php
$conn = mysqli_connect("localhost", "root", "", "celenganmasadepan");

session_start();

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function registrasi($data) {
    global $conn;

    $name     = htmlspecialchars($data["name"]);
    $username = strtolower(stripslashes($data["username"]));
    $no_telp  = htmlspecialchars($data["no_telp"]);
    $email    = htmlspecialchars($data["email"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);

    // 1. Cek username sudah terdaftar atau belum
    $result = mysqli_query($conn, "SELECT username FROM donors WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        $_SESSION['terdaftar'] = 'Username Sudah Sudah Tersedia!'; 
        return false;
    }

    // 3. Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Insert ke tabel donors
    mysqli_query($conn, "
        INSERT INTO donors (name, username, email, no_telp, password)
        VALUES (
            '$name',
            '$username',
            '$email',
            '$no_telp',
            '$password'
        )
        ");


    return mysqli_affected_rows($conn);
}

function konfirmasi($data) {
    global $conn;

    $id_campaign = (int) $_SESSION["id"];
    $donor_id = $_SESSION['donor_id'] ?? 'NULL';
    $amount = (int) $_POST['amount'];
    $name = htmlspecialchars($_POST['name']);
    $no_telp = htmlspecialchars($_POST['no_telp']);
    $email = htmlspecialchars($_POST['email']);
    $massage = htmlspecialchars($_POST['massage']);
    $message = trim($massage) === '' ? 'NULL' : "'" . mysqli_real_escape_string($conn, $massage) . "'";


    $query = "
        INSERT INTO donations 
        (campaign_id, donor_id, donor_name, donor_email, donor_phone, amount, message)
        VALUES 
        (
            $id_campaign,
            $donor_id,
            '$name',
            '$email',
            '$no_telp',
            $amount,
            $message
        )
    ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function tambah_campaign($data) {
    global $conn;

    $name = htmlspecialchars($data["name"]);
    $contributor_id = $data["contributor_id"];
    $target = $data["target"];
    $type = $data["type"];
    $end_date = $data["end_date"];
    $description = htmlspecialchars($data["description"]);
    $start_date = date("Y-m-d"); // Mulai hari ini

    // Upload Gambar
    $image = upload_gambar();
    if (!$image) {
        return false;
    }

    $query = "INSERT INTO campaigns (contributor_id, name, description, target, start_date, end_date, type, image)
              VALUES ('$contributor_id', '$name', '$description', '$target', '$start_date', '$end_date', '$type', '$image')";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function upload_gambar() {
    $namaFile = $_FILES['image']['name'];
    $ukuranFile = $_FILES['image']['size'];
    $tmpName = $_FILES['image']['tmp_name'];

    $ekstensiValid = ['jpg', 'jpeg', 'png', 'jfif'];
    $ekstensi = explode('.', $namaFile);
    $ekstensi = strtolower(end($ekstensi));

    if (!in_array($ekstensi, $ekstensiValid)) {
        echo "<script>alert('Gagal: Format file harus gambar!');</script>";
        return false;
    }

    if ($ukuranFile > 2000000) { // Max 2MB
        echo "<script>alert('Gagal: Ukuran file terlalu besar!');</script>";
        return false;
    }

    $namaBaru = uniqid() . '.' . $ekstensi;
    move_uploaded_file($tmpName, '../img/' . $namaBaru);
    return $namaBaru;
}

function ubah_campaign($data) {
    global $conn;

    $id = $data["id"];
    $name = htmlspecialchars($data["name"]);
    $description = htmlspecialchars($data["description"]);
    $target = $data["target"];
    $end_date = $data["end_date"];
    $type = $data["type"];
    $gambarLama = $data["gambarLama"];

    // Cek apakah user pilih gambar baru atau tidak
    if ($_FILES['image']['error'] === 4) {
        $image = $gambarLama;
    } else {
        $image = upload_gambar();
        // Hapus file lama di server jika gambar baru berhasil diupload (Optional tapi disarankan)
        if($image && $gambarLama !== 'default.jpg' && file_exists('../img/' . $gambarLama)) {
            unlink('../../img/' . $gambarLama);
        }
    }

    $query = "UPDATE campaigns SET 
                name = '$name',
                description = '$description',
                target = '$target',
                end_date = '$end_date',
                type = '$type',
                image = '$image'
              WHERE id = $id";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

?>