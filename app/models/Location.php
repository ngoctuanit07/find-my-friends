<?php

class Location extends Eloquent {
    protected $table = 'location';

    public function toArray()
    {
        return ['latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'accuracy' => $this->accuracy,
                'created_at' => $this->created_at];
    }
}