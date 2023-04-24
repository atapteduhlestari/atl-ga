<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetChild extends Model
{
    use HasFactory;

    protected $table = 'asset_child';
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function trnRenewal()
    {
        return $this->hasMany(TrnRenewal::class, 'asset_child_id');
    }

    public function sdb()
    {
        return $this->belongsTo(SDB::class, 'sdb_id');
    }

    public function sbu()
    {
        return $this->belongsTo(SBU::class, 'sbu_id');
    }

    public function loan()
    {
        return $this->hasMany(Loan::class, 'asset_child_id');
    }

    public function trnSDBDetail()
    {
        return $this->hasOne(TrnSDBDetail::class, 'asset_child_id');
    }

    public function getTakeDocAttribute()
    {
        return "/storage/{$this->file}";
    }

    public static function getAllLastTransaction($time)
    {

        return DB::table('trn_renewal')
            ->select(['trn_renewal.*', 'trn_renewal.id as trn_id',  'asset_child.*', 'asset_renewal.*', 'sbu.sbu_name'])
            ->join('asset_child', 'trn_renewal.asset_child_id', 'asset_child.id')
            ->join('asset_renewal', 'trn_renewal.renewal_id', 'asset_renewal.id')
            ->join('sbu', 'trn_renewal.sbu_id', 'sbu.id')
            // ->groupBy('asset_renewal.name', 'asset_child.doc_name')
            ->where('trn_status', false)
            ->where('trn_date', '<=', $time);
    }

    public static function getLastTransaction($time)
    {
        // return AssetChild::join(
        //     'trn_renewal',
        //     fn ($q) => $q->on('asset_child.id', '=', 'trn_renewal.asset_child_id')
        //         ->whereRaw('trn_renewal.id IN (select MAX(a2.id) from trn_renewal as a2 join asset_child as u2 on u2.id = a2.asset_child_id group by u2.id)')
        //         ->whereDate('trn_renewal.trn_date', '<=', $time)
        // )->join('asset_renewal', 'trn_renewal.renewal_id', '=', 'asset_renewal.id')->get();
        DB::statement("SET SQL_MODE=''");

        if (isSuperadmin()) {
            return DB::table('trn_renewal')
                ->select(['trn_renewal.*', 'trn_renewal.id as trn_id',  'asset_child.*', 'asset_renewal.*', ' sbu.sbu_name'])
                ->join('asset_child', 'trn_renewal.asset_child_id', 'asset_child.id')
                ->join('asset_renewal', 'trn_renewal.renewal_id', 'asset_renewal.id')
                ->join('sbu', 'trn_renewal.sbu_id', 'sbu.id')
                // ->groupBy('asset_renewal.name', 'asset_child.doc_name')
                ->where('trn_status', false)
                ->where('trn_date', '<=', $time);
        } else {
            return DB::table('trn_renewal')
                ->select(['trn_renewal.*', 'trn_renewal.id as trn_id',  'asset_child.*', 'asset_renewal.*', 'sbu.sbu_name'])
                ->join('asset_child', 'trn_renewal.asset_child_id', 'asset_child.id')
                ->join('asset_renewal', 'trn_renewal.renewal_id', 'asset_renewal.id')
                ->join('sbu', 'trn_renewal.sbu_id', 'sbu.id')
                // ->groupBy('asset_renewal.name', 'asset_child.doc_name')
                ->where('trn_date', '<=', $time)
                ->where('trn_status', false)
                ->where('trn_renewal.sbu_id', userSBU());
        }
    }
}
