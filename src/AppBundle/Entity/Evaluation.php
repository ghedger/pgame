<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 2:27 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="EvaluationRepository")
 * @ORM\Table(name="evaluation")
 * @package AppBundle\Entity
 */
class Evaluation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="GameLog", mappedBy="evaluation")
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
     * @param mixed $id
     * @return Evaluation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getVictor()
    {
        return $this->victor;
    }

    /**
     * @param integer $victor
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
     * @param integer $vanquished
     * @return Evaluation
     */
    public function setVanquished($vanquished)
    {
        $this->vanquished = $vanquished;
        return $this;
    }
}
