<?php

namespace AppBundle\Infrastructure\Order;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="sales_order_item_status_history")
 * @ORM\Entity
 */
class ItemStatusHistory
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
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="itemStatusHistory")
     * @ORM\JoinColumn(name="fk_item", referencedColumnName="id")
     */
    private $item;

    /**
     * @var ItemStatus
     *
     * @ORM\ManyToOne(targetEntity="ItemStatus")
     * @ORM\JoinColumn(name="fk_sales_order_item_status_market", referencedColumnName="id")
     */
    private $statusMarket;

    /**
     * @var ItemStatus
     *
     * @ORM\ManyToOne(targetEntity="ItemStatus")
     * @ORM\JoinColumn(name="fk_sales_order_item_status_seller", referencedColumnName="id")
     */
    private $statusSeller;

    /**
     * @var string
     *
     * @ORM\Column(name="event_name", type="string", length=255, nullable=true)
     */
    private $eventName;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     * @return ItemStatus
     */
    public function setItem(Item $item)
    {
        $this->item = $item;

        return $this;
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
     * @return ItemStatus
     */
    public function setStatusMarket(ItemStatus $statusMarket)
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
     * @return ItemStatus
     */
    public function setStatusSeller(ItemStatus $statusSeller)
    {
        $this->statusSeller = $statusSeller;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     * @return ItemStatus
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
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
}
