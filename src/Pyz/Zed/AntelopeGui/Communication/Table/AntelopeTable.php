<?php

namespace Pyz\Zed\AntelopeGui\Communication\Table;

use Orm\Zed\Antelope\Persistence\Map\PyzAntelopeTableMap;
use Orm\Zed\Antelope\Persistence\PyzAntelopeQuery;
use Orm\Zed\AntelopeLocation\Persistence\Map\PyzAntelopeLocationTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AntelopeTable extends AbstractTable
{
    protected const COL_ID_ANTELOPE = PyzAntelopeTableMap::COL_ID_ANTELOPE;

    protected const COL_NAME = PyzAntelopeTableMap::COL_NAME;

    protected const COL_COLOR = PyzAntelopeTableMap::COL_COLOR;

    /**
     * Virtual column added via withColumn() from the joined pyz_antelope_location table.
     */
    protected const COL_LOCATION_NAME = 'locationName';

    public function __construct(protected PyzAntelopeQuery $antelopeQuery)
    {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_ANTELOPE => 'ID',
            static::COL_NAME => 'Name',
            static::COL_COLOR => 'Color',
            static::COL_LOCATION_NAME => 'Location',
        ]);

        $config->setSortable([
            static::COL_ID_ANTELOPE,
            static::COL_NAME,
            static::COL_COLOR,
            static::COL_LOCATION_NAME,
        ]);

        // Search conditions are built as raw SQL, so the real joined column is used instead of the alias.
        $config->setSearchable([
            static::COL_ID_ANTELOPE,
            static::COL_NAME,
            static::COL_COLOR,
            PyzAntelopeLocationTableMap::COL_LOCATION_NAME,
        ]);

        $config->setDefaultSortField(static::COL_ID_ANTELOPE, TableConfiguration::SORT_ASC);
        $config->setUrl('table');

        return $config;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->antelopeQuery
            ->leftJoinPyzAntelopeLocation()
            ->withColumn(PyzAntelopeLocationTableMap::COL_LOCATION_NAME, static::COL_LOCATION_NAME);

        $results = [];
        foreach ($this->runQuery($query, $config) as $antelopeRow) {
            $results[] = [
                static::COL_ID_ANTELOPE => $antelopeRow[static::COL_ID_ANTELOPE],
                static::COL_NAME => $antelopeRow[static::COL_NAME],
                static::COL_COLOR => $antelopeRow[static::COL_COLOR],
                static::COL_LOCATION_NAME => $antelopeRow[static::COL_LOCATION_NAME] ?? '',
            ];
        }

        return $results;
    }
}
