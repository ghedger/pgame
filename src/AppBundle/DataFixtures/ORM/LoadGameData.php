<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 4:10 PM
 */
// src/AppBundle/DataFixtures/ORM/LoadGameData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\GameConstants as GC;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\Sign;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 *
 * The main data for this game rests in its evaluation table which determines victories and defeats.  This table-driven
 * approach minimizes the need for excessive game logic in the control logic, reducing the latter to checking for ties.
 *
 * The following argument could be made: since this DataFixture loads a PHP-based table into the database, and the
 * table is therefore in PHP already, why have it in the database?  The answers are consistency, extensibility, and
 * scalability.  For example, what if a new requirement came in to add "Kangaroo" and "Blue Whale" as options?
 */
class LoadGameData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    private function loadSigns(ObjectManager $manager)
    {
        try {
            // i serves as the index to the table.  We will populate the signs in a simple lookup table and
            // associate the ids with the names.
            $i = 0;
            foreach (GC::$names as $name) {
                $sign = new Sign();
                $sign->setId($i)->setName($name);
                $manager->persist($sign);
                $i++;
            }
        } catch (Exception $e) {
            // Handle an exception and print a rudimentary error with enough info to get us close to the root.
            $logger = new LoggerInterface();
            $logger->error($e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadEval(ObjectManager $manager)
    {
        // This table holds the win/loss rules for the game.  Format:
        //
        //      Winner, Loser
        //
        // We cannot use key/value pairing because the left side has multiple entries of the same value and is therefore
        // not unique and not a candidate key, so no associative array.
        static $evalTab = [
            GC::INVALID, GC::INVALID,       // special association signifying any tie
            GC::ROCK, GC::SCISSORS,
            GC::ROCK, GC::LIZARD,
            GC::PAPER, GC::ROCK,
            GC::PAPER, GC::SPOCK,
            GC::SCISSORS, GC::PAPER,
            GC::SCISSORS, GC::LIZARD,
            GC::SPOCK, GC::ROCK,
            GC::SPOCK, GC::SCISSORS,
            GC::LIZARD, GC::SPOCK,
            GC::LIZARD, GC::PAPER,
        ];

        // Here we iterate through the table defined above and place it in our database using Doctrine.
        // A foreach ($evalTab as $key => $val) would be a tad more elegant, but won't work because there's no key.
        // Alternative approaches could increase the degree of the table, to wit, winner, loser, loser, but that
        // sacrifices extensibility.
        try {
            $tot = count($evalTab);
            for ($i = 0; $i < $tot; $i += 2) {
                $eval = new Evaluation();
                $eval->setVictor($evalTab[$i])->setVanquished($evalTab[$i + 1]);
                $manager->persist($eval);
            }
        } catch (Exception $e) {
            // Handle an exception and print a rudimentary error with enough info to get us close to the root.
            $logger = new LoggerInterface();
            $logger->error($e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage());
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadSigns($manager);
        $this->loadEval($manager);
    }
}
