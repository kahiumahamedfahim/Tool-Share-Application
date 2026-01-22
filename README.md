# 🛠️ Tool Share Application

A PHP MVC–based web application that allows users to **share, rent, and manage tools** within a community.  
The system supports **role-based access**, **rent request workflows**, and **tool management** with a clean MVC architecture.

---

## 📌 Table of Contents
- [Project Overview](#-project-overview)
- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [System Roles](#-system-roles)
- [Project Structure](#-project-structure)
- [Installation & Setup](#-installation--setup)
- [How to Use](#-how-to-use)
- [Database](#-database)
- [Screens & UI Flow](#-screens--ui-flow)
- [Future Improvements](#-future-improvements)
- [Contributing](#-contributing)
- [License](#-license)

---

## 📖 Project Overview

The **Tool Share Application** solves a real-life problem where people own tools they rarely use, while others need them temporarily.

This platform enables:
- Tool owners to list and manage tools
- Users to request tools for rent
- A structured rent lifecycle (request → accept → return → confirm)
- Admins to monitor and manage the system

---

## ✨ Features

### 👤 Authentication & Authorization
- User registration & login
- Session-based authentication
- Role-based access control (User, Vendor, Admin)

### 🧰 Tool Management
- Add, edit, and delete tools
- Upload multiple tool images
- View tool details with availability

### 🔁 Rent Workflow
- Send rent requests
- Accept / reject requests (Owner)
- Request return (Renter)
- Confirm return (Owner)
- Status-based UI actions

---

## 🛠️ Technology Stack

- **Backend:** PHP (MVC Pattern)
- **Frontend:** HTML5, CSS3, JavaScript
- **Database:** MySQL
- **Architecture:** Controller → Service → Repository
- **Session Management:** PHP Sessions

---

## 👥 System Roles

| Role | Capabilities |
|------|-------------|
| User | Browse tools, send rent requests, request returns |
| Vendor | Manage tools, accept/reject rents, confirm returns |
| Admin | View & manage all users, tools, and rent requests |

---

## 📂 Project Structure

```text
app/
 ├── controllers/
 │   ├── ToolController.php
 │   ├── RentController.php
 │   ├── UserController.php
 │   └── AdminController.php
 │
 ├── services/
 │   ├── ToolService.php
 │   ├── RentService.php
 │   └── UserService.php
 │
 ├── repositories/
 │   ├── ToolRepository.php
 │   ├── RentRepository.php
 │   └── UserRepository.php
 │
 ├── views/
 │   ├── tools/
 │   ├── rent/
 │   ├── user/
 │   └── admin/
 │
 └── layouts/
     ├── header.php
     └── footer.php
```
**Layered responsibility:**
- Business logic → services
- Database queries → repositories
- UI logic → views

---


## ⚙️ Installation & Setup

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/kahiumahamedfahim/Tool-Share-Application.git
```
### 2️⃣ Move Project to Server
```text
htdocs/ (XAMPP)
or
www/ (WAMP)
```
### 3️⃣ Import Database
- Open **phpMyAdmin**
- Create database: `tool_sharing`
- Import:
```text
tool_sharing.sql
```
### 4️⃣ Configure Database
```text
host: localhost
database_name: tool_sharing
username: root
password:
```
### 5️⃣ Run the Project
http://localhost/tool-share-application
