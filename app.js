function editEmployee(e){
  document.getElementById('emp_id').value=e.id||'';
  document.getElementById('employee_code').value=e.employee_code||'';
  document.getElementById('name').value=e.name||'';
  document.getElementById('gender').value=e.gender||'other';
  document.getElementById('department').value=e.department||'';
  document.getElementById('position').value=e.position||'';
  document.getElementById('phone').value=e.phone||'';
  document.getElementById('status').value=e.status||'active';
  document.querySelector('#employeeModal .modal-title').textContent='កែប្រែបុគ្គលិក';
  new bootstrap.Modal('#employeeModal').show();
}
function openAttendance(a){
  document.getElementById('a_employee_id').value=a.id;
  document.getElementById('a_date').value=a.date;
  document.getElementById('a_title').textContent='វត្តមាន — '+a.name;
  document.getElementById('a_status').value=a.status;
  document.getElementById('a_in').value=a.check_in;
  document.getElementById('a_out').value=a.check_out;
  document.getElementById('a_note').value=a.note;
  new bootstrap.Modal('#attendanceModal').show();
}
document.addEventListener('hidden.bs.modal',()=>{});
