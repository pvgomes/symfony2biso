<?php

namespace AppBundle\Application\Api\v1\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JsonSchema\Uri\UriRetriever;
use AppBundle\Application\AppEvents;
use AppBundle\Application\Api\v1\Auth\TokenAuthentication;
use AppBundle\Application\Api\v1\Validator\JsonValidator;
use AppBundle\Application\Api\ApiController;
use AppBundle\Domain;

class ProductController extends ApiController implements TokenAuthentication
{
    use JsonValidator;

    /**
     * <strong>Parameters Headers</strong>:<br>
     * seller-access-token = hashCode<br>
     * seller-key = sellerKeyName<br>
     * Accept = application/json<br>
     * <strong>Body Request</strong>:<br>
     * <pre>{
     *     "status": "active|inactive",
     *     "name": "Product name",
     *     "description": "Product description",
     *     "price": 19.99,
     *     "special_price": 9.99,
     *     "quantity": 10
     * }</pre>
     *
     * Esse recurso aceita atualização parcial, caso precise atualizar apenas
     * um ou mais campos (não obrigatoriamente todos os campos). Exemplo:<br>
     * <pre>{
     *     "name": "Product name",
     *     "quantity": 10
     * }</pre>
     *
     * @Rest\Patch("/api/v1/product/{sku}")
     *
     * @ApiDoc(
     *  section="Product",
     *  description="Atualiza dados do Produto para todos os Parceiros",
     *  requirements={
     *      {
     *          "name"="sku",
     *          "dataType"="string",
     *          "description"="Sku do produto a ser atualizado"
     *      }
     *  },
     *  parameters={
     *      {
     *          "name"        = "status",
     *          "dataType"    = "string",
     *          "required"    = false,
     *          "description" = "status do produto (active|inactive)"
     *      },
     *      {
     *          "name"        = "name",
     *          "dataType"    = "string",
     *          "required"    = false,
     *          "description" = "nome do produto"
     *      },
     *      {
     *          "name"        = "description",
     *          "dataType"    = "string",
     *          "required"    = false,
     *          "description" = "descrição do produto"
     *      },
     *      {
     *          "name"        = "price",
     *          "dataType"    = "double",
     *          "required"    = false,
     *          "description" = "preço original do produto"
     *      },
     *      {
     *          "name"        = "special_price",
     *          "dataType"    = "double",
     *          "required"    = false,
     *          "description" = "preço de venda (exibido para o cliente)"
     *      },
     *      {
     *          "name"        = "quantity",
     *          "dataType"    = "integer",
     *          "required"    = false,
     *          "description" = "quantidade do produto para venda"
     *      }
     *  },
     *  statusCodes={
     *      204="Request completada com sucesso",
     *      400="Erro na requisição. Ex: Campo obrigatorio não enviado"
     *  },
     *  tags={
     *      "stable" = "#6BB06C"
     *  }
     * )
     */
    public function product($sku)
    {
        $request = $this->get('request');
        $jsonSchema = $this->productUpdateSchema();

        $this->resolveAction($request, $sku, $jsonSchema);
    }

    /**
     * <strong>Parameters Headers</strong>:<br>
     * seller-access-token = hashCode<br>
     * seller-key = sellerKeyName<br>
     * Accept = application/json<br>
     * <strong>Body Request</strong>:<br>
     * <pre>{
     *     "quantity": 1
     * }</pre>
     *
     * @Rest\Put("/api/v1/product/{sku}/stock")
     *
     * @ApiDoc(
     *  section="Product",
     *  description="Atualiza o estoque do Produto para todos os Parceiros",
     *  requirements={
     *      {
     *          "name"="sku",
     *          "dataType"="string",
     *          "description"="SKU do produto a ser atualizado"
     *      }
     *  },
     *  parameters={
     *    { "name"="quantity", "dataType"="integer", "required"=true }
     *  },
     *  statusCodes={
     *      204="Request completada com sucesso",
     *      400="Erro na requisição. Ex: Campo obrigatorio não enviado"
     *  },
     *  tags={
     *      "stable" = "#6BB06C"
     *  }
     * )
     */
    public function stock($sku)
    {
        $request = $this->get('request');
        $jsonSchema = $this->productStockUpdateSchema();

        $this->resolveAction($request, $sku, $jsonSchema);
    }

    /**
     * <strong>Parameters Headers</strong>:<br>
     * seller-access-token = hashCode<br>
     * seller-key = sellerKeyName<br>
     * Accept = application/json<br>
     * <strong>Body Request</strong>:<br>
     * <pre>{
     *     "price": 25.90,
     *     "special_price": 20.90
     * }</pre>
     *
     * @Rest\Put("/api/v1/product/{sku}/price")
     *
     * @ApiDoc(
     *  section="Product",
     *  description="Atualiza o preço do Produto para todos os Parceiros",
     *  requirements={
     *      {
     *          "name"="sku",
     *          "dataType"="string",
     *          "description"="SKU do produto a ser atualizado"
     *      }
     *  },
     *  parameters={
     *      {
     *          "name"        = "price",
     *          "dataType"    = "double",
     *          "required"    = true,
     *          "description" = "preço original do produto"
     *      },
     *      {
     *          "name"        = "special_price",
     *          "dataType"    = "double",
     *          "required"    = true,
     *          "description" = "preço de venda (exibido para o cliente)"
     *      }
     *  },
     *  statusCodes={
     *      204="Request completada com sucesso",
     *      400="Erro na requisição. Ex: Campo obrigatorio não enviado"
     *  },
     *  tags={
     *      "stable" = "#6BB06C"
     *  }
     * )
     */
    public function price($sku)
    {
        $request = $this->get('request');
        $jsonSchema = $this->productPriceUpdateSchema();

        $this->resolveAction($request, $sku, $jsonSchema);
    }

    private function resolveAction($request, $sku, $jsonSchema)
    {
        $sellerKey = $request->headers->get('seller-key');
        $productData = $request->getContent();

        try {
            if (!$this->isValidJson($jsonSchema, json_decode($productData))) {
                throw new HttpException(400, $this->getJsonErrors());
            }

            $productContext = new Domain\Product\ProductContext($sellerKey);
            $productContext->setEventName(AppEvents::VENTURE_UPDATE_PRODUCT);
            $productContext->setSku($sku);
            $productContext->setProductData($productData);
            $productContext->setAdditionalInfo(['request' => $request]);

            /** @var \AppBundle\Application\Product\ProductService $productService */
            $productService = $this->get('product_service');
            $productService->setContext($productContext);
            $productService->update();

        } catch (Domain\Exception\ProductNotFoundException $e) {
            throw new HttpException(404, $e->getMessage());

        } catch (\Exception $exception) {
            if ($exception instanceof HttpException) {
                throw $exception;
            }

            throw new HttpException(500, 'Could not update product');
        }
    }
}
