<?php

namespace App\Transformers;

class XmlTransformer
{
    public function transformJson(array|object $json): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<users></users>');
        if (gettype($json) === 'array') {
            foreach ($json as $item) {
                $this->handle($item, $xml);
            }
//            for ($i = count($json) - 1; $i >= 0; $i--) {
//                $this->handle($json[$i], $xml);
//            }
        } else {
            $this->handle($json->toArray(), $xml);
        }

        return $xml;
    }

    private function handle(array $jsonItem, \SimpleXMLElement $xml): void
    {
        $user = $xml->addChild('user');
        foreach ($jsonItem as $key => $value) {
            $user->addChild($key, $value);
        }
    }
}
