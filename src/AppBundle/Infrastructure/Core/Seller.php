<?php

namespace AppBundle\Infrastructure\Core;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="seller")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Core\SellerRepository")
 */
class Seller
{
    const PARTNER_STATE_MACHINE = 'orders_market';
    const TRIKAN_PARTNER_STATE_MACHINE = 'trikan_orders_market';
    const VENTURE_STATE_MACHINE = 'orders_seller';
    const DAFITI_VENTURE_STATE_MACHINE = 'dafiti_orders_seller';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ConfigurationRepository
     */
    private $configuration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, unique=false)
     * @ORM\Column(name="key_name", type="string", length=60, unique=true)
     */
    private $keyName;

    /**
     * @var String
     * @ORM\Column(name="access_token", type="string", nullable=true)
     */
    private $accessToken;

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

    private $productPrefix;
    private $categoryPrefix;

    /** @var  SellerSpecification */
    private $specification;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Seller
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set a key name
     * @param $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * @return String
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName;
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

    public function getRepositoryType()
    {
        return $this->configuration
            ->getSellerRepositoryType($this->getKeyName());
    }

    public function getRepositoryLocation()
    {
        return $this->configuration
            ->getSellerRepositoryLocation($this->getKeyName());
    }

    public function getProductPrefix()
    {
        return $this->configuration
            ->getSellerProductPrefix($this->getKeyName());
    }

    public function getCategoryPrefix()
    {
        if (!$this->categoryPrefix) {
            $this->categoryPrefix = $this->configuration
                ->getCategoryPrefix($this->getKeyName());
        }
        return $this->categoryPrefix;
    }

    public function getBaseUrl()
    {
        return $this->configuration
            ->getSellerBaseUrl($this->getKeyName());
    }

    public function getImageStaticUrl()
    {
        return $this->configuration
            ->getSellerImageStaticUrl($this->getKeyName());
    }

    /**
     * @deprecated
     */
    public function getWebServiceConfiguration()
    {
        return $this->configuration
            ->getWebServiceConfiguration($this->getKeyName());
    }

    public function getSellerWebservice()
    {
        return $this->configuration
            ->getSellerWebservice($this->getKeyName());
    }

    public function getMarketWebservice($marketKey)
    {
        return $this->configuration
            ->getMarketWebservice($this->getKeyName(), $marketKey);
    }

    public function getFreightConfiguration()
    {
        return $this->configuration
            ->getFreightConfiguration($this->getKeyName());
    }

    public function handleMarketConfiguration(Market $market)
    {
        $marketConfigurationConnection = $this->getMarketWebservice($market->getKeyName());
        if ($marketConfigurationConnection) {
            $market->setConfiguration($marketConfigurationConnection);
        }

        // Set Bounded context prefix
        $this->configuration->setPrefixName($this->getKeyName().'.'.$market->getKeyName());
        $this->categoryPrefix = $this->configuration->getCategoryPrefix($this->getKeyName());
        $this->productPrefix = $this->configuration->getSellerProductPrefix($this->getKeyName());
    }

    /**
     * @param \AppBundle\Infrastructure\Product\Product $product
     * @param int $requestQuantity
     * @return bool
     */
    public function isAvailableStock(\AppBundle\Infrastructure\Product\Product $product, $requestQuantity)
    {
        return $this->getSpecification()->isAvailableStock($product, $requestQuantity);
    }

    /**
     * @return bool
     */
    public function canCalculateFreight()
    {
        return $this->getSpecification()->canCalculateFreight();
    }

    public function getSellerStateMachineGraph()
    {
        return $this->getSpecification()->getSellerStateMachineGraph();
    }

    public function getMarketStateMachineGraph($marketKey)
    {
        return $this->getSpecification()->getMarketStateMachineGraph($marketKey);
    }

    private function getSpecification()
    {
        if (is_null($this->specification)) {
            $sellerKeyName = $this->getKeyName();
            $specificationClass = 'AppBundle\Infrastructure\Core\\'
                . ucfirst($sellerKeyName) . 'Specification';

            if (class_exists($specificationClass)) {
                $this->specification = new $specificationClass($this);
            } else {
                $this->specification = new SellerSpecification($this);
            }
        }

        return $this->specification;
    }


    public function isSkuCached($sku)
    {
        return (bool) $this->configuration->isSkuCached($sku, $this->getKeyName());
    }

    public function cacheSku($sku)
    {
        return $this->configuration->cacheSkus([$sku], $this->getKeyName());
    }

    public function cacheSkus(array $skus)
    {
        return $this->configuration->cacheSkus($skus, $this->getKeyName());
    }

    public function orderStatistics(array $orderStatistics = null)
    {
        $key = $this->getKeyName().".order.statistics";
        if ($orderStatistics) {
            $this->configuration->cacheStatistics($key, $orderStatistics);
        }
        return $this->configuration->getStatistics($key);
    }

    public function productStatistics(array $productStatistics = null)
    {
        $key = $this->getKeyName().".product.statistics";
        if ($productStatistics) {
            $this->configuration->cacheStatistics($key, $productStatistics);
        }
        return $this->configuration->getStatistics($key);
    }

    public function clearCacheSkus()
    {
        return $this->configuration->clearCacheSkus($this->getKeyName());
    }

    /**
     * @return bool
     */
    public function canSendExternalSku()
    {
        return $this->getSpecification()->canSendExternalSku();
    }

    /**
     * @return bool
     */
    public function canSendMarketOrderNumber()
    {
        return $this->getSpecification()->canSendMarketOrderNumber();
    }

    /**
     * @return bool
     */
    public function canSendIncompleteShipped()
    {
        return $this->getSpecification()->canSendIncompleteShipped();
    }

    public function canSendIncompleteDelivery()
    {
        return $this->getSpecification()->canSendIncompleteDelivery();
    }

    public function getQuantityShippedItems(\stdClass $orderDataObject)
    {
        return $this->getSpecification()->getQuantityItemsBySellerData($orderDataObject);
    }

    public function getQuantityDeliveredItems(\stdClass $orderDataObject)
    {
        return $this->getSpecification()->getQuantityItemsBySellerData($orderDataObject);
    }

    public function getIdShippedItems(\stdClass $orderDataObject)
    {
        return $this->getSpecification()->getItemsBySellerData($orderDataObject);
    }

    public function getIdDeliveredItems(\stdClass $orderDataObject)
    {
        return $this->getSpecification()->getItemsBySellerData($orderDataObject);
    }
}
