<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $fillable = ["class_name", "school_id"];
    protected $table = "classes";

    public function school()
    {
        return $this->belongsTo(School::class, "school_id");
    }
    public function students()
    {
        return $this->hasMany(Classes::class, "class_id");
    }
}
