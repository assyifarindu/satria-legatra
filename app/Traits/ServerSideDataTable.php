<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ServerSideDataTable
{
    protected function datatable(
        Request $request,
        Builder $query,
        array $columns,
        callable $transform
    ) {

        $draw = intval($request->draw);

        $start = intval($request->start);

        $length = intval($request->length);

        $search = $request->input('search.value');

        $recordsTotal = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($search)) {

            $query->where(function ($q) use ($columns, $search) {

                foreach ($columns as $column) {

                    $q->orWhere($column, 'like', "%{$search}%");

                }

            });

        }

        $recordsFiltered = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Order
        |--------------------------------------------------------------------------
        */

        if ($request->has('order')) {

            $columnIndex = $request->order[0]['column'];

            $direction = $request->order[0]['dir'];

            if (isset($columns[$columnIndex])) {

                $query->orderBy(
                    $columns[$columnIndex],
                    $direction
                );

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $rows = $query
            ->skip($start)
            ->take($length)
            ->get();

        $data = [];

        foreach ($rows as $row) {

            $data[] = $transform($row);

        }

        return response()->json([

            'draw' => $draw,

            'recordsTotal' => $recordsTotal,

            'recordsFiltered' => $recordsFiltered,

            'data' => $data

        ]);

    }
}