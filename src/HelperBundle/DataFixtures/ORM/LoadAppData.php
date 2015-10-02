<?php

namespace HelperBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Infrastructure\Core\User;
use AppBundle\Infrastructure\Core\UserRole;
use AppBundle\Infrastructure\Core\Market;
use AppBundle\Infrastructure\Core\Seller;
use AppBundle\Infrastructure\Order\ItemStatus;

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

    private $markets = ['Walmart', 'aliexpress', 'biso'];

    private $sellers = [
        'Aliexpress' => [
            'username' => 'fulano',
            'name' => 'Fulano de Tal',
            'password' => '123',
        ],
        'Biso' => [
            'username' => 'beltrano',
            'name' => 'Beltrano da Silva',
            'password' => '123',
        ],
        'Dafiti' => [
            'username' => 'sicrano',
            'name' => 'Sicrano Pereira',
            'password' => '123',
        ]
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

        foreach ($this->markets as $marketName) {
            $market = new Market();
            $market->setName($marketName);

            $manager->persist($market);
            $this->addReference($marketName, $market);
        }

        $role = new UserRole();
        $role->setName('ROLE_SYSTEM_ADMIN');
        $role->setDescription('Administrator');

        $manager->persist($role);

        foreach ($this->sellers as $sellerName => $userData) {
            $seller = new Seller();
            $seller->setName($sellerName);
            $seller->setAccessToken('123');

            $manager->persist($seller);
            $this->addReference($sellerName, $seller);

            $user = new User();
            $user->setUsername($userData['username']);
            $user->setName($userData['name']);
            $user->setSeller($seller);
            $plainPassword = $userData['password'];
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->addUserRole($role);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
