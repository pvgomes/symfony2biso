<?php

namespace AppBundle\Application\Api\v1\Controller\Market;

use AppBundle\Application\AppEvents;
use AppBundle\Domain\Order\OrderContext;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JsonSchema\Uri\UriRetriever;
use AppBundle\Application\Api\v1\Auth\TokenAuthentication;
use AppBundle\Application\Api\v1\Validator\JsonValidator;
use AppBundle\Application\Api\ApiController;
use AppBundle\Domain\Exception\InvalidOrderException;

class OrderController extends ApiController implements TokenAuthentication
{
    use JsonValidator;

    /**
     * <strong>Parameters Headers</strong>:<br>
     * market-access-token = hashCode<br>
     * market-key = marketKeyName<br>
     * <strong>Body Request</strong>:<br>
     * <pre>{
     *     "notification": "message with the reason for cancellation"
     * }</pre>
     *
     * @Rest\Put("/api/v1/order/{marketOrderNumber}/cancel")
     *
     * @ApiDoc(
     *  section = "Order",
     *  description = "Venture notifica cancelamento de um pedido",
     *  requirements = {
     *      {
     *          "name" = "marketOrderNumber",
     *          "dataType" = "string",
     *          "description" = "Número do pedido a ser cancelado"
     *      }
     *  },
     *  parameters = {
     *      {
     *          "name"        = "notification",
     *          "dataType"    = "string",
     *          "required"    = true,
     *          "description" = "Mensagem com o motivo do cancelamento"
     *      }
     *  },
     *  statusCodes = {
     *      204 = "Request com sucesso",
     *      400 = "Erro na requisição. Ex: Campo obrigatorio não enviado"
     *  },
     *  tags = {
     *      "stable" = "#6BB06C"
     *  },
     *  views = { "default", "market" }
     * )
     */
    public function cancel($marketOrderNumber)
    {
        $request = $this->get('request');
        $marketKey = $request->headers->get('market-key');
        $requestContent = json_decode($request->getContent());
        $jsonResponse = new JsonResponse();
        $contentError['requestId'] = $this->requestId;

        try {
            if (!$this->isValidJson($this->orderCancelSchema(), $requestContent)) {
                throw new HttpException(400, $this->getJsonErrors());
            }
            /** @var \AppBundle\Application\Order\OrderService $serviceOrder */
            $serviceOrder = $this->get('order_service');
            $orderContext = new OrderContext($marketKey, null);
            $orderContext->setEventName(AppEvents::VENTURE_CANCEL_ORDER);
            $orderContext->setOrderNumberType(OrderContext::ORDER_NUMBER_TYPE_VENTURE);
            $orderContext->setOrderNumber($marketOrderNumber);
            $orderContext->addAdditionalInfo(['notification' => $requestContent->notification]);
            $orderContext->addAdditionalInfo(['request' => $request]);
            $orderContext->addAdditionalInfo(['request_id' => $this->requestId]);
            $serviceOrder->setContext($orderContext);
            $serviceOrder->setRequestId($this->requestId);
            $serviceOrder->cancel();
            $jsonResponse->setStatusCode(204);

        } catch (HttpException $httpException) {
            $contentError['description'] = $httpException->getMessage();
            $jsonResponse->setStatusCode(400);
            $jsonResponse->setData($contentError);

            $logger = $this->get('logger');
            $logContext = [
                'exception_code' => $httpException->getCode(),
                'exception_message' => $httpException->getMessage(),
                'exception_trace' => $httpException->getTraceAsString(),
            ];
            $message = "Venture ($marketKey) cannot cancel Order #$marketOrderNumber: Invalid JSON format";
            $logger->error($message, $logContext);

        } catch (InvalidOrderException $invalidOrderException) {
            $contentError['description'] = $invalidOrderException->getMessage();
            $jsonResponse->setStatusCode(400);
            $jsonResponse->setData($contentError);

        } catch (\Exception $exception) {
            $contentError['description'] = $exception->getMessage();
            $jsonResponse->setStatusCode(500);
            $jsonResponse->setData($contentError);
        }

        return $jsonResponse;
    }

