<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Product\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Seller;
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Seller")
     * @ORM\JoinColumn(name="fk_seller", referencedColumnName="id")
     */
    private $seller;

    /**
     * @var Category;
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="fk_category", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ExternalProduct", mappedBy="product", cascade={"persist"})
     */
    private $externalProducts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sku;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @var float
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @var float
     * @ORM\Column(name="special_price", type="decimal", scale=2)
     */
    private $specialPrice;

    /**
     * @var array productAttributes
     *
     * @ORM\Column(name="product_attributes", type="array")
     */
    protected $productAttributes;

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
     * @var ProductSeller\ProductDataMapperAbstract
     */
    private $dataMapper;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->externalProducts = new ArrayCollection();
        $this->productAttributes = new ArrayCollection();
    }

    public function __call($name, $arguments = [])
    {
        if ($name == 'getUrl') {
            $arguments = $this->seller->getBaseUrl();
            return $this->getDataMapper()->$name($arguments);
        }

        if ($name == 'getImageUrl') {
            return $this->seller->getImageStaticUrl();
        }

        return $this->getDataMapper()->$name();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Seller $seller
     */
    public function setSeller(Seller $seller)
    {
        $this->seller = $seller;
    }

    /**
     * @return Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        if (!$this->category instanceof Category) {
            $this->category = new Category();
        }
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Add externalProduct.
     *
     * @param ExternalProduct $externalProduct
     *
     * @return Product
     */
    public function addExternalProduct(ExternalProduct $externalProduct)
    {
        $this->externalProducts[] = $externalProduct;

        return $this;
    }

    /**
     * Remove externalProduct.
     *
     * @param ExternalProduct $externalProduct
     */
    public function removeExternalProduct(ExternalProduct $externalProduct)
    {
        $this->externalProducts->removeElement($externalProduct);
    }

    /**
     * Get externalProduct.
     *
     * @return \Doctrine\ORM\PersistentCollection
     */
    public function getExternalProducts()
    {
        return $this->externalProducts;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        if (is_null($this->name)) {
            $this->setName($this->getDataMapper()->getName());
        }

        return $this->name;
    }
    /**
     * Set sku.
     *
     * @param string $sku
     *
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        if (is_null($this->stock)) {
            $this->setStock($this->getDataMapper()->getQuantity());
        }

        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        if (is_null($this->price)) {
            $this->setPrice($this->getDataMapper()->getPrice());
        }

        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getSpecialPrice()
    {
        if (is_null($this->specialPrice)){
            $this->setSpecialPrice($this->getDataMapper()->getSpecialPrice());
        }

        return $this->specialPrice;
    }

    /**
     * @param float $specialPrice
     */
    public function setSpecialPrice($specialPrice)
    {
        $this->specialPrice = $specialPrice;
    }

    /**
     * @param array $productAttributes
     *
     * @return Product
     */
    public function setProductAttributes(array $productAttributes)
    {
        $this->productAttributes = $productAttributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getProductAttributes()
    {
        return $this->productAttributes;
    }

    /**
     * @return ProductSeller\ProductDataMapperAbstract
     * @throws \Exception
     */
    private function getDataMapper()
    {
        if (is_null($this->dataMapper)) {
            $sellerKeyName = $this->getSeller()->getKeyName();
            $dataMapperClass = 'AppBundle\Infrastructure\Product\Seller\\'
                . ucfirst($sellerKeyName) . 'DataMapper';

            if (!class_exists($dataMapperClass)) {
                //@todo: implement log
                throw new \Exception("Data Mapper not implemented for $sellerKeyName seller");
            }

            $this->dataMapper = new $dataMapperClass($this);
        }

        return $this->dataMapper;
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

    /**
     * Product must have at least one valid ExternalProduct
     *
     * @return bool
     */
    public function isEnabled()
    {
        foreach ($this->getExternalProducts() as $externalProduct) {
            if ($externalProduct->getStatus() == ExternalProduct::STATUS_ACTIVE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Market $market
     * @return bool
     */
    public function isEnabledToMarket(Market $market)
    {
        $result = false;

        $externalProduct = $this->getExternalProductFromMarket($market);

        if ($externalProduct &&
            $externalProduct->getStatus() == ExternalProduct::STATUS_ACTIVE) {
            $result = true;
        }

        return $result;
    }

    public function getExternalProductFromMarket(Market $market)
    {
        $externalProduct = null;

        $expression = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expression->eq('market', $market));
        /** @var \Doctrine\Common\Collections\ArrayCollection $matchResult */
        $matchResult = $this->externalProducts->matching($criteria);
        return $matchResult->current();
    }

    /**
     * Check if this product has already published on market
     * @param Market $market
     * @return bool
     */
    public function isPublishedInMarket(Market $market)
    {
        $isPublishedInMarket = false;
        $externalProducts = $this->getExternalProducts();
        foreach ($externalProducts as $externalProduct) {
            if (($market->getId() == $externalProduct->getMarket()->getId()) &&
                $externalProduct->getStatus() == ExternalProduct::STATUS_ACTIVE) {
                $isPublishedInMarket = true;
            }
        }
        return $isPublishedInMarket;
    }

    public function __toString()
    {
        return sprintf("PRODUCT: SKU %s, Seller %s, Stock %s, Price %s, SpecialPrice %s, Created %s, Updated %s",
            $this->getSku(),
            $this->getSeller()->getKeyName(),
            $this->getStock(),
            $this->getPrice(),
            $this->getSpecialPrice(),
            $this->getCreatedAt()->format('Y-m-d H:i:s'),
            $this->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
}
