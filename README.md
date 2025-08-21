# Passenger Manifest Information System

A full-stack PHP and MySQL application for managing a passenger manifest.

## Features

-   **Responsive Dashboard:** Built with Bootstrap for a clean and responsive UI.
-   **AJAX CRUD Operations:** Create, Read, Update, and Delete passengers without page reloads.
-   **Soft Deletes & Archiving:** Archive passengers instead of permanently deleting them, with the ability to view and restore archived records.
-   **Data Visualization:** Chart.js is used to display a summary of active vs. archived passengers.
-   **Toast Notifications:** User-friendly toast notifications for feedback on operations.
-   **PDF & Word Export:** Export the passenger list to PDF or Word documents.
-   **Overlay Modals:** All forms are handled in modals for a seamless user experience.

## Requirements

-   PHP 7.4 or higher
-   MySQL or MariaDB
-   Apache or Nginx web server
-   Composer for dependency management

## Setup Instructions

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/passenger-manifest.git
    cd passenger-manifest
    ```

2.  **Install dependencies:**
    This project requires `tecnickcom/tcpdf` and `phpoffice/phpword`. Install them using Composer:
    ```bash
    composer require tecnickcom/tcpdf
    composer require phpoffice/phpword
    ```
    This will create a `vendor` directory with the required libraries.

3.  **Database Setup:**
    -   Create a new MySQL database named `passenger_manifest`.
    -   Run the following SQL query to create the `passengers` table:
        ```sql
        CREATE TABLE `passengers` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `first_name` varchar(255) NOT NULL,
          `last_name` varchar(255) NOT NULL,
          `email` varchar(255) NOT NULL,
          `phone` varchar(20) DEFAULT NULL,
          `address` text DEFAULT NULL,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
          `deleted_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ```

4.  **Configure Database Connection:**
    -   Open the `config/database.php` file.
    -   Update the database credentials (`$host`, `$db_name`, `$username`, `$password`) to match your local environment.

5.  **Run the application:**
    -   Place the project directory in your web server's root (e.g., `htdocs` for XAMPP, `www` for WAMP).
    -   Open your web browser and navigate to `http://localhost/passenger-manifest/`.

## How to Use

-   **Add a Passenger:** Click the "Add New Passenger" button and fill out the form.
-   **Edit a Passenger:** Click the "Edit" button next to a passenger's record.
-   **Delete a Passenger:** Click the "Delete" button to move a passenger to the archive.
-   **View Archived Passengers:** Click the "View Archived" button to see all archived passengers.
-   **Restore a Passenger:** In the archived view, click the "Restore" button to make a passenger active again.
-   **Export Data:** Use the "Export to PDF" or "Export to Word" buttons to download the passenger list.
