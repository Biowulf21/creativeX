<?php

namespace App\Http\Repositories\TweetRepository;

use Symfony\Component\HttpFoundation\JsonResponse;

interface TweetRepositoryInterface
{
    /**
     * @return JsonResponse
     * @param array<int,mixed> $data
     */

    public function createTweet(array $data);

    /**
     * @return JsonResponse
     */

    public function getTweet(int $id);

    /**
     * @return JsonResponse
     void*/

    public function getTweets();

    /**
     * @return JsonResponse
     */

    public function deleteTweet(int $id);
}
