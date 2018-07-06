<?php
namespace db;

function connect(){
  DB::connect();
}

function query($q, $safe=false){
  DB::query($q);
}
function are_results(){
    return is_object(DB::$result);
}
function query_single($q){

	DB::query($q);

  return DB::getSingle();

}
//@deprecated don't use anymore, use DB::getArray or DB::getObjects
function query_array( $q ){

    DB::query($q);
    return DB::getArray();
}

function get_array_full(){
  return DB::getArray();
}

function last_id(){
	return DB::lastId();
}

function prepare($statement){
  return DB::prepare($statement);
}

function execute($arguments, $statement=false){
  return DB::execute($arguments, $statement);
}
function rowCount( $statement=false){
  return DB::rowCount($statement);
}
function quote($string){
  return DB::$con->quote($string);
}

function getSqlWhereCompare($column, $text){
	return (strpos($text,'%')===false)? " $column= '$text' ": " $column LIKE '$text' ";
}

class DB{
    public static $con = null;
    public static $result = null;
    private static $lastQuery= null;
    public static $statement = null;

    static function connect(){
      try{
        self::$con = new \PDO("mysql:host=localhost;charset=utf8;dbname=".DBDB, DBUSER, DBPASS);

        //select the type of error produced
        self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      }catch(\PDOException $e){
        self::throwError("No se ha podido conectar a la base de datos", $e->getMessage());
      }
    }

    static function lastId(){
      if(is_null(self::$con))
        DB::connect();
      try{
        return self::$con->lastInsertId();

      }catch(\PDOException $e){
        self::throwError("No se ha podido leer el último id", $e->getMessage());
      }
    }
    /*
    * @param query complete query.
    * @return void
    */
    static function query($query){
      if(is_null(self::$con))
        DB::connect();
      try{
        self::$result= self::$con->query($query);
        self::$lastQuery= $query;

      }catch(\PDOException $e){
        self::throwError("No se ha podido ejecutar la query",  "query '$query', produciendo el error ".$e->getMessage());
      }
    }

    /*
    * Warning: only works once per query. To reuse the result, iterate over the result->fetch() directly
    * @param className Specifies the name of class to create the objects, 'stdClass' by default.
    * @return array of objects
    */
    static function getSingle(){
      if(is_null(self::$con))
        DB::connect();

      try{
        if(is_object(self::$result)){

          return self::$result->fetchColumn(0);
        }else
          self::throwError("El resultado de la query no es un objeto",  "query '".self::$lastQuery."', valor ".self::$result);

      }catch(\PDOException $e){
        self::throwError("No se ha podido ejecutar la query",  "query '".self::$lastQuery."', produciendo el error ".$e->getMessage());
      }
    }
    /*
    * Warning: only works once per query. To reuse the result, iterate over the result->fetch() directly
    * @param className Specifies the name of class to create the objects, 'stdClass' by default.
    * @return array of objects
    */
    static function getArray(){
      if(is_null(self::$con))
        DB::connect();
      try{
        if(is_object(self::$result)){

          return self::$result->fetchAll(\PDO::FETCH_ASSOC);
        }else
          self::throwError("El resultado de la query no es un objeto",  "query '".self::$lastQuery."', valor ".self::$result);

      }catch(\PDOException $e){
        self::throwError("No se ha podido ejecutar la query",  "query '".self::$lastQuery."', produciendo el error ".$e->getMessage());
      }
    }

    /*
    * Warning: only works once per query. To reuse the result, iterate over the result->fetch() directly
    * @param className Specifies the name of class to create the objects, 'stdClass' by default.
    * @return array of objects
    */
    static function getObjects( $className = "stdClass" ){
      if(is_null(self::$con))
        DB::connect();
      try{
        if(is_object(self::$result)){

          return self::$result->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className);
        }else
          self::throwError("El resultado de la query no es un objeto",  "query '".self::$lastQuery."', valor ".self::$result);

      }catch(\PDOException $e){
        self::throwError("No se ha podido ejecutar la query",  "query '".self::$lastQuery."', produciendo el error ".$e->getMessage());
      }
    }

    /*
    * FIXME does not allow to use variables from outside, by now is only a reference of good code
    * Calls passed function on every table's row
    * @param callable function that receives $row as argument
    * @return void
    */
    static function walk($callable){
      if(is_null(self::$con))
        DB::connect();

      try{
        if(is_callable($callable)){
          if(is_object(self::$result)){

            while( $row= self::$result->fetch()){
              $callable($row);
            }

          }else
            self::throwError("El resultado de la query no es un objeto",  "query '".self::$lastQuery."', valor ".self::$result);

        }else
          throw new \Exception("DB.walk# se debe pasar una función como argumento");

      }catch(\PDOException $e){
        self::throwError("No se ha podido ejecutar la query",  "query '$query', produciendo el error ".$e->getMessage());
      }
    }

    static function transact($queries){
      if(is_null(self::$con))
        DB::connect();

      try{
        self::$con->beginTransaction();
        foreach($queries as $query){
          self::$con->exec($query);
        }
        self::$con->commit();
      }catch(\PDOException $e){
        self::$con->rollback();

        self::throwError("error en transaccion, rolledback",  $e->getMessage());
      }
    }

    static function last_error(){
      return mysqli_error(self::$con);
    }

    static function prepare($statement){
      self::$statement= self::$con->prepare($statement);
      return self::$statement;
    }
    static function execute($arguments, $statement=false){
      if($statement!==false){
        return $statement->execute($arguments);

      }else if(self::$statement){
        return self::$statement->execute($arguments);

      }else{
        throw new \Exception("No se ha preparado el statement previamente");
        return false;
      }
    }

    static function rowCount($statement=false){
      if($statement!==false){
        return $statement->rowCount();

      }else if(self::$statement){
        return self::$statement->rowCount();

      }else{
        throw new \Exception("No se ha preparado el statement previamente");
        return false;
      }
    }

    static function throwError($message, $debugInfo){
      if(DEBUG)
        $message .= ". ".$debugInfo;

      throw new \Exception($message);

    }
}
?>
