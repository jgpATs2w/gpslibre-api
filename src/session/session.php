<?php
namespace session;

session_start();

function print_info(){
    echo "id: ". session_id() ."<br>";
    echo "path: ". session_save_path() ."<br>";
    echo "name: ". session_name() ."<br>";
    $status = session_status();
    if( $status == PHP_SESSION_DISABLED )
        echo "status: DISABLED<br>";
    else
    if( $status == PHP_SESSION_ACTIVE )
        echo "status: ACTIVE<br>";
    else
    if( $status == PHP_SESSION_NONE )
        echo "status: NONE<br>";

    echo "cookie params: "; print_r ( session_get_cookie_params() );
}
function printr(){
    print_r( $_SESSION );
}
function put( $key, $mixed ){//deprecated
    $_SESSION[ $key ] = $mixed;
}
function set( $key, $mixed ){
    $_SESSION[ $key ] = $mixed;
}
function get( $key ){
    if( isset( $_SESSION[ $key ] ) )
        return $_SESSION[ $key ];

    return false;
}
function remove( $key ){
    unset( $_SESSION[ $key ] );
    return true;
}
function destroy(){
    session_destroy();
    unset( $_SESSION );
}

?>
