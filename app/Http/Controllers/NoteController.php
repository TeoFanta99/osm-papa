<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Note::orderBy('created_at', 'desc')->get();

        return view ('pages.notes', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('pages.newNote');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ], [
            'title.required' => 'Il campo "Titolo" è obbligatorio.',
            'title.max' => 'Il titolo non può superare i 255 caratteri.',
            'description.required' => 'Il campo "Descrizione" è obbligatorio.',
        ]);

        $note = new Note();
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->save();

        return redirect('notes')->with('success', 'Fattura e rata create con successo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note = Note::find($id);

        return view ('pages.editNote', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ], 
        [
            'title.required' => 'Il campo "Titolo" è obbligatorio e non può essere lasciato vuoto',
            'description.required' => 'Il campo "Testo della nota" è obbligatorio e non può essere lasciato vuoto'
        ]
    );

        // Trova la nota per ID
        $note = Note::findOrFail($id);

        // Aggiorna i campi della nota
        $note->title = $request->input('title');
        $note->description = $request->input('description');

        // Salva la nota aggiornata nel database
        $note->save();

        // Reindirizza o restituisci una risposta adeguata
        return redirect('notes')->with('success', 'Nota aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return redirect('notes')->with('success', 'Nota cancellata con successo!');
    }
}
