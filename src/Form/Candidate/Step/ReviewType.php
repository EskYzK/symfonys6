<?php

namespace App\Form\Candidate\Step;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'Review & Submit',
            'help' => 'Please review your information before submitting.',
            'inherit_data' => true,
        ]);
    }
}
