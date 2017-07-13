<?php
// src/AppBundle/Controller/PGameController.php
namespace AppBundle\Controller;

// ...
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\GameLog;
use AppBundle\Entity\Evaluation;

class PGameController extends Controller
{
    /*
     * This table contains an indexed array of signs (rock, scissors, etc.)
     * TODO: this should not be hardcoded here.  It should come from database or other table.
     */
    private static $signTab = [
        'rock',
        'paper',
        'scissors',
        'spock',
        'lizard',
    ];

    /**
     * createLogEntry
     * Creates a GameLog entry of what the player chose and what the computer chose for statistical tracking purposes.
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
     * @return boolean
     */
    private function evaluateChoices($playerChoice, $computerChoice)
    {
        $eval = $this->getDoctrine()
            ->getRepository(Evaluation::class)
            ->findOneBy(array(
                    "victor" => $playerChoice,
                    "vanquished" => $computerChoice
                )
            );
        
        if (!$eval) {
            return false;
        }
        return true;
    }

    /**
     * @Route("/pgame")
     */
    public function numberAction()
    {
        $playerId = 0;          // TODO
        $playerChoice = 0;      // TODO
        
        // TODO: Seed the pseudorandom number generator.
        $computerChoice = mt_rand(0, 4);
        $sign = PGameController::$signTab[$computerChoice];

        // Create a record of this game
        $this->createLogEntry($playerId, $playerChoice, $computerChoice);

        // Determine who won
        $winner = $this->evaluateChoices($playerChoice, $computerChoice);

        // Render the output page via Twig and exit
        return $this->render('pgame/play.html.twig', array(
            'sign' => $sign,
            'winner' => $winner,
        ));
    }
}