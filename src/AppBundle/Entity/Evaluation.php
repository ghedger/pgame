<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 2:27 PM
 */
// src/AppBundle/Entity/Evaluation.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="EvaluationRepository")
 * @ORM\Table(name="evaluation")
 */
class Evaluation
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
    private $victor;


    /**
     * @ORM\Column(type="integer")
     */
    private $vanquished;

    /*
     * GETTERS AND SETTERS
     */

    /**
     * @return mixed
     */
    public function getVictor()
    {
        return $this->victor;
    }

    /**
     * @param mixed $victor
     * @return Evaluation
     */
    public function setVictor($victor)
    {
        $this->victor = $victor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVanquished()
    {
        return $this->vanquished;
    }

    /**
     * @param mixed $vanquished
     * @return Evaluation
     */
    public function setVanquished($vanquished)
    {
        $this->vanquished = $vanquished;
        return $this;
    }
}
