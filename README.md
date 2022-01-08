# DBase

![dbase](https://user-images.githubusercontent.com/39766031/148632406-a8728f39-bd14-4dcf-930b-f74d7e1e1f94.png)

DBase is an easy-to-use backend for your mobile and web applications, host the files in an Ubuntu server and use the SDKs to perform CRUD operations, create infinite databases, and send Push Notifications to iOS and Android devices.


# Requirements
1. A VPS Server, minimum 2GB RAM, 1 vCPUs ~ AWS Lightsail with Ubuntu 18.04 is highly recommended in terms of performance and prices
2. Sublime Text (recommended), or any other HTML/text editor of your choice
3. An FTP account confgured in FileZilla, needed to upload/edit files
4. Chrome Web Browser (recommended), and/or Safari or Firefox

## For the iOS SDK:
1. The latest official version of Xcode – Beta versions of an IDE usually never work properly, and the code of this SDK has been written using the latest official version of Xcode. You can download it from the Mac App Store. Please avoid Betas.
2. An Apple Mac computer, updated to its latest OS version
3. An Apple Developer Account with an active iOS Development Program – This is needed for you to publish apps on the iTunes App Store.
Knowledge of Xcode and Swift programming
4. A real device to test the app - The Simulator may fail during tests, they are not reliable like a real device is

## For the Android SDK:
1. The latest official version of Android Studio – Beta versions of an IDE usually never work properly, and the code of this SDK has been written using the latest official version of Android Studio. Please avoid Betas.
2. An Apple Mac or Windows computer, updated to its latest OS version.
3. A Google Play Developer Account – This is needed for you to publish apps on the Play Store.
4. Knowledge of Android Studio and Java/XML programming.
5. A real device to test the app before submitting it to the Play Store – Emulators may fail during tests, they are not reliable like a real device is


# Installation
Option 1:
With FileZilla (recommended), upload the DBase folder into the root of your server - the /var/www/html/ directory on AWS Lightsail with Ubuntu 18.04).

Option 2:
You may also protect your API by renaming the DBase folder into some random characters - something like as45DfR6y9S, or whatever you want - then upload that folder into the root of your server.

In any case, take note of the name of the API folder in order to propely configure the SDKs.


# Configurations

On FileZilla, right-click on a file and select the *View/Edit* option. That will open the selected file in your favorite text editor - I recommend you to configure *Sublime Text* as custom editor in the FileZilla's *Settings* panel -> *File editing* option.

<img width="294" alt="filezilla" src="https://user-images.githubusercontent.com/39766031/148562212-356e932e-b8c8-4b05-8a6a-b20539d0a925.png">

