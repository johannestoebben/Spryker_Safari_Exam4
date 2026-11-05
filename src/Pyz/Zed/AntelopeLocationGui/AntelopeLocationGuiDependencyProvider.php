<?php

namespace Pyz\Zed\AntelopeLocationGui;

use Orm\Zed\AntelopeLocation\Persistence\PyzAntelopeLocationQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AntelopeLocationGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_ANTELOPE = 'FACADE_ANTELOPE';

    public const PROPEL_QUERY_ANTELOPE_LOCATION = 'PROPEL_QUERY_ANTELOPE_LOCATION';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addAntelopeFacade($container);
        $container = $this->addAntelopeLocationPropelQuery($container);

        return $container;
    }

    protected function addAntelopeFacade(Container $container): Container
    {
        $container->set(static::FACADE_ANTELOPE, function (Container $container) {
            return $container->getLocator()->antelope()->facade();
        });

        return $container;
    }

    protected function addAntelopeLocationPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_ANTELOPE_LOCATION, $container->factory(function () {
            return PyzAntelopeLocationQuery::create();
        }));

        return $container;
    }
}
