<?php

namespace Kinosklad\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nameEn', 'text', array('label' => 'Name (en)'))
            ->add('nameRu', 'text', array('label' => 'Name (ru)'))
            ->add('descriptionEn', 'textarea', array('label' => 'Description (en)'))
            ->add('descriptionRu', 'textarea', array('label' => 'Description (ru)'))
            ->add('imageFile', 'file', array('label' => 'Poster'))
            ->add('film.length', 'integer', array('label' => 'Length (minutes)'))
            ->add('film.country', 'country', array('label' => 'Country'))
            ->add('film.premiere', 'date', array('label' => 'Premiere date'))
            ->add('film.genres', 'entity', array(
                'class'    => 'KinoskladMainBundle:Genre',
                'label'    => 'Film genres',
                'expanded' => true,
                'multiple' => true
            ))
        ;

        if ($options['filmAlreadyHasImage']) {
            $builder->add('removeImage', 'checkbox', array('label' => 'Remove poster'));
        }
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'filmAlreadyHasImage' => false
        );
    }

    public function getName()
    {
        return 'film';
    }
}
