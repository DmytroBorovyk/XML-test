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

    public function testUsersGetWithAmount()
    {
        $amount = 3;
        $response = $this->get('/api/get-users/xml?amount=' . $amount);
        $response->assertStatus(200);
        $this->assertEquals($amount, $this->getUsersAmount($response->content()));

        $response = $this->get('/api/get-users/xml?amount=test');
        $response->assertStatus(422);
        $this->assertEquals("The amount must be an integer.", json_decode($response->content())->message);
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
