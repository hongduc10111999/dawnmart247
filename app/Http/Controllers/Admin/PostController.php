<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postService;

    public function __construct(
        PostService $postService
    ) {
        $this->postService = $postService;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        return $this->postService->index($request);
    }
}
