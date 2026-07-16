<?php
require_once __DIR__ . '/../helpers.php';

class DashboardController
{
    public function index(): void
    {
        require_role(['admin', 'pegawai', 'pimpinan']);
        include __DIR__ . '/../view/dashboard.php';
    }
}
