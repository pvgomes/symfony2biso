<?php

namespace AppBundle\Infrastructure\Order;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Infrastructure\Product;

/**
 * @ORM\Table(name="sales_order_item")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Order\ItemRepository")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Order\OrderRepository")
 */
class Item
{
    Const ADDITIONALS_TRACKING = "tracking";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Order\Order", inversedBy="items")
     * @ORM\JoinColumn(name="fk_order", referencedColumnName="id")
     */
    private $order;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Product\Product")
     * @ORM\JoinColumn(name="fk_product", referencedColumnName="id")
     */
    private $product;

    /**
     * @var Float
     * @ORM\Column(name="total_amount", type="float", scale=2)
     */
    private $total;

    /**
     * @var ItemStatus
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Order\ItemStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="fk_sales_order_item_status_market", referencedColumnName="id")
     */
    private $statusMarket;

    /**
     * @var ItemStatus
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Order\ItemStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="fk_sales_order_item_status_seller", referencedColumnName="id")
     */
    private $statusSeller;

    /**
     * Market Item Id
     *
     * @var string
     * @ORM\Column(name="market_id", type="string", nullable=true)
     */
    private $marketId;

    /**
     * In-Memory Status for State-Machine verifications.
     *
     * @var String
     */
    private $stateMarket;

    /**
     * In-Memory Status for State-Machine verifications.
     *
     * @var String
     */
    private $stateSeller;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ItemStatusHistory", mappedBy="item", cascade={"persist"})
     */
    private $itemStatusHistory;

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
     * @var string
     *
     * @ORM\Column(name="additionals", type="text", nullable=true)
     */
    private $additionals;

    public function __construct()
    {
        $this->itemStatusHistory = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set order
     *
     * @param Order $order
     *
     * @return Item
     */
    public function setOrder(Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Infrastructure\Product\Product $product
     *
     * @return Item
     */
    public function setProduct(Product\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Infrastructure\Product\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Item
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return ItemStatus
     */
    public function getStatusMarket()
    {
        return $this->statusMarket;
    }

    /**
     * @param ItemStatus $statusMarket
     *
     * @return Item
     */
    public function setStatusMarket($statusMarket)
    {
        $this->statusMarket = $statusMarket;

        return $this;
    }

    /**
     * @return ItemStatus
     */
    public function getStatusSeller()
    {
        return $this->statusSeller;
    }

    /**
     * @param ItemStatus $statusSeller
     *
     * @return Item
     */
    public function setStatusSeller($statusSeller)
    {
        $this->statusSeller = $statusSeller;

        return $this;
    }

    /**
     * @return String
     */
    public function getStateMarket()
    {
        if (is_null($this->stateMarket)) {
            return $this->getStatusMarket()->getKeyName();
        }

        return $this->stateMarket;
    }

    /**
     * @param String $stateMarket
     */
    public function setStateMarket($stateMarket)
    {
        $this->stateMarket = $stateMarket;
    }

    /**
     * @param String $stateSeller
     */
    public function setStateSeller($stateSeller)
    {
        $this->stateSeller = $stateSeller;
    }

    /**
     * @return String
     */
    public function getStateSeller()
    {
        if (is_null($this->stateSeller)) {
            return $this->getStatusSeller()->getKeyName();
        }

        return $this->stateSeller;
    }

    /**
     * Add ItemStatusHistory
     *
     * @param ItemStatusHistory $itemStatusHistory
     * @return Item
     */
    public function addItemStatusHistory(ItemStatusHistory $itemStatusHistory)
    {
        $this->itemStatusHistory[] = $itemStatusHistory;

        return $this;
    }

    /**
     * Remove ItemStatusHistory
     *
     * @param ItemStatusHistory $itemStatusHistory
     */
    public function removeItemStatusHistory(ItemStatusHistory $itemStatusHistory)
    {
        $this->itemStatusHistory->removeElement($itemStatusHistory);
    }

    /**
     * Get ItemStatusHistory
     *
     * @return ArrayCollection
     */
    public function getItemStatusHistory()
    {
        return $this->itemStatusHistory;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getMarketId()
    {
        return $this->marketId;
    }

    /**
     * @param string $marketId
     */
    public function setMarketId($marketId)
    {
        $this->marketId = $marketId;
    }

    /**
     * @return string
     */
    public function getAdditionals($additionalType = null)
    {
        $additionals = $this->additionals;

        if ($additionalType) {
            $additionalsArray = json_decode($this->additionals, true);
            if (array_key_exists($additionalType, $additionalsArray)) {
                $additionals = $additionalsArray[$additionalType];
            }

        }

        return $additionals;
    }

    /**
     * @param string $additionals
     */
    public function setAdditionals($additionals)
    {
        $this->additionals = $additionals;
    }

    public function addAdditionals($index, $value)
    {
        $unserializedAdditionals = [];
        $additionals = $this->getAdditionals();
        if ($additionals) {
            $unserializedAdditionals = json_decode($additionals, true);
        }
        $unserializedAdditionals[$index] = $value;

        $this->setAdditionals(json_encode($unserializedAdditionals));
    }

    /**
     * @return bool
     */
    public function isCanceled()
    {
        return ItemStatus::isCanceledItem($this);
    }

    /**
     * @return bool
     */
    public function isCanceledByMarket()
    {
        return ItemStatus::isCanceledByMarket($this);
    }

    /**
     * @return bool
     */
    public function isCanceledBySeller()
    {
        return ItemStatus::isCanceledBySeller($this);
    }
}
