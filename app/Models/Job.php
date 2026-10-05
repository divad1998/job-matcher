<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Job extends Model
{
    protected $fillable = [
        'source', 'source_id', 'company',
        'title', 'location', 'description',
        'url','posted_at','hash',
        'match_score','match_reasons','match_concerns',
        'emailed_at'
    ];

    protected $casts = [
        'posted_at'=>'datetime',
        'emailed_at'=>'datetime',
        'match_reasons'=>'array',
        'match_concerns'=>'array'
    ];
}
