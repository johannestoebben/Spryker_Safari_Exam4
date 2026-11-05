<?php

namespace Pyz\Zed\AntelopeLocationGui\Communication\Table;

use Orm\Zed\AntelopeLocation\Persistence\Map\PyzAntelopeLocationTableMap;
use Orm\Zed\AntelopeLocation\Persistence\PyzAntelopeLocationQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AntelopeLocationTable extends AbstractTable
{
    protected const COL_ID_ANTELOPE_LOCATION = PyzAntelopeLocationTableMap::COL_ID_ANTELOPE_LOCATION;

    protected const COL_NAME = PyzAntelopeLocationTableMap::COL_LOCATION_NAME;

    protected const COL_ACTIONS = 'actions';

    protected const URL_EDIT = '/antelope-location-gui/index/edit';

    public const PARAM_ID_ANTELOPE_LOCATION = 'id-antelope-location';

    public function __construct(protected PyzAntelopeLocationQuery $antelopeLocationQuery)
    {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_ANTELOPE_LOCATION => 'ID',
            static::COL_NAME => 'Name',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            static::COL_ID_ANTELOPE_LOCATION,
            static::COL_NAME,
        ]);

        $config->setSearchable([
            static::COL_ID_ANTELOPE_LOCATION,
            static::COL_NAME,
        ]);

        $config->setRawColumns([static::COL_ACTIONS]);
        $config->setDefaultSortField(static::COL_ID_ANTELOPE_LOCATION, TableConfiguration::SORT_ASC);
        $config->setUrl('table');

        return $config;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $results = [];
        $antelopeLocationRows = $this->runQuery($this->antelopeLocationQuery, $config);

        foreach ($antelopeLocationRows as $antelopeLocationRow) {
            $results[] = [
                static::COL_ID_ANTELOPE_LOCATION => $antelopeLocationRow[static::COL_ID_ANTELOPE_LOCATION],
                static::COL_NAME => $antelopeLocationRow[static::COL_NAME],
                static::COL_ACTIONS => $this->generateEditButton(
                    Url::generate(static::URL_EDIT, [
                        static::PARAM_ID_ANTELOPE_LOCATION => $antelopeLocationRow[static::COL_ID_ANTELOPE_LOCATION],
                    ]),
                    'Edit',
                ),
            ];
        }

        return $results;
    }
}
