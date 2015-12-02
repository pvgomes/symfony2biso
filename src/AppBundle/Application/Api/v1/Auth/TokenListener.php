<?php
namespace AppBundle\Application\Api\v1\Auth;

use Doctrine\ORM\EntityRepository;
use Domain\Core\MarketRepository;
use Domain\Core\SellerRepository;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TokenListener {

    /**
     * @var MarketRepository
     */
    private $marketRepository;

    /**
     * @var SellerRepository
     */
    private $sellerRepository;


    public function __construct(MarketRepository $marketRepository, SellerRepository $sellerRepository)
    {
        $this->marketRepository = $marketRepository;
        $this->sellerRepository = $sellerRepository;
    }


    public function onKernelResponse(FilterResponseEvent $event)
    {
        // check to see if onKernelController marked this as a token "auth'ed" request
        if (!$token = $event->getRequest()->attributes->get('auth_token')) {
            return;
        }

        $response = $event->getResponse();

        // create a hash and set it as a response header
        $hash = sha1($response->getContent().$token);
        $response->headers->set('X-CONTENT-HASH', $hash);
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthentication) {
            $token = $event->getRequest()->headers->get('access-token');
            $key   = $event->getRequest()->headers->get('key');

            try {
                $this->auth($key, $token);
            } catch(AccessDeniedHttpException $exception) {
                throw new AccessDeniedHttpException($exception->getMessage());
            } catch(\Exception $exception) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            }

        }
    }

    private function auth($key, $token)
    {
        if (!$this->marketRepository->byKeyNameAndToken($key, $token) &&
            !$this->sellerRepository->byKeyNameAndToken($key, $token)) {
            throw new AccessDeniedHttpException('This action needs a valid token!');
        }
    }

}
