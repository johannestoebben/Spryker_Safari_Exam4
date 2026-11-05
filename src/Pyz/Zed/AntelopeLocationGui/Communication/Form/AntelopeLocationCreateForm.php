<?php

namespace Pyz\Zed\AntelopeLocationGui\Communication\Form;

use Generated\Shared\Transfer\AntelopeLocationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Pyz\Zed\AntelopeLocationGui\Communication\AntelopeLocationGuiCommunicationFactory getFactory()
 */
class AntelopeLocationCreateForm extends AbstractType
{
    public const FIELD_LOCATION_NAME = 'locationName';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AntelopeLocationTransfer::class,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLocationNameField($builder);
    }

    protected function addLocationNameField(FormBuilderInterface $builder): static
    {
        // pyz_antelope_location.location_name: VARCHAR(120), required, unique
        $builder->add(static::FIELD_LOCATION_NAME, TextType::class, [
            'label' => 'Name',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 120]),
            ],
        ]);

        return $this;
    }

    public function getBlockPrefix(): string
    {
        return 'antelope_location';
    }
}
