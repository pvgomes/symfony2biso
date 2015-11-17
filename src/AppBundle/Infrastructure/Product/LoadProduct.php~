<?php

namespace AppBundle\Infrastructure\Product;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Infrastructure\Core;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="load_product")
 * @ORM\Entity(repositoryClass="AppBundle\Infrastructure\Product\LoadProductRepository")
 */
class LoadProduct
{
    const FILE_NAME = '-load-product.csv';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Core\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\User")
     * @ORM\JoinColumn(name="fk_user", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Core\Seller
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Seller")
     * @ORM\JoinColumn(name="fk_seller", referencedColumnName="id", nullable=false)
     */
    private $seller;

    /**
     * @var Core\Market
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Infrastructure\Core\Market")
     * @ORM\JoinColumn(name="fk_market", referencedColumnName="id", nullable=false)
     */
    private $market;

    /**
     * @ORM\OneToMany(targetEntity="LoadProductReport", mappedBy="loadProduct", cascade={"persist"})
     */
    private $loadProductReports;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var int
     * @ORM\Column(name="republish", type="boolean")
     */
    private $republish;

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
     * @Assert\NotBlank(message="Selecione o arquivo .csv")
     * @Assert\File(maxSize="6000000", mimeTypes = {"text/plain"})
     */
    public $file;

    public function __construct()
    {
        $this->loadProductReports = new  ArrayCollection();
    }

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
     * Set user.
     *
     * @param Core\User $user
     *
     * @return LoadProduct
     */
    public function setUser(Core\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set seller.
     *
     * @param Core\Seller $seller
     *
     * @return LoadProduct
     */
    public function setSeller(Core\Seller $seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller.
     *
     * @return Core\Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set market.
     *
     * @param Core\Market $market
     *
     * @return LoadProduct
     */
    public function setMarket(Core\Market $market)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market.
     *
     * @return Core\Market
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Add loadProductReport.
     *
     * @param loadProductReport $loadProductReport
     *
     * @return LoadProduct
     */
    public function addLoadProductReport(loadProductReport $loadProductReport)
    {
        $this->loadProductReports[] = $loadProductReport;

        return $this;
    }

    /**
     * Remove loadProductReport.
     *
     * @param LoadProductReport $loadProductReport
     */
    public function removeLoadProductReport(LoadProductReport $loadProductReport)
    {
        $this->loadProductReports->removeElement($loadProductReport);
    }

    /**
     * Get loadProductReports.
     *
     * @return ArrayCollection
     */
    public function getLoadProductReports()
    {
        return $this->loadProductReports;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return LoadProduct
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
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return LoadProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getRepublish()
    {
        return $this->republish;
    }

    /**
     * @param int $republish
     */
    public function setRepublish($republish)
    {
        $this->republish = $republish;
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
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return null === $this->getId()
            ? null
            : $this->getId().self::FILE_NAME;
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->getPath()
            ? null
            : $this->getUploadRootDir().'/'.$this->getPath();
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../app/data/upload';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if ($this->getFile()) {
            $this->setName($this->getFile()->getClientOriginalName());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $fileName = $this->getId().self::FILE_NAME;
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $fileName
        );

        $this->file = null;
    }

    public static function create($name, Core\Seller $seller, Core\Market $market, Core\User $user)
    {
        $loadProduct = new static();
        $loadProduct->setName($name);
        $loadProduct->setUser($user);
        $loadProduct->setSeller($seller);
        $loadProduct->addMarket($market);
        return $loadProduct;
    }

    /**
     * @return ArrayCollection
     */
    public function getLoadProductSuccess()
    {
        $expression = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expression->eq('type', LoadProductReport::SUCCESS));
        /** @var \Doctrine\Common\Collections\ArrayCollection $matchResult */
        $matchResult = $this->loadProductReports->matching($criteria);

        return $matchResult;
    }

    /**
     * @return ArrayCollection
     */
    public function getLoadProductErrors()
    {
        $expression = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expression->eq('type', LoadProductReport::ERROR));
        /** @var \Doctrine\Common\Collections\ArrayCollection $matchResult */
        $matchResult = $this->loadProductReports->matching($criteria);

        return $matchResult;
    }
}
