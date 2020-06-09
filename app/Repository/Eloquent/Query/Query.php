<?php

declare(strict_types=1);

namespace App\Repository\Eloquent\Query;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Query
{

    /**
     * Instance of Eloquent Model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
       
    /**
     * Searchable fields of Eloquent Model
     *
     * @var array
     */
    protected array $searchable;
       
    /**
     * Checkable array of fields of Eloquent Model
     *
     * @var array
     */
    protected array $checkable;

    /**
     * Selectable fields of Eloquent Model
     *
     * @var array
     */
    protected array $selectable;
       
    /**
     * Default page for query method
     *
     * @var int
     */
    protected int $page;

    /**
     * Default limit for query method
     *
     * @var int
     */
    protected int $limit;

    /**
     * Fields to search
     *
     * @var array
     */
    protected array $fields;
    
    /**
     * Full URL path
     *
     * @var string
     */
    protected string $path;

    /**
     * Set builder model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return self
     */
    public function model(Model $model): self
    {
        $this->model = $model;
        $this->searchable = $model->searchable;
        $this->checkable = $model->checkable;
        $this->selectable = $model->selectable;
        return $this;
    }
     
    /**
     * Set builder page
     *
     * @param  int $page
     * @return self
     */
    public function page(int $page): self
    {
        $this->page = $page;
        return $this;
    }
    
    /**
     * Set builder limit
     *
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Set builder fields
     *
     * @param array $fields
     * @return self
     */
    public function fields(array $fields): self
    {
        if (! isset($fields['orderby'])) {
            $fields['orderby'][] = $this->model->getTable() . '.id';
        }
        $this->fields = $fields;
        return $this;
    }

    /**
     * Set builder path
     *
     * @param string $fields
     * @return self
     */
    public function path(string $path): self
    {
        $this->path = $path;
        return $this;
    }
    
  
    /**
     * Get a collection of filtered Model instances
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        $query = $this->model::query();
        $query->distinct()->withJoins();
        foreach ($this->fields as $field => $value) {
            if ($field == 'query') {
                $this->search($query, $value);
            } elseif ($field == 'deleted') {
                if (
                    in_array(
                        'Illuminate\Database\Eloquent\SoftDeletes',
                        class_uses($this->model)
                    )
                ) {
                    $query->withTrashed();
                }
            } else {
                $this->queryAllTypes($query, $field, $value);
            }
        }
        foreach ($this->fields['orderby'] as $orderBy) {
            $orderWithoutSign = preg_replace('/^[-]/', '', $orderBy);
            if (in_array($orderWithoutSign, $this->model->searchable)) {
                $direction = strpos($orderBy, '-') !== false ? 'DESC' : 'ASC';
                if (strpos($orderWithoutSign, '__') === false) {
                    $table = $this->model->getTable();
                    $query->orderByRaw("$table.$orderWithoutSign $direction");
                } else {
                    [$relation, $field] = explode('__', $orderWithoutSign);
                    $query->orderByRaw("$relation.$field $direction");
                }
            }
        }
        $total = clone $query;
        $total = $total->count($this->model->getTable() . '.id');
        $from = $this->page * $this->limit - ($this->limit - 1);
        $to = $this->page * $this->limit != 0 ? $this->page * $this->limit : $total;
        $query->withAll()->offset($from - 1);
        if ($this->limit !== 0) {
            $query->limit($this->limit);
        }
        $collection = $query->get();
        $totalPages = $this->limit ? (int) ceil($total / $this->limit) : 1;
        return collect([
            'data' => $collection,
            'links' => [
                'first' => $this->replacePage(1),
                'last' => $this->replacePage($this->limit ? (int) ceil($total / $this->limit) : 1),
                'prev' => $this->page > 1 ? $this->replacePage($this->page - 1) : null,
                'next' => $this->page < $totalPages ? $this->replacePage($this->page + 1) : null,
            ],
            'meta' => [
                'path' => strtok($this->path, '?'),
                'total' => $total,
                'page' => $this->page,
                'limit' => $this->limit != 0 ? $this->limit : null,
                'previous_page' => $this->page > 1 ? $this->page - 1 : null,
                'next_page' => $this->page < $totalPages ? $this->page + 1 : null,
                'total_pages' => $totalPages,
                'from' => $from,
                'to' => $to < $total ? $to : $total
            ]
        ]);
    }
        
    /**
     * Replace page in a query string
     *
     * @param  int $newPage
     * @return string
     */
    protected function replacePage(int $newPage): string
    {
        $fields = $this->fields;
        $fields['page'] = $newPage;
        return strtok($this->path, '?') . '?' . http_build_query($fields);
    }
    
