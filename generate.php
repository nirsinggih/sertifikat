<?php
// Tampilkan semua error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include FPDF
require('fpdf/fpdf.php');

// Cek apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = htmlspecialchars($_POST['name']);
    $institution = htmlspecialchars($_POST['institution']);

    // Validasi data sederhana
    if (empty($name) || empty($institution)) {
        die('Nama dan Institusi harus diisi.');
    }

	// Inisialisasi FPDF dengan orientasi landscape (L)
    $pdf = new FPDF('L', 'mm', 'A4'); // 'L' untuk landscape, 'mm' untuk millimeter, 'A4' adalah ukuran kertas
 
    
    // Halaman pertama: Sertifikat utama
    $pdf->AddPage();
    
    // Background image sertifikat (sesuaikan ukuran dengan template Anda)
    $pdf->Image('sertifikat-depan.jpg', 0, 0, 297, 210); // A4 ukuran 210x297mm landscape
    
    // Set font untuk nama dan institusi
    $pdf->SetFont('Arial', 'B', 24);
    
  // Tulis nama peserta
    $pdf->SetXY(90, 94); // Atur posisi teks di landscape
    $pdf->Cell(110, 10, $name, 0, 1, 'C'); // Tulis nama di tengah
    
    // Tulis nama institusi
    $pdf->SetXY(90, 107); // Atur posisi teks di landscape
    $pdf->Cell(110, 10, $institution, 0, 1, 'C'); // Tulis institusi di tengah
    
    // Halaman kedua: Tetap (gambar JPG tanpa teks)
    $pdf->AddPage(); // Tambah halaman baru
    
    // Background image untuk halaman kedua (tetap)
    $pdf->Image('sertifikat-belakang.jpg', 0, 0, 297, 210); // Sesuaikan path dan ukuran JPG untuk halaman kedua

    // Output file PDF (langsung download)
    $pdf->Output('D', 'Sertifikat_'.$name.'.pdf');
} else {
    die('Metode pengiriman tidak valid.');
}
?>
