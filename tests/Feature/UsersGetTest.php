<?php

namespace Tests\Feature;

use SimpleXMLElement;
use Tests\TestCase;

class UsersGetTest extends TestCase
{
    public function testUsersGet()
    {
        $response = $this->get('/api/get-users/xml');
        $response->assertStatus(200);
        $this->assertEquals(10, $this->getUsersAmount($response->content()));
    }

    private function getUsersAmount($xmlData)
    {
        $xml = new SimpleXMLElement($xmlData);
        $json = json_decode(json_encode($xml, JSON_PRETTY_PRINT));
        if ($json->user) {
            return count($json->user);
        }

        return 0;
    }
}
