<?php

namespace App\Repositories;

use App\Models\Post;
use Carbon\Carbon;

class PostRepository extends BaseRepository
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function getlist($request)
    {
        $limit = (int)$request->input('length');
        $start = (int)$request->input('start');
        $orderColumn = (int)$request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');
        $searchValue = $request->input('search.value');

        $orderColumns = ['id', 'title', 'category_id', 'user_id', 'pay', 'status', 'description', 'created_at'];
        $totalPosts = $this->model->count();

        $query = $this->model->offset($start)
            ->limit($limit);

        if (!empty($orderColumn)) {
            $query->orderBy($orderColumns[$orderColumn], $orderDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('category_id', 'like', "%{$searchValue}%")
                    ->orWhere('user_id', 'like', "%{$searchValue}%")
                    ->orWhere('status', 'like', "%{$searchValue}%")
                    ->orWhere('pay', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhere('created_at', 'like', "%{$searchValue}%");
            });
        }

        $posts = $query->get();
        $data = [];
        $status = config('common.status');
        $pay = config('common.pay');
        foreach ($posts as $post) {
            $statusText = $status[$post->status] ?? '';
            $payText = $pay[$post->pay] ?? '';
            $data[] = [
                'title' => $post->title,
                'category_id' => $post->category->name ?? '',
                'user_id' => $post->user->name ?? '',
                'pay' => $payText,
                'priority' => $post->priority,
                'status' => $statusText,
                'created_at' => Carbon::parse($post->created_at)->format(config('common.format_date')),
                'id' => $post->id
            ];
        }

        $response = [
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => (int)$totalPosts,
            'recordsFiltered' => (int)$totalPosts,
            'data' => $data
        ];

        return response()->json($response);
    }

    public function getBySlug(string $slug)
    {
        $posts = $this->model->where('slug', $slug)->first();
        if($posts) {
            return $posts;
        }
    }
}
