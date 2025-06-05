<?php
// Tampilkan semua error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include FPDF
require('fpdf/fpdf.php');

// Cek apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $institution = htmlspecialchars($_POST['institution']);

    // Validasi data
    if (empty($name) || empty($institution)) {
        die('Nama dan Institusi harus diisi.');
    }

    // Validasi dan upload file foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png'];
        $fileType = mime_content_type($_FILES['photo']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            die('Hanya file JPG dan PNG yang diizinkan.');
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoFilename = $uploadDir . 'foto_' . time() . '.' . $extension;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoFilename)) {
            die('Gagal mengupload foto.');
        }
    } else {
        die('Foto tidak ditemukan atau terjadi kesalahan upload.');
    }

    // Inisialisasi FPDF
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Gambar latar sertifikat
    $pdf->Image('sertifikat_foto.jpg', 0, 0, 297, 210);

    // Tambahkan Foto Peserta
    $pdf->Image($photoFilename, 80, 160, 30, 40); // (x, y, width, height)

    // Tulis Nama
    $pdf->AddFont('PirataOne-Regular','','PirataOne-Regular.php');
    $pdf->SetFont('PirataOne-Regular', '', 46);
    $pdf->SetTextColor(3, 75, 77);
    $pdf->SetXY(90, 76);
    $pdf->Cell(110, 10, ucwords(strtolower($name)), 0, 1, 'C');

    // Tulis Institusi
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetXY(90, 94);
    $pdf->Cell(110, 10, $institution, 0, 1, 'C');

        // Output PDF
    $pdf->Output('D', 'Sertifikat_' . preg_replace('/[^a-zA-Z0-9]/', '_', $name) . '.pdf');
} else {
    die('Metode pengiriman tidak valid.');
}
?>
