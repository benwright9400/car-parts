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

3. Setup the application
Enter the directory of your project on a bash terminal and run the
following commands:

php artisan migrate

php artisan db:seed

4. Launch the application
Run the following command

php artisan serve

Then open the link presented on a successful launch
