<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $filters
     * @param int $totalRows
     * @return array
     */
    public function getPagination(array $filters, int $totalRows): array
    {
        $totalPages = (int)ceil($totalRows / $filters['limit']);

        return [
            'before' => $filters['page'] === 1 ? $filters['page'] : $filters['page'] - 1,
            'next' => $filters['page'] === $totalPages ? $totalPages : $filters['page'] + 1,
            'last' => $totalPages,
            'current' => $filters['page'],
            'totalPages' => $totalPages,
            'totalCount' => $totalRows,
        ];
    }
}
