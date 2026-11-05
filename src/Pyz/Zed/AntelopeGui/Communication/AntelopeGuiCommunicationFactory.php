<?php

namespace Pyz\Zed\AntelopeGui\Communication;

use Generated\Shared\Transfer\AntelopeTransfer;
use Orm\Zed\Antelope\Persistence\PyzAntelopeQuery;
use Pyz\Zed\Antelope\Business\AntelopeFacadeInterface;
use Pyz\Zed\AntelopeGui\AntelopeGuiDependencyProvider;
use Pyz\Zed\AntelopeGui\Communication\Form\AntelopeCreateForm;
use Pyz\Zed\AntelopeGui\Communication\Form\DataProvider\AntelopeCreateFormDataProvider;
use Pyz\Zed\AntelopeGui\Communication\Table\AntelopeTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class AntelopeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    public function createAntelopeTable(): AntelopeTable
    {
        return new AntelopeTable($this->getAntelopePropelQuery());
    }

    public function createAntelopeCreateForm(?AntelopeTransfer $antelopeTransfer = null): FormInterface
    {
        return $this->getFormFactory()->create(
            AntelopeCreateForm::class,
            $antelopeTransfer ?? new AntelopeTransfer(),
            $this->createAntelopeCreateFormDataProvider()->getOptions(),
        );
    }

    public function createAntelopeCreateFormDataProvider(): AntelopeCreateFormDataProvider
    {
        return new AntelopeCreateFormDataProvider($this->getAntelopeFacade());
    }

    public function getAntelopeFacade(): AntelopeFacadeInterface
    {
        return $this->getProvidedDependency(AntelopeGuiDependencyProvider::FACADE_ANTELOPE);
    }

    public function getAntelopePropelQuery(): PyzAntelopeQuery
    {
        return $this->getProvidedDependency(AntelopeGuiDependencyProvider::PROPEL_QUERY_ANTELOPE);
    }
}
