<?php
require __DIR__ . '/../app/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM employees WHERE id=?");
        $stmt->execute([(int)$_POST['id']]);
        flash('success','បានលុបបុគ្គលិក។');
        redirect('employees.php');
    }

    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['employee_code'] ?? '');
        $gender = $_POST['gender'] ?? 'other';
        $department = trim($_POST['department'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $status = $_POST['status'] ?? 'active';

        $photo = null;
        if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed, true) && $_FILES['photo']['size'] <= 3*1024*1024) {
                $photo = 'uploads/' . bin2hex(random_bytes(8)) . '.' . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/' . $photo);
            }
        }

        if ($id) {
            $sql = "UPDATE employees SET employee_code=?,name=?,gender=?,department=?,position=?,phone=?,status=?";
            $params = [$code,$name,$gender,$department,$position,$phone,$status];
            if ($photo) { $sql .= ",photo=?"; $params[] = $photo; }
            $sql .= " WHERE id=?";
            $params[] = $id;
            $pdo->prepare($sql)->execute($params);
            flash('success','បានកែប្រែព័ត៌មានបុគ្គលិក។');
        } else {
            $pdo->prepare("INSERT INTO employees(employee_code,name,gender,department,position,phone,status,photo) VALUES(?,?,?,?,?,?,?,?)")
                ->execute([$code,$name,$gender,$department,$position,$phone,$status,$photo]);
            flash('success','បានបន្ថែមបុគ្គលិកថ្មី។');
        }
        redirect('employees.php');
    }
}

$q = trim($_GET['q'] ?? '');
$stmt = $pdo->prepare("SELECT * FROM employees WHERE name LIKE ? OR employee_code LIKE ? OR department LIKE ? ORDER BY id DESC");
$like = "%$q%"; $stmt->execute([$like,$like,$like]);
$employees = $stmt->fetchAll();

$pageTitle='បុគ្គលិក';
include __DIR__ . '/../views/header.php';
?>
<div class="section-head"><div><h1>បុគ្គលិក</h1><p>បញ្ជីឈ្មោះ រូបថត ផ្នែក តួនាទី និងស្ថានភាព</p></div>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeModal"><i class="bi bi-person-plus"></i> បន្ថែមបុគ្គលិក</button></div>

<div class="table-card">
<div class="toolbar"><form class="search" method="get"><i class="bi bi-search"></i><input name="q" value="<?= e($q) ?>" placeholder="ស្វែងរកឈ្មោះ / លេខកូដ / ផ្នែក..."></form></div>
<div class="table-responsive"><table class="table align-middle mb-0">
<thead><tr><th>រូបថត</th><th>លេខកូដ</th><th>ឈ្មោះ</th><th>ផ្នែក</th><th>តួនាទី</th><th>ទូរស័ព្ទ</th><th>ស្ថានភាព</th><th></th></tr></thead>
<tbody>
<?php foreach($employees as $e1): ?>
<tr>
<td><img class="avatar" src="<?= e($e1['photo'] ?: 'assets/img/avatar.svg') ?>" onerror="this.src='assets/img/avatar.svg'"></td>
<td><b><?= e($e1['employee_code']) ?></b></td><td><?= e($e1['name']) ?></td><td><?= e($e1['department']) ?></td><td><?= e($e1['position']) ?></td><td><?= e($e1['phone']) ?></td>
<td><span class="badge-status <?= e($e1['status']) ?>"><?= $e1['status']==='active'?'Active':'Inactive' ?></span></td>
<td><button class="btn btn-sm btn-light" onclick='editEmployee(<?= json_encode($e1, JSON_UNESCAPED_UNICODE) ?>)'><i class="bi bi-pencil"></i></button>
<form class="d-inline" method="post" onsubmit="return confirm('លុបបុគ្គលិកនេះ?')"><input type="hidden" name="csrf" value="<?= csrf_token() ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $e1['id'] ?>"><button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>

<div class="modal fade" id="employeeModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= csrf_token() ?>"><input type="hidden" name="action" value="save"><input type="hidden" id="emp_id" name="id">
<div class="modal-header"><h5 class="modal-title">បន្ថែមបុគ្គលិក</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
<div class="col-md-6"><label>លេខកូដ</label><input id="employee_code" class="form-control" name="employee_code" required></div>
<div class="col-md-6"><label>ឈ្មោះពេញ</label><input id="name" class="form-control" name="name" required></div>
<div class="col-md-4"><label>ភេទ</label><select id="gender" class="form-select" name="gender"><option value="male">ប្រុស</option><option value="female">ស្រី</option><option value="other">ផ្សេងៗ</option></select></div>
<div class="col-md-4"><label>ផ្នែក</label><input id="department" class="form-control" name="department"></div>
<div class="col-md-4"><label>តួនាទី</label><input id="position" class="form-control" name="position"></div>
<div class="col-md-6"><label>ទូរស័ព្ទ</label><input id="phone" class="form-control" name="phone"></div>
<div class="col-md-6"><label>ស្ថានភាព</label><select id="status" class="form-select" name="status"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
<div class="col-12"><label>រូបថត (JPG/PNG/WebP, max 3MB)</label><input class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"></div>
</div></div>
<div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">បិទ</button><button class="btn btn-primary">រក្សាទុក</button></div>
</form></div></div></div>
<?php include __DIR__ . '/../views/footer.php'; ?>
