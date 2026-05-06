<?php
require_once 'auth.php';
require_once 'config/Database.php';
require_once 'app/models/OutletModel.php';
require_once 'app/models/MemberModel.php';
require_once 'app/models/UserModel.php';

$db     = (new Database())->connect();
$outlet = new OutletModel($db);
$member = new MemberModel($db);
$user   = new UserModel($db);

$page_title = 'Dashboard — Laundry Hisam';
require_once 'layout/sidebar.php';
?>

<div class="main-content">
    <div class="topbar">
        <button class="btn-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <h6><i class="bi bi-speedometer2 me-2"></i>Dashboard</h6>
        <span class="ms-auto text-muted" style="font-size:.8rem;"><?= date('d F Y') ?></span>
    </div>

    <?php if (isset($_GET['err']) && $_GET['err'] === 'akses'): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2">
            <i class="bi bi-shield-exclamation me-1"></i>Akses ditolak. Anda tidak memiliki izin.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($is_admin): ?>
    <!-- Dashboard Owner -->
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10" style="width:46px;height:46px;">
                        <i class="bi bi-shop text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1"><?= $outlet->count() ?></div>
                        <div class="text-muted" style="font-size:.8rem;">Total Outlet</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3" style="border-left:4px solid #0d6efd;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="width:46px;height:46px;">
                        <i class="bi bi-people text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1"><?= $member->count() ?></div>
                        <div class="text-muted" style="font-size:.8rem;">Total Member</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3" style="border-left:4px solid #fd7e14;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10" style="width:46px;height:46px;">
                        <i class="bi bi-person-gear text-warning fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1"><?= $user->count() ?></div>
                        <div class="text-muted" style="font-size:.8rem;">Total User</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header-custom">
                    <h5><i class="bi bi-shop me-2"></i>Outlet Terbaru</h5>
                    <a href="outlet.php" class="btn btn-hijau btn-sm">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>#</th><th>Nama</th><th>Alamat</th></tr></thead>
                        <tbody>
                            <?php $outlets = $outlet->latest(5); ?>
                            <?php if (empty($outlets)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-3">Belum ada outlet.</td></tr>
                            <?php else: foreach ($outlets as $i => $o): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($o['nama']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($o['alamat']) ?></td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header-custom">
                    <h5><i class="bi bi-people me-2"></i>Member Terbaru</h5>
                    <a href="member.php" class="btn btn-hijau btn-sm">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>#</th><th>Nama</th><th>Telepon</th></tr></thead>
                        <tbody>
                            <?php $members = $member->latest(5); ?>
                            <?php if (empty($members)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-3">Belum ada member.</td></tr>
                            <?php else: foreach ($members as $i => $m): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($m['nama']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($m['tlp']) ?></td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- Dashboard Kasir -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="card p-3" style="border-left:4px solid #0d6efd;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="width:46px;height:46px;">
                        <i class="bi bi-people text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1"><?= $member->count() ?></div>
                        <div class="text-muted" style="font-size:.8rem;">Total Member</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header-custom">
            <h5><i class="bi bi-people me-2"></i>Member Terbaru</h5>
            <a href="member.php" class="btn btn-hijau btn-sm">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Nama</th><th>Telepon</th></tr></thead>
                <tbody>
                    <?php $members = $member->latest(5); ?>
                    <?php if (empty($members)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada member.</td></tr>
                    <?php else: foreach ($members as $i => $m): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($m['nama']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($m['tlp']) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'layout/footer.php'; ?>