    /**
     * <strong>Parameters Headers</strong>:<br>
     * market-access-token = hashCode<br>
     * market-key = marketKeyName<br>
     * <strong>Body Request</strong>:<br>
     * <pre>{
     *     "notification": "message with details of ship"
     * }</pre>
     *
     * @Rest\Put("/api/v1/order/{marketOrderNumber}/ship")
     *
     * @ApiDoc(
     *  section = "Order",
     *  description = "Notifica o envio de um pedido",
     *  requirements = {
     *      {
     *          "name" = "marketOrderNumber",
     *          "dataType" = "string",
     *          "description" = "Número do pedido enviado"
     *      }
     *  },
     *  parameters = {
     *      {
     *          "name"        = "notification",
     *          "dataType"    = "string",
     *          "required"    = true,
     *          "description" = "Mensagem contendo detalhes do envio"
     *      }
     *  },
     *  statusCodes = {
     *      204 = "Request com sucesso",
     *      400 = "Erro na requisição. Ex: Campo obrigatorio não enviado"
     *  },
     *  tags = {
     *      "stable" = "#6BB06C"
     *  },
     *  views = { "default", "market" }
     * )
     */
    public function ship($marketOrderNumber)
    {
        $request = $this->get('request');
        $marketKey = $request->headers->get('market-key');
        $orderData = $request->getContent();
        $requestContent = json_decode($orderData);
        $jsonResponse = new JsonResponse();
        $contentError['requestId'] = $this->requestId;

        try {
            if (!$this->isValidJson($this->orderShipSchema(), $requestContent)) {
                throw new HttpException(400, $this->getJsonErrors());
            }

            /** @var \AppBundle\Application\Order\OrderService $serviceOrder */
            $serviceOrder = $this->get('order_service');
            $orderContext = new OrderContext($marketKey, null);
            $orderContext->setEventName(AppEvents::VENTURE_SHIP_ORDER);
            $orderContext->setOrderNumberType(OrderContext::ORDER_NUMBER_TYPE_VENTURE);
            $orderContext->setOrderNumber($marketOrderNumber);
            $orderContext->setOrderData($orderData);
            $orderContext->addAdditionalInfo(['notification' => $requestContent->notification]);
            $orderContext->addAdditionalInfo(['request' => $request]);
            $serviceOrder->setContext($orderContext);
            $serviceOrder->setRequestId($this->requestId);
            $serviceOrder->ship();

            $jsonResponse->setStatusCode(204);

        } catch (HttpException $httpException) {
            $contentError['description'] = $httpException->getMessage();
            $jsonResponse->setStatusCode(400);
            $jsonResponse->setData($contentError);

            $logger = $this->get('logger');
            $logContext = [
                'exception_code' => $httpException->getCode(),
                'exception_message' => $httpException->getMessage(),
                'exception_trace' => $httpException->getTraceAsString(),
                'event' => AppEvents::VENTURE_SHIP_ORDER,
                'request' => $request
            ];
            $message = "Venture ($marketKey) cannot ship Order #$marketOrderNumber: Invalid JSON format";
            $logger->error($message, $logContext);

        } catch (InvalidOrderException $invalidOrderException) {
            $contentError['description'] = $invalidOrderException->getMessage();
            $jsonResponse->setStatusCode(400);
            $jsonResponse->setData($contentError);

        } catch (\Exception $exception) {
            $contentError['description'] = $exception->getMessage();
            $jsonResponse->setStatusCode(500);
            $jsonResponse->setData($contentError);
        }

        return $jsonResponse;
    }

    /**
     * <strong>Parameters Headers</strong>:<br>
     * market-access-token = hashCode<br>
     * market-key = marketKeyName<br>
     * <strong>Body Request</strong>:<br>
     * <pre>{
     *     "notification": "message with details of deliver"
     * }</pre>
     *
     * @Rest\Put("/api/v1/order/{marketOrderNumber}/deliver")
     *
     * @ApiDoc(
     *  section = "Order",
     *  description = "Notifica a entrega de um pedido",
     *  requirements = {
     *      {
     *          "name" = "marketOrderNumber",
     *          "dataType" = "string",
     *          "description" = "Número do pedido enviado"
     *      }
     *  },
     *  parameters = {
     *      {
     *          "name"        = "notification",
     *          "dataType"    = "string",
     *          "required"    = true,
     *          "description" = "Mensagem contendo detalhes da entrega"
     *      }
     *  },
     *  statusCodes = {
     *      204 = "Request com sucesso",
     *      400 = "Erro na requisição. Ex: Campo obrigatorio não enviado"
     *  },
     *  tags = {
     *      "stable" = "#6BB06C"
     *  }
     * )
     */
    public function deliver($marketOrderNumber)
    {
        $request = $this->get('request');
        $marketKey = $request->headers->get('market-key');
        $orderData = $request->getContent();
        $requestContent = json_decode($orderData);
        $jsonResponse = new JsonResponse();
        $contentError['requestId'] = $this->requestId;

        try {
            if (!$this->isValidJson($this->orderShipSchema(), $requestContent)) {
                throw new HttpException(400, $this->getJsonErrors());
            }

            /** @var \AppBundle\Application\Order\OrderService $serviceOrder */
            $serviceOrder = $this->get('order_service');
            $orderContext = new OrderContext($marketKey, null);
            $orderContext->setEventName(AppEvents::VENTURE_DELIVER_ORDER);
            $orderContext->setOrderNumberType(OrderContext::ORDER_NUMBER_TYPE_VENTURE);
            $orderContext->setOrderNumber($marketOrderNumber);
            $orderContext->setOrderData($orderData);
            $orderContext->setAdditionalInfo(['notification' => $requestContent->notification]);
            $serviceOrder->setContext($orderContext);
            $serviceOrder->setRequestId($this->requestId);
            $serviceOrder->deliver();

            $jsonResponse->setStatusCode(204);

        } catch (HttpException $httpException) {
            $contentError['description'] = $httpException->getMessage();
            $jsonResponse->setStatusCode(400);
            $jsonResponse->setData($contentError);

            $logger = $this->get('logger');
            $logContext = [
                'exception_code' => $httpException->getCode(),
                'exception_message' => $httpException->getMessage(),
                'exception_trace' => $httpException->getTraceAsString(),
            ];
            $message = "Venture ($marketKey) cannot ship Order #$marketOrderNumber: Invalid JSON format";
            $logger->error($message, $logContext);

        } catch (InvalidOrderException $invalidOrderException) {
            $contentError['description'] = $invalidOrderException->getMessage();
            $jsonResponse->setStatusCode(400);
            $jsonResponse->setData($contentError);

        } catch (\Exception $exception) {
            $contentError['description'] = $exception->getMessage();
            $jsonResponse->setStatusCode(500);
            $jsonResponse->setData($contentError);
        }

        return $jsonResponse;
    }
}
