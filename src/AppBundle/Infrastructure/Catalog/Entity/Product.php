<?php
/**
 * Created by PhpStorm.
 * User: pvgomes
 * Date: 3/23/15
 * Time: 4:07 PM
 */

namespace AppBundle\Infrastructure\Catalog\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;


}