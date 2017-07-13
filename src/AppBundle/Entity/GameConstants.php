<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 6:46 PM
 */

namespace AppBundle\Entity;

/**
 * Class GameConstants
 * @package AppBundle\Entity
 */
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

    // Note that this table is order-sensitive and must match the constants' indexes as defined above.
    public static $names = [
        'rock',
        'paper',
        'scissors',
        'spock',
        'lizard',
    ];

    // These could be in their own constants class.  The philosophical tradeoff for such granularity is having constants
    // diffusely scattered throughout the codebase.
    const RESULT_LOSS = 0;
    const RESULT_WIN = 1;
    const RESULT_TIE = 2;
}