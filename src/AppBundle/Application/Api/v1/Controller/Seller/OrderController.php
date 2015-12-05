<?php

namespace AppBundle\Application\Api\v1\Controller\Seller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Application\Order\CreateOrderCommand;
use AppBundle\Application\Exception\InvalidJsonFormat;
use AppBundle\Application\Exception\InvalidOrderException;
use AppBundle\Application\Api\ApiController;
use AppBundle\Application\Api\v1\Validator\JsonValidator;

use Domain;

class OrderController extends ApiController
{
    use JsonValidator;

    /**
     * <strong>Parameters Headers</strong>:<br>
     * access-token = hashCode<br>
     * key = sellerKeyName<br>
     * <pre>{
     *   "orderId" : "MKT-1000121920192",
     *   "items" : [ {
     *     "id" : "ABC3534411",
     *     "quantity" : 1,
     *     "price" : 12.99,
     *     "commission" : 10,
     *     "freightCommission" : 2
     *   }, {
     *     "id" : "ABC3534412",
     *     "quantity" : 1,
     *     "price" : 16.99,
     *     "commission" : 10,
     *     "freightCommission" : 2
     *   } ],
     *   "clientProfileData" : {
     *     "email" : "basilio.jafet@palaciodoscedros.com.br",
     *     "firstName" : "Basílio Jafet Pereira",
     *     "lastName" : "Silva",
     *     "document" : "1234567890",
     *     "phone" : "1234567890",
     *     "corporateName" : "Nami Jafet e Irmãos",
     *     "tradeName" : "Nami Jafet e Irmãos",
     *     "corporateDocument" : "12345678901234567890",
     *     "stateInscription" : "20000516122345",
     *     "corporatePhone" : "1257890",
     *     "isCorporate" : true
     *   },
     *   "shippingData" : {
     *     "address" : {
     *       "addressId" : "Minha Casa",
     *       "addressType" : "residential",
     *       "receiverName" : "Adma Jafet",
     *       "postalCode" : "03424-101",
     *       "city" : "São Paulo",
     *       "state" : "SP",
     *       "country" : "BRA",
     *       "street" : "Rua Bom Pastor",
     *       "number" : "800",
     *       "neighborhood" : "Ipiranga",
     *       "complement" : "complement:69",
     *       "reference" : "Próximo ao Museu do Ipiranga"
     *     },
     *     "logisticsInfo" : [ {
     *       "item" : 0,
     *       "shippingType" : "Normal",
     *       "shippingLockTTL" : "2d",
     *       "shippingEstimate" : "5d",
     *       "price" : 10.99,
     *       "deliveryWindow" : null
     *     }, {
     *       "item" : 1,
     *       "shippingType" : "Normal",
     *       "shippingLockTTL" : "2d",
     *       "shippingEstimate" : "5d",
     *       "price" : 10.99,
     *       "deliveryWindow" : null
     *     } ]
     *   }
     * }</pre>
     *
     * @Rest\Post ("/api/v1/{marketKey}/{sellerKey}/order/create")
     *
     * @ApiDoc(
     *  section="Order",
     *  description="Endpoint responsible for receiving seller orders",
     *  requirements={
     *      {
     *          "name"="sellerKey",
     *          "dataType"="string",
     *          "description"="seller key name. Example: walmart, extra, shoptime"
     *      },
     *      {
     *          "name"="sellerToken",
     *          "dataType"="string",
     *          "description"="seller hash token"
     *      },
     *      {
     *          "name"="marketKey",
     *          "dataType"="string",
     *          "description"="market key name. Example: tricae, kanui, dafiti"
     *      },
     *  },
     *  statusCodes={
     *      200="Order successfully received",
     *      400="Error in the request, the validation rule is not equal with the market. Seller cancel the order immediately",
     *      500="Internal Server Error. Seller add the request in the queue to re-attempt"
     *  },
     *  tags={
     *      "stable" = "#6BB06C"
     *  },
     *  views = { "default", "seller" }
     * )
     */
    public function createOrderAction($sellerKey, $marketKey)
    {
        $request = $this->get('request');
        $orderData = $request->getContent();
        $incomingOrder = json_decode($orderData);
        $jsonResponse = new JsonResponse();

        try {
            /** @var \AppBundle\Application\CommandBus\CommandBus $commandBus */
            $commandBus = $this->get("command_bus");

            if (! $this->isValidJson($this->loadOrderCreateSellerSchema(), $incomingOrder)) {
                throw new InvalidJsonFormat(400, $this->getJsonErrors());
            }

            $createOrderCommand = new CreateOrderCommand($marketKey, $sellerKey, json_decode($orderData, true), Domain\Order\Events::SELLER_CREATE_ORDER);
            $orderNumber = $commandBus->execute($createOrderCommand);

            $jsonResponse->setData(['orderId' => $orderNumber]);

        } catch (InvalidJsonFormat $jsonException) {
            $jsonResponse->setData($jsonException->getMessage());
            $jsonResponse->setStatusCode(400);
        } catch (InvalidOrderException $invalidOrderException) {
            $jsonResponse->setData($invalidOrderException->getMessage());
            $jsonResponse->setStatusCode(400);
        } catch (\DomainException $domainException) {
            $jsonResponse->setData($domainException->getMessage());
            $jsonResponse->setStatusCode(400);
        } catch (\Exception $exception) {
            $jsonResponse->setData('Try again');
            $jsonResponse->setStatusCode(500);
        }

        return $jsonResponse;
    }

