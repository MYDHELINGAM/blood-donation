# Blood Donation Management System

This project is a simple blood donation management website built with HTML, CSS, and PHP. It includes donation and blood request flows, plus separate PHP-based versions of the system in the `BACK/` and `BLOOD/` folders.

## Features

- Landing page with Donate and Request actions
- Donor and request forms
- Thank-you pages after submission
- PHP-backed pages for backend flow handling
- Simple folder-based structure for alternate implementations

## Project Structure

- `index.html` - Main landing page
- `donate.html`, `request.html`, `thankyou.html` - Front-end pages
- `donor.html`, `donor1.html`, `donor2.html` - Donor-related pages
- `style.css` - Shared styling
- `BACK/` - PHP version with backend pages and database setup
- `BLOOD/` - Another PHP-based version of the project
- `html codes/` - Additional login, register, and dashboard pages

## How to Use

1. Open `index.html` in a browser for the front-end version.
2. If you want the PHP version, place the project in a local PHP server environment such as XAMPP or WAMP.
3. Import the SQL files in `BACK/setup.sql` or `BLOOD/setup.sql` if you are using the database-backed pages.

## Notes

- Images and assets used by the pages are stored in the project folders.
- The repository currently contains multiple page variants, so choose the folder that matches the version you want to run.