<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/13/17
 * Time: 1:19 PM
 */

namespace AppBundle\Utils;

use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

/**
 * Class Statistics
 * @package AppBundle\Utils
 *
 * GPH NOTE: First attempt is simply to get a native SQL query to work.  Then if there's time (there won't be) try
 * to get it working The Doctrine Way, either with DQL or QueryBuilder.  This is my fallback as I'm running out of time
 * and, even if it's not pretty, must satisfy the requirements.
 *
 * TODO: Need to verify the ORM relationships.
 * Specifically, I believe we need to add a join table between Evaluation and Game since the Evaluation victor and vanquished
 * fields are many-to-many, not one-to-many.
 *
 * But let's get this working first to satisfy the core requirements then circle back to that more elegant approach.
 */
class Statistics extends Controller
{
    /**
     * query
     *
     * Generalized query executor
     *
     * @param $sql string
     * @param $controller Controller
     * @param $array boolean
     * @return array
     *
     */
    private function query($controller, $sql, $array=false)
    {
        // Get our primitive mysqli connection
        /* @var $conn Connection */
        $conn = $controller->getDoctrine()->getManager()->getConnection();

        try {
        // Prepare query for execution
        $sth = $conn->prepare($sql);

        // Execute query, fetch results, return
        $sth->execute();
        if ($array) {
            $result = $sth->fetchAll();
        } else {
            $result = $sth->fetch();
        }
        } catch (Exception $e) {
            // Handle an exception and print a rudimentary error with enough info to get us close to the root.
            /* @var $logger LoggerInterface */
            $logger = new LoggerInterface();
            $logger->error($e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage());
        }
        /* @var $result array */
        return $result;
    }

    /**
     * getHumanWins
     *
     * Get the number of times the human has beaten the computer.  This is for every human the world over
     * who has ever played the game; there is no account- or ip-based uniqueness yet.
     *
     * @param $controller
     * @return mixed
     */
    public function getHumanWins($controller)
    {
        $sql = 'select count(e.victor) c from gamelog g '
            . 'inner join evaluation e on g.computer_choice = e.vanquished and g.player_choice = e.victor';
        return $this->query($controller, $sql)['c'];
    }

    /**
     * @param $controller
     * @return mixed
     */
    public function getComputerWins($controller)
    {
        $sql = 'select count(e.victor) c from gamelog g '
            . 'inner join evaluation e on g.player_choice = e.vanquished and g.computer_choice = e.victor';
        return $this->query($controller, $sql)['c'];
    }

    /**
     * @param $controller
     * @return mixed
     */
    public function getTies($controller)
    {
        $sql = 'select count(*) c from gamelog where computer_choice = player_choice';
        return $this->query($controller, $sql)['c'];
    }

    /**
     * @param $controller
     * @return mixed
     */
    public function getTotal($controller)
    {
        $sql = 'select count(*) c from gamelog';
        return $this->query($controller, $sql)['c'];
    }

    /**
     * getHistory
     *
     * Returns quick and dirty enumerated count of historical computer choices and human choices
     *
     * @param $controller
     * @return array
     */
    public function getHistory($controller)
    {
        $sql = 'select count(*) c, s.name choice from gamelog ' .
            'inner join sign s on computer_choice = s.id group by computer_choice order by computer_choice;';

        $computerArray = $this->query($controller, $sql, true);

        $sql = 'select count(*) c, s.name choice from gamelog ' .
            'inner join sign s on player_choice = s.id group by player_choice order by player_choice;';
        
        $humanArray = $this->query($controller, $sql, true);

        return ['human' => $humanArray, 'computer' => $computerArray];
    }
}
