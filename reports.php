<?php
require __DIR__ . '/../app/bootstrap.php';
require_login();

$month=$_GET['month']??date('Y-m');
$stmt=$pdo->prepare("SELECT e.employee_code,e.name,e.department,
SUM(a.status='present') present_count,
SUM(a.status='late') late_count,
SUM(a.status='absent') absent_count,
SUM(a.status='leave') leave_count
FROM employees e LEFT JOIN attendance a ON a.employee_id=e.id AND DATE_FORMAT(a.attendance_date,'%Y-%m')=?
WHERE e.status='active' GROUP BY e.id ORDER BY e.name");
$stmt->execute([$month]); $rows=$stmt->fetchAll();

$pageTitle='របាយការណ៍';
include __DIR__ . '/../views/header.php';
?>
<div class="section-head"><div><h1>របាយការណ៍វត្តមាន</h1><p>សង្ខេបតាមខែ</p></div>
<form class="date-form"><input type="month" name="month" class="form-control" value="<?= e($month) ?>"><button class="btn btn-primary">ស្វែងរក</button></form></div>
<div class="table-card"><div class="table-responsive"><table class="table align-middle mb-0">
<thead><tr><th>លេខកូដ</th><th>ឈ្មោះ</th><th>ផ្នែក</th><th>Present</th><th>Late</th><th>Absent</th><th>Leave</th><th>សរុប</th></tr></thead>
<tbody><?php foreach($rows as $r): $total=(int)$r['present_count']+(int)$r['late_count']+(int)$r['absent_count']+(int)$r['leave_count']; ?>
<tr><td><?=e($r['employee_code'])?></td><td><b><?=e($r['name'])?></b></td><td><?=e($r['department'])?></td><td><span class="num green-t"><?=e($r['present_count']??0)?></span></td><td><span class="num orange-t"><?=e($r['late_count']??0)?></span></td><td><span class="num red-t"><?=e($r['absent_count']??0)?></span></td><td><span class="num"><?=e($r['leave_count']??0)?></span></td><td><b><?=$total?></b></td></tr>
<?php endforeach; ?></tbody></table></div></div>
<?php include __DIR__ . '/../views/footer.php'; ?>
