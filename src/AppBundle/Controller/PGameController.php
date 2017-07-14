<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/11/17
 * Time: 7:11 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\GameConstants as GC;
use AppBundle\Entity\GameLog;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\Sign;
use AppBundle\Utils\Statistics;

/**
 * Class PGameController
 *
 * This is pgame's controller class and central "brain".  It is responsible for the following:
 *
 *     - Processing user input
 *     - Generating the computer's choice
 *     - Evaluating the user's choice against the computer's
 *     - Generating feedback
 *     - Pulling and updating statistics
 *     - Rendering out the new page
 *
 * @package AppBundle\Controller
 */
class PGameController extends Controller
{
    /**
     * createLogEntry
     *
     * Creates a GameLog entry of what the player chose and what the computer chose for statistical tracking purposes.
     *
     * @param integer $playerId
     * @param integer $playerChoice
     * @param integer $computerChoice
     */
    private function createLogEntry($playerId, $playerChoice, $computerChoice)
    {
        // Create a GameLog entity via the ORM
        $gameLog = new GameLog();

        // Set the data in the new GameLog.
        // The results will be derived separately, thus we do not include an explicit win/loss field.
        $gameLog->setPlayerChoice($playerChoice);
        $gameLog->setPlayer($playerId);
        $gameLog->setComputerChoice($computerChoice);

        // Get the Evaluation Id, or null if a tie
        // Here, if we have a tie, we will mark both choices as the 'invalid' type for evaluation purposes, signifying
        // a tie.
        if($computerChoice == $playerChoice) {
            $playerChoice = GC::INVALID;
            $computerChoice =  GC::INVALID;
        }

        /* @var $evaluationResult Evaluation */
        $evaluationResult = $this->getDoctrine()
            ->getRepository(Evaluation::class)
            ->findOneBy(
                array(
                    "victor" => $playerChoice,
                    "vanquished" => $computerChoice
                )
            );

        // If we didn't get a result, reverse the choices
        if(!$evaluationResult) {
            $evaluationResult = $this->getDoctrine()
                ->getRepository(Evaluation::class)
                ->findOneBy(
                    array(
                        "victor" => $computerChoice,
                        "vanquished" => $playerChoice
                    )
                );
        }

        // There WILL be an evaluation
        $evaluationId = $evaluationResult->getId();
        $gameLog->setEvaluation($evaluationId);

        // Instantiate the ORM manager
        $doctrineMgr = $this->getDoctrine()->getManager();

        // Tell Doctrine to persist the new GameLog entity into its backend table (separation of concerns).
        $doctrineMgr->persist($gameLog);

        // Execute the query and insert the record of this game result to the backend database.
        $doctrineMgr->flush();
    }

    /**
     * evaluateChoices
     *
     * This evalutes whether the player won are lost (win == true)
     *
     * @param integer $playerChoice
     * @param integer $computerChoice
     * @return integer
     */
    private function evaluateChoices($playerChoice, $computerChoice)
    {
        $eval = $this->getDoctrine()
            ->getRepository(Evaluation::class)
            ->findOneBy(
                array(
                    "victor" => $playerChoice,
                    "vanquished" => $computerChoice
                )
            );

        if (!$eval) {
            if ($computerChoice != $playerChoice) {
                return GC::RESULT_LOSS;
            } else {
                return GC::RESULT_TIE;
            }
        }
        return GC::RESULT_WIN;
    }

    /**
     * getSigns
     *
     * Fetches the associative array of sign ids and names.
     *
     * @return array
     */
    private function getSigns()
    {
        $signs = $this->getDoctrine()
            ->getRepository(Sign::class)
            ->findAll();

        $signNames = array();
        /**
         * @var $sign Sign
         */
        foreach ($signs as $sign) {
            array_push($signNames, $sign->getName());
        }
        return $signNames;
    }

    /**
     * getResultString
     *
     * Converts the GC::RESULT_* into a string: Win, Loss, Tie
     *
     * @param integer $result
     * @return string
     */
    private function getResultString($result)
    {
        switch ($result) {
            case GC::RESULT_LOSS:
                $resultString = 'LOSS';
                break;
            case GC::RESULT_WIN:
                $resultString = 'WIN';
                break;
            case GC::RESULT_TIE:
                $resultString = 'TIE';
                break;
            default:
                $resultString = 'UNDEFINED';
                break;
        }
        return $resultString;
    }

    /**
     * getWinLossString
     *
     * "WINS-LOSSES-TIES"
     * For the sake of separation of concerns I elected to keep this here and not place it in the Statistics class.
     *
     * @return string
     */
    private function getWinLossString()
    {
        $stats = new Statistics();
        return $stats->getHumanWins($this) . '-'
            . $stats->getComputerWins($this) . '-'
            . $stats->getTies($this);
    }

    /**
     * getHistory
     *
     * Returns an array of two arrays, one representing human selection history and the other the computer's.
     *
     * @return array
     */
    private function getHistory()
    {
        $stats = new Statistics();
        return $stats->getHistory($this);
    }

    /**
     * start
     *
     * Serves as primary entry point for the game.
     *
     * @Route("/pgame")
     */
    public function start()
    {
        return $this->playRound(-1);
    }

    /**
     * playRound
     *
     * The {choice} variable comes from the URL via input.js and is the user's choice for this round of the game.
     * This is the primary function of the game.
     *
     * @param integer $choice This is the user choice.  -1 means no choice and signifies need to skip generating feeback.
     * @Route("/pgame/{choice}")
     * @return mixed
     */
    public function playRound($choice)
    {
        // TODO: For now, all players share a common ID
        $playerId = 0;

        // Populate the names of the signs ("rock", "paper", etc.) from the ORM
        $signTab = $this->getSigns();

        $playerChoiceString = $computerChoiceString = $winnerString = '';

        // On the first round, there will be no choice and start() will pass in a -1.
        // Get the maximum sign ID
        $max = count($signTab);
        if ($choice >= GC::MINCHOICE && $choice < $max) {
            $playerChoice = $choice;

            $playerChoiceString = $signTab[$choice];

            // TODO: Seed the pseudorandom number generator.
            $computerChoice = mt_rand(GC::MINCHOICE, $max - 1);
            $computerChoiceString = $signTab[$computerChoice];

            // Create a record of this game
            $this->createLogEntry($playerId, $playerChoice, $computerChoice);

            // Determine who won
            $winnerString = $this->getResultString($this->evaluateChoices($playerChoice, $computerChoice));
        }

        // Garner our statistics for the view to present
        $winLossString = $this->getWinLossString();
        $historyArray = $this->getHistory();

        // Render the output page via Twig and exit
        return $this->render(
            'pgame/play.html.twig', array(
            'computerChoice' => $computerChoiceString,
            'playerChoice' => $playerChoiceString,
            'winner' => $winnerString,
            'winLossTie' => $winLossString,
            'history' => $historyArray
        ));
    }
}