    /**
     * Make a string search in every searchable string type field
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function search(Builder $query, string $value): Builder
    {
        return $query->where(function (Builder $builder) use ($value): void {
            foreach ($this->searchable as $field) {
                if (strpos($field, '__') === false) {
                    $type = Schema::getColumnType($this->model->getTable(), $field);
                    $table = $this->model->getTable();
                    if ($type == 'string') {
                        $builder->orWhereRaw("LOWER($table.$field) LIKE ?", '%' . mb_strtolower($value) . '%');
                    }
                } else {
                    [$relation, $field] = explode('__', $field);
                    $table = $this->model->{$relation}()->getRelated()->getTable();
                    $type = Schema::getColumnType($table, $field);
                    if ($type == 'string') {
                        $builder->orWhereRaw("LOWER($table.$field) LIKE ?", '%' . mb_strtolower($value) . '%');
                    }
                }
            }
        });
    }
 
    /**
     * Query all type of fields
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string  $value
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function queryAllTypes(Builder $query, string $field, $value): Builder
    {
        $origin = $field;
        if (strpos($field, '_from') !== false) {
            $field = str_replace('_from', '', $field);
        }
        if (strpos($field, '_to') !== false) {
            $field = str_replace('_to', '', $field);
        }
        $data = [$query, $field, $value, $origin];
        $this->querySearchable(...$data);
        $this->queryCheckable(...$data);
        $this->querySelectable(...$data);
        return $query;
    }

    /**
     * Find given value in a column
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string  $type
     * @param  string  $field
     * @param  mixed   $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function queryField(Builder $query, string $type, string $field, $value): Builder
    {
        switch ($type) {
            case 'bigint':
                $query->where($field, '=', (int) $value);
                break;
            case 'integer':
                $query->where($field, '=', (int) $value);
                break;
            case 'boolean':
                $query->where($field, (bool) $value);
                break;
            case 'string':
                $query->whereRaw('LOWER(' . $field . ') = ?', mb_strtolower($value));
                break;
            case 'datetime':
                $date = Carbon::parse($value)->format('Y-m-d');
                if (strpos($field, '_from') !== false) {
                    $query->whereDate(str_replace('_from', '', $field), '>=', $date);
                }
                if (strpos($field, '_to') !== false) {
                    $query->whereDate(str_replace('_to', '', $field), '<=', $date);
                }
                break;
        }
        return $query;
    }

    /**
     * Find given value in a column
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string  $type
     * @param  string  $field
     * @param  mixed   $values
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function queryOrField(Builder $query, string $type, string $field, $values): Builder
    {
        $query->where(function ($query) use ($type, $field, $values): void {
            foreach ($values as $value) {
                switch ($type) {
                    case 'bigint':
                        $query->orWhere($field, '=', (int) $value);
                        break;
                    case 'integer':
                        $query->orWhere($field, '=', (int) $value);
                        break;
                    case 'boolean':
                        $query->orWhere($field, (bool) $value);
                        break;
                    case 'string':
                        $query->orWhereRaw('LOWER(' . $field . ') = ?', mb_strtolower($value));
                        break;
                }
            }
        });
        return $query;
    }
      
    /**
     * Query searchable fields
     *
     * @param  string $query
     * @param  string $field
     * @param  mixed $value
     * @param  string $origin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function querySearchable(Builder $query, string $field, $value, string $origin): Builder
    {
        if (in_array($field, $this->searchable)) {
            if (strpos($field, '__') === false) {
                $type = Schema::getColumnType($this->model->getTable(), $field);
                $this->queryField($query, $type, $this->model->getTable() . '.' . $origin, $value);
            } else {
                [$relation, $field] = explode('__', $field);
                $table = $this->model->{$relation}()->getRelated()->getTable();
                $type = Schema::getColumnType($table, $field);
                $query->whereHas($relation, function (Builder $builder) use ($query, $type, $table, $field, $value) {
                    $this->queryField($builder, $type, $table . '.' . $field, $value);
                });
            }
        }
        return $query;
    }

    /**
     * Query checkable fields
     *
     * @param  string $query
     * @param  string $field
     * @param  mixed $value
     * @param  string $origin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function queryCheckable(Builder $query, string $field, $values, string $origin): Builder
    {
        if (in_array($field, $this->checkable)) {
            $query->where(function ($query) use ($field, $values, $origin): void {
                foreach ($values as $value) {
                    if (strpos($field, '__') === false) {
                        $type = Schema::getColumnType($this->model->getTable(), $field);
                        $this->queryField($query, $type, $this->model->getTable() . '.' . $origin, $value);
                    } else {
                        [$relation, $fieldExp] = explode('__', $field);
                        $table = $this->model->{$relation}()->getRelated()->getTable();
                        $type = Schema::getColumnType($table, $fieldExp);
                        $query
                        ->whereHas($relation, function (Builder $builder) use ($query, $type, $table, $fieldExp, $value) {
                                $this->queryField($builder, $type, $table . '.' . $fieldExp, $value);
                        });
                    }
                }
            });
        }
        return $query;
    }

   /**
     * Query selectable fields
     *
     * @param  string $query
     * @param  string $field
     * @param  mixed $value
     * @param  string $origin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function querySelectable(Builder $query, string $field, $value, string $origin): Builder
    {
        if (in_array($field, $this->selectable)) {
            if (strpos($field, '__') === false) {
                $type = Schema::getColumnType($this->model->getTable(), $field);
                $this->queryOrField($query, $type, $this->model->getTable() . '.' . $origin, $value);
            } else {
                [$relation, $field] = explode('__', $field);
                $table = $this->model->{$relation}()->getRelated()->getTable();
                $type = Schema::getColumnType($table, $field);
                $query->whereHas($relation, function (Builder $builder) use ($query, $type, $table, $field, $value) {
                    $this->queryOrField($builder, $type, $table . '.' . $field, $value);
                });
            }
        }
        return $query;
    }
}
