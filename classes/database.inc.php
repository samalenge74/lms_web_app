<?php

// Copyright (c) 2006 Mark Jundo P. Documento

require_once("database_factory.inc.php");
require_once("datatable.inc.php");

abstract class Database
{
    // Clones the current instance of this class
    abstract function CloneObject();

    // Gets the unique name for this class
    abstract function GetName();

    // Connects to the database using host, user and password
    // as paramaeters. Uses database as default database.
    abstract function Connect($host, $database, $user, $password);

    // Returns true is connection is still open.
    abstract function IsOpen();

    // Closes the connection.
    abstract function Close();

    // Executes an SQL statement and returns a datatable object
    // if the SQL returns a row set
    abstract function Execute($sql);

    // Executes an SQL statement and returns a resource object
    // if the SQL returns a row set
    abstract function ExecuteRaw($sql);

    // Begins a transaction.
    abstract function Begin();

    // Ends the current transaction and saves
    // changes to the database.
    abstract function Commit();

    // Ends the current transaction and discards
    // changes to the database.
    abstract function Rollback();

    // Gets the last insert ID for auto increment fields
    abstract function GetLastInsertID();

    // Throws a new exception
    function ThrowException($message)
    {
        throw new Exception($message);
    }

    // Static functions
    static function AddSlashes($string)
    {
        return addslashes($string);
    }

    static function StripSlashes($string)
    {
        return stripslashes($string);
    }
};

?>