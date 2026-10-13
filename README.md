

Readme · MD
<div align="center">
 ⚡ EvUp Ticket
 
### Event management & online ticketing platform with QR code validation
 
*Create events, sell tickets online, and validate entries at the door, all in one place.*
 
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white)
![Inertia.js](https://img.shields.io/badge/Inertia.js-9553E9?style=for-the-badge&logo=inertia&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-635BFF?style=for-the-badge&logo=stripe&logoColor=white)
 
</div>
---
 
## 📖 Table of Contents
 
- [About the Project](#-about-the-project)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Architecture & Database](#-architecture--database)
- [Getting Started](#-getting-started)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [Known Limitations](#-known-limitations)
- [Roadmap](#-roadmap)
- [Authors](#-authors)
---
 
## 🎯 About the Project
 
**EvUp** (*Event* + *Up*) is a full-stack web platform that centralizes event management and ticket sales. It was built as a final-year project (PFE) for the Bachelor's degree in Mathematics & Computer Science (SMI) at **Cadi Ayyad University, Faculty of Sciences Semlalia, Marrakech**.
 
It addresses common problems in event organization: scattered tools, double bookings, poor capacity management, and no analytics. The platform serves three types of users, each with a dedicated interface:
 
| Role | What they do |
|------|--------------|
| 🛡️ **Administrator** | Validates organizer accounts and events, monitors sales and revenue |
| 🎤 **Organizer** | Creates and manages events, scans tickets at the entrance |
| 🎟️ **Participant** | Browses events, books tickets, pays online, downloads QR tickets |
 
---
 
## ✨ Key Features
 
### 🎟️ For Participants
- Browse upcoming events and search by **name, location, and date**, with a **category filter bar**
- Book tickets with **free seating**: choose a date and a quantity per pricing zone (Standard, VIP, Premium...) with real-time availability and live total
- Secure online payment through **Stripe Checkout** (sandbox mode)
- Receive tickets as a **PDF with a unique QR code**, by e-mail or direct download
- Personal dashboard with reservation history and ticket gallery (scanned / not scanned status)
- Built-in **chatbot** for instant support
### 🎤 For Organizers
- Registration through a dedicated "Become an organizer" form (company name, ICE, sector, manager details...)
- Create, edit, and delete events (before administrator validation)
- Dashboard with quick actions and recent activity
- **QR code scanner** to validate tickets at the entrance, via camera or image upload
### 🛡️ For Administrators
- Validate or suspend organizer accounts
- Validate or refuse submitted events
- View the list of participants
- Statistics dashboard: tickets sold, total revenue, reservations, revenue per event (**Chart.js** charts)
### ⚙️ Under the Hood
- Role-based access control with custom middleware
- Atomic database transactions for reservation creation, with a last-minute availability check to prevent **overbooking**
- **Automatic cleanup of unpaid reservations** (Artisan command + task scheduler) that releases blocked seats
- Soft deletes on main tables
- Database seeders for a ready-to-demo environment
---
 
## 🧰 Tech Stack
 
| Layer | Technologies |
|-------|--------------|
| **Backend** | Laravel (PHP), MVC architecture, Eloquent ORM |
| **Frontend** | Vue.js 3 (Composition API), Inertia.js, Blade (admin & organizer views), Tailwind CSS, jQuery |
| **Authentication** | Laravel Jetstream with Inertia |
| **Database** | MySQL |
| **Payments** | Stripe Checkout (sandbox) |
| **Tickets & QR** | `milon/barcode` (QR generation), `barryvdh/laravel-dompdf` (PDF), `html5-qrcode` (scanner) |
| **Charts** | Chart.js |
| **E-mail testing** | Mailtrap |
| **Design & tools** | Figma, Canva, VS Code, Git/GitHub, Composer, npm |
 
---
 
## 🗄️ Architecture & Database
 
The project follows the **MVC** pattern. Authentication data is kept in a base `users` table, and each role has its own profile table linked by a one-to-one relationship on `user_id`:
 
```
users ──┬── 1:1 ── organisateur
        ├── 1:1 ── participant
        └── 1:1 ── administrateur
 
organisateur ── 1:N ── evenements ── 1:N ── date_evenements ── 1:N ── zone_tarifaire
                           │
                           └── N:1 ── lieu_evenements
 
participant ── 1:N ── reservations ── 1:N ── billets
```
 
Main tables: `users`, `administrateur`, `organisateur`, `participant`, `evenements`, `lieu_evenements`, `date_evenements`, `zone_tarifaire`, `reservations`, `billets`.
 
---
 
## 🚀 Getting Started
 
### Prerequisites
 
- PHP ≥ 8.1 and [Composer](https://getcomposer.org)
- [Node.js](https://nodejs.org) and npm
- MySQL (for example via XAMPP)
- A [Stripe](https://stripe.com) account (test keys)
- A [Mailtrap](https://mailtrap.io) account (optional, for e-mail testing)
### Installation
 
```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/<your-repo>.git
cd <your-repo>
 
# 2. Install dependencies
composer install
npm install
 
# 3. Configure the environment
cp .env.example .env
php artisan key:generate
```
 
### Environment configuration
 
Edit the `.env` file:
 
```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evup
DB_USERNAME=root
DB_PASSWORD=
 
# Stripe (test keys from your Stripe dashboard)
STRIPE_KEY=pk_test_xxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxx
 
# Mailtrap (e-mail testing)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```
 
> ⚠️ Adjust the Stripe variable names to match your own config if they differ. **Never commit your `.env` file.**
 
### Database setup
 
```bash
# Create an empty database named "evup" in MySQL, then:
php artisan migrate --seed
php artisan storage:link
```
 
### Run the application
 
```bash
# Terminal 1: Laravel server
php artisan serve
 
# Terminal 2: frontend assets
npm run dev
 
# Terminal 3: task scheduler (cleans unpaid reservations every minute)
php artisan schedule:work
```
 
The app is now available at **http://localhost:8000**.
 
---
 
## 💡 Usage
 
### Test a payment
 
Stripe is in sandbox mode. Use the official test card:
 
| Field | Value |
|-------|-------|
| Card number | `4242 4242 4242 4242` |
| Expiry | Any future date |
| CVC | Any 3 digits |
 
### Typical workflow
 
```
1. Organizer creates an event
        ↓
2. Administrator validates it
        ↓
3. Participants browse and book tickets
        ↓
4. Secure payment through Stripe
        ↓
5. PDF ticket with QR code generated and e-mailed
        ↓
6. Ticket scanned at the entrance by the organizer
```
 
### Unpaid reservations
 
Reservations left in the *pending* state for more than 10 minutes are removed automatically and their seats are returned to stock:
 
```bash
php artisan reservations:clean-unpaid
```
 
---
 
## 📁 Project Structure
 
```
├── app/
│   ├── Actions/Fortify/         # CreateNewUser (role-aware registration)
│   ├── Console/Commands/        # reservations:clean-unpaid
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Dashboard, Evenement, Statistique controllers
│   │   │   ├── ReservationController.php
│   │   │   └── PaymentController.php
│   │   └── Middleware/          # Role-based access (admin, organizer...)
│   ├── Mail/                    # TicketsMail
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── Pages/               # Vue pages (Accueil, CategorySelection, PaymentPage...)
│   │   └── Layouts/             # GuestLayout, ParticipantLayout
│   └── views/
│       ├── admin/               # Blade views for the administrator
│       ├── organisateur/        # Blade views for the organizer
│       └── emails/              # PDF & e-mail templates
├── routes/
└── bootstrap/app.php            # Scheduler configuration
```
 
> Adapt this tree to your actual folder layout.
 
---
 
## ⚠️ Known Limitations
 
This is a first functional version built in two months. Honest notes:
 
- **Stripe webhooks are not implemented.** In sandbox mode, a reservation is marked as *paid* when the user lands on the success page. In production, a verified webhook must confirm payments server-side.
- **Free seating only.** Numbered seating with an interactive seat map is designed (Figma mockup) but not implemented yet.
- The statistics module is not fully complete.
---
 
## 🗺️ Roadmap
 
- [ ] Numbered seating with an interactive seat map
- [ ] Stripe webhooks for secure payment confirmation
- [ ] Promotions and discount codes for organizers
- [ ] Advanced commission management and automatic payouts
- [ ] Complete the statistics module
- [ ] Modern responsive UI improvements
- [ ] Multilingual support (FR / EN / AR)
---
 
## 👩‍💻 Authors
 
| Name | Role |
|------|------|
| **Basma KHALIL** | Full-stack developer |
| **Manal KHALIFI** | Full-stack developer |
 
**Supervisor:** Pr. Yassine AFOUDI
**Institution:** Faculté des Sciences Semlalia, Université Cadi Ayyad, Marrakech
**Period:** May 2025 – June 2025
 
---
 
<div align="center">
Made with ❤️ in Marrakech
 
⭐ If you like this project, feel free to star the repo!
 
</div>
