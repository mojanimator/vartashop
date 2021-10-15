<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function groups(Request $request)
    {
        $level = $request->level;
        $showTree = $request->show_tree;
        $ids = $request->ids;

        if (!$ids)
            $ids = Group::where('level', 1)->distinct('parent')->pluck('parent');

        $query = Group::query();

        if ($showTree) {
            return $query->select('id', 'name')->whereIn('id', $ids)->orderByDesc('id')->get()->map(function ($data) {
                $data['childs'] = Group::where('parent', $data['id'])->select('id', 'name')->get()->map(function ($data) {
                    $data['selected'] = false;
                    return $data;
                });

                return $data;

            });
        }
        if ($level)
            $query->where('level' . $level);


        return $query->get();
    }

    public function search(Request $request)
    {

//        $query = Report::query();
        $request->validate([

            'paginate' => 'sometimes|numeric',
            'page' => 'sometimes|numeric',
        ], []);


        $search = $request->search;
        $idNot = $request->id_not;
        $name = $request->name;
        $tags = $request->tags;
        $paginate = $request->paginate;
        $page = $request->page;
        $group_id = $request->group_id;
        $is_vip = $request->is_vip;
        $orderBy = $request->order_by;
        $orderByRaw = $request->order_by_raw;
        $dir = $request->dir;
        $shopId = $request->shop_id;
        $groupIds = $request->group_ids;


        if (!$paginate) {
            $paginate = 4;
        }
        if (!$page) {
            $page = 1;
        }
        if (!$dir) {
            $dir = 'DESC';
        }


        $query = Product::query();

//        if ($idNot)
//            $query = $query->where('id', '!=', $idNot);

        if ($search) {
            $query = $query->orwhere(function ($query) use ($search) {

                foreach (array_filter(explode(" ", $search), function ($el) {
                    return $el != '' && $el != ' ' && $el != null;
                }) as $word) {
                    $query->orWhere('name', 'LIKE', '%' . $word . '%')->orWhere('tags', 'LIKE', '%' . $word . '%');
                }

            });

        }

        if ($name)
            $query = $query->orwhere(function ($query) use ($name) {

                foreach ($name as $word) {
                    $query->orWhere('name', 'LIKE', '%' . $word . '%');
                }

            });
        if ($tags)
            $query = $query->orwhere(function ($query) use ($tags) {

                foreach ($tags as $tag) {
                    $query->orWhere('tags', 'LIKE', '%' . $tag . '%');
                }

            });

        if ($groupIds) {
            $g = Group::whereIn('parent', $groupIds)->orWhereIn('id', $groupIds)->pluck('id');

            $query = $query->whereIn('group_id', $g);

        }

        if ($shopId)
            $query = $query->where('shop_id', $shopId);

        if ($orderBy)
            $query = $query->orderBy($orderBy, $dir);
        elseif ($orderByRaw)
            $query = $query->orderByRaw($orderByRaw);
        else
            $query = $query->inRandomOrder();

        $query = $query->with('shop');

//        $data = $query->offset($page - 1)->limit($paginate)->get();
        $data = $query->paginate($paginate, ['*'], 'page', $page);

        foreach ($data as $idx => $item) {
            $img = \App\Models\Image::on(env('DB_CONNECTION'))->where('type', 'p')->where('for_id', $item->id)->inRandomOrder()->first();
            if ($img)
                $item['img'] = $img->id . '.jpg';
        }

        return $data;
    }
}