    /**
     * <strong>Body Request</strong>:<br>
     * <pre>
     *  { "orderId" : "EXTSHOP-123456" }
     * </pre>
     *
     * @Rest\Post ("/api/v1/seller/{marketKey}/fulfillment/order/confirm/{orderNumber}")
     *
     * @ApiDoc(
     *  section="Seller",
     *  description="Endpoint responsável por receber confirmação de pedidos do parceiro Seller",
     *  requirements={
     *      {
     *          "name"="marketKey",
     *          "dataType"="string",
     *          "description"="Nome da market. Exemplo: tricae, kanui"
     *      },
     *      {
     *          "name"="orderNumber",
     *          "dataType"="string",
     *          "description"="Numero do pedido no ExternalShop. Exemplo: TRI-0123456789"
     *      }
     *  },
     *  statusCodes={
     *      200="Confirmação recebida com sucesso",
     *      400="Erro na requisição, a regra de validação é de acordo com a market. Seller cancela o pedido imediatamente",
     *      500="Erro interno do servidor. Seller adicionará o pedido na fila de re-tentativa"
     *  },
     *  tags={
     *      "stable" = "#6BB06C"
     *  },
     *  views = { "default", "seller" }
     * )
     */
    public function confirmOrderAction($marketKey, $orderNumber)
    {
        $request = $this->get('request');
        $orderData = $request->getContent();
        $externalShopOrderNumber = json_decode($orderData)->orderId;

        $errorContent = ['requestId' => $this->requestId];
        $jsonResponse = new JsonResponse();

        try {
            $orderContext = new OrderContext($marketKey, self::PARTNER_KEY);
            $orderContext->setEventName(AppEvents::PARTNER_CONFIRM_ORDER);
            $orderContext->setOrderNumberType(OrderContext::ORDER_NUMBER_TYPE_EXTERNALSHOP);
            $orderContext->setOrderNumber($externalShopOrderNumber);
            $orderContext->setOrderData($orderData);
            $orderContext->addAdditionalInfo(['request' => $request]);

            /** @var \AppBundle\Application\Order\OrderService $orderService */
            $orderService = $this->get('order_service');
            $orderService->setContext($orderContext);
            $orderService->setRequestId($this->requestId);
            $externalShopOrderNumber = $orderService->confirm();

            $jsonResponse->setData(['orderId' => $externalShopOrderNumber]);

        } catch (InvalidOrderException $invalidOrderException) {
            $errorContent['summary'] = $invalidOrderException->getMessage();
            $jsonResponse->setData($errorContent);
            $jsonResponse->setStatusCode(400);

        } catch (\Exception $exception) {
            $errorContent['summary'] = "Could not confirm Order number: $orderNumber [internal error]";
            $jsonResponse->setData($errorContent);
            $jsonResponse->setStatusCode(500);
        }

        return $jsonResponse;
    }

