# web2019

![IISHF CI](https://github.com/IISHF/web2019/workflows/IISHF%20CI/badge.svg)

The relaunch of www.iishf.com

## VirtualBox Setup

The project contains a VirtualBox setup using Vagrant with Ansible 
provisioning. Just run `vagrant up` inside the `./vagrant` folder.
[VirtualBox][1], [Vagrant][2] and [Ansible][3] need to be installed 
on the host.

The Nginx webserver running on the virtual machine is configured to
use HTTP/2 and therefore requires SSL certificates. **Before running 
the Vagrant box for the first time ensure that the appropriate 
certificates have been created.** The *Common Name* for the 
certificate must be set to `iishf.test`. The script 
`./vagrant/roles/webserver/files/mkcert.sh` can be used to create an
appropriate key/certificate pair.

The virtual machine exposes HTTPS on port `4430` and the MySQL 
database on port `33060`. The project directory is mounted 
into the virtual machine to `/var/www/iishf` using NFS.

After the initial booting Vagrant will execute the Ansible provisioner.
This sets up the virtual machine with all required system dependencies.
It it still required however that you do setup the application and the 
database. 

## Application Setup

You can log into the virtual machine using `vagrant ssh`. The application
source code is mounted on `/var/www/iishf`. Follow the steps below to setup
the application, provision the database and prepare a first administrator 
account (use your own user details and email instead of the generic ones 
used below).

```
host$ cd vagrant
host$ vargant ssh
Welcome to Ubuntu 18.04.3 LTS (GNU/Linux 4.15.0-20-generic x86_64)
[...]
vagrant$ cd /var/www/iishf
```

The first step is to create a local configuration file and provide the 
required configuration values. 

```
vagrant$ cp .env .env.local
```

The `DATABASE_URL` should already by OK and match the virtual box setup.
Same goes for the `MAILER_DSN` which is set to disable mail transport. If
you intent to use something like [MailCatcher][4] or [MailHog][5] you can 
set this variable to `smtp://10.0.2.2:1025` - or whatever is suitable for
the mail catcher you intent to use (`10.0.2.2` is the IP of the host machine
as seen from within the virtual machine). 

To be able to use [Google reCAPTCHA][6] you need to adjust the configuration variables
`GOOGLE_RECAPTCHA_KEY` and `GOOGLE_RECAPTCHA_SECRET` with the real values from
your reCAPTCHA setup (make sure you use reCAPTCHA version 2).

Finally and not less important because of security reasons you should update
the `APP_SECRET` configuration to some random value. You may use the 
[Symfony 2 Secret Generator][7] or other tools that can create random strings.

With the configuration in place you can now prepare the application.

```
vagrant$ composer install
Loading composer repositories with package information
Installing dependencies (including require-dev) from lock file
[...]
vagrant$ ./bin/console doctrine:migrations:migrate
                                                              
                    IISHF Database Updates                    
                                                              

WARNING! You are about to execute a database migration that could result 
in schema changes and data loss. Are you sure you wish to continue? (y/n) y
```

Now the application and the database are ready to go. Next step is to create
an initial administrator account and set a password for the user.

```
vagrant$ ./bin/console app:user:create

Create a new user
=================

 First Name [First]:
 > John

 Last Name [Last]:
 > Doe

 E-mail [first.last@test.com]:
 > john.doe@test.test

 Role(s):
  [0] none
  [1] ROLE_ADMIN
 > 1

User Details
------------

 ------------ -------------------- 
  First Name   John                
  Last Name    Doe                 
  E-mail       john.doe@test.test  
  Roles        ROLE_ADMIN          
  Password     no                  
 ------------ -------------------- 

 Create new user? (yes/no) [yes]:
 > yes

                                                                                                                        
 [OK] User john.doe@test.test created.                                                                                  
                                                                                                                        
      Use token 3c49e1c59345f52702beb2f22f35a0469e52ddbbe066b82748b81149a5bf2f9a to confirm user.                       
                                                                                                                        

vagrant$ ./bin/console app:user:password john.doe@test.test

Set user password
=================

User Details
------------

 ------------ -------------------- 
  First Name   John                
  Last Name    Doe                 
  E-mail       john.doe@test.test  
  Roles        ROLE_ADMIN          
 ------------ -------------------- 

 Password:
 > **********

 Set password for user john.doe@test.test? (yes/no) [yes]:
 > yes

                                                                                                                        
 [OK] Password changed for user john.doe@test.test.                                                                     
                                                                                                                       

```

The last step now is to prepare the frontend assets such as Javascript and
CSS. Exit the virtual machine and continue on the host machine.

```
vagrant$ exit
logout
Connection to 127.0.0.1 closed.
host$ cd ..
host$ yarn install
yarn install v1.21.1
[1/4] 🔍  Resolving packages...
[...]
```

Depending on your needs you now have four options.

1. Build the production ready frontend assets
    ```
    host$ yarn run build 
    ```
2. Build the development frontend assets
    ```
    host$ yarn run dev 
    ```
3. Run a file watcher that updates the development frontend assets
    ```
    host$ yarn run watch 
    ```
4. Run the webpack development server for hot reloading support
    ```
    host$ yarn run dev-server 
    ```

 You should now be able to open [https://iishf.test:4430/]() in your web
 browser and login to the website using the email address you used to 
 create your user and the password entered in the last step.
 
 In general the application follows the common [Symfony][8] setup and layout.
 This means `./var/cache` and `./var/log` contain the cache and the log files
 respectively. There's one catch though: **due to performance reasons both the
 log and the cache directory are located at `/dev/shm/iishf/` on the virtual 
 machine when the application is run via PHP-FPM** (which is the case when
 accessing the application via the webserver). The CLI application `./bin/console` 
 uses the normal cache and log directories. So, to clear the cache and log
 correctly you must clear both `/var/www/iishf/var/{log,cache}` and 
 `/dev/shm/iishf/{log,cache}`.

[1]: https://www.virtualbox.org/wiki/Downloads
[2]: https://www.vagrantup.com/downloads.html
[3]: https://en.wikipedia.org/wiki/Ansible_(software)
[4]: https://mailcatcher.me
[5]: https://github.com/mailhog/MailHog
[6]: https://www.google.com/recaptcha/intro/v2.html
[7]: http://nux.net/secret
[8]: https://symfony.com
