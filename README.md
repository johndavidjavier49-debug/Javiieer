# Academic Portfolio Website

Full-stack academic portfolio using PHP + MySQL + HTML/CSS/JavaScript.

## Features
- Public portfolio with Home, About, Portfolio and Contact sections
- Categories: Quiz, Long Quiz, Activities, Midterms, Finals, Projects
- Admin-only login
- Upload, edit, replace and delete portfolio files
- Image previews
- View and download links for visitors
- MySQL database
- CSRF protection
- Password hashing
- 20 MB upload limit
- Responsive minimalist design
- Scroll and hover animations

## Installation with XAMPP
1. Install XAMPP and start Apache + MySQL.
2. Copy the `academic_portfolio` folder to `C:/xampp/htdocs/`.
3. Open phpMyAdmin.
4. Import `database/academic_portfolio.sql`.
5. Check `config.php`:
   - host: localhost
   - database: academic_portfolio
   - username: root
   - password: blank by default
6. Visit:
   http://localhost/academic_portfolio/
7. Admin:
   http://localhost/academic_portfolio/admin/login.php

## IMPORTANT
Change the default admin password immediately in production. The SQL file contains a starter password hash for:
Username: admin
Password: ChangeThisPassword123!

For a public/hosted deployment, use HTTPS and create a strong unique admin password.
