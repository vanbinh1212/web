<?php
if (!function_exists('sql_inject_chec')) {
    function sql_inject_chec(){
        // Anti-SQL Injection
        //"--"
        $badwords = array("--",";","'",'"',"DROP", "SELECT", "UPDATE", "DELETE", "drop", "select", "update", "delete", "WHERE", "where","exec","EXEC","procedure","PROCEDURE") ;
        foreach($_REQUEST as $value)  {
            foreach($badwords as $word)
                if(substr_count($value, $word) > 0) {
                    die("SQL Injection Detected - Make sure only to use letters and numbers!\n<br />\nIP: ".$_SERVER['REMOTE_ADDR'].$value);
                }
        }
    }
}
sql_inject_chec();
?>