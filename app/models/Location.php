<?php

class Location extends Eloquent {
    protected $table = 'location';

    public function toArray()
    {
        return ['latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'accuracy' => $this->accuracy,
                'updated' => $this->updated_at];
    }

    public function user()
    {
        return $this->belongsTo('User');
    }
}