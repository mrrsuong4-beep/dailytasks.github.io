<?php
require __DIR__ . '/../app/bootstrap.php';
require_login();

$today = date('Y-m-d');

$totalEmployees = (int)$pdo->query("SELECT COUNT(*) FROM employees WHERE status='active'")->fetchColumn();
$presentToday = (int)$pdo->query("SELECT COUNT(*) FROM attendance WHERE attendance_date='$today' AND status IN ('present','late')")->fetchColumn();
$lateToday = (int)$pdo->query("SELECT COUNT(*) FROM attendance WHERE attendance_date='$today' AND status='late'")->fetchColumn();
$absentToday = max(0, $totalEmployees - (int)$pdo->query("SELECT COUNT(*) FROM attendance WHERE attendance_date='$today'")->fetchColumn());

$recent = $pdo->query("
    SELECT a.*, e.name, e.photo, e.employee_code
    FROM attendance a
    JOIN employees e ON e.id=a.employee_id
    WHERE a.attendance_date='$today'
    ORDER BY COALESCE(a.check_in,'23:59:59') DESC, e.name ASC
    LIMIT 10
")->fetchAll();

$pageTitle = 'Dashboard';
include __DIR__ . '/../views/header.php';
?>
<div class="hero">
  <div>
    <div class="eyebrow">ATTENDANCE MANAGEMENT</div>
    <h1>Dashboard</h1>
    <p>គ្រប់គ្រងវត្តមានបុគ្គលិកប្រចាំថ្ងៃបានលឿន និងមានរបៀបរៀបរយ។</p>
  </div>
  <a class="btn btn-light" href="attendance.php"><i class="bi bi-calendar-check"></i> កត់វត្តមានថ្ងៃនេះ</a>
</div>

<div class="stats-grid">
  <div class="stat-card"><div class="icon blue"><i class="bi bi-people"></i></div><div><span>បុគ្គលិកសកម្ម</span><strong><?= $totalEmployees ?></strong></div></div>
  <div class="stat-card"><div class="icon green"><i class="bi bi-check2-circle"></i></div><div><span>មកធ្វើការ</span><strong><?= $presentToday ?></strong></div></div>
  <div class="stat-card"><div class="icon orange"><i class="bi bi-clock-history"></i></div><div><span>មកយឺត</span><strong><?= $lateToday ?></strong></div></div>
  <div class="stat-card"><div class="icon red"><i class="bi bi-person-x"></i></div><div><span>មិនទាន់មានកំណត់ត្រា</span><strong><?= $absentToday ?></strong></div></div>
</div>

<div class="section-head">
  <div><h2>វត្តមានថ្ងៃនេះ</h2><small><?= date('d M Y') ?></small></div>
  <a href="reports.php" class="btn btn-outline-primary">មើលរបាយការណ៍</a>
</div>

<div class="table-card">
<table class="table align-middle mb-0">
<thead><tr><th>បុគ្គលិក</th><th>លេខកូដ</th><th>Check-in</th><th>Check-out</th><th>ស្ថានភាព</th></tr></thead>
<tbody>
<?php foreach ($recent as $r): ?>
<tr>
  <td><div class="person"><img src="<?= e($r['photo'] ?: 'assets/img/avatar.svg') ?>" onerror="this.src='assets/img/avatar.svg'"><div><b><?= e($r['name']) ?></b></div></div></td>
  <td><?= e($r['employee_code']) ?></td>
  <td><?= e($r['check_in'] ?: '-') ?></td>
  <td><?= e($r['check_out'] ?: '-') ?></td>
  <td><span class="badge-status <?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span></td>
</tr>
<?php endforeach; ?>
<?php if (!$recent): ?><tr><td colspan="5" class="empty">មិនទាន់មានទិន្នន័យវត្តមានសម្រាប់ថ្ងៃនេះ។</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php include __DIR__ . '/../views/footer.php'; ?>
