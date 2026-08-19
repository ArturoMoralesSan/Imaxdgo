<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Giveaway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Participant;
use App\Models\Response;
use Illuminate\Support\Str;

class GiveawayController extends Controller
{
    /**
     * Listado
     */
    public function index()
    {
        $giveaways = Giveaway::withCount('questions')
        ->get();

        $giveaways->transform(function ($giveaway) {

        $giveaway->starts_at_formatted = $giveaway->starts_at
            ? $giveaway->starts_at
                ->timezone('America/Monterrey')
                ->format('d/m/Y h:i A')
            : null;

        $giveaway->ends_at_formatted = $giveaway->ends_at
            ? $giveaway->ends_at
                ->timezone('America/Monterrey')
                ->format('d/m/Y h:i A')
            : null;

        return $giveaway;
    });

        return view('giveaways.index', compact('giveaways'));
    }


    /**
     * Formulario crear
     */
    public function create()
    {
        return view('giveaways.create');
    }


    /**
     * Guardar
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'questions_count' => 'required|integer|min:1',
        ]);

        $giveaway = Giveaway::create([
            'name' => $request->name,
            'active' => $request->active,
            'description' => $request->description,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ]);

        $questionsCount = (int) $request->questions_count;

        for ($i = 0; $i < $questionsCount; $i++) {

            $type = $request->input("questions{$i}_type");

            $rules = [
                "questions{$i}_question" => 'required|string|max:500',
                "questions{$i}_type" => 'required|in:boolean,multiple',
                "questions{$i}_show_user" => 'required|boolean',
            ];

            if ($type === 'multiple') {

                $rules["questions{$i}_option_a"] =
                    'required|string|max:500';

                $rules["questions{$i}_option_b"] =
                    'required|string|max:500';

                $rules["questions{$i}_option_c"] =
                    'required|string|max:500';

                $rules["questions{$i}_option_d"] =
                    'required|string|max:500';

                $rules["questions{$i}_correct_option"] =
                    'required|in:A,B,C,D';
            }

            $request->validate($rules);

            $giveaway->questions()->create([
                'question' => $request->input(
                    "questions{$i}_question"
                ),

                'type' => $type,

                'show_user' => $request->boolean(
                    "questions{$i}_show_user"
                ),

                'option_a' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_a")
                    : null,

                'option_b' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_b")
                    : null,

                'option_c' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_c")
                    : null,

                'option_d' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_d")
                    : null,

                'correct_option' => $type === 'multiple'
                    ? $request->input("questions{$i}_correct_option")
                    : null,
            ]);
        }

        alert('Se ha agregado un sorteo correctamente.');

        return response('', 204, [
            'Redirect-To' => url('admin/sorteos/')
        ]);
    }

    public function edit($id)
    {
        $giveaway = Giveaway::with('questions')->findOrFail($id);

        if ($giveaway->starts_at) {
            $giveaway->starts_at = $giveaway->starts_at->format('Y-m-d\TH:i');
        }

        if ($giveaway->ends_at) {
            $giveaway->ends_at = $giveaway->ends_at->format('Y-m-d\TH:i');
        }

        #dd($giveaway);
        return view('giveaways.edit', compact('giveaway'));
    }


    /**
     * Actualizar giveaway
     */
    public function update(Request $request, $id)
    {
        $giveaway = Giveaway::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'questions_count' => 'required|integer|min:1',
        ]);

        $giveaway->update([
            'name' => $request->name,
            'active' => $request->active,
            'description' => $request->description,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ]);

        // Elimina las preguntas actuales
        $giveaway->questions()->delete();

        $questionsCount = (int) $request->questions_count;

        for ($i = 0; $i < $questionsCount; $i++) {

            $type = $request->input("questions{$i}_type");

            $rules = [
                "questions{$i}_question" => 'required|string|max:500',
                "questions{$i}_type" => 'required|in:boolean,multiple',
                "questions{$i}_show_user" => 'required|boolean',
            ];

            if ($type === 'multiple') {

                $rules["questions{$i}_option_a"] =
                    'required|string|max:500';

                $rules["questions{$i}_option_b"] =
                    'required|string|max:500';

                $rules["questions{$i}_option_c"] =
                    'required|string|max:500';

                $rules["questions{$i}_option_d"] =
                    'required|string|max:500';

                $rules["questions{$i}_correct_option"] =
                    'required|in:A,B,C,D';
            }

            $request->validate($rules);

            $giveaway->questions()->create([
                'question' => $request->input(
                    "questions{$i}_question"
                ),

                'type' => $type,

                'show_user' => $request->boolean(
                    "questions{$i}_show_user"
                ),

                'option_a' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_a")
                    : null,

                'option_b' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_b")
                    : null,

                'option_c' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_c")
                    : null,

                'option_d' => $type === 'multiple'
                    ? $request->input("questions{$i}_option_d")
                    : null,

                'correct_option' => $type === 'multiple'
                    ? $request->input("questions{$i}_correct_option")
                    : null,
            ]);
        }

        alert('Se ha actualizado un sorteo correctamente.');

        return response('', 204, [
            'Redirect-To' => url('admin/sorteos/')
        ]);
    }

    
    

    public function participant($id)
{
    $giveaway = Giveaway::with('questions')
        ->findOrFail($id);

    if (! $giveaway->active) {
        abort(404);
    }

    return view('giveaways.participant', compact('giveaway'));
}


