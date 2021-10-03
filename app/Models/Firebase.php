<?php
/**
 * Created by PhpStorm.
 * User: MSI GS72
 * Date: 29/09/2021
 * Time: 12:48 AM
 */

namespace App\Models;


use Kreait\Firebase\Factory;

class  Firebase

{

    public $database;

    public $auth;

    public $factory;

    public function __construct()

    {

        $this->factory = (new Factory)
            ->withServiceAccount('../firebase_credentials.json')
            ->withDatabaseUri(config('services.firebase.database_url'));


        $this->database = $this->factory->createDatabase();

        $this->auth = $this->factory->createAuth();

    }

}