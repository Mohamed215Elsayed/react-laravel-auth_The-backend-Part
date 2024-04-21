<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::query()
            ->where('user_id', request()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(1);
        return view('note.index', ['notes' => $notes]);
    
    }
    //note  user_id
    public function create()
    {
        return view('note.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'note' => ['required', 'string']
        ]);
        $data['user_id'] = $request->user()->id;
        $note = Note::create($data);
        return to_route('note.show', $note)->with('message', 'Note was create');
    }

    public function show(Note $note)
    {
        if ($note->user_id !== request()->user()->id) {
            abort(403);
        }
        return view('note.show', ['note' => $note]);
    }

    public function edit(Note $note)
    {
        if ($note->user_id !== request()->user()->id) {
            abort(403);
        }
        return view('note.edit', ['note' => $note]);
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== request()->user()->id) {
            abort(403);
        }
        $data = $request->validate([
            'note' => ['required', 'string']
        ]);
        $note->update($data);
        return to_route('note.show', $note)->with('message', 'Note was updated');
    }
    public function destroy(Note $note)
    {
        if ($note->user_id !== request()->user()->id) {
            abort(403);
        }
        $note->delete();
        return to_route('note.index')->with('message', 'Note was deleted');
    }
}
