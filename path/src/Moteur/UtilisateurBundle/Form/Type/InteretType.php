<?php

namespace Moteur\UtilisateurBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InteretType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'Moteur\UtilisateurBundle\Model\Interet',
        'name'       => 'interet',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
    }
}
