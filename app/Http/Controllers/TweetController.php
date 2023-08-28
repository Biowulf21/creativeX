<?php

namespace App\Http\Controllers;

use App\Http\Repositories\TweetRepository\TweetRepositoryInterface;
use App\Http\Requests\Tweet\NewTweetRequest;
use App\Http\Requests\Tweet\UpdateTweetRequest;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TweetController extends Controller
{

    private $tweet_repository;
    public function __construct(TweetRepositoryInterface $tweet_repository){
        $this->tweet_repository = $tweet_repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->tweet_repository->getTweets();
    }

    /**
     * Show the form for creating a new resource.
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
    }

    public function getUserTweets(int $user_id): JsonResponse
    {
        $user = User::findOrfail($user_id);
        return $this->tweet_repository->getUserTweets($user_id);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewTweetRequest $request)
    {
        return $this->tweet_repository->createTweet($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->tweet_repository->getTweet($id);
    }

    /**
     * Show the form for editing the specified resource.
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @return JsonResponse
     */
    public function update(UpdateTweetRequest $request, int $id): JsonResponse
    {
        return  $this->tweet_repository->updateTweet($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return $this->tweet_repository->deleteTweet($id);
    }
}
