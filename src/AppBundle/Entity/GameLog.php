<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 3:52 PM
 */
// src/AppBundle/Entity/GameLog.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="GameLogRepository")
 * @ORM\Table(name="gamelog")
 * GPH NOTE: Table name is optional.  I prefer to explicate the name in the database.
 */
class GameLog
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $player;

    /**
     * @ORM\Column(type="integer")
     */
    private $computer_choice;

    /**
     * @ORM\Column(type="text")
     */
    private $player_choice;

    /*
     * GETTERS AND SETTERS
     */

    /**
     * Gets the player id
     * @return $player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Sets the player id
     * @param $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * Gets the computer choice
     * @return $choice
     */
    public function getComputerChoice()
    {
        return $this->computer_choice;
    }

    /**
     * Sets the computer choice
     * @param $choice
     */
    public function setComputerChoice($choice)
    {
        $this->computer_choice = $choice;
        return $this;
    }

    /**
     * Gets the player choice
     * @return $choice
     */
    public function getPlayerChoice()
    {
        return $this->player_choice;
    }

    /**
     * Sets the player choice
     * @param $choice
     */
    public function setPlayerChoice($choice)
    {
        $this->player_choice = $choice;
        return $this;
    }

    /**
     * Gets the GameLog entry id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the GameLog entry id
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
