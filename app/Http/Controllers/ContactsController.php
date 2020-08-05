<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactsController extends Controller
{
    public function __invoke() {


        return response()->json($this->getContacts());
        // return response()->json([]);
    }

    private function getContacts() {
        $response = Http::withHeaders(
            ['Api-Token' => env("AC_TOKEN")]
        )->get(env("AC_BASE_URL") . "contacts?include=accountContacts.account");

        return $response->json();
    }
}
