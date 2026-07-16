<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/laporan.php';

class DataLaporanController
{
    public function index(): void
    {
        require_role(['admin', 'pimpinan']);
        include __DIR__ . '/../view/data_laporan_barang.php';
    }
}
