<?php

namespace HelperBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Domain\Product\Product;
use AppBundle\Domain\Product\ExternalProduct;
use AppBundle\Domain\Product\Category;

/**
 * LoadAppData.
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    private function getRawData()
    {
        $rawVentureProduct1 = [
        'meta' =>
            [
                'sku' => 'HA032TO39BOQTRI',
                'id_catalog_config' => '71360',
                'attribute_set_id' => '1',
                'name' => 'Pião Beyblade BBT Berserker Behemoth Hasbro',
                'weight' => '0.22',
                'height' => '21',
                'width' => '19',
                'length' => '4',
                'attribute_config_shipment_type_name' => 'Próprio',
                'attribute_config_shipment_type_id' => '1',
                'description' => '<h2> O Pião Beyblade BBT Berserker Behemoth Hasbro vai proporcionar momentos de pura adrenalina aos meninos! </h2><p></p><p><br></p><ul><strong>Principais Características:</strong><p></p><p></p><li>- Indicado para crianças acima de 8 anos; </li><li>- Certificado pelo INMETRO; </li><li>- Garantia contra defeito de fabricação; </li><li>- Fabricado em plástico resistente; </li><li>- Contém: 2 piões <i>Shogun Steel</i> de 5 partes, 2 propulsores, 2 cordas propulsoras, 2 chaves de montagem e 2 <i>cards</i> para batalhas online.</li></ul><br>Neste conjunto com 2 piões <strong>Beyblade Shogun Steel</strong>, os baixinhos poderão travar as mais emocionantes batalhas. Com tecnologia <i>Synchrome</i>, é possível personalizar e colecionar diversos modelos. <br><br>',
                'activated_at' => '2014-05-23 16:48:40',
                'updated_at' => '2014-09-30 14:07:47',
                'grouped_products' => 'HA032TO39BOQTRI',
                'categories' => '2##|##667##|##934##|##960##|##1543##|##1549##|##1560',
                'brand' => 'Hasbro',
                'merchandising' => 'Beyblade',
                'config_shipment_type' => 'Próprio',
                'config_shipment_type_position' => '0',
                'inbound_shipment_type' => 'Somente Fornecedor (CIF)',
                'inbound_shipment_type_position' => '0',
                'inbound_ipi' => '10.00',
                'inbound_st' => '73.22',
                'inbound_icms' => '18.00',
                'no_discounts_apply' => '1',
                'gender' => 'Masculino',
                'gender_position' => '0',
                'status_comercial' => 'Descontinuado',
                'status_comercial_position' => '2',
                'age_from' => '8',
                'age_from_position' => '8',
                'age_to' => '9',
                'age_to_position' => '8',
                'vpc' => '4.00',
                'is_gift' => '1',
                'has_free_gift' => '0',
                'free_gift_sku' => 'NULL',
                'main_category' => '960',
                'config_id' => '71360',
                'max_price' => '99.99',
                'price' => '99.99',
                'max_original_price' => NULL,
                'original_price' => NULL,
                'max_special_price' => NULL,
                'special_price' => NULL,
                'max_saving_percentage' => NULL,
                'special_price_comparison' => '0.00',
                'quantity' => 6,
                'free_shipping_rule' => NULL,
            ],
        'attributes' =>
            [
                'product_warranty' => '3 Meses',
                'description' => '<h2> O Pião Beyblade BBT Berserker Behemoth Hasbro vai proporcionar momentos de pura adrenalina aos meninos! </h2><p></p><p><br></p><ul><strong>Principais Características:</strong><p></p><p></p><li>- Indicado para crianças acima de 8 anos; </li><li>- Certificado pelo INMETRO; </li><li>- Garantia contra defeito de fabricação; </li><li>- Fabricado em plástico resistente; </li><li>- Contém: 2 piões <i>Shogun Steel</i> de 5 partes, 2 propulsores, 2 cordas propulsoras, 2 chaves de montagem e 2 <i>cards</i> para batalhas online.</li></ul><br>Neste conjunto com 2 piões <strong>Beyblade Shogun Steel</strong>, os baixinhos poderão travar as mais emocionantes batalhas. Com tecnologia <i>Synchrome</i>, é possível personalizar e colecionar diversos modelos. <br><br>',
                'product_contents' => '2 piões Shogun Steel de 5 partes, 2 propulsores, 2 cordas propulsoras, 2 chaves de montagem e 2 cards para batalhas online.',
                'short_description' => 'Contém 2 piões e diversos acessórios para os pequenos criarem eletrizantes batalhas.   INMETRO   CE-BRI/IQB-5128',
                'number_of_players' => '1',
                'certifications' => 'INMETRO   CE-BRI/IQB-5128',
                'manufacturer_age' => '8+',
                'carac_especial' => 'Com o conjunto de 2 piões de Beyblade Shogun Steel você poderá travar uma batalha incrível! Com a tecnologia Synchrome é possível personalizar seu pião combinando dois anéis metálicos de diferentes piões.',
                'customer_service_phone' => '11 4005-1093',
                'customer_service_email' => 'atendimento@tricae.com.br',
            ],
        'supplier_id' => '100',
        'simples' =>
            [
                'HA032TO39BOQTRI-237547' =>
                    [
                        'meta' =>
                            [
                                'sku' => 'HA032TO39BOQTRI-237547',
                                'id_catalog_simple' => '237547',
                                'price' => '99.99',
                                'barcode_ean' => '5010994788667',
                                'quantity' => '6',
                                'cif_cost' => '55.25',
                                'super_attribute' => 'Único',
                                'transport_type' => 'Freight',
                                'transport_type_position' => '1',
                                'special_price_comparison' => '0.00',
                                'supplier_code' => 'A2481',
                            ],
                        'attributes' =>
                            [
                            ],
                    ],
            ],
        'images' =>
            [
                0 =>
                    [
                        'image' => '1',
                        'main' => '1',
                        'original_filename' => 'HA032TO39BOQTRI_1.jpg',
                        'name' => 'Pião Beyblade BBT Berserker Behemoth Hasbro',
                        'sku' => 'HA032TO39BOQTRI',
                        'url' => '/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Hasbro-3750-06317-1',
                        'path' => 'product/06/317/1.jpg',
                        'sprite' => '/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Hasbro-3750-06317-sprite.jpg',
                    ],
                1 =>
                    [
                        'image' => '2',
                        'main' => '0',
                        'original_filename' => 'HA032TO39BOQTRI_2.jpg',
                        'name' => 'Pião Beyblade BBT Berserker Behemoth Hasbro',
                        'sku' => 'HA032TO39BOQTRI',
                        'url' => '/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Hasbro-3751-06317-2',
                        'path' => 'product/06/317/2.jpg',
                    ],
            ],
        'image' => '/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Hasbro-3750-06317-1',
        'sprite' => '/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Hasbro-3750-06317-sprite.jpg',
        'link' => 'piao-beyblade-bbt-berserker-behemoth-hasbro-71360.html',
    ];

        $rawVentureProduct2 = [
            'meta' =>
                [
                    'sku' => 'DI017AC36EGD',
                    'id_catalog_config' => '2863',
                    'attribute_set_id' => '4',
                    'name' => 'Cadeirinha de Descanso Musical Frutinhas Divertidas  Dican',
                    'bundle' => '0',
                    'weight' => '2.27',
                    'height' => '9',
                    'width' => '35',
                    'length' => '50',
                    'attribute_config_shipment_type_name' => 'Próprio',
                    'attribute_config_shipment_type_id' => '1',
                    'description' => '<h2> A Cadeirinha de Descanso Musical Frutinhas Divertidas Dican vai proporcionar segurança e diversão para o seu filho</h2>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="20">
<tbody><tr>
<td height="200" width="100%" valign="top">
<img src=" http://static.tricae.com.br/cms/guia_imagens/background/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-1.jpg" align="left" style="margin-right:10px;margin-bottom:10px;">
<p style="padding:0px 20px 20px 0px"> A fase das descobertas é talvez a mais gostosa e prazerosa na vida de um bebê. Pensando nisso, a <strong>Dican</strong> produziu a Cadeirinha de Descanso Musical Frutinhas Divertidas Dican, um acessório perfeito para o aprendizado do pequeno. Fabricada em tecido macio e suave, inclui 3 brinquedos pendurados para entreter e divertir o baixinho de forma leve e educativa. Não vai ter nada tão divertido no dia a dia do menino com esta cadeirinha!
</p></td>
</tr>
<tr>
<td height="26" valign="top"><h2>Características</h2></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6968-3682-2.jpg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;">
<h3> Segurança garantida </h3>
<p style="padding:0px 20px 20px 0px"> Em qualquer produto voltado para os bebês, a segurança deve ser redobrada. A <strong>Dican</strong> sabe muito bem disso e desenvolveu esta cadeirinha com o maior cuidado possível. O item inclui cinto com 3 pontos de retenção, com capacidade máxima indicada para uma criança de 11 kg, além de certificação do INMETRO, que dá a certeza aos pais de estarem adquirindo um produto de qualidade para o filhote.  </p></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/111629223_2GG.jpg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;"><h3> Diversão garantida </h3>
<p style="padding:0px 20px 20px 0px"> Com o intuito de levar mais alegria para o baixinho, vem com 3 brinquedos pendurados com formatos, tamanhos e texturas diferentes, para que ele tente alcançá-los, pegá-los e levá-los à boca. Além disso, possui vibrações relaxantes e músicas tranquilas que vão acalmar e entreter o pequeno enquanto ele descansa. É sensacional! </p></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/cadeirinha-Dican-Musical-Frutinhas-Divertidas-3552-Colorida-brinquedos.jpeg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;"><h3> Conforto?  Com toda a certeza! </h3>
<p style="padding:0px 20px 20px 0px"> Revestido em tecido macio e superaconchegante com frutinhas estampadas, o produto vai levar todo o conforto e bem-estar que o seu filho merece. Acolchoada, vem com capota removível que pode ser ajustada e adaptada de acordo com o tamanho da criança, podendo ser usada tanto como cadeirinha fixa, como cadeira de balanço. Diversão e comodidade na rotina do baixinho. Não é o máximo? </p></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/DICAN-LOGO.jpg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;"> <h3> Qualidade Dican </h3>
<p style="padding:0px 20px 20px 0px"> Criada em 2003, a marca tem o intuito de oferecer brinquedos para a primeira linha de faixa etária em todo o país. Sempre com produtos previamente testados e com certificado pelo INMETRO, o objetivo principal da marca é fazer parte do dia a dia das crianças com itens de qualidade e durabilidade diferenciados. E com a cadeirinha, não é diferente!
</p></td>
</tr>
</tbody></table>
',
                    'activated_at' => '2011-11-03 15:37:36',
                    'updated_at' => '2014-10-01 15:11:33',
                    'grouped_products' => 'DI017AC37EGC|DI017AC36EGD',
                    'categories' => '2##|##667##|##1021##|##1061##|##1102##|##1529##|##1535',
                    'brand' => 'Dican',
                    'ratings_total' => '4.48387',
                    'ratings_single' => '(3:4.0000],',
                    'config_shipment_type' => 'Próprio',
                    'config_shipment_type_position' => '0',
                    'supplier_delivery_time' => '5',
                    'inbound_shipment_type' => 'Somente Fornecedor (CIF)',
                    'inbound_shipment_type_position' => '0',
                    'minimum_delivery_time' => '2',
                    'inbound_ipi' => '4.00',
                    'inbound_st' => '0.00',
                    'inbound_icms' => '4.00',
                    'color_family' => 'Verde',
                    'no_discounts_apply' => '0',
                    'gender' => 'Masculino/Feminino',
                    'gender_position' => '2',
                    'acc_characteristics' => 'Cadeirinha Musical Suporta até 11 Kg, Vibração  Que Relaxa o  Bebê e Cinto  com Três Pontos  de  Retenção.',
                    'status_comercial' => 'Ativo',
                    'status_comercial_position' => '0',
                    'naoenvia_buscape' => '0',
                    'brinde' => '0',
                    'vpc' => '0,00',
                    'is_gift' => '0',
                    'naoexibe_catalogo' => '0',
                    'has_free_gift' => '0',
                    'main_category' => '1061',
                    'config_id' => '2863',
                    'max_price' => '249.00',
                    'price' => '249.00',
                    'max_original_price' => NULL,
                    'original_price' => NULL,
                    'max_special_price' => '161.90',
                    'special_price' => '161.90',
                    'max_saving_percentage' => '35',
                    'special_price_comparison' => '157.90',
                    'quantity' => 17,
                    'free_shipping_rule' =>
                        [
                            'state' =>
                                [
                                    'SP' => 'São Paulo',
                                ],
                            'capital' =>
                                [
                                    'ES' => 'Espírito Santo',
                                    'MG' => 'Minas Gerais',
                                    'RJ' => 'Rio de Janeiro',
                                    'GO' => 'Goiás',
                                    'PR' => 'Paraná',
                                    'RS' => 'Rio Grande do Sul',
                                    'SC' => 'Santa Catarina',
                                ],
                            'every' => false,
                        ],
                ],
            'attributes' =>
                [
                    'product_warranty' => '3 Meses',
                    'description' => '<h2> A Cadeirinha de Descanso Musical Frutinhas Divertidas Dican vai proporcionar segurança e diversão para o seu filho</h2>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="20">
<tbody><tr>
<td height="200" width="100%" valign="top">
<img src=" http://static.tricae.com.br/cms/guia_imagens/background/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-1.jpg" align="left" style="margin-right:10px;margin-bottom:10px;">
<p style="padding:0px 20px 20px 0px"> A fase das descobertas é talvez a mais gostosa e prazerosa na vida de um bebê. Pensando nisso, a <strong>Dican</strong> produziu a Cadeirinha de Descanso Musical Frutinhas Divertidas Dican, um acessório perfeito para o aprendizado do pequeno. Fabricada em tecido macio e suave, inclui 3 brinquedos pendurados para entreter e divertir o baixinho de forma leve e educativa. Não vai ter nada tão divertido no dia a dia do menino com esta cadeirinha!
</p></td>
</tr>
<tr>
<td height="26" valign="top"><h2>Características</h2></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6968-3682-2.jpg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;">
<h3> Segurança garantida </h3>
<p style="padding:0px 20px 20px 0px"> Em qualquer produto voltado para os bebês, a segurança deve ser redobrada. A <strong>Dican</strong> sabe muito bem disso e desenvolveu esta cadeirinha com o maior cuidado possível. O item inclui cinto com 3 pontos de retenção, com capacidade máxima indicada para uma criança de 11 kg, além de certificação do INMETRO, que dá a certeza aos pais de estarem adquirindo um produto de qualidade para o filhote.  </p></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/111629223_2GG.jpg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;"><h3> Diversão garantida </h3>
<p style="padding:0px 20px 20px 0px"> Com o intuito de levar mais alegria para o baixinho, vem com 3 brinquedos pendurados com formatos, tamanhos e texturas diferentes, para que ele tente alcançá-los, pegá-los e levá-los à boca. Além disso, possui vibrações relaxantes e músicas tranquilas que vão acalmar e entreter o pequeno enquanto ele descansa. É sensacional! </p></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/cadeirinha-Dican-Musical-Frutinhas-Divertidas-3552-Colorida-brinquedos.jpeg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;"><h3> Conforto?  Com toda a certeza! </h3>
<p style="padding:0px 20px 20px 0px"> Revestido em tecido macio e superaconchegante com frutinhas estampadas, o produto vai levar todo o conforto e bem-estar que o seu filho merece. Acolchoada, vem com capota removível que pode ser ajustada e adaptada de acordo com o tamanho da criança, podendo ser usada tanto como cadeirinha fixa, como cadeira de balanço. Diversão e comodidade na rotina do baixinho. Não é o máximo? </p></td>
</tr>
<tr>
<td height="200" valign="top"><img src=" http://static.tricae.com.br/cms/guia_imagens/background/DICAN-LOGO.jpg" width="200" height="200" align="left" style="margin-right:10px;margin-bottom:10px;"> <h3> Qualidade Dican </h3>
<p style="padding:0px 20px 20px 0px"> Criada em 2003, a marca tem o intuito de oferecer brinquedos para a primeira linha de faixa etária em todo o país. Sempre com produtos previamente testados e com certificado pelo INMETRO, o objetivo principal da marca é fazer parte do dia a dia das crianças com itens de qualidade e durabilidade diferenciados. E com a cadeirinha, não é diferente!
</p></td>
</tr>
</tbody></table>
',
                    'product_contents' => '1 Cadeirinha Musical',
                    'product_weight' => '1.000',
                    'short_description' => 'Cadeirinha Musical com Capota Retrátil, Suporta até 11 Kg, vibração que relaxa o bebê, cinto com três pontos de retenção,  toca músicas com controle de volume.',
                    'certifications_ace' => 'INMETRO',
                    'product_dimensions' => '45 x 70 x 70',
                ],
            'supplier_id' => '74',
            'simples' =>
                [
                    'DI017AC36EGD-6459' =>
                        [
                            'meta' =>
                                [
                                    'sku' => 'DI017AC36EGD-6459',
                                    'id_catalog_simple' => '6459',
                                    'price' => '249.00',
                                    'barcode_ean' => '7899097936520',
                                    'special_price' => '161.90',
                                    'quantity' => '17',
                                    'cif_cost' => '102.40',
                                    'super_attribute' => 'not defined',
                                    'special_price_comparison' => '157.90',
                                    'supplier_code' => '3652',
                                ],
                            'attributes' =>
                                [
                                ],
                        ],
                ],
            'images' =>
                [
                    0 =>
                        [
                            'image' => '1',
                            'main' => '1',
                            'original_filename' => 'DI017AC36EGD_1.jpg',
                            'name' => 'Cadeirinha de Descanso Musical Frutinhas Divertidas  Dican',
                            'sku' => 'DI017AC36EGD',
                            'url' => '/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-1',
                            'path' => 'product/36/82/1.jpg',
                            'sprite' => '/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-sprite.jpg',
                        ],
                    1 =>
                        [
                            'image' => '2',
                            'main' => '0',
                            'original_filename' => 'DI017AC36EGD_2.jpg',
                            'name' => 'Cadeirinha de Descanso Musical Frutinhas Divertidas  Dican',
                            'sku' => 'DI017AC36EGD',
                            'url' => '/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6968-3682-2',
                            'path' => 'product/36/82/2.jpg',
                        ],
                ],
            'image' => '/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-1',
            'sprite' => '/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-sprite.jpg',
            'link' => 'cadeirinha-de-descanso-musical-frutinhas-divertidas-dican-2863.html',
        ];

        $rawPartnerProduct1 = <<< EOD
{
    "id": 3499903,
    "name": "Pião Beyblade BBT Berserker Behemoth Up Hasbro",
    "skuName": " cor não informada",
    "description": " O Pião Beyblade BBT Berserker Behemoth Hasbro vai proporcionar momentos de pura adrenalina aos meninos! <br>Principais Características:- Indicado para crianças acima de 8 anos; - Certificado pelo INMETRO; - Garantia contra defeito de fabricação; - Fabricado em plástico resistente; - Contém: 2 piões Shogun Steel de 5 partes, 2 propulsores, 2 cordas propulsoras, 2 chaves de montagem e 2 cards para batalhas online.<br>Neste conjunto com 2 piões Beyblade Shogun Steel, os baixinhos poderão travar as mais emocionantes batalhas. Com tecnologia Synchrome, é possível personalizar e colecionar diversos modelos. <br><br>",
    "active": true,
    "possuiImagem": true,
    "price": 79.99,
    "priceDiscount": 79.99,
    "quantity": 99999,
    "ean": "5010994788667",
    "urlImage": "http://static.tricae.com.br/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Up-Hasbro-3750-06317-1-product.jpg",
    "url": "http://www.tricae.com.br/piao-beyblade-bbt-berserker-behemoth-up-hasbro-71360.html",
    "sellerId": 554,
    "height": 21,
    "weight": 0.22,
    "length": 4,
    "width": 19,
    "brand": "Hasbro",
    "integrationStatus": "Nova Oferta",
    "blocked": false,
    "sellerSKU": "HA032TO39BOQTRI-237547",
    "category": {
        "id": 137294,
        "name": "Lançadores, Piões e Batalhas",
        "active": true,
        "id_category": 65,
        "parent": null,
        "sellerId": null
    },
    "images": [
        {
            "name": "image-1",
            "url": "http://static.tricae.com.br/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Up-Hasbro-3750-06317-1.jpg"
        },
        {
            "name": "image-2",
            "url": "http://static.tricae.com.br/p/Hasbro-PiC3A3o-Beyblade-BBT-Berserker-Behemoth-Up-Hasbro-3751-06317-2.jpg"
        }
    ],
    "specifications": [
        {
            "name": "product_warranty",
            "value": "3 Meses"
        },
        {
            "name": "description",
            "value": "<h2> O Pião Beyblade BBT Berserker Behemoth Hasbro vai proporcionar momentos de pura adrenalina aos meninos! </h2><p></p><p><br></p><ul><strong>Principais Características:</strong><p></p><p></p><li>- Indicado para crianças acima de 8 anos; </li><l"
        },
        {
            "name": "product_contents",
            "value": "2 piões Shogun Steel de 5 partes, 2 propulsores, 2 cordas propulsoras, 2 chaves de montagem e 2 cards para batalhas online."
        },
        {
            "name": "short_description",
            "value": "Contém 2 piões e diversos acessórios para os pequenos criarem eletrizantes batalhas.   INMETRO   CE-BRI/IQB-5128"
        },
        {
            "name": "number_of_players",
            "value": "1"
        },
        {
            "name": "certifications",
            "value": "INMETRO   CE-BRI/IQB-5128"
        },
        {
            "name": "manufacturer_age",
            "value": "8+"
        },
        {
            "name": "carac_especial",
            "value": "Com o conjunto de 2 piões de Beyblade Shogun Steel você poderá travar uma batalha incrível! Com a tecnologia Synchrome é possível personalizar seu pião combinando dois anéis metálicos de diferentes piões."
        },
        {
            "name": "customer_service_phone",
            "value": "11 4005-1093"
        },
        {
            "name": "customer_service_email",
            "value": "atendimento@tricae.com.br"
        }
    ]
}
EOD;

        $rawPartnerProduct2 = <<< EOD
{
    "id": 32708,
    "name": "Cadeirinha de Descanso Musical Frutinhas Divertidas  Dican",
    "description": "A Cadeirinha de Descanso Musical Frutinhas Divertidas Dican vai proporcionar segurança e diversão para o seu filho\n\n\n\n\n\n A fase das descobertas é talvez a mais gostosa e prazerosa na vida de um bebê. Pensando nisso, a Dican produziu a Cadeirinha de Descanso Musical Frutinhas Divertidas Dican, um acessório perfeito para o aprendizado do pequeno. Fabricada em tecido macio e suave, inclui 3 brinquedos pendurados para entreter e divertir o baixinho de forma leve e educativa. Não vai ter nada tão divertido no dia a dia do menino com esta cadeirinha! \n\n\n\nCaracterísticas\n\n\n\n Segurança garantida \n Em qualquer produto voltado para os bebês, a segurança deve ser redobrada. A Dican sabe muito bem disso e desenvolveu esta cadeirinha com o maior cuidado possível. O item inclui cinto com 3 pontos de retenção, com capacidade máxima indicada para uma criança de 11 kg, além de certificação do INMETRO, que dá a certeza aos pais de estarem adquirindo um produto de qualidade para o filhote.  \n\n\n Diversão garantida \n Com o intuito de levar mais alegria para o baixinho, vem com 3 brinquedos pendurados com formatos, tamanhos e texturas diferentes, para que ele tente alcançá-los, pegá-los e levá-los à boca. Além disso, possui vibrações relaxantes e músicas tranquilas que vão acalmar e entreter o pequeno enquanto ele descansa. É sensacional! \n\n\n Conforto?  Com toda a certeza! \n Revestido em tecido macio e superaconchegante com frutinhas estampadas, o produto vai levar todo o conforto e bem-estar que o seu filho merece. Acolchoada, vem com capota removível que pode ser ajustada e adaptada de acordo com o tamanho da criança, podendo ser usada tanto como cadeirinha fixa, como cadeira de balanço. Diversão e comodidade na rotina do baixinho. Não é o máximo? \n\n\n  Qualidade Dican \n Criada em 2003, a marca tem o intuito de oferecer brinquedos para a primeira linha de faixa etária em todo o país. Sempre com produtos previamente testados e com certificado pelo INMETRO, o objetivo principal da marca é fazer parte do dia a dia das crianças com itens de qualidade e durabilidade diferenciados. E com a cadeirinha, não é diferente!  \n\n\n\n",
    "active": true,
    "price": "209.90",
    "priceDiscount": "169.90",
    "quantity": "363",
    "ean": "7899097936520",
    "url": "http://www.tricae.com.br/cadeirinha-de-descanso-musical-frutinhas-divertidas-dican-2863.html",
    "sellerSKU": "DI017AC36EGD-6459",
    "category": {
        "name": "Cadeira Descanso Outlet B",
        "id_category": 220,
        "active": true
    },
    "brand": "Dican",
    "height": "9",
    "width": "35",
    "length": "50",
    "weight": "2.27",
    "specifications": [
        {
            "name": "product_warranty",
            "value": "3 Meses"
        },
        {
            "name": "description",
            "value": "<h2> A Cadeirinha de Descanso Musical Frutinhas Divertidas Dican vai proporcionar segurança e diversão para o seu filho</h2>\n\n<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"20\">\n<tbody><tr>\n<td height=\"200\" width=\"100%\" "
        },
        {
            "name": "product_contents",
            "value": "1 Cadeirinha Musical"
        },
        {
            "name": "product_weight",
            "value": "1.000"
        },
        {
            "name": "short_description",
            "value": "<div class=\"clear box-super-preco\"></div>"
        },
        {
            "name": "certifications_ace",
            "value": "INMETRO"
        },
        {
            "name": "product_dimensions",
            "value": "45 x 70 x 70"
        }
    ],
    "skuName": " Verde",
    "images": [
        {
            "name": "image-1",
            "url": "http://static.tricae.com.br/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-1.jpg"
        },
        {
            "name": "image-2",
            "url": "http://static.tricae.com.br/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6968-3682-2.jpg"
        }
    ],
    "urlImage": "http://static.tricae.com.br/p/Dican-Cadeirinha-de-Descanso-Musical-Frutinhas-Divertidas--Dican-6966-3682-1-product.jpg"
}
EOD;

        return [
            [
                'skuVenture' => 'HA032TO39BOQTRI-237547',
                'skuPartner' => 3499903,
                'seller' => $rawVentureProduct1,
                'partner' => $rawPartnerProduct1,
                'categoryId' => 960,
                'categoryName' => 'Lançadores, Piões e Batalhas',
                'categoryKeyName' => 'lancadores',
            ],
            [
                'skuVenture' => 'DI017AC36EGD-6459',
                'skuPartner' => 32708,
                'seller' => $rawVentureProduct2,
                'partner' => $rawPartnerProduct2,
                'categoryId' => 220,
                'categoryName' => 'Cadeira Descanso Outlet B',
                'categoryKeyName' => 'cadeiras',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tricaeVenture = $this->getReference('Tricae');
        $walmartPartner = $this->getReference('Walmart');

        foreach ($this->getRawData() as $rawData) {
            $category = new Category();
            $category->setVenture($tricaeVenture);
            $category->setCategoryVentureId($rawData['categoryId']);
            $category->setName($rawData['categoryName']);
            $category->setNameKey($rawData['categoryKeyName']);

            $manager->persist($category);

            $product = new Product();
            $product->setSku($rawData['skuVenture']);
            $product->setProductAttributes($rawData['seller']);
            $product->setVenture($tricaeVenture);
            $product->setCategory($category);
            $product->setName($product->getName());
            $product->setStock($product->getStock());
            $product->setPrice($product->getPrice());
            $product->setSpecialPrice($product->getSpecialPrice());

            $manager->persist($product);

            $externalProduct = new ExternalProduct();
            $externalProduct->setPartner($walmartPartner);
            $externalProduct->setSku($rawData['skuPartner']);
            $externalProduct->setJson($rawData['partner']);
            $externalProduct->setStatus(ExternalProduct::STATUS_ACTIVE);
            $externalProduct->setProduct($product);

            $manager->persist($externalProduct);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 2;
    }
}
