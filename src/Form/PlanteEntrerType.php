<?php

namespace App\Form;

use App\Entity\Plante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanteEntrerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('periodeArrosage', null, [
                'label' => 'Description'
            ])
            ->add('famille',ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            // ->add('positionGPS')
            // ->add('userOwningPlant')
            // ->add('userKeepingPlant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plante::class,
        ]);
    }

    private function getChoices()
    {
        $choices = Plante::FAMILLELIST;
        $output = [];
        foreach($choices as $k => $v)
        {
            $output[$v] = $k;
        }
        return $output;
    }
}
