<?php

namespace App\Http\Controllers;

use App\Http\Repositories\TweetRepository\TweetRepositoryInterface;
use App\Http\Requests\Tweet\NewTweetRequest;
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
     */
    public function create()
    {
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
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->tweet_repository->deleteTweet($id);
    }
}
