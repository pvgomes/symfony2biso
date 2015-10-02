<?php

namespace AppBundle\Infrastructure\Order;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="sales_order_item_status")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Order\ItemStatusRepository")
 * @UniqueEntity("keyName")
 */
class ItemStatus
{
    const STATUS_PARTNER_CREATE_ORDER = 'market-create-order';
    const STATUS_VENTURE_CREATE_WAITING = 'seller-create-waiting';
    const STATUS_VENTURE_CREATE_PROCESSING = 'seller-create-processing';
    const STATUS_VENTURE_CREATE_ORDER = 'seller-create-order';
    const STATUS_PARTNER_CONFIRM_ORDER = 'market-confirm-order';
    const STATUS_VENTURE_CONFIRM_ORDER = 'seller-confirm-order';
    const STATUS_PARTNER_SHIPPED_ORDER = 'market-shipped-order';
    const STATUS_VENTURE_SHIPPED_ORDER = 'seller-shipped-order';
    const STATUS_PARTNER_DELIVERED_ORDER = 'market-delivered-order';
    const STATUS_VENTURE_DELIVERED_ORDER = 'seller-delivered-order';
    const STATUS_VENTURE_DELIVERED_FAIL_ORDER = 'seller-delivered-fail-order';
    const STATUS_PARTNER_CANCEL_ORDER = 'market-cancel-order';
    const STATUS_VENTURE_CANCEL_ORDER = 'seller-cancel-order';
    const STATUS_CANCELED = 'canceled';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\Choice(callback = "listStatus", message = "Choose a valid status.")
     * @ORM\Column(name="key_name", type="string", length=30, unique=true)
     */
    private $keyName;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $description;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * @param string $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return array
     */
    public static function listStatus()
    {
        return [
            self::STATUS_PARTNER_CREATE_ORDER      => self::STATUS_PARTNER_CREATE_ORDER,
            self::STATUS_VENTURE_CREATE_WAITING    => self::STATUS_VENTURE_CREATE_WAITING,
            self::STATUS_VENTURE_CREATE_PROCESSING => self::STATUS_VENTURE_CREATE_PROCESSING,
            self::STATUS_VENTURE_CREATE_ORDER      => self::STATUS_VENTURE_CREATE_ORDER,
            self::STATUS_PARTNER_CONFIRM_ORDER     => self::STATUS_PARTNER_CONFIRM_ORDER,
            self::STATUS_VENTURE_CONFIRM_ORDER     => self::STATUS_VENTURE_CONFIRM_ORDER,
            self::STATUS_PARTNER_SHIPPED_ORDER     => self::STATUS_PARTNER_SHIPPED_ORDER,
            self::STATUS_VENTURE_SHIPPED_ORDER     => self::STATUS_VENTURE_SHIPPED_ORDER,
            self::STATUS_PARTNER_CANCEL_ORDER      => self::STATUS_PARTNER_CANCEL_ORDER,
            self::STATUS_VENTURE_CANCEL_ORDER      => self::STATUS_VENTURE_CANCEL_ORDER,
            self::STATUS_CANCELED                  => self::STATUS_CANCELED,
            self::STATUS_PARTNER_DELIVERED_ORDER   => self::STATUS_PARTNER_DELIVERED_ORDER,
            self::STATUS_VENTURE_DELIVERED_ORDER   => self::STATUS_VENTURE_DELIVERED_ORDER,
            self::STATUS_VENTURE_DELIVERED_FAIL_ORDER  => self::STATUS_VENTURE_DELIVERED_FAIL_ORDER,
        ];
    }

    /**
     * Check if item is canceled.
     * @param Item $item
     * @return bool
     */
    public static function isCanceledItem(Item $item)
    {
        return static::isCanceledBySeller($item) || static::isCanceledByMarket($item);
    }

    /**
     * Check if item is canceled by market.
     * @param Item $item
     * @return bool
     */
    public static function isCanceledByMarket(Item $item)
    {
        $canceledStatus = [
            static::STATUS_CANCELED,
            static::STATUS_PARTNER_CANCEL_ORDER,
        ];
        return in_array($item->getStatusMarket()->getKeyName(), $canceledStatus);
    }

    /**
     * Check if item is canceled by market.
     * @param Item $item
     * @return bool
     */
    public static function isCanceledBySeller(Item $item)
    {
        $canceledStatus = [
            static::STATUS_CANCELED,
            static::STATUS_VENTURE_CANCEL_ORDER,
        ];
        return in_array($item->getStatusSeller()->getKeyName(), $canceledStatus);
    }

    public static function isShippedBySeller(Item $item)
    {
        $shippedStatus = [
            static::STATUS_VENTURE_SHIPPED_ORDER,
        ];
        return in_array($item->getStatusSeller()->getKeyName(), $shippedStatus);
    }


    public static function isDeliveredBySeller(Item $item)
    {
        $deliveredStatus = [
            static::STATUS_VENTURE_DELIVERED_ORDER,
        ];
        return in_array($item->getStatusSeller()->getKeyName(), $deliveredStatus);
    }
}
