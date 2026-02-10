<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    // Bagian ini PENTING. Semua kolom yang ingin disimpan wajib ditulis di sini.
    protected $fillable = [
        'user_id',
        'tool_id',
        'loan_date',
        'return_date',          // Tanggal Rencana Kembali
        'actual_return_date',   // Tanggal Asli Kembali
        'status',
        'fine',                 
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Tool (Alat)
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}