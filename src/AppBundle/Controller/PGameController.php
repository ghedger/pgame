<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/11/17
 * Time: 7:11 PM
 */
// src/AppBundle/Controller/PGameController.php
namespace AppBundle\Controller;

// ...
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\GameConstants as GC;
use AppBundle\Entity\GameLog;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\Sign;

/**
 * Class PGameController
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
     * This converts the GC::RESULT_* into a string: Win, Loss, Tie
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
     * @Route("/pgame")
     */
    public function playRound()
    {
        $playerId = 0;          // TODO
        $playerChoice = 0;      // TODO

        $signTab = $this->getSigns();

        // TODO: Seed the pseudorandom number generator.
        $computerChoice = mt_rand(0, 4);
        $signString = $signTab[$computerChoice];

        // Create a record of this game
        $this->createLogEntry($playerId, $playerChoice, $computerChoice);

        // Determine who won
        $winnerString = $this->getResultString($this->evaluateChoices($playerChoice, $computerChoice));

        // Render the output page via Twig and exit
        return $this->render('pgame/play.html.twig', array(
            'sign' => $signString,
            'winner' => $winnerString,
        ));
    }
}