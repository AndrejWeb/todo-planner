# Todo Planner

Web application built with passion and enjoyment in my free time using Laravel 5.5. Should be a good resource for those who want to learn Laravel and those who are looking for a Laravel developer in their team. In this case that would be me. :)

Since Laravel 5.5 was used to create this web app, your web server must have at least PHP version 7.0.0.

Below are steps on how to run the app in localhost.

To get things started, in project directory open your terminal and run

    composer install

Then set your SQL credentials and other stuff like app name, app url in .env.
    
After successful configuration, migrations are next so run this command
   
    php artisan migrate
    
and finally you can run the app

    php artisan serve
    
If you get an error "No application encryption key has been specified." run

    php artisan key:generate
    
and then __php artisan serve__ again.
    
You should now be able to access the web application @ __localhost:8000__

From there it should be straightforward. Once you create an account and login you can see the dashboard where you can add/edit/delete todos (or tasks), mark them as completed/uncompleted and you can also edit your profile details if you click your name in the nav bar and then "My Profile".

Some things to be noted: Completed Date is when you mark a todo as completed. Once you mark it as uncompleted again, the completed date is removed. Only uncompleted todos can be edited. Completed todos can only be deleted. To edit them, you need to mark them as uncompleted again.

All in all, the app has some room for adjustments here and there which I may do in the future or you can take the app to the next level. 

Web app screenshots:

![Screenshot](https://i.imgur.com/GbEUOjS.png)

![Screenshot](https://i.imgur.com/gvx2R9S.png)

![Screenshot](https://i.imgur.com/AO8WRTh.png)

![Screenshot](https://i.imgur.com/71emY1O.png)

![Screenshot](https://i.imgur.com/ZJB8cVD.png)

![Screenshot](https://i.imgur.com/n6JMim4.png)

![Screenshot](https://i.imgur.com/jATHEFp.png)

![Screenshot](https://i.imgur.com/lBEccj4.png)
