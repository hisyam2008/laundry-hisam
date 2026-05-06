ja sa<?php
$current  = basename($_SERVER['PHP_SELF']);
$is_admin = $_SESSION['role'] === 'admin';
$is_owner = $_SESSION['role'] === 'owner';
$is_kasir = $_SESSION['role'] === 'kasir';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Laundry Hisam' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }

        .sidebar {
            width: 230px;
            min-height: 100vh;
            background: #1a2e22;
            position: fixed;
            top: 0; left: 0;
            z-index: 1040;
            transition: transform .25s ease;
            display: flex;
            flex-direction: column;
        }
        .sidebar.hide { transform: translateX(-100%); }

        .sidebar-brand {
            padding: 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
        }
        .sidebar-brand .brand-title { font-size: .95rem; }
        .sidebar-brand .brand-role {
            font-size: .75rem;
            padding: 3px 8px;
            border-radius: 16px;
            font-weight: 500;
        }
        .sidebar-close {
            display: none;
            background: none;
            border: none;
            color: rgba(255,255,255,.6);
            font-size: 1.1rem;
            cursor: pointer;
        }

        .nav-section {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,.3);
            padding: 14px 16px 4px;
        }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 16px;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: .85rem;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .sidebar-nav a:hover { background: rgba(255,255,255,.07); color: #fff; }
        .sidebar-nav a.active { background: rgba(255,255,255,.1); color: #fff; border-left-color: #52b788; }
        .sidebar-nav a i { width: 16px; font-size: .95rem; }

        .sidebar-user {
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,.08);
            font-size: .82rem;
            color: rgba(255,255,255,.5);
        }
        .sidebar-user strong { color: #fff; display: block; margin-bottom: 4px; font-size: .85rem; }
        .sidebar-user a { color: rgba(255,255,255,.55); text-decoration: none; display: flex; align-items: center; gap: 5px; }
        .sidebar-user a:hover { color: #fff; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1039;
        }
        .sidebar-overlay.show { display: block; }

        .main-content { margin-left: 230px; padding: 24px; }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 11px 20px;
            margin: -24px -24px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar h6 { margin: 0; font-weight: 600; color: #1a2e22; font-size: .9rem; }
        .topbar .ms-auto { margin-left: auto !important; }
        .btn-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #1a2e22;
            cursor: pointer;
            padding: 0;
        }

        .card { border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; }
        .card-header-custom {
            padding: 12px 18px;
            background: #f8f9fa;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header-custom h5 { margin: 0; font-size: .95rem; font-weight: 600; color: #1a2e22; }

        .table thead th { background: #f8f9fa; font-size: .82rem; font-weight: 600; color: #495057; border-bottom: 2px solid #e2e8f0; }
        .table td { font-size: .85rem; vertical-align: middle; }

        .stat-card { border-left: 4px solid #2d6a4f !important; }

        .btn-hijau { background: #2d6a4f; color: #fff; border: none; }
        .btn-hijau:hover { background: #245a42; color: #fff; }

        .form-control, .form-select {
            font-size: .875rem;
            border-color: #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 .2rem rgba(45,106,79,.15);
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-close { display: block; }
            .main-content { margin-left: 0; padding: 16px; }
            .topbar { margin: -16px -16px 16px; padding: 10px 14px; }
            .btn-toggle { display: block; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>
<div class="sidebar" id="sidebar">
    <?php
    $roleColor = ['admin'=>'danger','owner'=>'warning','kasir'=>'success'];
    $roleLabel = ['admin'=>'Admin','owner'=>'Owner','kasir'=>'Kasir'];
    $userRoleColor = $roleColor[$_SESSION['role']] ?? 'secondary';
    $userRoleLabel = $roleLabel[$_SESSION['role']] ?? ucfirst($_SESSION['role']);
    ?>
    <div class="sidebar-brand">
        <div class="brand-title">🧺 Laundry Hisam</div>
        <span class="badge bg-<?= $userRoleColor ?>-subtle text-<?= $userRoleColor ?> border border-<?= $userRoleColor ?>-subtle brand-role"><?= $userRoleLabel ?></span>
    </div>

    <div class="sidebar-nav">
        <a href="/Laundry-hisam/index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Operasional</div>
        <a href="/Laundry-hisam/transaksi.php" class="<?= in_array($current, ['transaksi.php','tambah_transaksi.php','detail_transaksi.php']) ? 'active' : '' ?>">
            <i class="bi bi-receipt"></i> Transaksi
        </a>
        <a href="/Laundry-hisam/paket.php" class="<?= $current === 'paket.php' ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i> Paket
        </a>
        <a href="/Laundry-hisam/member.php" class="<?= $current === 'member.php' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Pelanggan
        </a>

        <div class="nav-section">Manajemen</div>
        <a href="/Laundry-hisam/outlet.php" class="<?= $current === 'outlet.php' ? 'active' : '' ?>">
            <i class="bi bi-shop"></i> Outlet
        </a>

        <?php if ($is_admin || $is_owner): ?>
        <a href="/Laundry-hisam/daftar_user.php" class="<?= in_array($current, ['daftar_user.php','tambah_user.php','edit_user.php']) ? 'active' : '' ?>">
            <i class="bi bi-person-gear"></i> Pengguna
        </a>
        <?php endif; ?>

        <a href="/Laundry-hisam/logout.php" style="color:#e53e3e;">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>

    <div class="sidebar-user"></div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}
</script>
