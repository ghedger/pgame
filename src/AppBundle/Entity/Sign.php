<?php
/**
 * Created by PhpStorm.
 * User: ghedger
 * Date: 7/12/17
 * Time: 7:35 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SignRepository")
 * @ORM\Table(name="sign")
 * @package AppBundle\Entity
 */

class Sign
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return Sign
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Sign
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
