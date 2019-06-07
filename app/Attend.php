<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attend extends Model
{
    //
    use CompositePrimaryKeyTrait;
    /**
     * 複合キー
     * @var array
     */
    protected $fillable = [
        'userId',
        'attend',
        'scheduleId',
        'candidateId',
    ];

    protected $primaryKey = ['candidateId', 'userId'];
    //protected $primaryKey = 'userId';

    public $timestamps = false;

    public $incrementing = false;

    public function candidate() {
        return $this->belongsTo('App\Candidate', 'candidateId', 'candidateId');
    }

    public function user() {
        return $this->belongsTo('App\User', 'userId');
    }
}
