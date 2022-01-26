<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Venturecraft\Revisionable\RevisionableTrait;


class GoodReceivedNote extends Model{

    use SoftDeletes, RevisionableTrait;

    protected $primaryKey = 'grn_id';

    protected $table = 'good_received_note';

    protected $fillable = [
        'grn_no',
        'grn_date',
        'grn_amt',
        'added_by'
    ];

    public static function getGRNNo(){
        $user = Auth::user();
        $newSoNo = NULL;
        $soNo = GoodReceivedNote::whereNull('deleted_at')->max('grn_no');
        if($soNo){
            $explodeString = explode("/",$soNo);
            $nxtNo = $explodeString[2] + 1;
            $newSoNo = 'POS/GRN'.'/' . str_pad($nxtNo,5,0,STR_PAD_LEFT);
        } else {
            $newSoNo = 'POS/GRN'.'/' . str_pad(1,5,0,STR_PAD_LEFT);
        }

        return $newSoNo;
    }

}
