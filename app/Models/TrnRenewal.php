<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrnRenewal extends Model
{
    use HasFactory;

    protected $table = 'trn_renewal';
    protected $guarded = ['id'];

    public function document()
    {
        return $this->belongsTo(AssetChild::class, 'asset_child_id');
    }

    public function renewal()
    {
        return $this->belongsTo(Renewal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sbu()
    {
        return $this->belongsTo(SBU::class, 'sbu_id');
    }

    public function getTakeDocAttribute()
    {
        return "/storage/{$this->file}";
    }

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['start']  ?? false, function ($query, $from) {
            return $query->whereDate('trn_start_date', '>=', $from);
        });

        $query->when($filters['end']  ?? false, function ($query, $to) {
            return $query->whereDate('trn_date', '<=', $to);
        });

        $query->when($filters['sbu_id'] ?? false, function ($query, $sbu) {
            return $query->whereHas('sbu', function ($q) use ($sbu) {
                $q->where('sbu_id', $sbu);
            });
        });

        $query->when($filters['asset_id'] ?? false, function ($query, $asset) {
            return $query->whereHas('document', function ($q) use ($asset) {
                if ($asset == 'empty')
                    $q->whereNull('asset_id');
                else
                    $q->where('asset_id', $asset);
            });
        });

        $query->when($filters['renewal_id'] ?? false, function ($query, $id) {
            return $query->whereHas('renewal', function ($q) use ($id) {
                $q->where('renewal_id', $id);
            });
        });

        $query->when($filters['status']  ?? false, function ($query, $status) {
            return $query->where('trn_status', $status);
        });

        $query->when($filters['trn_type']  ?? false, function ($query, $type) {
            return $query->where('trn_type', $type);
        });
    }

    public function scopeSearch($query, $filters)
    {
        $query->when($filters['start_date']  ?? false, function ($query, $from) {
            return $query->whereDate('trn_start_date', '>=', $from);
        });

        $query->when($filters['due_date']  ?? false, function ($query, $to) {
            return $query->whereDate('trn_date', '<=', $to);
        });

        $query->when($filters['sbu_search_id'] ?? false, function ($query, $sbu) {
            return $query->whereHas('sbu', function ($q) use ($sbu) {
                $q->where('sbu_id', $sbu);
            });
        });

        $query->when($filters['asset_search_id'] ?? false, function ($query, $asset) {
            return $query->whereHas('document', function ($q) use ($asset) {
                if ($asset == 'empty')
                    $q->whereNull('asset_id');
                else
                    $q->where('asset_id', $asset);
            });
        });

        $query->when($filters['renewal_search_id'] ?? false, function ($query, $renewal) {
            return $query->whereHas('renewal', function ($q) use ($renewal) {
                $q->where('renewal_id', $renewal);
            });
        });

        $query->when($filters['status']  ?? false, function ($query, $status) {
            return $query->where('trn_status', $status);
        });

        $query->when($filters['trn_type']  ?? false, function ($query, $type) {
            return $query->where('trn_type', $type);
        });
    }
}
