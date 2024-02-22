Project Setup Instructions
==========================

This guide will walk you through setting up your project environment and initializing the database with necessary data.

Steps to Set Up
---------------

### 1\. **Create a Database**

Begin by creating a new database for the project. Open your terminal and execute the following command:

bashCopy code

`php bin/console doctrine:database:create`

### 2\. **Apply Migrations**

With the database created, the next step is to apply migrations. These migrations will set up the required tables in your database. Run:

bashCopy code

`php bin/console doctrine:migrations:migrate`

### 3\. **Import Data**

Once the database structure is in place, you can proceed to import data into it. Use the following commands to import customers and loans data into their respective tables.

*   **Import Customers**
    
    To import customer data into the database, run:
    
    bashCopy code
    
    `php bin/console app:import-customer`
    
*   **Import Loans**
    
    To import loan data, use:
    
    bashCopy code
    
    `php bin/console app:import-loans`
