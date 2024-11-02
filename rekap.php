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

// Query untuk mengambil semua data dari tabel sertifikat
$query = "SELECT no_sertifikat, nama, institusi, tanggal_terbit FROM sertifikat";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Data Sertifikat</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Rekap Data Sertifikat</h2>
<table>
    <thead>
        <tr>
            <th>No. Sertifikat</th>
            <th>Nama</th>
            <th>Institusi</th>
            <th>Tanggal Terbit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output data untuk setiap baris
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['no_sertifikat']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                echo "<td>" . htmlspecialchars($row['institusi']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tanggal_terbit']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data yang ditemukan</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
// Tutup koneksi database
$conn->close();
?>
