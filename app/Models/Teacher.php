<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;


    protected $fillable = [
        "teacher_name",
        "teacher_address",
        "teacher_phone",
        "teacher_salary",
        "teacher_genre",
        "teacher_photo",
        "teacher_birthdate",
        "teacher_hire_date",
        "school_id",
        'teacher_national_number',
        'user_id'
    ];

    /*
    public function school()
    {
        return $this->belongsTo(School::class, "school_id");
    }
*/

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function getClassesAttribute() {
        $classes_ids = $this->subjects->pluck('class_id');
        return Classes::findMany($classes_ids);
    }
}
