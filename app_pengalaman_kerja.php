<?php
session_start();

// Cek apakah session untuk pengalaman kerja sudah ada, jika belum buat array kosong
if (!isset($_SESSION['pekerjaan'])) {
    $_SESSION['pekerjaan'] = [];
}

// Menambahkan pengalaman kerja
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_pekerjaan'])) {
    $posisi = $_POST['posisi'];
    $perusahaan = $_POST['perusahaan'];
    $tahun = $_POST['tahun'];
    $deskripsi = $_POST['deskripsi'];

    // Menambahkan data ke session
    $_SESSION['pekerjaan'][] = [
        'posisi' => $posisi,
        'perusahaan' => $perusahaan,
        'tahun' => $tahun,
        'deskripsi' => $deskripsi
    ];
}

// Menghapus pengalaman kerja berdasarkan index
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete'])) {
    $index = $_GET['delete'];
    // Menghapus data pengalaman kerja dari session
    unset($_SESSION['pekerjaan'][$index]);
    // Menyusun ulang array setelah penghapusan
    $_SESSION['pekerjaan'] = array_values($_SESSION['pekerjaan']);
}

// Memperbarui pengalaman kerja
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_pekerjaan'])) {
    $index = $_POST['index'];
    $posisi = $_POST['posisi'];
    $perusahaan = $_POST['perusahaan'];
    $tahun = $_POST['tahun'];
    $deskripsi = $_POST['deskripsi'];

    // Memperbarui data pengalaman kerja di session
    $_SESSION['pekerjaan'][$index] = [
        'posisi' => $posisi,
        'perusahaan' => $perusahaan,
        'tahun' => $tahun,
        'deskripsi' => $deskripsi
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengalaman Kerja - Aplikasi CRUD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #40e0d0;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #40e0d0;
            color: white;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Pengalaman Kerja</h2>
        
        <!-- Form untuk menambah pengalaman kerja -->
        <form method="POST">
            <input type="text" name="posisi" placeholder="Posisi" required>
            <input type="text" name="perusahaan" placeholder="Perusahaan" required>
            <input type="text" name="tahun" placeholder="Tahun" required>
            <textarea name="deskripsi" placeholder="Deskripsi Pekerjaan" required></textarea>
            <button type="submit" name="add_pekerjaan">Tambah Pengalaman</button>
        </form>

        <!-- Menampilkan daftar pengalaman kerja -->
        <h2>Daftar Pengalaman Kerja</h2>
        <?php if (!empty($_SESSION['pekerjaan'])): ?>
            <table>
                <tr>
                    <th>Posisi</th>
                    <th>Perusahaan</th>
                    <th>Tahun</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($_SESSION['pekerjaan'] as $index => $pekerjaan): ?>
                    <tr>
                        <td><?= htmlspecialchars($pekerjaan['posisi']) ?></td>
                        <td><?= htmlspecialchars($pekerjaan['perusahaan']) ?></td>
                        <td><?= htmlspecialchars($pekerjaan['tahun']) ?></td>
                        <td><?= htmlspecialchars($pekerjaan['deskripsi']) ?></td>
                        <td class="action-buttons">
                            <!-- Tombol untuk menghapus pengalaman kerja -->
                            <a href="?delete=<?= $index ?>"><button>Hapus</button></a>

                            <!-- Tombol untuk mengupdate pengalaman kerja -->
                            <button onclick="openEditForm(<?= $index ?>, '<?= htmlspecialchars($pekerjaan['posisi']) ?>', '<?= htmlspecialchars($pekerjaan['perusahaan']) ?>', '<?= htmlspecialchars($pekerjaan['tahun']) ?>', '<?= htmlspecialchars($pekerjaan['deskripsi']) ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Belum ada pengalaman kerja yang ditambahkan.</p>
        <?php endif; ?>
    </div>

    <!-- Form untuk mengedit pengalaman kerja -->
    <div id="editForm" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; width: 400px; border-radius: 10px;">
            <h3>Edit Pengalaman Kerja</h3>
            <form method="POST">
                <input type="hidden" name="index" id="editIndex">
                <input type="text" name="posisi" id="editPosisi" required placeholder="Posisi">
                <input type="text" name="perusahaan" id="editPerusahaan" required placeholder="Perusahaan">
                <input type="text" name="tahun" id="editTahun" required placeholder="Tahun">
                <textarea name="deskripsi" id="editDeskripsi" required placeholder="Deskripsi Pekerjaan"></textarea>
                <button type="submit" name="update_pekerjaan">Perbarui Pengalaman</button>
                <button type="button" onclick="closeEditForm()">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function openEditForm(index, posisi, perusahaan, tahun, deskripsi) {
            document.getElementById('editIndex').value = index;
            document.getElementById('editPosisi').value = posisi;
            document.getElementById('editPerusahaan').value = perusahaan;
            document.getElementById('editTahun').value = tahun;
            document.getElementById('editDeskripsi').value = deskripsi;
            document.getElementById('editForm').style.display = 'flex';
        }

        function closeEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
</body>
</html>
