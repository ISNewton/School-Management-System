<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        "student_name",
        "student_address",
        "student_birthdate",
        "student_registered_date",
        "student_paid_price",
        "student_genre",
        "student_photo",
        "parent_id",
        "class_id",
        "school_id"
    ];
    protected $table = "students";

    public function school()
    {
        return $this->belongsTo(School::class, "school_id");
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, "class_id");
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, "parent_id");
    }
}
