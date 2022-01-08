# DBase

<img src="http://3.67.163.164/dbase/assets/img/dbase.png" />

DBase is an easy-to-use backend for your mobile and web applications

# Requirements
1. A VPS Server, minimum 2GB RAM, 1 vCPUs, 60GB Disk Storage - Amazon AWS Lightsail with Ubuntu is the best choice in terms of speed, reliability and price.
2. Sublime Text (recommended), or any other HTML/text editor of your choice.
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


## Protect JSON data files from being discovered

If you installed DBase in an Ubuntu instance, you must open the apache2.conf file - it's into the /etc/apache2/ directory and you can access it with Filezilla - and add the following lines on the bottom of that file:

```
 <Directory /var/www/html/>
    <Files "*.json">
      Order allow,deny
      Deny from all
    </Files>
 </Directory>
```

In this way, nobody will ever see any JSON file of your database from a browser, not even you, and your data are safe.

## DBase Databse path

Open the `_config.php` file and edit the database path:

``` $DATABASE_PATH = "https://yourdomain.com/dbase/"; ```

You must change that string into the URL of your server, where you've uploaded the DBase folder into - even if you've renamed it.
 
Example: Let's pretend your domain name is mydomain.net and you kept the DBase folder's name. You should set the **$DATABASE_PATH** variable as it follows:

``` $DATABASE_PATH = "https://mydomain.com/dbase/"; ```

Instead, if you've renamed the DBase folder into something else:

``` $DATABASE_PATH = "https://mydomain.com/your-new-folder-name/"; ```


