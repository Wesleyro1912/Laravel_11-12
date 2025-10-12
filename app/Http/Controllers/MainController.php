<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Encryption\DecryptException;

class MainController extends Controller
{
    public function index() {
        // Load user's notes
        $id = session('user.id');
        $notes = $this->UserModel
                        ->find($id)
                        ->notes()
                        ->whereNull('deleted_at')
                        ->get()
                        ->toArray();

        // show home view
         return view('home', ['notes' => $notes]);

    }

    public function newNote() {
        // Show new view
        return view('new_note');
    }

    public function newNoteSubmit(Request $request) {

        // Form Validation
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000',
            ],

            // Messagens
            [
                'text_title.required' => 'Campo titlo é obrigatório',
                'text_title.min' => 'Campo Note Title deve ter no mínimo :min caracteres',
                'text_title.max' => 'Campo Note Title deve ter no máximo :max caracteres',

                'text_note.required' => 'Campo Note Text é obrigatório',
                'text_note.min' => 'Campo Note Text deve ter no mínimo :min caracteres',
                'text_note.max' => 'Campo Note Text deve ter no máximo :max caracteres',
            ]
        );

        // get user id
        $id = session('user.id');

        // create new note
        $note = $this->NoteModel;
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // redirect to homw
        return redirect()->route('home');
    
    }

    public function editNote(string $id) {
        try {
            // $id_note = $this->decryptId($id);
            $id_note = $this->Operations->decryptId($id);

            if($id_note === null) {
                return redirect()->route('home');
            }

            if (!$id_note) {
                return redirect()->route('home')->with('error', 'ID inválido ou não encontrado.');
            }

            Log::info("Acessando nota para edição. ID descriptografado: {$id_note}");

            // load note
            $note = $this->NoteModel->find($id_note);

            // show edit note view
            return view('edit_note', ['note' => $note]); 

        } catch (DecryptException $e) {
            Log::warning('Erro ao descriptografar o ID: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'ID inválido.');
        } catch (\Exception $e) {
            Log::error('Erro inesperado em editNote: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Erro interno no servidor.');
        }
    }

    public function editNoteSubmit(Request $request) {

        // Form Validation
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000',
            ],

            // Messagens
            [
                'text_title.required' => 'Campo titlo é obrigatório',
                'text_title.min' => 'Campo Note Title deve ter no mínimo :min caracteres',
                'text_title.max' => 'Campo Note Title deve ter no máximo :max caracteres',

                'text_note.required' => 'Campo Note Text é obrigatório',
                'text_note.min' => 'Campo Note Text deve ter no mínimo :min caracteres',
                'text_note.max' => 'Campo Note Text deve ter no máximo :max caracteres',
            ]
        );

        // check if note_id exists
        if($request->note_id == null){
            return redirect()->to('home');
        }

        // decrypt note_id
        $id = $this->Operations->decryptId($request->note_id); 

        if($id === null) {
                return redirect()->route('home');
            }

        // load note
        $note = $this->NoteModel->find($id);

        // update note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // redirect to home
        return redirect()->route('home');
    }

    public function deleteNote(string $id) {
         try {
            // $id_note = $this->decryptId($id);
            $id_note = $this->Operations->decryptId($id);

            if($id_note === null) {
                return redirect()->route('home');
            }

            if (!$id_note) {
                return redirect()->route('home')->with('error', 'ID inválido ou não encontrado.');
            }

            Log::info("Acessando nota para delete. ID descriptografado: {$id_note}");

            $note = $this->NoteModel->find($id_note);
            return view('delete_note', ['note' => $note]);

        } catch (DecryptException $e) {
            Log::warning('Erro ao descriptografar o ID: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'ID inválido.');
        } catch (\Exception $e) {
            Log::error('Erro inesperado em deleteNote: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Erro interno no servidor.');
        }
       
        echo $id_note;
    }

    public function deleteNoteConfirm(string $id) {

        $id_note = $this->Operations->decryptId($id);

        if($id_note === null) {
                return redirect()->route('home');
            }

        //  load note
        $note = $this->NoteModel->find($id_note); 

        // 1. had delete
        // $note->delete();

        // 2. soft delete
        // $note->deleted_at = date('Y:m:d H:i:s');
        // $note->save();

        // 3. soft delet (property in model)
        // $note->delete();

        // 4. hard delet (property in model)
        $note->forceDelete();

        // redirect to home
        return redirect()->route('home');
    }

  

}
