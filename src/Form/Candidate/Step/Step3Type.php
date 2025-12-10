<?php

namespace App\Form\Candidate\Step;

use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Flow\ButtonFlowInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step3Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('availableImmediately', CheckboxType::class, [
                'required' => false,
                'label' => 'Disponible immédiatement',
            ])
            ->add('availabilityDate', DateType::class, [
                'required' => false,
                'label' => 'Date de disponibilité',
                'widget' => 'single_text',
            ])
        ;

        // Validation personnalisée après soumission
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $rootForm = $form->getRoot();

            // Ne valider que si on avance (pas sur "Précédent")
            $clickedButton = $rootForm->getClickedButton();
            if ($clickedButton instanceof ButtonFlowInterface && $clickedButton->isPreviousAction()) {
                return;
            }

            // Utiliser les données des champs directement (déjà synchronisées)
            $isAvailableImmediately = $form->get('availableImmediately')->getData();
            $availabilityDate = $form->get('availabilityDate')->getData();

            // Si availableImmediately n'est pas coché et pas de date, ajouter une erreur
            if (!$isAvailableImmediately && $availabilityDate === null) {
                $form->get('availabilityDate')->addError(
                    new FormError('Veuillez indiquer une date de disponibilité.')
                );
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'inherit_data' => true,
        ]);
    }
}
