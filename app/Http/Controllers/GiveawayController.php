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

    /*
     * ==========================================
     * GENERAR FOLIO CONSECUTIVO POR GIVEAWAY
     * ==========================================
     */

    $lastParticipant = Participant::where('giveaway_id', $id)
        ->whereNotNull('folio')
        ->orderByDesc('id')
        ->first();

    $nextNumber = 1;

    if ($lastParticipant) {
        $nextNumber = ((int) substr($lastParticipant->folio, -2)) + 1;
    }

    $participant = Participant::create([
        'giveaway_id' => $giveaway->id,
        'instagram' => $request->instagram,
        'folio' => 'IMAX-' . strtoupper(Str::random(2)) . str_pad(
            $nextNumber,
            2,
            '0',
            STR_PAD_LEFT
        ),
        'status' => 'started',
        'type' => null,
    ]);

    $answers = $request->input('answers', []);

    $totalQuestions = 0;
    $correctAnswers = 0;
    $incorrectAnswers = 0;
    $results = [];

    /*
     * ==========================================
     * EVALUAR SOLAMENTE PREGUNTAS MULTIPLE
     * ==========================================
     *
     * Las BOOLEAN se ignoran aquí.
     * Se validarán posteriormente.
     */

    foreach ($giveaway->questions as $question) {

        /*
         * Solo preguntas que se muestran al usuario.
         */
        if (
            !(
                $question->show_user === true ||
                $question->show_user === 1 ||
                $question->show_user === '1'
            )
        ) {
            continue;
        }

        /*
         * Las BOOLEAN no se evalúan aquí.
         */
        if ($question->type === 'boolean') {
            continue;
        }

        /*
         * Solo MULTIPLE participa en el resumen.
         */
        if ($question->type !== 'multiple') {
            continue;
        }

        $answer = $answers[$question->id] ?? null;

        /*
         * Si no respondió una pregunta MULTIPLE,
         * se considera incorrecta.
         */
        if ($answer === null) {

            $totalQuestions++;
            $incorrectAnswers++;

            $results[] = [
                'question_id' => $question->id,
                'question' => $question->question,
                'answer' => null,
                'correct_answer' => $question->correct_option,
                'is_correct' => false,
            ];

            /*
             * Guardamos también la respuesta vacía.
             */
            Response::create([
                'participant_id' => $participant->id,
                'question_id' => $question->id,
                'answer' => null,
                'is_correct' => 0,
                'verified' => 0,
            ]);

            continue;
        }

        $totalQuestions++;

        $isCorrect =
            strtoupper(trim((string) $answer)) ===
            strtoupper(trim((string) $question->correct_option));

        if ($isCorrect) {
            $correctAnswers++;
        } else {
            $incorrectAnswers++;
        }

        Response::create([
            'participant_id' => $participant->id,
            'question_id' => $question->id,
            'answer' => $answer,
            'is_correct' => $isCorrect ? 1 : 0,
            'verified' => 0,
        ]);

        $results[] = [
            'question_id' => $question->id,
            'question' => $question->question,
            'answer' => $answer,
            'correct_answer' => $question->correct_option,
            'is_correct' => $isCorrect,
        ];
    }

    

    return response()->json([
        'success' => true,
        'participant_id' => $participant->id,
        'folio' => $participant->folio,

        'total_questions' => $totalQuestions,
        'correct_answers' => $correctAnswers,
        'incorrect_answers' => $incorrectAnswers,

        'results' => $results,

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

    /*
     * ==========================================
     * DETECTAR PREGUNTAS BOOLEAN
     * ==========================================
     */

    $hasBooleanQuestions = $participant->giveaway->questions
        ->contains(function ($question) {

            return $question->type === 'boolean' &&
                (
                    $question->show_user === true ||
                    $question->show_user === 1 ||
                    $question->show_user === '1'
                );
        });

    /*
     * ==========================================
     * SI YA FUE VALIDADO
     * ==========================================
     *
     * Si validateParticipantStore() ya determinó
     * el premio, respetamos ese resultado.
     */

    if ($participant->status === 'validated') {

        $prizeType = $participant->prize_type;

        $allCorrect = $prizeType === 'Premio principal';

    } elseif ($hasBooleanQuestions) {

        /*
         * ==========================================
         * TIENE BOOLEAN PENDIENTE
         * ==========================================
         */

        $prizeType = 'Sin premio';

        $allCorrect = false;

        $participant->update([
            'prize_type' => 'Sin premio',
            'prize_delivered' => 0,
        ]);

    } else {

        /*
         * ==========================================
         * SOLO MULTIPLE
         * ==========================================
         */

        $multipleQuestions = $participant->giveaway->questions
            ->filter(function ($question) {

                return $question->type === 'multiple' &&
                    (
                        $question->show_user === true ||
                        $question->show_user === 1 ||
                        $question->show_user === '1'
                    );
            });

        $allCorrect = true;

        foreach ($multipleQuestions as $question) {

            $response = $responses->firstWhere(
                'question_id',
                $question->id
            );

            /*
             * Si no existe respuesta,
             * es incorrecta.
             */

            if (!$response) {
                $allCorrect = false;
                break;
            }

            /*
             * Comparar directamente la respuesta
             * contra la opción correcta.
             */

            $answer = strtoupper(
                trim((string) $response->answer)
            );

            $correctAnswer = strtoupper(
                trim((string) $question->correct_option)
            );

            $isCorrect = $answer === $correctAnswer;

            /*
             * Actualizamos is_correct para mantener
             * la respuesta sincronizada.
             */

            $response->update([
                'is_correct' => $isCorrect ? 1 : 0,
            ]);

            if (!$isCorrect) {
                $allCorrect = false;
                break;
            }
        }

        /*
         * ==========================================
         * DETERMINAR PREMIO
         * ==========================================
         */

        $prizeType = $allCorrect
            ? 'Premio principal'
            : 'Consolación';

        /*
         * ==========================================
         * GUARDAR PREMIO
         * ==========================================
         */

        $participant->update([
            'prize_type' => $prizeType,
            'prize_delivered' => 1,
            'status'  => 'validated'
        ]);
    }

    /*
     * ==========================================
     * ACTUALIZAR VALORES PARA LA RESPUESTA
     * ==========================================
     */

    $participant->prize_type = $prizeType;

    $participant->prize_delivered =
        (int) $participant->prize_delivered;

    /*
     * ==========================================
     * RESPUESTAS MULTIPLE
     * ==========================================
     */

    $participant->multiple_responses = $responses
        ->filter(function ($response) {

            return $response->question &&
                $response->question->type === 'multiple';
        })
        ->values();

    /*
     * ==========================================
     * BOOLEAN PENDIENTES
     * ==========================================
     */

    $participant->boolean_questions = $participant->giveaway->questions
        ->filter(function ($question) use ($responses) {

            if ($question->type !== 'boolean') {
                return false;
            }

            $response = $responses->firstWhere(
                'question_id',
                $question->id
            );

            /*
             * Si ya fue validada, no aparece
             * como pendiente.
             */

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

    $participant = Participant::with([
        'giveaway.questions',
        'responses'
    ])->findOrFail($request->participant_id);

    /*
     * ==========================================
     * VALIDAR PREGUNTAS BOOLEAN
     * ==========================================
     */

    foreach ($request->responses as $questionId => $answer) {

        $question = $participant->giveaway
            ->questions()
            ->where('questions.id', $questionId)
            ->where('type', 'boolean')
            ->first();

        if (!$question) {
            continue;
        }

        /*
         * Convertir correctamente la respuesta.
         */
        $isCorrect = filter_var(
            $answer,
            FILTER_VALIDATE_BOOLEAN
        );

        $response = $participant->responses()
            ->where('question_id', $question->id)
            ->first();

        if ($response) {

            $response->update([
                'answer' => $answer,
                'is_correct' => $isCorrect ? 1 : 0,
                'verified' => true,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

        } else {

            $participant->responses()->create([
                'question_id' => $question->id,
                'answer' => $answer,
                'is_correct' => $isCorrect ? 1 : 0,
                'verified' => true,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
        }
    }

    /*
     * ==========================================
     * VALIDAR MULTIPLE
     * ==========================================
     */

    $multipleQuestions = $participant->giveaway
        ->questions()
        ->where('type', 'multiple')
        ->where(function ($query) {
            $query->where('show_user', true)
                ->orWhere('show_user', 1)
                ->orWhere('show_user', '1');
        })
        ->get();

    $allMultipleCorrect = true;

    foreach ($multipleQuestions as $question) {

        $response = $participant->responses()
            ->where('question_id', $question->id)
            ->first();

        if (
            !$response ||
            (int) $response->is_correct !== 1
        ) {
            $allMultipleCorrect = false;
            break;
        }
    }

    /*
     * ==========================================
     * VALIDAR BOOLEAN
     * ==========================================
     */

    $booleanQuestions = $participant->giveaway
        ->questions()
        ->where('type', 'boolean')
        ->where(function ($query) {
            $query->where('show_user', true)
                ->orWhere('show_user', 1)
                ->orWhere('show_user', '1');
        })
        ->get();

    $allBooleanCorrect = true;

    foreach ($booleanQuestions as $question) {

        $response = $participant->responses()
            ->where('question_id', $question->id)
            ->first();

        /*
         * Debe existir respuesta y debe ser TRUE.
         */
        if (
            !$response ||
            (int) $response->is_correct !== 1
        ) {
            $allBooleanCorrect = false;
            break;
        }
    }

    /*
     * ==========================================
     * DETERMINAR PREMIO
     * ==========================================
     */

    $allCorrect =
        $allMultipleCorrect &&
        $allBooleanCorrect;

    $prizeType = $allCorrect
        ? 'Premio principal'
        : 'Consolación';

    /*
     * ==========================================
     * GUARDAR PREMIO
     * ==========================================
     */

    $participant->update([
        'prize_type' => $prizeType,
        'status' => 'validated',
        'prize_delivered' => 1,
    ]);

    /*
     * Recargar para devolver los valores
     * realmente guardados.
     */
    $participant->refresh();

    return response()->json([
        'success' => true,
        'message' => 'La participación fue validada correctamente.',
        'participant_id' => $participant->id,
        'folio' => $participant->folio,
        'prize_type' => $participant->prize_type,
        'all_correct' => $allCorrect,
        'all_multiple_correct' => $allMultipleCorrect,
        'all_boolean_correct' => $allBooleanCorrect,
        'status' => $participant->status,
        'prize_delivered' => $participant->prize_delivered,
    ]);
}


public function participants($id)
{
    $giveaway = Giveaway::with('questions')->findOrFail($id);

    $totalQuestions = $giveaway->questions->count();

    $participants = Participant::where('giveaway_id', $giveaway->id)
        ->with('responses.question')
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($participant) use ($totalQuestions) {

            $responses = $participant->responses;

            $answeredQuestions = $responses
                ->filter(function ($response) {
                    return $response->question !== null;
                })
                ->count();

            $correctAnswers = $responses
                ->where('is_correct', 1)
                ->count();

            return [
                'id' => $participant->id,

                'folio' => $participant->folio,

                'instagram' => $participant->instagram,

                'status' => $participant->status,

                // Preguntas
                'questions_answered' => $answeredQuestions,

                'questions_total' => $totalQuestions,

                'questions_result' =>
                    $answeredQuestions . ' / ' . $totalQuestions,

                // Correctas
                'correct_answers' => $correctAnswers,

                // Premio
                'prize_type' => $participant->prize_type,

                'prize_delivered' =>
                    (bool) $participant->prize_delivered,

                // Fecha
                'created_at' => $participant->created_at,

                'created_at_formatted' => $participant->created_at
                    ? $participant->created_at->format('d/m/Y h:i A')
                    : null,

                // Respuestas
                'responses' => $responses,
            ];
        });

    return view(
        'giveaways.participants',
        compact(
            'giveaway',
            'participants'
        )
    );
}


}
