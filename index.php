<?php
session_start();
require __DIR__.'/config/database.php';
require __DIR__.'/app/controllers/PengeluaranController.php';
require __DIR__.'/app/controllers/KategoriController.php';


$pengeluaran_controller = new PengeluaranController();
$kategoriController = new KategoriController();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch($request) {
    case '/':
        $pengeluaran_controller->index();
        break;
    case '/tambah':
        $pengeluaran_controller->tambah();
        break;
    case strpos($request, '/edit/') === 0:
        $id = str_replace('/edit/', '', $request);
        $pengeluaran_controller->edit($id);
        break;
    case strpos($request, '/hapus/') === 0:
        $id = str_replace('/hapus/', '', $request);
        $pengeluaran_controller->hapus($id);
        break;
    
    // Untuk Kategori
    case $request === '/kategori':
        $kategoriController->index();
        break;
    case $request === '/kategori/tambah':
        $kategoriController->tambah();
        break;
    case strpos($request, '/kategori/edit/') === 0:
        $id = str_replace('/kategori/edit/', '', $request);
        $kategoriController->edit($id);
        break;
    case strpos($request, '/kategori/hapus/') === 0:
        $id = str_replace('/kategori/hapus/', '', $request);
        $kategoriController->hapus($id);
        break;
    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan";
        break;
}
?>