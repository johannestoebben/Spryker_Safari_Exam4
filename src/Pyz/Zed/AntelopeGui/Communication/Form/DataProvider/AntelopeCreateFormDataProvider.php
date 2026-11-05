<?php

namespace Pyz\Zed\AntelopeGui\Communication\Form\DataProvider;

use Pyz\Zed\Antelope\Business\AntelopeFacadeInterface;
use Pyz\Zed\AntelopeGui\Communication\Form\AntelopeCreateForm;

class AntelopeCreateFormDataProvider
{
    public function __construct(protected AntelopeFacadeInterface $antelopeFacade)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            AntelopeCreateForm::OPTION_LOCATION_CHOICES => $this->getLocationChoices(),
        ];
    }

    /**
     * @return array<string, int> Location name => location ID, as expected by Symfony's ChoiceType.
     */
    protected function getLocationChoices(): array
    {
        $choices = [];
        $antelopeLocationTransfers = $this->antelopeFacade
            ->getAntelopeLocationCollection()
            ->getAntelopeLocations();

        foreach ($antelopeLocationTransfers as $antelopeLocationTransfer) {
            $choices[$antelopeLocationTransfer->getLocationName()] = $antelopeLocationTransfer->getIdAntelopeLocation();
        }

        return $choices;
    }
}