public function participate(Request $request, $id)
{
    $giveaway = Giveaway::with('questions')
        ->findOrFail($id);

    $request->validate([
        'instagram' => 'required|string|max:255',
        'answers' => 'nullable|array',
    ]);

    $participant = Participant::create([
        'giveaway_id' => $giveaway->id,
        'instagram' => $request->instagram,
        'folio' => strtoupper(
            'IMAX-' . Str::random(8)
        ),
        'status' => 'started',
    ]);

    $answers = $request->input('answers', []);

    foreach ($giveaway->questions as $question) {

        if ($question->type !== 'multiple') {
            continue;
        }

        $answer = $answers[$question->id] ?? null;

        if ($answer === null) {
            continue;
        }

        $isCorrect = strtoupper($answer) === strtoupper(
            $question->correct_option
        );

        Response::create([
            'participant_id' => $participant->id,
            'question_id' => $question->id,
            'answer' => $answer,
            'is_correct' => $isCorrect ? 1 : 0,
            'verified' => 0,
        ]);
    }

    $participant->update([
        'status' => 'completed',
    ]);

    return response()->json([
        'success' => true,
        'participant_id' => $participant->id,
        'folio' => $participant->folio,
        'redirect' => route(
            'giveaways.participant.result',
            [
                'id' => $giveaway->id,
                'participant' => $participant->id,
            ]
        ),
    ]);
}


public function participantResult($id, $participant)
{
    $giveaway = Giveaway::findOrFail($id);

    $participant = Participant::where('id', $participant)
        ->where('giveaway_id', $giveaway->id)
        ->with('responses.question')
        ->firstOrFail();

    return view(
        'giveaways.result',
        compact(
            'giveaway',
            'participant'
        )
    );
}


public function validateParticipant()
{
    return view('giveaways.show');
}

public function findParticipant($folio)
{
    $folio = 'IMAX-' . strtoupper(trim($folio));

    $participant = Participant::with([
        'giveaway.questions',
        'responses.question'
    ])
    ->where('folio', $folio)
    ->first();

    if (!$participant) {
        return response()->json([
            'message' => 'No se encontró ningún participante con ese folio.'
        ], 404);
    }

    $responses = $participant->responses;

    $participant->multiple_responses = $responses
        ->filter(function ($response) {
            return $response->question &&
                $response->question->type === 'multiple';
        })
        ->values();

    $participant->boolean_questions = $participant->giveaway->questions
        ->filter(function ($question) use ($responses) {

            if ($question->type !== 'boolean') {
                return false;
            }

            // Buscar respuesta del participante
            $response = $responses->firstWhere(
                'question_id',
                $question->id
            );

            // Si ya fue verificada, no se muestra
            if ($response && $response->verified) {
                return false;
            }

            return true;
        })
        ->values();

    return response()->json([
        'participant' => $participant
    ]);
}


public function validateParticipantStore(Request $request)
{
    $request->validate([
        'participant_id' => 'required|exists:participants,id',
        'responses' => 'required|array',
    ]);

    $participant = Participant::findOrFail(
        $request->participant_id
    );

    foreach ($request->responses as $questionId => $answer) {

        // Verificar que la pregunta pertenece al giveaway
        // y que realmente es de tipo boolean
        $question = $participant->giveaway
            ->questions()
            ->where('questions.id', $questionId)
            ->where('type', 'boolean')
            ->first();

        if (!$question) {
            continue;
        }

        // Buscar si ya existe una respuesta para esta pregunta
        $response = $participant->responses()
            ->where('question_id', $question->id)
            ->first();

        if ($response) {

            // Ya existe: solamente actualizamos la validación
            $response->update([
                'answer'      => $answer,
                'verified'    => true,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

        } else {

            // No existe porque el participante nunca contestó esta
            // pregunta boolean. La creamos nosotros.
            $participant->responses()->create([
                'question_id' => $question->id,
                'answer'      => $answer,
                'is_correct'  => true,
                'verified'    => true,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'La participación fue validada correctamente.',
    ]);
}



}
