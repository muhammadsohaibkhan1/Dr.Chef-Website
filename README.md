# Dr.Chef-Website
BSCS Final Year Project using Laravel 9 and Bootstrap 5.2 (Session 2019 - 2023)

Website Running Manual
For Database
1.	Install Xammp Control Panel.
2.	Start Apache and MySQL services.
3.	Click on Admin in MySQL.
4.	Php My Admin panel will open in browser. 
5.	From side menu of the Php My Amin panel click on new.
6.	Write database name dr_chef  and click on create button.
7.	Datebase will be created.
8.	After that from top navbar in Php My Admin Panel, Click on Import tab.
9.	Choose the sql file dr_chef that is attached in this folder.
10.	Click on import button.
11.	Database will be imported.
For running Websise
1.	Open the folder Dr.Chef Final.
2.	Open folder dr_chef.
3.	In top address bar type cmd and press enter.
4.	Commad prompt appears.
5.	Type code . in it and press enter.
6.	Application will be open in VS code.
7.	To open terminal in VS code press ctrl+shift+`.
If adding new user then no need for step 8 and 9. If user is already registered then perform  step 8 an 9 to add user daily calorie need in database.
8.	In terminal run this command php artisan schedule:run (If yesterday date is not available in the user_calories table in database this command will return fail error).
9.	To make this command run you have to edit the last date to yesterday date in the user_calories table in database, then again run the above command in terminal.
10.	After that enter command php artisan serve in terminal.
11.	Go to this link in browser http://127.0.0.1:8000/.
Website is ready to use.
Credientials:
1.	Admin
•	Email: muhammadsohaib@gmail.com
•	Password: admin
2.	Chef
•	Email: muhammadsohaibkhan@gmail.com
•	Password: muhammadsohaib

3.	Dietitian
•	Email: muhammadshams@gmail.com
•	Password: muhammadshams
4.	User
•	Email: ayeshakhan@gmail.com
•	Password: ayeshakhan
