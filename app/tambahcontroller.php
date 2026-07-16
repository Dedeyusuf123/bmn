<?php
require_once __DIR__ . '/../helpers.php';

class TambahController
{
    public function index(): void
    {
        require_role(['admin']);
        include __DIR__ . '/../view/tambah.php';
    }
}
