pgame
=====

A Symfony project created on July 8, 2017, 12:28 pm.

This is a test project demonstrating a web implementation of rock/paper/scissors/spock/lizard prepared for Advisors Excel, a Kansas-based independent marketing organization assisting independent insurance agents and financial advisors (the client).

An effort has been made to approximate the client's own development environment and PSR-2 coding standards as closely as possible.

Requirements
============

PGame is a probability game based on an extension to the childhood "Rock, Paper Scissors" popularized by the television series "The Big Bang Theory".  Each of two players presents a sign of one of the five signs: rock, paper, scissors, Spock, lizard.  Each sign beats two others and is defeated by the remaining two.  A tie results the event of the same selection by both parties.

In this web-based implementation, the key concerns rest in the underlying code and frameworks. Symfony 2.6 or later is to be used as a framework.  The Doctrine ORM will be used for mapping the model.  All underlying code must follow the PSR-2 coding standard (http://www.php-fig.org/psr/psr-2/).

The web page must present the following:
* A way for the player to input his choice.
* Reporting against a game history log and derived statistics is a must.
   
Additionally, the following must apply:
* All code must obey the PSR-2 coding standards (http://www.php-fig.org/psr/psr-2/).
* Doctrine must be exercised as much as possible.
* The Git Version Control System must be utilized for source control.

We will exercise as much of the Symfony framework and supporting components like Assetic as we can within the scope of time and the core requirements.

Authentication is not required for this implementation.  A fancy front end is not required.

Theory of Operation
===================

State machine:

(URL)/pgame

(URL)/pgame/(choice) -> (repeat)   

Here is the basic use case, ommitting low-level details about TCP/IP, HTTP and Apache:

* The user starts at the root, (URL)/pgame, and selects a number 1-5.
* The browser, via Javascript, issues a GET request to the server containing the selected number as the final element in the URL.
* Upon receiving the request, Symfony maps it and sends it to the controller.
* The controller parameterizes the user selection.  
* The controller chooses a random computer selection using PHP's intrinsic pseudo-random number generator.
* The controller evaluates the pseudo-random selection against the user's and generates win/loss feedback.
* The controller creates a log entry to the game log recording the selections.
* The controller updates statistics.
* The controller pulls statistics (win/loss and history) and generates the relevant feedback data for Twig.
* The controller renders a page using Twig, passing it the generated feedback data.
* Twig renders the page and sends it back to the client.

Architecture
============

View/Presentation layer
-----------------------
 
app/Resources/views/pgame/play.html.twig
AppBundle/Resources/public/js/input.js

Controller
----------
AppBundle/Controller/PGameController.php

Model
-----
AppBundle/Entity/Sign.php -- This simple entity represents elements of the id -> name associative array for the "signs" required for the game.

AppBundle/Entity/GameLog.php -- GameLog represents the result of a single game, and holds the computer's choice and the player's choice.  This is a transaction table.

AppBundle/Entity/Evaluation.php -- Evaluation is comprised of entities that represent results based on two signs via two fields, "victor" and "vanquished".  One entry would be "victor" -> 1 (paper), "vanquished" -> 0 (rock).  This is a lookup table, with a foreign 

Database
--------
The database is MySQL.


Deployment
==========

System Requirements
-------------------
Our deployment environment runs Ubuntu 14.04 and the following:
 
Apache v2.4\
MySQL v5.55\
PHP v5.6\
Composer v1.4.2\
Symfony v2.8

Packages
--------
Following are Symfony-integrated packages for pgame:

doctrine/annotations                 v1.2.7\
doctrine/cache                       v1.5.4\
doctrine/collections                 v1.3.0\
doctrine/common                      v2.5.3\
doctrine/data-fixtures               v1.2.2\
doctrine/dbal                        v2.5.12\
doctrine/doctrine-bundle             1.6.8\
doctrine/doctrine-cache-bundle       1.3.0\
doctrine/doctrine-fixtures-bundle    2.3.0\
doctrine/inflector                   v1.1.0\
doctrine/lexer                       v1.0.1\
doctrine/orm                         v2.4.8\
incenteev/composer-parameter-handler v2.1.2\
ircmaxell/password-compat            v1.0.4\
jdorn/sql-formatter                  v1.2.17\
kriswallsmith/assetic                v1.4.0\
monolog/monolog                      1.23.0\
paragonie/random_compat              v2.0.10\
psr/log                              1.0.2\
sensio/distribution-bundle           v4.0.38\
sensio/framework-extra-bundle        v3.0.26\
sensio/generator-bundle              v3.1.4\
sensiolabs/security-checker          v3.0.7\
swiftmailer/swiftmailer              v5.4.8\
symfony/assetic-bundle               v2.8.1\
symfony/monolog-bundle               v3.1.0\
symfony/phpunit-bridge               v2.8.24\
symfony/polyfill-apcu                v1.4.0\
symfony/polyfill-intl-icu            v1.4.0\
symfony/polyfill-mbstring            v1.4.0\
symfony/polyfill-php54               v1.4.0\
symfony/polyfill-php55               v1.4.0\
symfony/polyfill-php56               v1.4.0\
symfony/polyfill-php70               v1.4.0\
symfony/polyfill-util                v1.4.0\
symfony/security-acl                 v2.8.0\
symfony/swiftmailer-bundle           v2.6.2\
symfony/symfony                      v2.8.24\
twig/twig                            v1.34.4\

Code VCS Repository
-------------------
https://github.com/ghedger/pgame


Suggested Improvements
======================

There is no CSS and this is not a mobile-friendly, responsive presentation.

The URLs are not beautified and go through app_dev.php.  It would be good to eliminate this.  I spent about 93% of my time setting up the environment and didn't have time for non-critical path items.

The app takes only keyboard input from number keys, no keypad and no mobile device support.  It would be nice to have clickable icons.

There are no explicitly prescribed Doctrine results caching.

I spent a considerable amount of time trying to work out how to perform the statistical win-loss query using QueryBuilder (hours spent on this).  That devolved to use of native queries (http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/native-sql.html), still without success.  Finally, I resorted to simple SQL queries which did the trick but are not the Doctrine way of doing things.  I chalk this up to my own lack of experience with this particular framework, and suspect the ORM relationships in Entity/Evaluation.php and Entity/GameLog.php are not set up properly.  I suspect a join table is needed between these, and getting that up and running is the correct way to connect via the ORM.  I will continue to research this.

Though I installed ReactJS in its own repository I did not have time to integrate it into pgame.  It's impressive and should be leveraged in appropriate situations.
