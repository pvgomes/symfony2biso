<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="category", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="name_seller", columns={"name_key", "fk_seller"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Product\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="category_seller_id", type="string", length=255)
     */
    private $categorySellerId;

    /**
     * @var Seller;
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Seller")
     * @ORM\JoinColumn(name="fk_seller", referencedColumnName="id")
     */
    private $seller;

    /**
     * @var array categoryAttributes
     *
     * @ORM\Column(name="category_attributes", type="array")
     */
    protected $categoryAttributes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="name_key", type="string", length=255)
     */
    private $nameKey;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $categorySellerId
     * @return Category
     */
    public function setCategorySellerId($categorySellerId)
    {
        $this->categorySellerId = $categorySellerId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategorySellerId()
    {
        return $this->categorySellerId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @param Seller $seller
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;
    }

    /**
     * @param array $categoryAttributes
     *
     * @return Product
     */
    public function setCategoryAttributes(array $categoryAttributes)
    {
        $this->categoryAttributes = $categoryAttributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategoryAttributes()
    {
        return $this->categoryAttributes;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getNameKey()
    {
        return $this->nameKey;
    }

    /**
     * @param mixed $nameKey
     */
    public function setNameKey($nameKey)
    {
        $this->nameKey = $nameKey;
    }

    /**
     * Check Bounded Context to this Entity
     */
    public function isValid()
    {
        $isValid = false;

        if ($this->getSeller() && $this->getCategorySellerId() && $this->getName()) {
            $isValid = true;
        }

        return $isValid;
    }

    public static function create(Seller $seller, $name, $key, $categorySellerId = null, $id = null)
    {
        $category = new static();
        $category->setId($id);
        $category->setSeller($seller);
        $category->setName($name);
        $category->setNameKey($key);
        $category->setCategorySellerId($categorySellerId);
        return $category;
    }
}
