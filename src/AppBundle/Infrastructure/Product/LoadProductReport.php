<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Infrastructure\Core;

/**
 * @ORM\Entity
 * @ORM\Table(name="load_product_report")
 */
class LoadProductReport
{
    const ERROR = 'error';
    const SUCCESS = 'success';

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="LoadProduct", inversedBy="loadProductReports")
     * @ORM\JoinColumn(name="fk_load_product", referencedColumnName="id")
     */
    private $loadProduct;

    /**
     * @var Market
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Market", cascade={"persist"})
     * @ORM\JoinColumn(name="fk_market", referencedColumnName="id", nullable=true)
     */
    private $market;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $sku;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set loadProduct.
     *
     * @param LoadProduct $loadProduct
     *
     * @return LoadProductReport
     */
    public function setLoadProduct(LoadProduct $loadProduct)
    {
        $this->loadProduct = $loadProduct;

        return $this;
    }

    /**
     * Get loadProduct.
     *
     * @return LoadProduct
     */
    public function getLoadProduct()
    {
        return $this->loadProduct;
    }

    /**
     * Set market.
     *
     * @param Market $market
     *
     * @return LoadProductReport
     */
    public function setMarket(Market $market)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market.
     *
     * @return Market
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * @param string $sku
     *
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $type
     *
     * @return LoadProductReport
     */
    public function setType($type)
    {
        if (!in_array($type, [self::ERROR, self::SUCCESS])) {
            throw new \InvalidArgumentException('Invalid type: '.$type);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
