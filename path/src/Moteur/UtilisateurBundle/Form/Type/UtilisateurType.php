<?php

namespace Moteur\UtilisateurBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UtilisateurType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'Moteur\UtilisateurBundle\Model\Utilisateur',
        'name'       => 'utilisateur',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
        $builder->add('prenom');
        if(!isset($options['connexion'])){
	        $builder->add('mail');
	        $builder->add('age');
	        $builder->add('ville');
	        $builder->add('description');
        }
    }
}
