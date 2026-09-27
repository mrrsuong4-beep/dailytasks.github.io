# Attendance Pro — PHP Attendance Management System

ប្រព័ន្ធគ្រប់គ្រងវត្តមានបុគ្គលិក បែបអាជីព សម្រាប់ដំណើរការលើ PHP + MySQL។

## Features
- Dashboard: សរុបបុគ្គលិក / មកធ្វើការ / អវត្តមាន / យឺត
- Employee management: រូបថត, ឈ្មោះ, ភេទ, ផ្នែក, តួនាទី, ទូរស័ព្ទ, ស្ថានភាព
- Daily attendance: Present, Late, Absent, Leave
- Check-in / Check-out
- Search & filter
- Monthly attendance report
- Responsive UI សម្រាប់ Computer / Tablet / Phone
- PDO prepared statements
- MySQL database
- Upload រូបថតបុគ្គលិក
- GitHub-ready structure

## Requirements
- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.4+
- Apache / XAMPP / Laragon

## Installation
1. Copy project to `htdocs/attendance_pro`
2. Create database `attendance_pro`
3. Import `database/attendance_pro.sql`
4. Edit `config/database.php`
5. Open `http://localhost/attendance_pro/public/`

Default demo login:
- Username: `admin`
- Password: `admin123`

> Change the password before production use.

## GitHub
Upload the whole folder to a GitHub repository. Do not upload real employee photos/data to a public repository unless you intend to make them public.

## Structure
- `public/` web entry point and assets
- `app/` controllers/helpers
- `config/` database configuration
- `database/` SQL schema + seed data
- `views/` UI templates


## Folder layout (GitHub-friendly)
- `javascript/` — JavaScript
- `photo/` — employee photos/uploads
- `php/` — PHP backend/config/database
- `style/` — CSS styles
- `webfonts/` — place custom webfonts here
- `index.html` — first/landing entry
