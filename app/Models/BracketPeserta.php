<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BracketPeserta extends Model

{

    use HasFactory;

    protected $table = 'bracket_peserta';

    protected $guarded = ['id'];



    public function kelasPertandingan()

    {

        return $this->belongsTo(KelasPertandingan::class);

    }

    public function player()

    {

        return $this->belongsTo(Player::class);

    }
    

}