# Kampung Sentosa Issues Tracker

Community-driven neighborhood issue reporting and management portal for **Kampung Sentosa**, enabling residents to submit and track community issues and committee administrators to review and manage reports.

---

## Project Structure

```text
KG-SENTOSA/
├── admin/                        # Committee Admin Portal
│   ├── loginadmin.php            # Committee Admin login & signup page
│   ├── auth.php                  # Committee Admin login/signup form handler
│   ├── adminhome.php             # Committee Admin dashboard
│   ├── adminview.php             # Committee Admin: Manage admin accounts
│   ├── userview.php              # Committee Admin: Manage resident accounts
│   └── report.php                # Committee Admin: View & manage reported issues
│
├── user/                         # Resident Portal
│   ├── register.php              # Resident registration page
│   ├── index1.php                # Resident login page
│   ├── about1.php                # Resident home / About Us (authenticated)
│   ├── issueuser.php             # Resident: Report an Issue form
│   ├── view_issues.php           # Resident: View reported issues list
│   ├── edit_issue.php            # Resident: Edit a reported issue
│   ├── delete_issue.php          # Resident: Delete a reported issue
│   └── edit_profile.php          # Resident: Edit profile & account settings
│
├── css/                          # Stylesheets (vendor & custom CSS)
│   ├── admin.css                 # Admin login/signup page styles
│   ├── animate.css               # Animation library
│   ├── aos.css                   # Animate On Scroll styles
│   ├── bootstrap-datepicker.css  # Datepicker styles
│   ├── bootstrap.min.css         # Bootstrap 4 stylesheet
│   ├── flaticon.css              # Flaticon icon styles
│   ├── icomoon.css               # Icomoon icon styles
│   ├── ionicons.min.css          # Ionicons stylesheet
│   ├── jquery.timepicker.css     # Timepicker styles
│   ├── magnific-popup.css        # Lightbox popup styles
│   ├── mystyle.css               # Additional custom styles
│   ├── open-iconic-bootstrap.min.css
│   ├── owl.carousel.min.css      # Owl Carousel styles
│   ├── owl.theme.default.min.css # Owl Carousel theme
│   └── style.css                 # Main public & resident portal styles
│
├── js/                           # JavaScript files (vendor & custom JS)
│   ├── adminscript.js            # Admin login/signup toggle script
│   ├── main.js                   # Main frontend interactions & carousel init
│   └── ...                       # jQuery, Bootstrap, Owl Carousel, AOS, Waypoints
│
├── database/                     # SQL database dump
│   └── kg_sentosa.sql            # Combined schema & seed data (`issues`, `myadmin`, `users`)
│
├── src/                          # Static images & favicons
│   ├── admin.png                 # Admin login favicon
│   ├── adminicon.png             # Resident login/register favicon
│   ├── contct.png                # Contact Us favicon
│   ├── dadmin.png                # Admin dashboard favicon
│   ├── editprof.png              # Edit Profile favicon
│   ├── gambar.jpg                # About section image
│   ├── icon.png                  # Public home favicon
│   ├── issue.png                 # Report Issue favicon
│   ├── kampung.jpeg              # Hero banner background image
│   ├── regis1.png                # Role selection favicon
│   ├── user.png                  # User icon
│   └── view.png                  # View Issues favicon
│
├── uploads/                      # User-uploaded issue photos
│
├── config.php                    # DB connection (`kg_sentosa`)
├── db.php                        # DB connection (`kg_sentosa`)
├── index.php                     # Public landing / About Us page
├── contactus.php                 # Public Contact Us page & map
├── register1.php                 # Role selection portal (Resident vs. Committee Admin)
└── logout.php                    # Session logout handler
```

---

## Local Setup (XAMPP)

1. Place or sync the project folder inside `/Applications/XAMPP/xamppfiles/htdocs/KG-SENTOSA`.
2. Start **Apache** and **MySQL** in XAMPP.
3. Import `database/kg_sentosa.sql` into a database named `kg_sentosa` via phpMyAdmin (`http://localhost/phpmyadmin`).
4. Verify database credentials in `config.php` and `db.php`.
5. Visit `http://localhost/KG-SENTOSA/index.php`.
