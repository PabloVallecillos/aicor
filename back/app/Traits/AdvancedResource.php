<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait AdvancedResource
{
    protected $queryResource;

    /**
     * Default query conditions and restrictions key -> value.
     */
    public function resourceDefaultConditions(): array
    {
        return [];
    }

    /**
     * Default order fields for query.
     */
    public function resourceDefaultOrder(): array
    {
        return [];
    }

    /**
     * Group query by these parameters.
     */
    public function resourceDefaultGroup(): array
    {
        return [];
    }

    /**
     * Define field filters. Use with filters "search".
     */
    public function resourceDefaultFieldsFilter(): array
    {
        return [];
    }

    /**
     * Define relations field filters. Use with filters "search".
     */
    public function resourceDefaultRelationsFieldsFilter(): array
    {
        return [];
    }

    /**
     * Define model relations to add in list and queries
     */
    public function resourceDefaultAddRelations(): array
    {
        return [];
    }

    /**
     * Shows All the columns of the Corresponding Table of Model.
     *
     * If You need to get all the Columns of the Model Table.
     * Useful while including the columns in search
     *
     **/
    public function getTableColumns(): array
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * Starts a query with the model restrictions.
     */
    public function defaultQuery(): static
    {
        $this->queryResource = $this->query();
        if ($this->resourceDefaultAddRelations()) {
            $this->queryResource->with($this->resourceDefaultAddRelations());
        }
        $defaultConditions = $this->resourceDefaultConditions();
        if ($defaultConditions) {
            foreach ($defaultConditions as $column => $condition) {
                $this->queryResource->where($column, $condition);
            }
        }

        return $this;
    }

    public function resourceSelection(array $select = [], array $subtractSelect = []): static
    {
        $query = $this->queryResource;
        if ($select) {
            $query->select($select);
        }
        if ($subtractSelect) {
            if (! $select) {
                $select = $this->getTableColumns();
            }
            $query->select(array_diff($select, $subtractSelect));
        }

        return $this;
    }

    public function resourceFilters(array $filters = []): static
    {
        $query = $this->queryResource;
        if ($filters) {
            $this->getFilters($query, $filters);
        }

        return $this;
    }

    public function resourceOrder(array $order = []): static
    {
        $query = $this->queryResource;
        if ($order) {
            foreach ($order as $column => $direction) {
                if ($direction == 'ASC' || $direction == 'DESC') {
                    $query->orderBy($column, $direction);
                }
            }
        } else {
            $defaultOrder = $this->resourceDefaultOrder();
            if ($defaultOrder) {
                foreach ($defaultOrder as $column => $direction) {
                    $query->orderBy($column, $direction);
                }
            }
        }

        return $this;
    }

    public function getQueryResource()
    {
        return $this->queryResource;
    }

    public function resourceGroup(array $group = []): static
    {
        $query = $this->queryResource;
        $defaultGroup = $this->resourceDefaultGroup();
        if ($defaultGroup) {
            $query->groupBy($defaultGroup);
        }
        if ($group) {
            $query->groupBy($group);
        }

        return $this;
    }

    /**
     * Filter a model instance.
     */
    public function getFilters(Builder $query, array $filters): Builder
    {
        if ($filters) {
            $fieldsFilter = $this->resourceDefaultFieldsFilter();
            $fieldsRelationsFilter = $this->resourceDefaultRelationsFieldsFilter();
            foreach ($filters as $keyFilter => $valueFilter) {
                if ($keyFilter == 'search') {
                    if ($fieldsFilter) {
                        // Search by fields filter
                        $query->where(function ($query) use ($fieldsFilter, $valueFilter) {
                            foreach ($fieldsFilter as $fieldFilter) {
                                $query->orWhere($fieldFilter, 'like', '%'.$valueFilter.'%');
                            }
                        });
                    }
                    if ($fieldsRelationsFilter) {
                        $relations = [];
                        foreach ($fieldsRelationsFilter as $fieldRelationFilter) {
                            [$relation, $column] = explode('.', $fieldRelationFilter);
                            $relations[$relation][] = $column;
                        }
                        foreach ($relations as $keyRelation => $relation) {
                            $query->orWhereHas($keyRelation, function ($sub) use ($valueFilter, $relation) {
                                for ($i = 0; $i < count($relation); $i++) {
                                    if ($i == 0) {
                                        $sub->where($relation[$i], 'like', '%'.$valueFilter.'%');

                                        continue;
                                    }
                                    $sub->orwhere($relation[$i], 'like', '%'.$valueFilter.'%');
                                }
                            });
                        }
                    }
                } elseif ($keyFilter != 'search') {
                    // Normal search
                    if (is_array($valueFilter) && ! empty($valueFilter)) {
                        if (array_key_exists('operator', $valueFilter)) {
                            if ($valueFilter['operator'] == '!=' && is_array($valueFilter['values'])) {
                                $query->whereNotIn($keyFilter, $valueFilter['values']); // distinct from array
                            } else {
                                $query->where($keyFilter, $valueFilter['operator'], $valueFilter['values']);
                            }
                        } elseif (array_key_exists('dates', $valueFilter)) {
                            $query->whereBetween($keyFilter, $valueFilter['dates']);
                        } else {
                            $query->whereIn($keyFilter, $valueFilter);
                        }
                    } else {
                        $query->where($keyFilter, '=', $valueFilter);
                    }
                }
            }
        }

        return $query;
    }
}
