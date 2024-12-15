
# Laravel Campaign Management System

This is a Laravel-based application to manage campaigns with features like user authentication, campaign creation, CSV upload, email sending, queue processing, and more.

## Features

- **Authentication**: Implemented using Laravel Breeze.
- **Campaign Management**: Users can create, view, and process campaigns.
- **CSV File Upload & Validation**: Upload CSV files, validate their contents, and process them.
- **Email Notification**: Send email notifications with a dynamic template.
- **Queue System**: Campaign processing is handled via Laravel's queue system to ensure scalability and performance.
- **User Interface**: Built using **Vue 3** with Inertia.js, providing a smooth and modern user experience.
- **Testing**: Unit and feature tests are written using **PEST**.

## Requirements

- PHP ^8.2
- Composer
- Node.js
- Laravel 11.x or later
- MySQL or SQLite (as configured in the `.env` file)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/ErRV/rannkly-task.git
cd rannkly-task
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Set up the environment file

Copy the `.env.example` file to `.env` and configure your environment settings.

```bash
cp .env.example .env
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Run the migrations

```bash
php artisan migrate
```

If you're using SQLite, make sure to set the database path in the `.env` file:

```bash
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

### 6. Install JavaScript dependencies

```bash
npm install
```

### 7. Run the application

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## Features Breakdown

### **Authentication (Laravel Breeze)**

The application uses Laravel Breeze to handle user authentication.

- **Registration**: Users can sign up for an account.
- **Login**: Users can log in using email and password.
- **Profile**: Users can edit their profile information.

### **Campaign Management**

- **Create Campaign**: Users can create campaigns by providing a campaign name and uploading a CSV file.
- **View Campaign**: After creation, users can view the details of a particular campaign.
- **Process Campaign**: After uploading the CSV, the campaign is processed asynchronously using Laravel's queue system.
  
### **CSV Validation**

- When uploading a CSV file, the application validates that:
  - The name and email fields are not empty.
  - The email is in a valid format.
- If validation fails, appropriate error messages are shown.

### **Email Notification**

- After a campaign is processed, an email notification is sent to the campaign owner using a fixed HTML template.
- The template includes a dynamic `{{username}}` variable, which is replaced with the recipient's name.

### **Queue System**

- Campaign processing is done in the background using Laravel's queue system, ensuring the application remains responsive.
- The progress of the campaign is tracked and displayed on the frontend.

### **Frontend (Vue 3 with Inertia.js)**

- The frontend is built using **Vue 3** and **Inertia.js** for smooth page transitions without full page reloads.
- **SweetAlert2** is used for displaying success/error messages.

### **Testing**

- The application is tested using **PEST** for unit and feature tests.
- The tests ensure the application behaves as expected, particularly around CSV validation, campaign creation, and email notification.

---

## API Endpoints

### **Authentication**

- **POST /register**: Register a new user.
- **POST /login**: Log in a user.
- **POST /logout**: Log out the authenticated user.

### **Campaign Management**

- **GET /campaign-list**: List all campaigns.
- **GET /campaign/{id}**: View a specific campaign.
- **POST /campaign-form**: Create a new campaign and Upload a CSV file for the campaign.

### **Campaign Processing**

- Campaign processing happens in the background via a queue system, and progress is tracked.

---

## Frontend Usage

### **Campaign Creation**

1. Log in to the system.
2. Go to the **Campaigns** section.
3. Click on **Add Campaign**.
4. Provide a name for the campaign.
5. Upload a CSV file containing name and email columns.
6. The campaign will be created, and an email will be sent to the users in the CSV file.

### **Campaign Progress**

- Once a campaign is created, users can view the processing progress.
- A notification will be sent via EMail once the campaign is successfully processed.

---

## License

This project is licensed under the MIT License.

---