![filezilla2](https://user-images.githubusercontent.com/39766031/148562373-6a8dc164-256b-466f-bedf-467eeb720a34.png)


## Prevent JSON data files from being viewed

If you installed DBase in an Ubuntu instance, open the `apache2.conf` file - it's into the `/etc/apache2/` directory and you can access it with Filezilla - and add the following lines on the bottom of that file:

```
 <Directory /var/www/html/>
    <Files "*.json">
      Order allow,deny
      Deny from all
    </Files>
 </Directory>
```


## The *_config.php* file

Open `_config.php` file and start by editing the database path:

``` $DATABASE_PATH = "https://yourdomain.com/dbase/"; ```

You must change that string into the URL of your server, where you've uploaded the DBase folder into - even if you've renamed it.
 
Example: Let's pretend your domain name is mydomain.net and you kept the DBase folder's name. You should set the **$DATABASE_PATH** variable as it follows:

``` $DATABASE_PATH = "https://mydomain.com/dbase/"; ```

Instead, if you've renamed the DBase folder into something else:

``` $DATABASE_PATH = "https://mydomain.com/your-new-folder-name/"; ```


## Application name
Set the name of your application in this variable:

``` $APP_NAME = "DBase"; ```

## Admin login credentials
Change the default username and password strings into your own ones ~ they are needed to access the DBase admin dashboard:
```
$ADMIN_USERNAME = "admin";
$ADMIN_PASSWORD = "admin";
```

## Admin email address
Provide an existing email address of your choice for users to get in touch with you:

``` $ADMIN_EMAIL = "myemail@address.com"; ```

## Data for Pear Mail PHP
Since this backend contains the necessary code to allow you to send email with [Pear PHP mail](https://pear.php.net/package/Mail/) you must set your credentials here:

```
$EMAIL_FOR_SENDMAIL = "email@example.com"; // <-- set an email address for send-mail.php
$PASSWORD_FOR_SENDMAIL = "your-password"; // <-- set a password
$SMTP_HOST = "ssl://address"; // <-- Your ssl address
```


> In case you want to use a Gmail address:
```
  $SMTP_HOST = 'ssl://smtp.gmail.com';
  $EMAIL_FOR_SENDMAIL = 'your_email@gmail.com';
  $PASSWORD_FOR_SENDMAIL = 'your_gmail_password' 
```
  
**IMPORTANT: You must also turn the "Less Secure App" option ON on your Gmail settings here.**
<br>
Be aware that the Gmail SMTP server sends no more than 99 emails/day, and the `from` input gets ignored, so the emails you'll receive will show your gmail address in the `FROM` field.


## Google Sign In ~ Web OAuth Key

In order to sing in with Google on a Login web page, you must provide your own OAuth key.
Create it on your own [Google Cloud Platform](https://console.cloud.google.com/apis/credentials) and set it in this variable:

``` $GOOGLE_SIGNIN_KEY = ''; ```

## Apple Sign In + iOS Push Notifications
In order to make your iOS app send and receive Push Notifications and Sign In with Apple, you must perform the following actions:
1. Register your app's Bundle Identifier (the App ID) on your **[Apple Developer Account](https://developer.apple.com/account/) -> Certificates, Identifiers & Profiles -> Identifiers** section
2. Click the Keys option from the left menu, then the **(+)** blue button to add a new Key.
3. Type a key name, enable the **Apple Push Notifications service (APNs)** and **Sign In with Apple** options in the Register a New Key section.
4. Click the Configure button next to the Sign In with Apple option and select your *Bundle Identifier* from the **Primary App ID** dropdown menu. Lastly, click the Save button.
5. Download the `AuthKey.p8` file on your computer - pay attention, you'll be able to download it only once, so save it in a safe place.
6. On the Configure key page, click the Continue and then the Save button.
7. Upload that p8 file inside the `_Push` folder.
When you're done, replace the following variables in the `_config.php` file with your own data:

```
$AUTH_KEY_FILE = 'AuthKey_ABC123EFG.p8'; // Your p8 Key file name
$APN_KEY_ID = 'ABC123EFG';    // Your Apple Push Notification Key ID, you can get it by removing 'AuthKey_' and '.p8' from your Key file's name
$TEAM_ID = 'Z123ABC456D';   
$BUNDLE_ID = 'com.yourname.appname';    // Your iOS App's Bundle Identifier, the one you've set in Xcode
$APN_URL = 'https://api.development.push.apple.com'; // OR: 'https://api.push.apple.com';   [for Production environment] */
```

> NOTE: You can leave the `$APN_URL` variable as it is, since Push Notifications will still work even if you publish your app on the App Store without setting it into Production environment. It's just your choice.


## Android Push Notifications
If you don't have' a Firebase project on your Firebase Console, create one and enter the **Project Overview -> Cloud Messaging** section, copy the `Server key` from the **Project credentials** box and paste it in this varibale:

``` $FCM_SERVER_KEY = ''; ```

Remember to also download the **google-services.json** file and replace the one in the app folder of the Android Studio project's folder, that file will set the values of your project to send/receive Push Notifications in your Android app.

## Email Verification
If you want to send an email verification link to new users who sign up for the first time in your apps/website, just set the following variable into true:

```$IS_EMAIL_VERIFICATION = false;```

> NOTE: If you want to edit the message and other details of the Verification email, open the `m-signup.php` file that's inside the `_Tables` folder, scroll down and check the code inside the `if ($IS_EMAIL_VERIFICATION) { ... }` statement.
> 
Do not change or remove the `$APP_NAME` and `$DATABASE_PATH` variables, you may change the message strings and "From" email address.


## Utility Functions
The `_config.php` file contains some utility functions and variables that make the entire system work.

Unless you're an experience developer, do not edit anything below this comment:

```// UTILITY FUNCTIONS AND GLOBAL VARIABLES```


# The DBase Dashboard

Here's how the admin panel looks like, where you can view your database data, add/delete columns, edit/delete/add rows and sort objects, as well as send Push Notifications to mobile devices:

![dashboard](https://user-images.githubusercontent.com/39766031/148633646-5fe503bc-be20-4c0d-b7f6-497723180e3f.png)

## Understanding Data Tables in DBase
The DBase backend is based on JSON files, they are the **Tables** that host all data.<br>

In the `_Tables` folder of the package you can find 2 files called `Users.json` and `Posts.json`.<br>

You can rename the `Posts.json` file, or add new Tables with the **Add Table** button, anyway follow these important directions:<br>
1. You MUST NOT delete or rename the `Users.json` file
2. Use the **Add Table** button on the Dashboard to create JSON files in the `_Tables` folder which will work as your database tables
3. When you create a Table, the next thing to do is to **add columns**, otherwise data cannot be properly saved.
4. Your Tables must at least have 1 row to keep their column names, so if you use the Dashboard to delete rows, DBase will create an empty default row that won't be visible in the Dashboard, but if you manually delete all data in a JSON Table file, you will lose all columns and will have to add them again.
You may manually edit a JSON file on your HTML editor, just be careful to keep the correct syntax and data
Make a backup of your work frequently, as you may mess something up or lose important data for your database

## Top Navigation Buttons
On the top navigation bar you can find 3 buttons:<br>
 1. Tools
 2. Refresh
 3. Logout
 
By clicking the **Tools** button you can find all the buttons that allow you to manage the database, such as **Add Table**, **Add Column**, etc.

## Add a Table
You can create a Table by clicking the **Add Table** button and typing the desired name.

<img width="1054" alt="addtable" src="https://user-images.githubusercontent.com/39766031/148634714-deb0f825-6b68-4080-8fb0-555e8f51e532.png">

**IMPORTANT:**
1. If you want to manually edit a Table's data, you must open its JSON file on your favorite HTML editor from your server's `_Tables` folder
2. If you want to delete a Table, delete its JSON file form your server
3. If you want to change a Table name, simply rename its JSON files
4. Spaces and special characters are not allowed while naming a Table, so:
```
MyTable -> OK

My Table -> NO
@My Talbe -> NO
$myTable -> NO
```

## Import Tables
Click the **Import Tables** button in the Dashboard and choose one or more JSON files (just make sure they are compatible with the DBase's syntax for Tables):

<img width="434" alt="import" src="https://user-images.githubusercontent.com/39766031/148634810-7da7b3d9-2378-402a-8078-745c7fca5a52.png">
<img width="485" alt="import2" src="https://user-images.githubusercontent.com/39766031/148634816-4b33859d-8133-410e-97e3-8bd9bc99aad4.png">

## Export Tables
You can download JSON tables with the **Export a Table** button and save them as a backup in your machine:

<img width="357" alt="export" src="https://user-images.githubusercontent.com/39766031/148634879-eab8609e-2769-48c3-be50-32f55d3d2871.png">
<img width="493" alt="export2" src="https://user-images.githubusercontent.com/39766031/148634884-0f194f22-929e-4add-8428-dcb52dbbaedb.png">


## Rename a Table
From the Tools menu, click che **Rename a Table** button, select the Table you want to rename, type a new name for it and hit **Rename Table**

<img width="318" alt="renametable" src="https://user-images.githubusercontent.com/39766031/148635113-d9b51ba4-ba2c-4364-a16c-2e5736840ec7.png">
<img width="490" alt="renametable2" src="https://user-images.githubusercontent.com/39766031/148635115-ac542bf0-c91f-4267-bff7-f3bb49879db7.png">

## Delete a Table
Click che **Delete a Table** tool button, select the Table you want to delete and hit **Delete Table**

<img width="267" alt="deletetable" src="https://user-images.githubusercontent.com/39766031/148635153-f266c739-c81e-4d27-91eb-8de027dd1bdd.png">
<img width="471" alt="deletetable2" src="https://user-images.githubusercontent.com/39766031/148635155-b2c7a1bb-8d72-4bcc-952d-519fe876cd4f.png">

## Add a row
When you want to add a row to a Table, click the **Add Row** button ~ either in the **Tools** menu or use the quick button:

<img width="245" alt="addrow" src="https://user-images.githubusercontent.com/39766031/148635451-a88e0149-4fd9-4872-afc6-7b31758afa36.png">
<img width="302" alt="addrow2" src="https://user-images.githubusercontent.com/39766031/148635452-81f24d71-44ed-4bdc-9ca1-7a2dc1c8d334.png">

## Add a Column
Click the **Add Column** button ~ either from the **Tools** menu or the quick button ~ and select the Type of data you want to set between the following ones:
```
String
Number
Array
File
Boolean
GPS
Pointer
Date
```
Type a name for your column and click **Add column**.

If the Table is empty, your new column gets created along with 3 default ones, which are:
```
ID_id
DT_createdAt
DT_updatedAt
```

<img width="236" alt="addcolumn" src="https://user-images.githubusercontent.com/39766031/148635569-26d43b1c-9110-4ce3-83eb-89d75b27c355.png">
<img width="493" alt="addcolumn2" src="https://user-images.githubusercontent.com/39766031/148635570-0e210c66-7ec7-4c55-aaac-beff309a1691.png">

**IMPORTANT ~ FOR THE `Users` TABLE!**

When you add a column in the empty `Users` table with the Dashboard, DBase adds a few default columns that are needed for the Web and Mobile SDKs to work properly, as well as for the whole API:
```
ID_id
DT_createdAt
DT_updatedAt
ST_username
ST_password
ST_email
ST_iosDeviceToken
ST_androidDeviceToken
NU_badge
BL_emailVerified
ST_signInWith
```

**Although the Dashboard doesn't allow you to delete those columns, it's still good to know that you MUST NOT manually delete them from the `Users.json` file, neither change their names!**





