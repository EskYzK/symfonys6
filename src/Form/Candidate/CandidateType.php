<?php

namespace App\Form\Candidate;

use App\Entity\Candidate;
use App\Form\Candidate\Step\Step2Type;
use App\Form\Candidate\Step\ReviewType;
use App\Form\Candidate\Step\Step1Type;
use App\Form\Candidate\Step\Step3Type;
use App\Form\Candidate\Step\Step4Type;
use Symfony\Component\Form\Flow\AbstractFlowType;
use Symfony\Component\Form\Flow\FormFlowBuilderInterface;
use Symfony\Component\Form\Flow\Type\NavigatorFlowType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateType extends AbstractFlowType
{
    public function buildFormFlow(FormFlowBuilderInterface $builder, array $options): void
    {
        $builder
            ->addStep('userInfo', Step1Type::class)
            ->addStep('experienceDetails', Step2Type::class, [], fn(Candidate $data) => !$data->hasExperience())
            ->addStep('availability', Step3Type::class)
            ->addStep('consentRGPD', Step4Type::class);
        $builder->addStep('review', ReviewType::class);
        $builder->add('navigator', NavigatorFlowType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'step_property_path' => 'currentStep',
        ]);
    }
}