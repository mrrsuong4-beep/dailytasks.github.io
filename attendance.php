<?php
require __DIR__ . '/../app/bootstrap.php';
require_login();

$date = $_GET['date'] ?? date('Y-m-d');

if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $employeeId=(int)$_POST['employee_id'];
    $date=$_POST['attendance_date'];
    $status=$_POST['status'];
    $checkIn=$_POST['check_in'] ?: null;
    $checkOut=$_POST['check_out'] ?: null;
    $stmt=$pdo->prepare("INSERT INTO attendance(employee_id,attendance_date,status,check_in,check_out,note) VALUES(?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE status=VALUES(status),check_in=VALUES(check_in),check_out=VALUES(check_out),note=VALUES(note)");
    $stmt->execute([$employeeId,$date,$status,$checkIn,$checkOut,trim($_POST['note'] ?? '')]);
    flash('success','បានរក្សាទុកវត្តមាន។');
    redirect('attendance.php?date='.urlencode($date));
}

$employees=$pdo->query("SELECT * FROM employees WHERE status='active' ORDER BY name")->fetchAll();
$stmt=$pdo->prepare("SELECT a.*,e.name,e.employee_code,e.photo FROM attendance a JOIN employees e ON e.id=a.employee_id WHERE a.attendance_date=? ORDER BY e.name");
$stmt->execute([$date]); $rows=$stmt->fetchAll();
$map=[]; foreach($rows as $r) $map[$r['employee_id']]=$r;

$pageTitle='វត្តមាន';
include __DIR__ . '/../views/header.php';
?>
<div class="section-head"><div><h1>វត្តមានប្រចាំថ្ងៃ</h1><p>កត់ត្រា Check-in / Check-out និងស្ថានភាព</p></div>
<form class="date-form"><input type="date" name="date" class="form-control" value="<?= e($date) ?>"><button class="btn btn-primary">បង្ហាញ</button></form></div>

<div class="table-card"><div class="table-responsive"><table class="table align-middle mb-0">
<thead><tr><th>បុគ្គលិក</th><th>Check-in</th><th>Check-out</th><th>ស្ថានភាព</th><th>កំណត់ចំណាំ</th><th></th></tr></thead><tbody>
<?php foreach($employees as $emp): $a=$map[$emp['id']]??null; ?>
<tr>
<td><div class="person"><img src="<?= e($emp['photo'] ?: 'assets/img/avatar.svg') ?>" onerror="this.src='assets/img/avatar.svg'"><div><b><?= e($emp['name']) ?></b><small><?= e($emp['employee_code']) ?></small></div></div></td>
<td><?= e($a['check_in']??'—') ?></td><td><?= e($a['check_out']??'—') ?></td>
<td><?= $a ? '<span class="badge-status '.e($a['status']).'">'.e(ucfirst($a['status'])).'</span>' : '<span class="badge-status absent">Not set</span>' ?></td>
<td><?= e($a['note']??'') ?></td>
<td><button class="btn btn-sm btn-primary" onclick='openAttendance(<?= json_encode(["id"=>$emp["id"],"name"=>$emp["name"],"date"=>$date,"check_in"=>$a["check_in"]??"","check_out"=>$a["check_out"]??"","status"=>$a["status"]??"present","note"=>$a["note"]??""],JSON_UNESCAPED_UNICODE) ?>)'>កត់ត្រា</button></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>

<div class="modal fade" id="attendanceModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="post">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>"><input type="hidden" id="a_employee_id" name="employee_id"><input type="hidden" id="a_date" name="attendance_date">
<div class="modal-header"><h5 class="modal-title" id="a_title">វត្តមាន</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><label>ស្ថានភាព</label><select class="form-select mb-3" name="status" id="a_status"><option value="present">Present</option><option value="late">Late</option><option value="absent">Absent</option><option value="leave">Leave</option></select>
<div class="row g-3"><div class="col-6"><label>Check-in</label><input type="time" class="form-control" name="check_in" id="a_in"></div><div class="col-6"><label>Check-out</label><input type="time" class="form-control" name="check_out" id="a_out"></div></div>
<label class="mt-3">កំណត់ចំណាំ</label><textarea class="form-control" name="note" id="a_note" rows="3"></textarea></div>
<div class="modal-footer"><button class="btn btn-primary">រក្សាទុក</button></div></form></div></div></div>
<?php include __DIR__ . '/../views/footer.php'; ?>
