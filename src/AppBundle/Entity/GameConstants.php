<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 6:46 PM
 */

namespace AppBundle\Entity;


class GameConstants
{
    // NOTE: Although PSR-2 states that "Visibility MUST be declared on all properties and methods", this is only
    // permissible in PHP 7.1 and onward.  I elected to use PHP 5.6 for this exercise; thus declaring these public is
    // not an option...
    const ROCK = 0;
    const PAPER = 1;
    const SCISSORS = 2;
    const SPOCK = 3;
    const LIZARD = 4;
}