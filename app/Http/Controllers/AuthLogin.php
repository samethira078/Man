<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthLogin extends Controller {
    //Doordat er Laravel passport authenticatie wordt gebruikt, gebruiken we tokens om met de front-end te communiceren.
    public function login( Request $request ) {
        //client voor de api requests
        $http = new Client( [
            //Basis url met /api/
            'base_uri' => config( 'services.passport.login_url' ),
        ]);
        try {
            //Probeer in te loggen, gelukt? Sla de token op
            $response = $http->post( '/oauth/token', [
                'json' => [
                    'grant_type'    => 'password',
                    'client_id'     => config( 'services.passport.client_id' ),
                    'client_secret' => config( 'services.passport.client_key' ),
                    'username'      => $request->username,
                    'password'      => $request->password,
                ]
            ] );
            //Response naar Vue om de token in localstorage op te slaan zodat de gebruiker ingelogd kan blijven op de website
            $response = json_decode( $response->getBody(), true );
            return json_encode($response['access_token']);
        } catch ( BadResponseException $e ) {
            //Login mislukt
            return response($e->getCode(), 400);
        }
    }
    //Creeer een account
    public function register( Request $request ) {
        return User::create( [
            'email'    => $request->username,
            'password' => Hash::make( $request->password ),
        ] );
    }
    //Uitloggen
    public function logout() {
        //Verwijderd alle tokens die gelinkt zijn met het gebruikt zodat de gebruiker op ieder apparaat geen toegang meer krijgt om in te loggen.
        auth()->user()->tokens->each( function ( $token, $key ) {
            $token->delete();
        } );
        //Logout confirm
        return response()->json( 'Logged out', 200 );
    }
}
