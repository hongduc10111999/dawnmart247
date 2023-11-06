<?php

namespace App\Repositories;

abstract class BaseRepository
{
    public function customPagination($object)
    {
        $total = $object->total();
        $page = $object->currentPage();
        $limit = $object->perPage();

        return [
            '_current' => $page,
            '_next' => ($page * $limit) < $total ? $page + 1 : null,
            '_prev' => $page > 1 ? $page - 1 : null,
            '_last' => $object->lastPage(),
            '_limit' => $object->perPage(),
            '_total' => $total,
        ];
    }
}
