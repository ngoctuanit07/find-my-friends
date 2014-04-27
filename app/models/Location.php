<?php

class Location extends Eloquent {
    protected $table = 'location';

    public function toArray()
    {
        return ['lat' => $this->latitude];
    }
}