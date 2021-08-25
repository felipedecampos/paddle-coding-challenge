<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRespository
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected string $modelClass;

    /**
     * @return Builder
     */
    protected function newQuery(): Builder
    {
        return app($this->modelClass)->newQuery();
    }

    /**
     * @param Builder|null $query
     * @param int $take
     * @param bool $paginate
     *
     * @return Collection|Paginator
     */
    protected function doQuery(Builder $query = null, int $take = 15, bool $paginate = true): Collection|Paginator
    {
        if (is_null($query)) {
            $query = $this->newQuery();
        }

        if (true === $paginate) {
            return $query->simplePaginate($take);
        }

        if ($take > 0 || false !== $take) {
            $query->take($take);
        }

        return $query->get();
    }

    /**
     * Returns all records.
     * If $take is false then brings all records
     * If $paginate is true returns Paginator instance.
     *
     * @param int $take
     * @param bool $paginate
     *
     * @return Paginator
     */
    public function getAll(int $take = 15, bool $paginate = true): Paginator
    {
        return $this->doQuery(null, $take, $paginate);
    }

    /**
     * Retrieves a record by his id
     * If fail is true $ fires ModelNotFoundException.
     *
     * @param int $id
     * @param bool $fail
     *
     * @return Model
     */
    public function findById(int $id, bool $fail = true): Model
    {
        if ($fail) {
            return $this->newQuery()->findOrFail($id);
        }

        return $this->newQuery()->find($id);
    }

    /**
     * Create an entity and persists on database
     *
     * @param array $fields
     *
     * @return Model
     */
    public function create(array $fields): Model
    {
        return $this->newQuery()->create($fields);
    }

    /**
     * Update an entity and persist on database
     * returns modified Entity instance
     *
     * @param $id
     * @param array $fields
     *
     * @return Model
     */
    public function update($id, array $fields): Model
    {
        $data = $this->findById($id);
        $data->update($fields);

        return $data->fresh();
    }

    /**
     * Delete an entity and persist on database
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        $data = $this->findById($id);

        return $data->delete();
    }
}
