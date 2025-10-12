<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;

use Illuminate\Support\Facades\Crypt;
use \Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
abstract class Controller
{
    protected $UserModel;
    protected $NoteModel;
    protected $Operations;

    public function __construct() {
        $this->UserModel = new User;
        $this->NoteModel = new Note();
        $this->Operations = new Operations();
    }

    // Modificar para enviar parametros com array assosiativo
    public function respondWithError(int $statusCode, string $message): JsonResponse {
        return response()->json(['mensagem' => $message], $statusCode);
    }

    // public function decryptId(string $id) {
    //     try {
    //         $id_note = Crypt::decrypt($id);
    //         return $id_note;

    //     } catch (DecryptException $e) {
    //         return false;
    //     }
    // }
}
