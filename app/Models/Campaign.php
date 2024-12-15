<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['name', 'user_id', 'status'];

    public function contacts()
    {
        return $this->hasMany(CampaignContact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
