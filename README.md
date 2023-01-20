This is a small CRUD project built with php larvavel for the
management of the stock of car parts.

 === Installation instructions ===

1. Configure the database

Open your MySQL database manager and create a database called 
'parts_project'

If there is not a user with these credentials for the database server 
create one:

username: 'root'

password: ''

Ensure that the server is running locally on port 3306.
If you wish to access the sever through a different port
please change the 'DB_PORT' variable in the .env file of
the project to the relevent port.

2. Download the application
Create a new directory on your computer.
Initalise a git repository then perform the following commands in a
bash terminal with git installed:

git remote add origin https://github.com/benwright9400/car-parts.git

git pull origin master

Also ensure that you have downloaded and installed composer 
from https://getcomposer.org/

3. Setup the application
Enter the directory of your project and remove the .example extension
from the .env file.

Enter the directory of your project on a bash terminal and run the
following commands:

composer install

php artisan migrate

php artisan db:seed

4. Launch the application
Run the following command

php artisan serve

Then open the link presented on a successful launch