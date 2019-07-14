# Device Borrowing System (DBS)

## About

The original purpose of this project was to improve the process of loaning devices (specifically, ipads and laptops) at the New York City College of Technology’s Ursula Library, especially to stop relying on paper forms. Some of the DBS capabilities include adding devices and accessories, loan and return devices, loan history, among others. See the [documentation](/docs/DBS_Help_Doc.pdf) for more. I hope you can extend this project and make great usage of it.

## Prerequisite

1. Install a server that supports PHP 7, such as WAMP, or XAMP and run it. Although this project can run in PHP 5.6, it is configured for PHP 7 for strict type variables in the classes methods. You will need to remove declare(strict_types=1) from classes and also type declarations in the methods such as int and bool if you wish to use PHP 5.6. I recommend you use PHP 7.
2. Download a copy of CodeIgniter 3 into your servers' web folder and rename it Libservices.
3. Make sure you can access your installation of CodeIgniter in your browser by going to 'yourDomain'/Libservices.

## Installation and Deployment

1.	Drop the DBS deviceLoan_assets folder where your Libservices application folder is located.
2.	Place all DBS files and folders in their respective Libservices folders. 
    - Drop the files inside the DBS config folder into your Libservices/application/config folder, replace the files in destination, or edit your originals accordingly.
    - Drop the files inside the DBS controllers folder into your Libservices/application/controllers folder.
    - Drop the files inside the DBS language\english folder into your Libservices/language/english folder.
    - Drop the files inside the DBS library folder into your Libservices/application/libraries folder.
    - Drop the files inside the DBS log folder into your Libservices/application/logs folder.
    - Drop the files inside the DBS models folder into your Libservices/application/models folder.
    - Drop the folders inside the DBS views folder into your Libservices/application/views folder.
3.	In the DBS sql folder, locate the sql file. The DEFINER for triggers and procedures is ‘root’@’localhost’ and you need to change it if you are not using a local server. You can change it by opening the file in your code editor and doing a search/replace accordingly. 
4.	Load the DBS sql file into your database, through the command line, MySQL Workbench or other, making sure to include both structure and data. 
5.	Place the DBS docs folder wherever you can easily access it for reference.
6.	Head to 'yourDomain'/Libservices/index.php/auth/login to **login with barcode 12345 or email: dbs@dbs.com and password: password**. 
7.	Read the docs or go to 'yourDomain'/LibServices/index.php/DeviceManage/documentation to see its page.

#### Setup Email in The Application

Some functions in the DBS, such as creating a new user and loaning/returning devices, will require a Gmail email to be setup. 

1.	In Application/config folder, change smtp_user and smtp_pass in the emai.php file for emailing to work. The Gmail account used must be allowed smtp requests, although I noticed that creating a new account didn’t create such issue; you may want to do that for testing purposes.
2.	Also in the config folder, change the ion_auth file’s  admin_email to whichever email you want, which will be used for the reply-to function in Gmail if you need it.
3.  To modify the email content, go to application/views/deviceFormViews/emailTemplate.php and change the file accordingly.
    - Note: To show the logo picture in the email, change the src value from 'DBS_Logo.png' to a publicly accessible picture. 
    
The email template will look similar to this:

![Sample DBS Email](/docs/sampleEmailTemplate.png)

#### Other Notes

- Although I added a user for you of admin level privilege, you can go to 'yourDomain'/Libservices/index.php/auth/index to add more users. 
- Ion Auth files were modified, especially the views. signature_pad was also modified. Take note of this if you wish to update these plugins later on.
- To change the amount of days a device can be loaned, search for 'P3D' in the project and change the number 3 that represents the days to another number.
- If there is a problem with uploading images or you wish to upload larger size images, and the CodeIgniter upload settings are not taking effect, you may need make upload changes in your php.ini.
- The known issues and possible future improvements you can make are listed in the [documentation](/docs/DBS_Help_Doc.pdf).

## Tests and Automation

Packaged with the DBS is a selenium java project, which covers most of the application's functionality and hopefully will be helpful to you in many ways, such as testing the loan/return forms if you decide to make meaningful changes to them. View the [readme.md](/TestsAndAutomation/DeviceLoan_UnitTests/readme.md) file to get started using it.

## Built With

- CodeIgniter PHP framework - https://www.codeigniter.com/
- Foundation for Sites by ZURB front-end framework - https://foundation.zurb.com

## Author

- Anderson Uribe-Rodriguez

## Acknowledgments

#### Plugins
- CodeIgniter Ion Auth by Ben Edmunds - https://github.com/benedmunds/CodeIgniter-Ion-Auth
- signature pad by Szymon Nowak - https://github.com/szimek/signature_pad
- jQuery.floatThead by Misha Koryak - http://mkoryak.github.io/floatThead/
- jQuery Timepicker Addon by Trent Richardson - https://trentrichardson.com/examples/timepicker/

#### People or Institution
- Ursula C. Schwerin Library at the City College of Technology of New York for providing the servers and helping shape the application through usage and recommendations. 
    - [Yi Meng Chen](https://www.linkedin.com/in/yimechen?trk=chatin_wnc_redirect_pubprofile&ctx=cnpartner&trk=chatin_me_view-profile_wnc&from=singlemessage&isappinstalled=0). I built on top of the work he did at the library. Specifically in the Server, Authentication, Webuser, Router and Housekeeper classes and kept much of his code, even though it is not used, in hope it can help others make the application better.

## License
- This project is licensed under the MIT License - see the [LICENSE.md](/LICENSE) file for details
