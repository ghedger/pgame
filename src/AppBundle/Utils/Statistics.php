<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/13/17
 * Time: 1:19 PM
 */

namespace AppBundle\Utils;

use AppBundle\Entity\GameLog;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\Sign;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;

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
     * @param $sql
     * @return mixed
     *
     * GPH NOTE: As stated above, a native SQL query was not my first choice but a fallback.
     */
    private function query($controller, $sql, $array=0)
    {
        // Get our primitive mysqli connection
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
            $logger = new LoggerInterface();
            $logger->error($e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage());
        }
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