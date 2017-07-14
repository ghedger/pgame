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
    const INVALID = 0;
    const ROCK = 1;
    const PAPER = 2;
    const SCISSORS = 3;
    const SPOCK = 4;
    const LIZARD = 5;

    const MINCHOICE = 1;

    // Note that this table is order-sensitive and must match the constants' indexes as defined above.
    public static $names = [
        'invalid',
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