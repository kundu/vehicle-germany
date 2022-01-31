<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleDetail extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $fillable = ['manufacturer','model','fin'];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function lastEditedBy(){
        return $this->belongsTo(User::class, 'last_edited_by', 'id');
    }

}
