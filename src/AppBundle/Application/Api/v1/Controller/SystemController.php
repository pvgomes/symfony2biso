<?php

namespace AppBundle\Application\Api\v1\Controller;

use AppBundle\Application\AppEvents;
use AppBundle\Application\Core\CreateConfigurationCommand;
use AppBundle\Domain\Order\OrderContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JsonSchema\Uri\UriRetriever;
use AppBundle\Application\Api\v1\Auth\TokenAuthentication;
use AppBundle\Application\Api\v1\Validator\JsonValidator;
use AppBundle\Application\Api\ApiController;

class SystemController extends ApiController implements TokenAuthentication
{
    use JsonValidator;


    /**
     * <strong>Parameters Headers</strong>:<br>
     * access-token = hashCode<br>
     * key = keyName<br>
     * <strong>Request body</strong>:<br>
     * <pre>{
     *     "key": "keyName",
     *     "value": "value"
     * }</pre>
     *
     * @Rest\Post("/api/v1/configuration/")
     *
     * @ApiDoc(
     *  section = "System",
     *  description = "Create and Market Configuration",
     *  parameters = {
     *      {
     *          "name"        = "key",
     *          "dataType"    = "string",
     *          "required"    = true,
     *          "description" = "key name configuration"
     *      },
     *      {
     *          "name"        = "value",
     *          "dataType"    = "string",
     *          "required"    = true,
     *          "description" = "value configuration"
     *      }
     *  },
     *  statusCodes = {
     *      204 = "Configuration created with success",
     *      400 = "Error on request. Ex: Required fields not sent",
     *      500 = "Error server, try again later"
     *  },
     *  tags = {
     *      "stable" = "#6BB06C"
     *  },
     *  views = { "default", "market" }
     * )
     */
    public function configurationCreate()
    {
        $request = $this->get('request');
        $marketKey = $request->headers->get('key');
        $requestContent = json_decode($request->getContent());
        $jsonResponse = new JsonResponse();
        $contentError['requestId'] = $this->requestId;
        $commandBus = $this->get("command_bus");

        try {
            if (!$this->isValidJson($this->loadConfigurationCreateSchema(), $requestContent)) {
                throw new HttpException(400, $this->getJsonErrors());
            }

            $createConfigurationCommand = new CreateConfigurationCommand($marketKey, $requestContent->key, $requestContent->value);
            $commandBus->execute($createConfigurationCommand);
            $jsonResponse->setStatusCode(204);
        } catch (\DomainException $exception) {
            $contentError['description'] = $exception->getMessage();
            $jsonResponse->setStatusCode(400);
            $jsonResponse->setData($contentError);
        } catch (\Exception $exception) {
            $contentError['description'] = "Cagou :(";
            $jsonResponse->setStatusCode(500);
            $jsonResponse->setData($contentError);
        }

        return $jsonResponse;
    }


    /**
     * <strong>Parameters Headers</strong>:<br>
     * access-token = hashCode<br>
     * key = keyName<br>
     *
     * @Rest\Get("/api/v1/ping")
     *
     * @ApiDoc(
     *  section="System",
     *  description="Tests the service availability",
     *  statusCodes={
     *      200="Available service",
     *      400="Unauthorized service",
     *      500="Unavailable service"
     *  },
     *  tags={
     *      "stable" = "#6BB06C"
     *  },
     * views = { "default", "seller", "market"}
     * )
     */
    public function ping()
    {
        $response = new Response(json_encode(['message' => 'pong']));
        return $response;
    }
}
