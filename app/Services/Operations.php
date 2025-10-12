<?php 

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use \Illuminate\Contracts\Encryption\DecryptException;

class Operations {

     public static function decryptId(string $id) {
        try {
            $id_note = Crypt::decrypt($id);
            return (int) $id_note;

        } catch (DecryptException $e) {
            return null;
        }
    }
}