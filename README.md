###### Run:
1. Clone
2. Open CMD:
	- cd project folder \ composer install
	- copy .env.example .env
3. Create a DB (phpmyadmin)
4. Import testCompanyDB.csv into DB
5. Open .env file and add Database name than SAVE
6. Run XAMPP and start Apache, MySQL 
7. cmd \ cd project folder \ php artisan serve

###### Endpoints:
0. You have to call /set-db endpoint first!

Create Company:
1. POST request: /create-company
Body - raw - JSON:
{
    "companyName": "Teszt Kft.",
    "companyRegistrationNumber": "123456789-54",
    "companyFoundationDate": "2009.01.01.",
    "country": "Hungary",
    "zipCode": "3533",
    "city": "Miskolc",
    "streetAddress": "Teszt u. 12.",
    "latitude": "48.1",
    "longitude": "20.78333",
    "companyOwner": "Teszt Ferenc",
    "employees": 22,
    "activity": "tools",
    "active": true,
    "email": "gfgy@xggy.hu",
    "password": "12345678"
}

Get details of company by ID:
2. GET request: /company-details
Body - raw - JSON
{
    "company": 76,
    "company1": 87
}

Update company datas:
3. PUT request: /update-company
Body - raw - JSON
{
    "companyId": 76,
    "companyName": "Tortor PC XYZ",
    "companyRegistrationNumber": "1234564789-65",
    "companyFoundationDate": "2009.01.01.",
    "country": "Hungary",
    "zipCode": "3533",
    "city": "Miskolc",
    "streetAddress": "Teszt u. 12.",
    "latitude": "48.1",
    "longitude": "20.78333",
    "companyOwner": "Teszt Ferenc",
    "employees": 22,
    "activity": "tools",
    "active": true,
    "email": "gfxy@xdfy.hu",
    "password": "12345678"
}

You can get companies since 2001.01.01.
4. GET request: /get-companies-since-2001
