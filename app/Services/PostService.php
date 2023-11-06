<?php

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostService extends BaseService {
     
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request) {
        if (!$request->ajax()) {
            return view('admin.post.index');
        }

        return $this->postRepository->getlist($request);
    }

    public function show(string $slug) {
        $post = $this->postRepository->getBySlug($slug);

        return $this->responseSuccess(["new_detal" => new PostResource($post)]) ;
    }
}
