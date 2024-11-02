<?php
// Tampilkan semua error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Koneksi ke database
$conn = new mysqli("localhost", "rzbwfmra_data_sertifikat", "rzbwfmra_data_sertifikat", "rzbwfmra_data_sertifikat");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include FPDF dan phpqrcode
require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');

// Cek apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = htmlspecialchars($_POST['name']);
    $institution = htmlspecialchars($_POST['institution']);

    // Validasi data sederhana
    if (empty($name) || empty($institution)) {
        die('Nama dan Institusi harus diisi.');
    }

    // Ambil nomor sertifikat terakhir dari database
    $query = "SELECT MAX(no_sertifikat) AS max_no FROM sertifikat";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $no_seri_terakhir = $row['max_no'] ?? 0;

    // Tambahkan nomor seri baru
    $no_seri_baru = $no_seri_terakhir + 1;

    // Format nomor sertifikat
    $nomor_sertifikat = "Nomor: 800/" . str_pad($no_seri_baru, 4, '0', STR_PAD_LEFT) . "/2024";

    // Buat data untuk QR code
    $qr_data = "Nomor Sertifikat: $nomor_sertifikat\nNama: $name\nUnit Kerja: $institution";

    // Generate QR code image
    $qr_file = "qrcode_$no_seri_baru.png";
    QRcode::png($qr_data, $qr_file, QR_ECLEVEL_L, 3);

    // Inisialisasi FPDF dengan orientasi landscape (L)
    $pdf = new FPDF('L', 'mm', 'A4');

    // Halaman pertama: Sertifikat utama
    $pdf->AddPage();

    // Background image sertifikat
    $pdf->Image('sertifikat-depan-seri-qr.jpg', 0, 0, 297, 210);

    // Set font untuk nama, institusi, dan nomor sertifikat
    $pdf->SetFont('Arial', 'B', 24);

    // Tulis nama peserta
    $pdf->SetXY(90, 94);
    $pdf->Cell(110, 10, $name, 0, 1, 'C');

    // Tulis nama institusi
    $pdf->SetXY(90, 107);
    $pdf->Cell(110, 10, $institution, 0, 1, 'C');

    // Tulis nomor sertifikat
    $pdf->SetFont('Courier', 'I', 28);
    $pdf->SetXY(90, 65);
    $pdf->Cell(110, 10, $nomor_sertifikat, 0, 1, 'C');

    // Tambahkan QR code ke sertifikat
    $pdf->Image($qr_file, 50, 150, 40, 40); // Sesuaikan posisi dan ukuran QR code

    // Halaman kedua
    $pdf->AddPage();
    $pdf->Image('sertifikat-belakang.jpg', 0, 0, 297, 210);

    // Output file PDF
    $pdf->Output('D', 'Sertifikat_'.$name.'.pdf');

    // Hapus file QR code setelah digunakan
    unlink($qr_file);

    // Simpan nomor sertifikat baru ke database
    $query_insert = "INSERT INTO sertifikat (no_sertifikat, nama, institusi) VALUES ($no_seri_baru, '$name', '$institution')";
    $conn->query($query_insert);

} else {
    die('Metode pengiriman tidak valid.');
}

$conn->close();
?>
