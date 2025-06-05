<?php
function deleteFolderContents($folder) {
    // Pastikan folder ada
    if (!is_dir($folder)) {
        echo "Folder tidak ditemukan!";
        return;
    }

    // Scan isi folder
    $files = array_diff(scandir($folder), array('.', '..'));

    // Hapus setiap file atau folder di dalamnya
    foreach ($files as $file) {
        $filePath = $folder . DIRECTORY_SEPARATOR . $file;

        if (is_dir($filePath)) {
            // Jika folder, hapus secara rekursif
            deleteFolderContents($filePath);
            rmdir($filePath);
        } else {
            // Jika file, hapus
            unlink($filePath);
        }
    }
    echo "Semua isi folder '$folder' telah dihapus.";
}

// Panggil fungsi untuk folder 'uploads'
deleteFolderContents('uploads');
?>
