<?php

namespace HelperBundle\DataFixtures\ORM;

use AppBundle\Infrastructure\Product\Category;
use AppBundle\Infrastructure\Product\ExternalProduct;
use AppBundle\Infrastructure\Product\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Infrastructure\Core\Market;
use AppBundle\Infrastructure\Core\Seller;
use AppBundle\Infrastructure\Core\User;
use AppBundle\Infrastructure\Core\UserRole;
use AppBundle\Infrastructure\Order\ItemStatus;
use AppBundle\Infrastructure\Core\Configuration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * LoadAppData.
 */
class LoadAppData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    private $itemStatus = [
        ['market-create-order', 'Market criou pedido', 'Pedido criado no ExtShop pelo Market'],
        ['seller-create-waiting', 'Seller espera pedido', 'Pedido aguardando criação na Seller'],
        ['seller-create-processing', 'Seller processa pedido', 'Pedido aceito e em processo de avaliação para criação definitiva'],
        ['seller-create-order', 'Seller criou pedido', 'Pedido enviado para Seller e aguardando confirmação de pagamento pelo Market'],
        ['market-confirm-order', 'Market confirmou pedido', 'Pagamento aprovado pelo Market'],
        ['seller-confirm-order', 'Seller confirmou pedido', 'Pedido aprovado e enviado para a Seller'],
        ['seller-shipped-order', 'Seller enviou pedido', 'Seller notificou envio do pedido, aguardando notificação no parceiro'],
        ['market-shipped-order', 'Market enviou pedido', 'Pedido despachado pela Seller'],
        ['market-cancel-order', 'Market cancelou pedido', 'Pedido está na fila aguardando ser cancelado na Seller'],
        ['seller-cancel-order', 'Seller cancelou pedido', 'Pedido está na fila aguardando ser cancelado no Parceiro'],
        ['canceled', 'Cancelado', 'Pedido cancelado (Market ou Seller)'],
    ];

    private $markets = [
        'Kanui' => [
            'username' => 'fulano',
            'name' => 'Fulano de Tal',
            'password' => '123',
            'configuration' =>  [
                'kanui.base_url' => 'http://www.kanui.com.br',
                'kanui.repository_type' => 'redis',
                'kanui.webservice' => [
                    'api_key' => '123123',
                    'id_seller' => '77',
                    'base_url' => 'http://mockserver',
                    'uri_create_order' => '/kanui/order',
                    'uri_confirm_order' => '/kanui/order/confirm',
                    'uri_cancel_order' => '/kanui/order/cancel'
                ]
            ],
            'product' => [
                    'sku' => 'SKT317983',
                    'name' => 'SKATE ROXY VAGUE PRETO',
                    'weight' => '0.22',
                    'height' => '21',
                    'width' => '19',
                    'length' => '4',
                    'description' => 'Skate Roxy Vague preto, fabricado em madeira, com lixa emborracha',
                    'activated_at' => '2014-05-23 16:48:40',
                    'updated_at' => '2014-09-30 14:07:47',
                    'category' => 'games',
                    'brand' => 'sony',
                    'max_price' => '500.00',
                    'price' => '399,90',
                    'special_price' => '350,90',
                    'quantity' => 25,
                    'free_shipping_rule' => NULL,
                    'supplier_id' => '100',
                    'sprite' => 'sprite.jpg',
                    'link' => 'skate.html',
            ],
            'category' => [
                'categoryId' => '857',
                'categoryName' => 'Street',
                'categoryKeyName' => 'street',
            ]
        ],
        'Tricae' => [
            'username' => 'beltrano',
            'name' => 'Beltrano da Silva',
            'password' => '123',
            'configuration' =>  [
                'tricae.base_url' => 'http://www.tricae.com.br',
                'tricae.repository_type' => 'redis',
                'tricae.webservice' => [
                    'api_key' => '123123',
                    'id_seller' => '78',
                    'base_url' => 'http://mockserver',
                    'uri_create_order' => '/tricae/order',
                    'uri_confirm_order' => '/tricae/order/confirm',
                    'uri_cancel_order' => '/tricae/order/cancel'
                ]
            ],
            'product' => [
                'sku' => 'MTKBX2015',
                'name' => 'Mortal Kombat X',
                'weight' => '0.22',
                'height' => '21',
                'width' => '19',
                'length' => '4',
                'description' => 'Mortal Kombat',
                'activated_at' => '2014-05-23 16:48:40',
                'updated_at' => '2014-09-30 14:07:47',
                'brand' => 'sony',
                'max_price' => '99.99',
                'price' => '99.99',
                'special_price' => '85.99',
                'quantity' => 6,
                'sprite' => 'mortalx.jpg',
                'link' => 'mortalx.html',
            ],
            'category' => [
                'categoryId' => '12',
                'categoryName' => 'Games',
                'categoryKeyName' => 'games',
            ]
        ],
        'Dafiti' => [
            'username' => 'sicrano',
            'name' => 'Sicrano Pereira',
            'password' => '123',
            'configuration' =>  [
                'dafiti.base_url' => 'http://www.dafiti.com.br',
                'dafiti.repository_type' => 'redis',
                'dafiti.webservice' => [
                    'api_key' => '123123',
                    'id_seller' => '79',
                    'base_url' => 'http://mockserver',
                    'uri_create_order' => '/dafiti/order',
                    'uri_confirm_order' => '/dafiti/order/confirm',
                    'uri_cancel_order' => '/dafiti/order/cancel'
                ]
            ],
            'product' => [
                'sku' => 'SPTLUIZA245245',
                'name' => 'Sapato Luiza Barcelos',
                'weight' => '0.22',
                'height' => '21',
                'width' => '19',
                'length' => '4',
                'description' => 'SAPATO',
                'activated_at' => '2014-05-23 16:48:40',
                'updated_at' => '2014-09-30 14:07:47',
                'category' => 'games',
                'brand' => 'sony',
                'max_price' => '500.99',
                'price' => '485.99',
                'special_price' => '420.99',
                'quantity' => 6,
                'free_shipping_rule' => NULL,
                'supplier_id' => '100',
                'sprite' => 'sapatolb.jpg',
                'link' => 'sapatolb.html',
            ],
            'category' => [
                'categoryId' => '18',
                'categoryName' => 'Sapato',
                'categoryKeyName' => 'Sapato',
            ]
        ],
    ];

    private $sellers = [
        'Walmart',
        'Americanas',
        'Extra',
        'Shoptime'
    ];

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->itemStatus as $data) {
            $status = new ItemStatus();
            $status->setKeyName($data[0]);
            $status->setName($data[1]);
            $status->setDescription($data[2]);

            $manager->persist($status);
        }

        $sellersEntities = [];
        foreach ($this->sellers as $sellerName) {
            $seller = new Seller();
            $seller->setName($sellerName);
            $seller->setKeyName(strtolower($sellerName));
            $seller->setAccessToken('123');
            $manager->persist($seller);
            $this->addReference($sellerName, $seller);
            $sellersEntities[] = $seller;
            unset($seller);
        }

        $role = new UserRole();
        $role->setName('ROLE_SYSTEM_ADMIN');
        $role->setDescription('Administrator');

        $manager->persist($role);

        foreach ($this->markets as $marketName => $userData) {
            $market = new Market();
            $market->setName($marketName);
            $market->setKeyName(strtolower($marketName));
            $market->setAccessToken('456');

            $manager->persist($market);
            $this->addReference($marketName, $market);

            $user = new User();
            $user->setUsername($userData['username']);
            $user->setName($userData['name']);
            $user->setMarket($market);

            $plainPassword = $userData['password'];
            /** @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder $encoder */
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->addUserRole($role);

            $manager->persist($user);

            // create configuration
            foreach ($userData['configuration'] as $key => $value) {
                $configuration = new Configuration();
                $configuration->setMarket($market);
                $configuration->setKey($key);
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $configuration->setValue($value);
                $manager->persist($configuration);
            }

            // create market category
            $category = new Category();
            $category->setMarket($market);
            $category->setCategoryMarketId($userData['category']['categoryId']);
            $category->setName($userData['category']['categoryName']);
            $category->setNameKey($userData['category']['categoryKeyName']);
            $manager->persist($category);

            // create market product
            $product = new Product();
            $product->setCategory($category);
            $product->setName($userData['product']['name']);
            $product->setSku($userData['product']['sku']);
            $product->setPrice($userData['product']['price']);
            $product->setStock($userData['product']['quantity']);
            $product->setSpecialPrice($userData['product']['special_price']);
            $product->setProductAttributes(json_encode($userData['product']));
            $product->setMarket($market);
            $manager->persist($product);

            // create one external product for each seller
            foreach ($sellersEntities as $seller) {
                $externalProduct = new ExternalProduct();
                $externalProduct->setSku(uniqid());
                $externalProduct->setJson("{}");
                $externalProduct->setProduct($product);
                $externalProduct->setSeller($seller);
                $externalProduct->setStatus(ExternalProduct::STATUS_ACTIVE);
                $manager->persist($externalProduct);
            }



        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}
