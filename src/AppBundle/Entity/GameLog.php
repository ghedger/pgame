<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 3:52 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameLogRepository")
 * @ORM\Table(name="gamelog")
 * @package AppBundle\Entity
 *
 * GPH NOTE: Table name is optional.  I prefer to explicate the name in the database.
 */
class GameLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="player_choice", type="integer")
     */
    private $playerChoice;

    /**
     * @var int
     *
     * @ORM\Column(name="computer_choice", type="integer")
     */
    private $computerChoice;

    /**
     * @var int
     *
     * @ORM\Column(name="evaluation_id", type="integer")
     * @ORM\ManyToOne(targetEntity="Evaluation", inversedBy="id")
     * @ORM\JoinColumn(name="evaluation_id", referencedColumnName="id")
     */
    private $evaluationId;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set playerChoice
     *
     * @param integer $playerChoice
     * @return GameLog
     */
    public function setPlayerChoice($playerChoice)
    {
        $this->playerChoice = $playerChoice;

        return $this;
    }

    /**
     * Get playerChoice
     *
     * @return integer
     */
    public function getPlayerChoice()
    {
        return $this->playerChoice;
    }

    /**
     * Set computerChoice
     *
     * @param integer $computerChoice
     * @return GameLog
     */
    public function setComputerChoice($computerChoice)
    {
        $this->computerChoice = $computerChoice;

        return $this;
    }

    /**
     * Get computerChoice
     *
     * @return integer
     */
    public function getComputerChoice()
    {
        return $this->computerChoice;
    }

    /**
     * Set evaluationId
     *
     * @param integer $evaluationId
     * @return GameLog
     */
    public function setEvaluationId($evaluationId)
    {
        $this->evaluationId = $evaluationId;

        return $this;
    }

    /**
     * Get evaluationId
     *
     * @return integer
     */
    public function getEvaluationId()
    {
        return $this->evaluationId;
    }
}
