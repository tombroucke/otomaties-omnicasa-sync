<?php

namespace Otomaties\Omnicasa\Models;

use Otomaties\Omnicasa\Database\Status;
use Otomaties\Omnicasa\Helpers\Formatter;
use Otomaties\Omnicasa\Models\Abstracts\Post;

class Property extends Post
{
    public static function postType() : string
    {
        return 'property';
    }
    
    public function showPrice() : bool
    {
        return $this->getMeta('PublishPrice') === '1';
    }

    public function price() : string
    {
        return $this->getMeta('Price');
    }

    public function formattedPrice() : string
    {
        return $this->showPrice() ? Formatter::price($this->price()) : '';
    }
    
    public function address() : array
    {
        $street = $this->getMeta('Street');
        $streetNumber = $this->getMeta('HouseNumber');
        $postCode = $this->getMeta('Zip');
        $city = $this->getMeta('City');
        
        return [
            'street' => $street,
            'streetNumber' => $streetNumber,
            'postCode' => $postCode,
            'city' => $city,
        ];
    }

    public function formattedAddress() : string
    {
        $address = $this->address();
        return $address['street'] . ' ' . $address['streetNumber'] . '<br /> ' . $address['postCode'] . ' ' . $address['city'];
    }

    public function status() : int
    {
        return (int) $this->getMeta('Status');
    }

    public function formattedStatus() : string
    {
        return (new Status())->get($this->status())->name;
    }
}
