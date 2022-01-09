<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['name','local','description','section_id'];
    public $timestamps = false;
    protected $hidden = [
        'created_at', 'updated_at','section_id'
    ];

    /////////////////Begin relation here/////////////////////
    public function Section()
    {
        return $this->belongsTo(Section::class);
    }
}
