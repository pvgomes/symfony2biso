<?php

namespace AppBundle\Infrastructure\Order;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Infrastructure\Core;

/**
 * @ORM\Table(name="sales_order")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Order\OrderRepository")
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * ExternalShop Order Number: acting as a common number between Seller and Market
     *
     * @var string
     *
     * @ORM\Column(name="order_number", type="string", nullable=true)
     */
    private $orderNumber;

    /**
     * Market Order Number
     *
     * @var string
     *
     * @ORM\Column(name="market_order_number", type="string", length=255)
     */
    private $marketOrderNumber;

    /**
     * Seller Order Number
     *
     * @var string
     * @ORM\Column(name="seller_order_number", type="string", nullable=true)
     */
    private $sellerOrderNumber;

    /**
     * @var Core\Seller;
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Seller")
     * @ORM\JoinColumn(name="fk_seller", referencedColumnName="id")
     */
    private $seller;

    /**
     * @var Core\Market;
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Market")
     * @ORM\JoinColumn(name="fk_market", referencedColumnName="id")
     */
    private $market;

    /**
     * @var Item
     * @ORM\OneToMany(targetEntity="Item", mappedBy="order", cascade={"persist"})
     */
    private $items;

    /**
     * @var float
     * @ORM\Column(name="total_amount", type="decimal", scale=2)
     */
    private $totalAmount;

    /**
     * @var float
     * @ORM\Column(name="freight_amount", type="decimal", scale=2)
     */
    private $freightAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_data", type="text")
     */
    private $rawData;

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
     * @var \AppBundle\Infrastructure\Order\Market\MarketDataMapper
     */
    private $dataMapper;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function __call($name, $arguments = [])
    {
        return $this->getDataMapper()->$name();
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
     * Get orderNumber
     *
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set marketOrderNumber
     *
     * @param string $marketOrderNumber
     *
     * @return Order
     */
    public function setMarketOrderNumber($marketOrderNumber)
    {
        $this->marketOrderNumber = $marketOrderNumber;

        return $this;
    }

    /**
     * Get marketOrderNumber
     *
     * @return string
     */
    public function getMarketOrderNumber()
    {
        return $this->marketOrderNumber;
    }

    /**
     * @return string
     */
    public function getSellerOrderNumber()
    {
        return $this->sellerOrderNumber;
    }

    /**
     * @param string $sellerOrderNumber
     *
     * @return Order
     */
    public function setSellerOrderNumber($sellerOrderNumber)
    {
        $this->sellerOrderNumber = $sellerOrderNumber;

        return $this;
    }


    /**
     * Set Seller
     *
     * @param Core\Seller $seller
     *
     * @return Order
     */
    public function setSeller(Core\Seller $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get Seller
     *
     * @return Core\Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set Market
     *
     * @param Core\Market $market
     *
     * @return Order
     */
    public function setMarket(Core\Market $market = null)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get Market
     *
     * @return Core\Market
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Add Item
     *
     * @param Item $item
     * @return Order
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove Item
     *
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set totalAmount
     *
     * @param float $totalAmount
     *
     * @return Order
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return float
     */
    public function getFreightAmount()
    {
        return $this->freightAmount;
    }

    /**
     * @param float $freightAmount
     *
     * @return Order
     */
    public function setFreightAmount($freightAmount)
    {
        $this->freightAmount = $freightAmount;

        return $this;
    }

    /**
     * Set rawData
     *
     * @param string $rawData
     *
     * @return Order
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;

        return $this;
    }

    /**
     * Get rawData
     *
     * @return string
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @param string|null $marketKey
     * @return Market\MarketDataMapper
     * @throws \Exception
     */
    public function getDataMapper($marketKey = null)
    {
        if (is_null($this->dataMapper)) {
            $marketKeyName = is_null($marketKey)
                ? $this->getMarket()->getKeyName()
                : $marketKey;
            $dataMapperClass = 'AppBundle\Infrastructure\Order\Market\\'
                . ucfirst($marketKeyName) . 'DataMapper';

            if (!class_exists($dataMapperClass)) {
                //@todo: implement log
                throw new \Exception("Data Mapper not implemented for $marketKeyName market");
            }

            $this->dataMapper = new $dataMapperClass($this);
        }

        return $this->dataMapper;
    }

    /**
     * @param int $orderId
     * @throws \Exception
     */
    public function generateOrderNumber($orderId)
    {
        if (! is_int($orderId)) {
            throw new \Exception('OrderId must be a integer: '. $orderId);
        }

        $sellerKey = $this->getSeller()->getKeyName();
        if (! $sellerKey) {
            throw new \Exception('Undefined Seller Key');
        }

        $number = $orderId;

        $validKeys = array('4', '2', '6', '9', '8', '7', '5', '1', '3');

        $result = '';
        $length = count($validKeys);

        while (floor($number) != 0) {
            $remainder = $number % $length;
            $result .= $validKeys[$remainder];
            $number = floor(($number - $remainder) / $length);
        }

        $sellerPrefix = strtoupper(substr(
            $this->getSeller()->getKeyName(), 0, 3)
        );

        $result = $sellerPrefix.'-'.str_pad($result, 10, '0', STR_PAD_LEFT);

        $this->orderNumber = $result;
    }

    /**
     * Verify if order is canceled by seller.
     * @return bool
     */
    public function alreadyCanceled()
    {
        /** @var Item $item */
        $item = $this->getItems()->first();
        return $item->isCanceled();
    }

    public function marketAlreadyConfirmed()
    {
        $result = false;

        $approvedStates = [
            ItemStatus::STATUS_PARTNER_CONFIRM_ORDER,
            ItemStatus::STATUS_PARTNER_SHIPPED_ORDER,
        ];

        $item = $this->getItems()->first();
        $currentItemStatus = $item->getStatusMarket()->getKeyName();
        if (in_array($currentItemStatus, $approvedStates)) {
            $result = true;
        }

        return $result;
    }


    public function marketAlreadyCanceled()
    {
        $result = true;

        foreach($this->getItems() as $item) {
            $status = $item->getStatusMarket()->getKeyName();
            if ($status != ItemStatus::STATUS_PARTNER_CANCEL_ORDER){
                $result = false;
            }
        }
        return $result;
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

    public function updateItemsByIds(array $ids)
    {
        $expression = Criteria::expr();
        $criteria = Criteria::create();

        $criteria->where($expression->in('id' , $ids));
        $matchResult = $this->items->matching($criteria);

        $this->items = $matchResult;

    }

    public function updateItemsMarketIds(array $marketIds)
    {
        $expression = Criteria::expr();
        $criteria = Criteria::create();

        $criteria->where($expression->in('marketId' , $marketIds));
        $matchResult = $this->items->matching($criteria);

        $this->items = $matchResult;

    }

    public function getShippedItems()
    {
        $shippedItems = [];
        $items = $this->getItems();
        foreach ($items as $item) {
            if ($item instanceof Item) {
                if (ItemStatus::isShippedBySeller($item)) {
                    $shippedItems[] = $item;
                }
            }
        }

        return $shippedItems;
    }

    public function getDeliveredItems()
    {
        $deliveredItems = [];
        $items = $this->getItems();
        foreach ($items as $item) {
            if ($item instanceof Item) {
                if (ItemStatus::isDeliveredBySeller($item)) {
                    $deliveredItems[] = $item;
                }
            }
        }

        return $deliveredItems;
    }

    public function addTracking($tracking)
    {
        /** @var Item $item */
        foreach ($this->items as $item) {
            $item->addAdditionals(Item::ADDITIONALS_TRACKING, $tracking);
        }
    }
}
