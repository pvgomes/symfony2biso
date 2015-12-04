<?php

namespace AppBundle\Application\Controller\Web;

use AppBundle\Application\Core\ConfigurationQuery;
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
     * @Route("/system/configuration", name="configuration_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function configurationAction(Request $request)
    {
        /** @var \AppBundle\Application\CommandBus\CommandBus $commandBus */
        $commandBus = $this->get("command_bus");
        /** @var \Domain\Core\ConfigurationRepository $configurationRepository */
        $configurationRepository = $this->get("configuration_repository");
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('key', 'text')
            ->add('value', 'textarea')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            try {
                $createConfigurationCommand = new CreateConfigurationCommand($this->getUser()->getMarket()->getKeyName(), $data['key'], $data['value']);
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
        $viewVars['configurations'] = $configurationRepository->getByMarket($this->getUser()->getMarket());

        return $this->render('web/system/configuration.html.twig', $viewVars);
    }

    /**
     * @Route("/system/configuration-update", name="update_key", condition="request.isXmlHttpRequest()")
     */
    public function configurationUpdateAction(Request $request)
    {
        $isUpdated = true;

        try {
            $commandBus = $this->get("command_bus");
            $key = $request->get('key');
            $value = $request->get('value');

            $createConfigurationCommand = new CreateConfigurationCommand($this->getUser()->getMarket(), $key, $value);
            $commandBus->execute($createConfigurationCommand);

        } catch (\Exception $e) {
            $isUpdated = false;
        }

        return new JsonResponse(['isUpdated' => $isUpdated]);
    }

    /**
     * @Route("/system/account", name="my_account")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accountAction(Request $request)
    {
        /** @var \Domain\Core\User $user */
        $user = $this->getUser();

        $form = $this->createForm(new UserForm(), $user);
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
}
