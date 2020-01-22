To run the software, you will need PHP and MySQL installed on a server.

To set up the database create a new database and switch to it:

    CREATE DATABASE dbname;
    USE dbname;
    
Use the following to set up the appropriate tables:

    CREATE TABLE development_teams
    (
        name VARCHAR(255) NOT NULL PRIMARY KEY
    );
---
    CREATE TABLE users
    (
        username VARCHAR(255) NOT NULL PRIMARY KEY,
        password VARCHAR(255) NULL,
        development_team VARCHAR(255) NULL,
        type VARCHAR(30) DEFAULT 'Developer' NULL,
        experience VARCHAR(30) DEFAULT 'Experienced' NULL,
        CONSTRAINT users_ibfk_1 FOREIGN KEY (development_team) REFERENCES development_teams (name) ON DELETE CASCADE
    );
---
    CREATE TABLE unavailability
    (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        date_start DATE NOT NULL,
        date_end DATE NOT NULL,
        CONSTRAINT unavailability_ibfk_1 FOREIGN KEY (username) REFERENCES users (username) ON DELETE CASCADE
    );
---
    CREATE TABLE support_team
    (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date_start DATE NOT NULL,
        date_end DATE NOT NULL,
        developer_1 VARCHAR(255) NULL,
        developer_2 VARCHAR(255) NULL,
        CONSTRAINT support_team_ibfk_1 FOREIGN KEY (developer_1) REFERENCES users (username) ON DELETE SET NULL,
        CONSTRAINT support_team_ibfk_2 FOREIGN KEY (developer_2) REFERENCES users (username) ON DELETE SET NULL
    );
---
    CREATE TABLE audit_log
    (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message TEXT NOT NULL
    );

## **IMPORTANT**

The database will not have any users when first set up so you will not be able to do anything so we need to add an admin account.
The following will set up an admin account with a name of your choice, and set the password to 'password' (which can be changed later on).
Enter your username of choice in-between the quotes "`<enter username here>`"

    INSERT INTO users (username, password, type) VALUES ("<enter username here>", "$2y$10$4dJuOZUEYEtGvi0UgsgI6O3pjfl8KOxSKhJ84HjRy0nPqFU6zOa8W", "admin");
    
To set up the server, navigate into `FanaticsSupportRota/` folder and execute:
    
`$ php -S localhost:8000`
