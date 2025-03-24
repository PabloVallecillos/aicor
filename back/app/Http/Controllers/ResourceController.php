<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

class ResourceController extends Controller
{
    public function list(string $modelClass, $data): array|null|LengthAwarePaginator
    {
        $ret = ['errors' => [__('Unable to filter requested resource.')]];
        if (is_callable($modelClass, 'defaultQuery')) {
            $model = new $modelClass;
            $perPage = config('resource.listing.per_page');
            $sideLinks = config('resource.listing.page_links_side');

            if (! isset($data['paginator_mode'])) {
                $data['paginator_mode'] = 0;
            }

            $list = $model->defaultQuery()
                ->resourceSelection($data['only_fields'] ?? [], $data['hide_fields'] ?? [])
                ->resourceFilters($data['filters'] ?? [])
                ->resourceOrder($data['order'] ?? [])
//                ->resourceGroup($data['group'] ?? [])
                ->getQueryResource();

            switch ($data['paginator_mode']) {
                case 2:
                    if ($data['limit'] ?? false) {
                        $list = ['data' => $list->limit($data['limit'])->get()];
                        break;
                    }
                    $list = ['data' => $list->get()];
                    break;
                case 1:
                    $list = $list->simplePaginate($data['per_page'] ?? $perPage)
                        ->withQueryString();
                    break;
                default:
                    $list = $list->paginate($data['per_page'] ?? $perPage)
                        ->onEachSide($data['link_range'] ?? $sideLinks)
                        ->withQueryString();
                    break;
            }
            $ret = $list;
        }

        return $ret;
    }
}
