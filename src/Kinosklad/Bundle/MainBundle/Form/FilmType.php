<?php

namespace Kinosklad\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('length')
            ->add('country', 'country')
            ->add('premiere')
            ->add('description')
            ->add('genres')
        ;
    }

    public function getName()
    {
        return 'film';
    }
}