    /**
     * Consulta de Frete e Estoque por itens
     *
     * @Rest\Post ("/api/v1/seller/{marketKey}/fulfillment-preview")

     * @ApiDoc(
     * section="Seller",
     * views = { "default", "seller" }
     * )
     */
    public function previewOrderAction($marketKey)
    {
        $request = $this->get('request');
        $content = $request->getContent();
        $jsonResponse = new JsonResponse();
        $errorContent = ['requestId' => $this->requestId];
        /** @var \AppBundle\Application\Order\OrderService $orderService */
        $orderService = $this->get('order_service');
        $orderService->setRequestId($this->requestId);

        if ($this->isValidJson($this->loadOrderPreviewSellerSchema(), json_decode($content))) {

            try {
                $orderPreview = $orderService->simulate($content, $marketKey, self::PARTNER_KEY);
                $jsonResponse->setContent($orderPreview);

            } catch (\Exception $e) {
                $errorContent['summary'] = 'Could not generate order preview';
                $jsonResponse->setData($errorContent);
                $jsonResponse->setStatusCode(500);
            }

        } else {
            $logContext['request'] = $request;
            $logContext['requestId'] = $this->requestId;
            $logContext['event'] = AppEvents::PARTNER_ORDER_PREVIEW_ERROR;
            $this->get('logger')->error(sprintf("Invalid format json: %s", $this->getJsonErrors()), $logContext);
            $errorContent['summary'] = $this->getJsonErrors();
            $jsonResponse->setData($errorContent);
            $jsonResponse->setStatusCode(500);
        }

        return $jsonResponse;
    }

    /**
     * <strong>Body Request</strong>:<br>
     * <pre>
     * { "orderId" : "EXTSHOP-123456" }
     * </pre>
     *
     * @Rest\Post ("/api/v1/seller/{marketKey}/fulfillment/order/cancel/{marketplaceOrderId}")
     *
     * @ApiDoc (
     * section="Seller",
     * description="Realiza o cancelamento da Order",
     * requirements={
     *      {
     *          "name"="marketKey",
     *          "type"="string",
     *          "description"="Nome da market. Exemplo: tricae, kanui"
     *      },
     *      {
     *          "name"="marketplaceOrderId",
     *          "dataType"="string",
     *          "description"="Numero do pedido no ExternalShop. Exemplo: EXTSHOP-123456"
     *      }
     * },
     * statusCodes={
     *      200="Pedido de cancelamento recebido com sucesso",
     *      400="Erro na requisição, a regra de validação é de acordo com a market. Seller cancela o pedido imediatamente",
     *      500="Erro interno do servidor. Seller adicionará o pedido na fila de re-tentativa"
     *  },
     * tags={
     *      "stable" = "#6BB06C"
     *  },
     * views = { "default", "seller" }
     * )
     */
    public function cancelOrderAction($marketKey, $marketplaceOrderId)
    {
        $errorContent = ['requestId' => $this->requestId];
        $request  = $this->get('request');
        $jsonResponse = new JsonResponse();
        $content = json_decode($request->getContent());
        $orderNumber = $content->orderId;

        try {
            /** @var \AppBundle\Application\Order\OrderService $orderService */
            $orderService = $this->get('order_service');
            $orderContext = new OrderContext($marketKey, self::PARTNER_KEY);
            $orderContext->setEventName(AppEvents::PARTNER_CANCEL_ORDER);
            $orderContext->setOrderNumberType(OrderContext::ORDER_NUMBER_TYPE_EXTERNALSHOP);
            $orderContext->setOrderNumber($orderNumber);
            $orderContext->addAdditionalInfo(['request' => $request]);
            $orderService->setContext($orderContext);
            $orderCanceled = $orderService->cancel();
            $jsonResponse->setData(['orderId' => $orderCanceled]);

        } catch (InvalidOrderException $invalidOrderException) {
            $errorContent['summary'] = $invalidOrderException->getMessage();
            $jsonResponse->setData($errorContent);
            $jsonResponse->setStatusCode(400);

        } catch (\Exception $exception) {
            $errorContent['summary'] = 'Could not cancel order, internal error. Try again';
            $jsonResponse->setData($errorContent);
            $jsonResponse->setStatusCode(500);
        }

        return $jsonResponse;
    }

}
