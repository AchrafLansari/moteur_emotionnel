<?php

namespace Acme\UserBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UtilisateurType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'Acme\UserBundle\Model\Utilisateur',
        'name'       => 'utilisateur',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
        $builder->add('prenom');
        $builder->add('age');
        $builder->add('ville');
        $builder->add('ip');
        $builder->add('description');
    }
}
