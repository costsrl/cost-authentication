CostAuthentication
=======

### What is CostAuthentication? ###


### What exactly does CostAuthentication do? ###

CsnUser has been created with educational purposes to demonstrate how Authentication can be done. It is fully functional.

CsnUser module consists of:

* Login with remember me
* Registration with Captcha and Confirmation email
* Forgotten password with confirmation email.

In addition, the passwords have two levels of protection - a dynamic and static salt.

### What's the use again? ###

An alternative to ZfcUser with more functionality added.

Installation
------------

2. Add `CostAuthentication`, `DoctrineModule` and `DoctrineORMModule` in your application configuration at: `./config/application.config.php`. An example configuration may look like the following :


branch 'master' of http://git.cost.it/cost/cost-authentication.git

```
'modules' => array(
    'Application',
    'DoctrineModule',
    'DoctrineORMModule',
    'CostAuthentication'
)
```

Configuration
-------------
CostAuthentication requires setting up a Connection for Doctrine, a simple Mail configuration and importing a database schema.

1. Create a new database (or use an existing one, dedicated to your application).

2. Copy the sample Doctrine configuration from `./vendor/cost/cost-authentication/config/doctrineorm.local.php.dist` to `./config/autoload` renaming it to **doctrineorm.local.php**. Edit the file, replacing the values (*username*, *password*, etc) with your personal database parameters.

3. Run `./vendor/bin/doctrine-module orm:schema-tool:create` to generate the database schema. Import the sample SQL data (for default roles and languages) located in `./vendor/cost/cost-authentication/data/SampleData.sql`. You can easily do that with *PhpMyAdmin* for instance.

4. Copy the sample Mail configuration from `./vendor/cost/cost-authentication/config/mail.config.local.php.dist` to `./config/autoload` renaming it to **mail.config.local.php**. Edit the file, replacing the values (*host*, *username*, etc) with your SMTP server parameters.

5. Depend on Database overwrite Enity "User" with class under data/EntityPlatform and rename it.
Options
-------

The CostAuthentication module has some options to allow you to quickly customize the basic
functionality. 

Create novigo folder under vendor directory 
After installing CostAuthentication, copy
`./vendor/cost/cost-authentication/config/cost-authentication.global.php.dist` to
`./config/autoload`, renaming it to **cost-authentication.global.php** and change the values as desired, following the instructions.

The following options are available:

- **STATIC_SALT** Constant string value, prepended to the password before hashing
- **login_redirect_route** String value, name of a route in the application
  which the user will be redirected to after a successful login.
- **logout_redirect_route** String value, name of a route in the application which
  the user will be redirected to after logging out.
- **nav_menu** Bool value, show or hide navigation menu.

>### It is ready? ###
Navigate to *[hostname]/user* in your browser to view different options for login, registration, forgotten password, etc.

Dependencies
------------

This Module depends on the following Modules:

 - [Laminas Framework](https://github.com/laminas/laminas-mvc-skeleton) 

 - [DoctrineORMModule] (https://github.com/doctrine/DoctrineORMModule) - DoctrineORMModule integrates Doctrine 2 ORM with Laminas Framework 2 quickly and easily.

Recommends
----------
branch 'laminas' of https://rsalvatori_novigo@bitbucket.org/novigoteam/cost-authentication.git
- 'laminas' of https://gitlab.cost.it/cost/cost-authentication
- [cost/cost-authentication](https://gitlab.cost.it/cost/cost-authentication);

