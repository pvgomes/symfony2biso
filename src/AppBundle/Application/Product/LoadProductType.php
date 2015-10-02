<?php

namespace AppBundle\Application\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LoadProductType
 */
class LoadProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('market', 'entity', [
                'class' => 'AppBundle\Infrastructure\Core\Market',
                'choice_label' => 'name',
            ])
            ->add('republish', 'checkbox', [
                'label' => 'Re-Publicação',
                'required' => false
            ])
            ->add('file')
        ;
    }

    public function getName()
    {
        return 'loadProduct';
    }
}
