<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Horses;
use Illuminate\Http\Request;

class UserManage extends Controller {
    //Pak de gebruiker informatie
    function userInfo() {
        return response()->json( [
            'name' => auth()->user()->email,
            'rank' => auth()->user()->rank,
        ] );
    }

    //Voeg een paard toe
    function add_horse( Request $request ) {
        //Converteer request image naar datum als naam + extensie.
        $image = time() . '.' . $request->file( 'file' )->getClientOriginalExtension();
        //Voeg paard toe
        Horses::insert( [
            'naam'     => $request->name,
            'leeftijd' => $request->age,
            'type'     => $request->race,
            'bio'      => $request->bio,
            'img'      => $image
        ] );
        //Als true, voeg de tijdelijk afbeelding toe aan folder.
        $request->file( 'file' )->move( public_path() . '\src\assets\paarden', $image );
    }

    //Pak de paarden
    function horses() {
        $horses = Horses::all();

        return $horses;
    }

    function tijden( Request $request ) {
        //Array uit tijden
        $times = [
            '08:00-09:00',
            '09:00-10:00',
            '10:00-11:00',
            '11:00-12:00',
            '12:00-13:00',
            '13:00-14:00',
            '14:00-15:00',
            '16:00-17:00',
            '17:00-18:00'
        ];
        //Pak de gebruikte tijden waar de paard datum & tijd gelijk zijn.
        $date = Booking::where( [
            [ 'datum', '=', $request['date'] ],
            [ 'paard_id', '=', $request['horse'] ]
        ] )->pluck( 'tijd' )->toArray();
        //Converteer ze weg uit lijst
        $result = array_diff( $times, $date );

        //Stuur als array terug naar vue promise
        return json_encode( array_values( $result ) );
    }

    public function order( Request $request ) {
        //Valideer of de tijden geldig invoermethode is
        $request->validate( [
            'tijd' => [ 'required', 'string', 'regex:/^[0-9]{2}:[0-9]{2}-[0-9]{2}:[0-9]{2}/' ],
            'date' => [ 'required', 'string', 'regex:/^[0-9]{4}-[0-9]{2}-[0-9]{2}/' ],
        ], [
            'tijd.required' => 'Tijd is leeg',
            'tijd.regex'    => 'Geen geldig tijd formaat',
            'date.required' => 'Datum is leeg',
            'date.regex'    => 'Geen geldig datum formaat',
        ] );

        // Validatie succes, doorgaan met updaten of toevoegen.
        return Booking::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'datum'    => $request->date,
                'tijd'     => $request->tijd,
                'paard_id' => $request->paard_id,
                'user_id'  => auth()->user()->id,
            ] );
    }

    public function orders() {
        //Pak de onbetaalde aankopen en join paard gegevens
        $orders = Booking::where( [
            [ 'user_id', '=', auth()->user()->id ],
            [ 'betaald', '=', 0 ]
        ] )->join( 'horses', 'horses.id', '=', 'paard_id' )->get( array( 'bookings.*', 'horses.naam', 'horses.img' ) );
        echo json_encode( $orders );
    }

    //Verwijder een bestelling
    public function removeOrders( Request $request ) {
        if(auth()->user()->rank == 1){
            return Booking::where( [
                [ 'id', '=', $request['id'] ]
            ] )->delete();
        }
        return Booking::where( [
            [ 'user_id', '=', auth()->user()->id ],
            [ 'id', '=', $request['id'] ]
        ] )->delete();
    }
    //Afronding van bestelling creeert unieke code en voert gegevens in tabel.
    public function submit_order( Request $request ) {
        foreach ( $request->horses as $key ) {
            $referral_code = random_int( 100000, 999999 );
            Booking::where( [
                [ 'user_id', '=', auth()->user()->id ],
                [ 'id', '=', $key ]
            ] )
                   ->update( [
                       'fullname' => $request->naam,
                       'adres'    => $request->adres,
                       'nummer'   => $request->nummer,
                       'betaald'  => true,
                       'unique'   => $referral_code
                   ] );
        }
    }

    //Pak de betaalde paarden van gebruiker
    public function user_horses() {
        return Booking::where( [
            [ 'user_id', '=', auth()->user()->id ],
            [ 'betaald', '=', '1' ]
        ] )->join( 'horses', 'horses.id', '=', 'paard_id' )->get();
    }
    //Verwijder paard.
    //Note: Ook alle geboekte paarden met zelfde ID worden verwijderd. Eigelijk kan dit natuurlijk niet in het echt, maar daar maak je ook weer een ander
    //systeem voor. Als ik de boeking niet weghaal = bug.
    public function removeHorse( Request $request ) {
        Horses::where( [
            [ 'id', '=', $request['id'] ]
        ] )->delete();
        Booking::where( [
            [ 'paard_id', '=', $request['id'] ]
        ] )->delete();
    }

    //Verwijder gebruiker
    public function remove_user( Request $request ) {
        if(auth()->user()->rank == 1) {
            \App\Models\User::where( [
                [ 'id', '=', $request['id'] ]
            ] )->delete();
        }
    }

    //Update paard
    public function update_horse( Request $request ) {
        if(auth()->user()->rank == 1) {
            return Horses::where( 'id', $request->id )->update(
                [
                    'naam'     => $request->name,
                    'bio'      => $request->bio,
                    'leeftijd' => $request->age,
                    'type'     => $request->type,
                ] );
        }
    }

    public function update_order_admin( Request $request ) {
        if(auth()->user()->rank == 1) {
            return Booking::where( 'id', $request->id )->update(
                [
                    'fullname' => $request->fullname,
                    'adres'    => $request->adres,
                    'datum'    => $request->datum,
                    'tijd'     => $request->tijd,
                ] );
        }
    }

    //Update gebruiker
    public function update_user( Request $request ) {
        if(auth()->user()->rank == 1) {
            return \App\Models\User::where( 'id', $request->id )->update(
                [
                    'rank'  => $request->rank,
                    'email' => $request->mail
                ] );
        }
    }

    //Pak de gebruikers
    public function user_list() {
        if(auth()->user()->rank == 1) {
            $users = \App\Models\User::all();

            return $users;
        }
    }

    public function all_orders() {
        if ( auth()->user()->rank == 1 ) {
            return Booking::where( [
                [ 'betaald', '=', '1' ]
            ] )->join( 'users', 'users.id', '=', 'user_id' )->get( array( 'bookings.*', 'users.email' ) );
        }
    }
}
