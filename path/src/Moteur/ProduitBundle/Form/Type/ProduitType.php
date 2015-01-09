<?php

namespace Moteur\ProduitBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProduitType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'Moteur\ProduitBundle\Model\Produit',
        'name'       => 'produit',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre');
        $builder->add('sous_titre');
        $builder->add('auteur');
        $builder->add('description','textarea');
        $builder->add('image');
        $builder->add('lien');
    }
}
