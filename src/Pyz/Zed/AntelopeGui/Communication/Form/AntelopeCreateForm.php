<?php

namespace Pyz\Zed\AntelopeGui\Communication\Form;

use Generated\Shared\Transfer\AntelopeTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Pyz\Zed\AntelopeGui\Communication\AntelopeGuiCommunicationFactory getFactory()
 */
class AntelopeCreateForm extends AbstractType
{
    public const FIELD_NAME = 'name';

    public const FIELD_COLOR = 'color';

    public const FIELD_ID_LOCATION = 'idLocation';

    public const OPTION_LOCATION_CHOICES = 'location_choices';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AntelopeTransfer::class,
        ]);
        $resolver->setRequired(static::OPTION_LOCATION_CHOICES);
        $resolver->setAllowedTypes(static::OPTION_LOCATION_CHOICES, 'array');
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addColorField($builder)
            ->addLocationField($builder, $options[static::OPTION_LOCATION_CHOICES]);
    }

    protected function addNameField(FormBuilderInterface $builder): static
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'required' => true,
            'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ]);

        return $this;
    }

    protected function addColorField(FormBuilderInterface $builder): static
    {
        $builder->add(static::FIELD_COLOR, TextType::class, [
            'label' => 'Color',
            'required' => true,
            'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ]);

        return $this;
    }

    /**
     * @param array<string, int> $locationChoices
     */
    protected function addLocationField(FormBuilderInterface $builder, array $locationChoices): static
    {
        $builder->add(static::FIELD_ID_LOCATION, ChoiceType::class, [
            'label' => 'Location',
            'required' => true,
            'choices' => $locationChoices,
            'placeholder' => 'Select a location',
            'constraints' => [new NotBlank()],
        ]);

        return $this;
    }

    public function getBlockPrefix(): string
    {
        return 'antelope';
    }
}
