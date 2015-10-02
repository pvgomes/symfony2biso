<?php

/**
 * Class ExternalProduct.
 *
 * @author Thiago Muniz <thiago.muniz@tricae.com.br>
 */
namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Infrastructure\Core\Market;

/**
 * @ORM\table(name="external_product")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Product\ExternalProductRepository")
 */
class ExternalProduct
{
    const STATUS_NEW = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Market
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Market", cascade={"persist"})
     * @ORM\JoinColumn(name="fk_market", referencedColumnName="id", nullable=false)
     */
    private $market;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="externalProducts")
     * @ORM\JoinColumn(name="fk_product", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $json;

    /**
     * @var string
     *
     * @Assert\Choice(callback = "listStatus", message = "Choose a valid status.")
     * @ORM\Column(name="status", type="string", length=30)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

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
     * Set market.
     *
     * @param Market $market
     *
     * @return ExternalProduct
     */
    public function setMarket(Market $market = null)
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
     * Set product.
     *
     * @param Product $product
     *
     * @return ExternalProduct
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set sku.
     *
     * @param string $sku
     *
     * @return ExternalProduct
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku.
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param string $json
     *
     * @return $this
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * @param string $status
     *
     * @return ExternalProduct
     */
    public function setStatus($status)
    {
        if (!in_array($status, [self::STATUS_NEW, self::STATUS_ACTIVE, self::STATUS_INACTIVE])) {
            throw new \InvalidArgumentException("Invalid status");
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return array
     */
    public static function listStatus()
    {
        return [
            self::STATUS_NEW => self::STATUS_NEW,
            self::STATUS_ACTIVE => self::STATUS_ACTIVE,
            self::STATUS_INACTIVE => self::STATUS_INACTIVE,
        ];
    }
}
