<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QuerySearch
{
    /**
     * Apply search, filter, and pagination to a query
     * 
     * @param Builder $query The Eloquent query builder
     * @param Request $request The HTTP request
     * @param array $searchableColumns Columns to search in (e.g., ['name', 'email'])
     * @param array $filterableColumns Columns that can be filtered (e.g., ['role', 'status'])
     * @param int $perPage Number of items per page (default: 10)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function apply(
        Builder $query,
        Request $request,
        array $searchableColumns = [],
        array $filterableColumns = [],
        int $perPage = 10,
        array $defaultSort = []
    ) {
        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchableColumns, $searchTerm) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // Apply filters
        foreach ($filterableColumns as $filterKey => $column) {
            $requestKey = is_string($filterKey) ? $filterKey : $column;
            $value = $request->input($requestKey);

            if (!empty($value)) {
                $query->where($column, $value);
            }
        }

        // Apply sorting
        if ($request->filled('sort_by')) {
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($request->sort_by, $sortDirection);
        } elseif (!empty($defaultSort)) {
            // Apply default sorting jika tidak ada sort dari request
            foreach ($defaultSort as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        // Apply pagination
        $perPageFromRequest = $request->get('per_page', $perPage);
        return $query->paginate($perPageFromRequest)->appends($request->except('page'));
    }

    /**
     * Apply search, filter without pagination (returns all results)
     * 
     * @param Builder $query The Eloquent query builder
     * @param Request $request The HTTP request
     * @param array $searchableColumns Columns to search in
     * @param array $filterableColumns Columns that can be filtered
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function applyWithoutPagination(
        Builder $query,
        Request $request,
        array $searchableColumns = [],
        array $filterableColumns = []
    ) {
        // Apply search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchableColumns, $searchTerm) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // Apply filters
        foreach ($filterableColumns as $column) {
            if ($request->has($column) && !empty($request->$column)) {
                $query->where($column, $request->$column);
            }
        }

        // Apply sorting
        if ($request->has('sort_by') && !empty($request->sort_by)) {
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($request->sort_by, $sortDirection);
        }

        return $query->get();
    }
}
