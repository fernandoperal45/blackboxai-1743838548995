
Built by https://www.blackbox.ai

---

```markdown
# Shipping History Tracker

## Project Overview
The Shipping History Tracker is a PHP web application designed to help users manage and track shipping data efficiently. It provides functionalities for user registration, login, and managing shipping records, making it suitable for businesses looking to enhance their shipping operations.

## Installation
To set up the Shipping History Tracker locally, follow these steps:

1. Clone the repository:
    ```bash
    git clone <repository-url>
    ```

2. Navigate to the project directory:
    ```bash
    cd shipping-history-tracker
    ```

3. Ensure you have PHP and SQLite installed on your machine. You can run the project using a local PHP server:
    ```bash
    php -S localhost:8000
    ```

4. Open your web browser and go to `http://localhost:8000`.

5. The application will automatically create the SQLite database (`database.sqlite`) and necessary tables upon first access.

## Usage
1. **Create an Account**: Navigate to `register.php` to create a new business account.
2. **Login**: After registration, log in through `index.php`. Depending on your role (`admin` or `user`), you will be redirected to the appropriate dashboard.
3. **Manage Shipping Data**: Admin users can upload shipping data, while regular users can view their shipping history.

## Features
- User registration and login system.
- Role-based access with functionality for both admin and customer users.
- SQLite database for data storage.
- Error handling and user feedback on the login and registration forms.

## Dependencies
This project uses the following PHP dependencies (if applicable):
- PDO for database interactions (comes built-in with PHP).

There are no additional dependencies specified in a package.json file in this project.

## Project Structure
The project consists of the following files:

```
/shipping-history-tracker
├── config.php             # Configuration file for database connection and error handling.
├── index.php              # Main entry point for login functionality.
├── register.php           # User registration form and logic.
├── logout.php             # Script to handle user logout.
├── test_db.php            # Test script to check the SQLite database and tables.
└── dashboard              # Directory for user dashboards
    ├── admin              # Admin specific functionality and upload interface
    └── customer           # Customer specific dashboard
```

### **Database Tables**
1. `users` - Stores all user accounts with fields such as business name, first name, last name, email, hashed password, and role.
2. `shipping_data` - Stores shipping details with fields like invoice number, shipping dates, quantities, customer PO, etc.

## Contributing
If you'd like to contribute to the project, feel free to fork the repository and submit a pull request. 

## License
This project is open-source and available under the MIT License.
```