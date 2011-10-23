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
            ->add('nameRu', 'text', array('label' => 'Название (ru)'))
            ->add('descriptionEn', 'textarea', array('label' => 'Description (en)'))
            ->add('descriptionRu', 'textarea', array('label' => 'Описание (ru)'))
            ->add('imageFile', 'file', array('label' => 'Poster'))
            ->add('removeImage', 'checkbox', array('label' => 'Remove poster'))
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
    }

    public function getName()
    {
        return 'film';
    }
}
