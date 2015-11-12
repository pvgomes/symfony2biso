<?php

namespace AppBundle\Application\Controller\Web;

use AppBundle\Application\Core\CreateConfigurationCommand;
use AppBundle\Application\Core\CreateMarketCommand;
use AppBundle\Infrastructure\Core;
use AppBundle\Application\Core\UserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Application\Controller\Pagination;
use AppBundle\Infrastructure\Product;

class SystemController extends Controller
{

    /**
     * @Route("/system/market", name="market_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function marketAction(Request $request)
    {
        /** @var \AppBundle\Application\CommandBus\CommandBus $commandBus */
        $commandBus = $this->get('command_bus');

        $market = new Core\Market();
        $form = $this->createFormBuilder($market)
            ->add('name', 'text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $createMarketCommand = new CreateMarketCommand($market);
                $commandBus->execute($createMarketCommand);
                $this->addFlash('success', 'Mercado criado com sucesso');

            } catch (\Exception $exception) {
                $this->addFlash('alert', $exception->getMessage());
            }
        }

        $viewVars['form'] = $form->createView();
        $viewVars['markets'] = [];

        return $this->render('web/system/market.html.twig', $viewVars);
    }

    /**
     * @Route("/system/seller", name="seller_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sellerAction(Request $request)
    {
        $repositorySeller = $this->get('seller_repository');

        $seller = new Core\Seller();
        $form = $this->createFormBuilder($seller)
            ->add('name', 'text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accessToken = strtoupper(sha1($seller->getKeyName().uniqid('',true)));
            $seller->setAccessToken($accessToken);

            try {
                $repositorySeller->add($seller);
                $this->addFlash('success', 'Seller criada com sucesso');

            } catch (\Exception $exception) {
                $this->addFlash('alert', $exception->getMessage());
            }
        }

        $sellers = $repositorySeller->getAll();
        $viewVars['form'] = $form->createView();
        $viewVars['sellers'] = $sellers;

        return $this->render('web/system/seller.html.twig', $viewVars);
    }

    /**
     * @Route("/system/user", name="user_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAction(Request $request)
    {
        $repositoryUser = $this->get('user_repository');

        $user = new Core\User();
        $form = $this->createForm(new UserForm(), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            try {
                $repositoryUser->add($user);
                $this->addFlash('success', 'Usuário criado com sucesso');

            } catch (\Exception $exception) {
                $this->addFlash('alert', $exception->getMessage());
            }
        }

        $users = $repositoryUser->getAll();

        $viewVars['users'] = $users;
        $viewVars['form'] = $form->createView();

        return $this->render('web/system/user.html.twig', $viewVars);
    }


    /**
     * @Route("/system/user/edit/{id}", name="user_edit")
     * @param Request $request
     * @param $id
     * @internal param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, $id)
    {
        $userRepository =  $this->get('user_repository');
        $user = $userRepository->get($id);

        $form = $this->createForm(new UserForm(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            try {
                $userRepository->add($user);
                $this->addFlash('success', 'Informações do usuário atualizadas com sucesso');

            } catch (\Exception $exception) {
                $this->addFlash('alert', $exception->getMessage());
            }
        }

        $viewVars['user'] = $user;
        $viewVars['form'] = $form->createView();

        return $this->render('web/system/user_edit.html.twig', $viewVars);
    }


    /**
     * @Route("/system/configuration", name="configuration_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function configurationAction(Request $request)
    {
        /** @var \AppBundle\Application\CommandBus\CommandBus $commandBus */
        $commandBus = $this->get("command_bus");

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('key', 'text')
            ->add('value', 'textarea')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            try {
                $createConfigurationCommand = new CreateConfigurationCommand($this->getUser()->getMarket(), $data['key'], $data['value']);
                $commandBus->execute($createConfigurationCommand);
                $flashMsg = "Chave gravada.";
                $flashMsgType = "success";
            } catch (\DomainException $e) {
                $flashMsg = $e->getMessage();
                $flashMsgType = "warning";
            } catch (\Exception $e) {
                $flashMsg = "Erro ao inserir a chave de configuração.";
                $flashMsgType = "warning";
            }

            $this->addFlash($flashMsgType , $flashMsg);
        }

        $viewVars['form'] = $form->createView();
        $viewVars['configurations'] = [];

        return $this->render('web/system/configuration.html.twig', $viewVars);
    }


    /**
     * @Route("/system/account", name="my_account")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accountAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new UserForm(), $user);
        $form->remove('userRole');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);

                $userRepository =  $this->get('user_repository');
                $userRepository->add($user);
                $this->addFlash('success', 'Informações de conta atualizadas');

            } catch (\Exception $exception) {
                $this->addFlash('alert', $exception->getMessage());
            }
        }

        $viewVars['form'] = $form->createView();
        $viewVars['user'] = $user;

        return $this->render('web/system/user_edit.html.twig', $viewVars);
    }

    /**
     * @Route("/system/removekey", name="remove_key", condition="request.isXmlHttpRequest()")
     */
    public function removekeyAction(Request $request)
    {
        $isRemoved = true;

        try {
            $key = $request->get('key');
            /**
             * @var \Predis\Client $redisClient;
             */
            $redisClient = $this->get('redis.client');
            $redisClient->del([$key]);
        } catch (\Exception $e) {
            $isRemoved = false;
        }

        return new JsonResponse(['isRemoved' => $isRemoved]);
    }

    /**
     * @Route("/system/updatekey", name="update_key", condition="request.isXmlHttpRequest()")
     */
    public function updatekeyAction(Request $request)
    {
        $isUpdated = true;

        try {
            $key = $request->get('key');
            $value = $request->get('value');
            /**
             * @var \Predis\Client $redisClient;
             */
            $redisClient = $this->get('redis.client');
            $redisClient->set($key, $value);
        } catch (\Exception $e) {
            $isUpdated = false;
        }

        return new JsonResponse(['isUpdated' => $isUpdated]);
    }

    /**
     * @Route("/system/productcache", name="product_cache", condition="request.isXmlHttpRequest()")
     */
    public function productcacheAction(Request $request)
    {
        $isProductsCached = true;

        try {
            /** @var \AppBundle\Infrastructure\Core\User $user */
            $user = $this->getUser();
            /** @var \AppBundle\Infrastructure\Core\Seller $seller */
            $seller = $user->getSeller();
            /** @var \AppBundle\Infrastructure\Core\ConfigurationService $configurationService */
            $configurationService = $this->get('configuration_service');
            $configurationService->warmUpProductCache($seller);
        } catch (\Exception $e) {
            $isProductsCached = false;
        }

        return new JsonResponse(['isProductsCached' => $isProductsCached]);
    }

}
