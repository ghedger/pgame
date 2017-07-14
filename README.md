pgame
=====

A Symfony project created on July 8, 2017, 12:28 pm.

This is a test project demonstrating a web implementation of rock/paper/scissors/spock/lizard for Advisors Excel.

Requirements
============

PGame is a probability game based on an extension to the childhood "Rock, Paper Scissors" popularized by the television series "The Big Bang Theory".  Each of two players presents a sign of one of the five signs: rock, paper, scissors, Spock, lizard.  Each sign beats two others and is defeated by the remaining two.  A tie results the event of the same selection by both parties.

In this web-based implementation, the key concerns rest in the underlying code and frameworks. Symfony 2.6 or later is to be used as a framework.  The Doctrine ORM will be used for mapping the model.  All underlying code must follow the PSR-2 coding standard (http://www.php-fig.org/psr/psr-2/).

The web page must present the following:
    - A way for the player to input his choice.
    - Reporting against a game history log and derived statistics is a must.

We will exercise as much of the Symfony framework and supporting components like Assetic as we can within the scope of time and the core requirements.

Authentication is NOT required for this implementation.

Theory of Operation
===================

State machine:

(URL)/pgame

(URL)/pgame/(choice) -> (repeat)   

Architecture
============

Suggested Improvements
======================

There is no CSS and this is not a mobile-friendly, responsive presentation.

The URLs are not beautified and go through app_dev.php.  It would be good to eliminate this.  I spent about 93% of my time setting up the environment and didn't have time for non-critical path items.

The app takes only keyboard input from number keys, no keypad and no mobile device support.  It would be nice to have clickable icons.

There are no explicitly prescribed Doctrine results caching.

I spent a considerable amount of time trying to work out how to perform the statistical win-loss query using QueryBuilder (hours spent on this).  That devolved to use of native queries (http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/native-sql.html), still without success.  Finally, I resorted to simple SQL queries which did the trick but are not the Doctrine way of doing things.  I chalk this up to my own lack of experience with this particular framework, and suspect the ORM relationships in Entity/Evaluation.php and Entity/GameLog.php are not set up properly.  I suspect a join table is needed between these, but realized this too late in the process.

