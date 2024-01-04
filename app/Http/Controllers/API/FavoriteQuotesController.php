<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FavoriteQuote;
use Illuminate\Support\Facades\Validator;
class FavoriteQuotesController extends BaseController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $favoriteQuotes = $user->favoriteQuotes;

        return $this->sendResponse($favoriteQuotes, 'Favorite Quotes retrieved successfully.');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'quote' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $quote = $request->input('quote');

        $favoriteQuote = new FavoriteQuote([
            'quote' => $quote,
        ]);

        $user->favoriteQuotes()->save($favoriteQuote);

        return $this->sendResponse($favoriteQuote, 'Favorite Quote added successfully.');
    }


    public function delete($id)
    {
        $user = Auth::user();
        $favoriteQuote = FavoriteQuote::find($id);

        if (!$favoriteQuote) {
            return response()->json(['message' => 'Favorite Quote not found.'], 404);
        }

        // Verificar si el usuario autenticado es dueño de la cita favorita
        if ($favoriteQuote->user_id != $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        // Realizar el soft delete de la cita favorita
        $favoriteQuote->delete();

        return response()->json(['message' => 'Favorite Quote deleted successfully.']);
    }
}

