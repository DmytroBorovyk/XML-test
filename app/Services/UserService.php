<?php

namespace App\Services;

use App\Transformers\XmlTransformer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class UserService
{
    public function __construct(private readonly XmlTransformer $transformer)
    {
    }

    /**
     * @throws \Exception
     */
    public function getUsers($request): Response|Application|ResponseFactory
    {
        $users = $this->extractUserData(
            $this->retrieveUsersArray(array_key_exists('amount', $request) ? $request['amount'] : 10)
        );
        $this->orderUsers($users);
        $xml = $this->transformer->transformJson($users);
        return response($xml->asXML(), 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * @throws \Exception
     */
    private function retrieveUsersArray(int $amount = 10): array
    {
        $users = [];
        for ($i = 0; $i < $amount; $i++) {
            $users[] = $this->makeApiRequest();
        }

        return $users;
    }

    /**
     * @throws \Exception
     */
    private function makeApiRequest(): object
    {
        try {
            $user = json_decode(Http::get(env('API_ENDPOINT'))->body());
            if (!$user) {
                throw new \Exception('No data', 404);
            }

            return array_shift($user->results);
        } catch (\Exception $e) {
            throw new \Exception('External api is down ' . $e->getMessage(), 500);
        }
    }

    private function extractUserData($users): array
    {
        return array_reduce($users, function ($carry, $user) {
            $carry[] = [
                'full_name' => $user->name->title . ' ' . $user->name->first . ' ' . $user->name->last,
                'phone' => $user->phone ?? $user->cell,
                'email' => $user->email,
                'country' => $user->location->country,
            ];
            return $carry;
        }, []);
    }

    private function orderUsers(array &$users): void
    {
        usort($users, function ($a, $b) {
            $lastNameA = explode(' ', $a["full_name"]);
            $lastNameA = end($lastNameA);
            $lastNameB = explode(' ', $b["full_name"]);
            $lastNameB = end($lastNameB);

            return strcasecmp($lastNameB, $lastNameA);
        });
    }
}
