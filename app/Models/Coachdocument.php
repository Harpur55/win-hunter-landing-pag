<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachDocument extends Model
{
    protected $table = 'coach_document';

    protected $fillable = [
        'document',
        'coach_id',
        'document_name',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
}
