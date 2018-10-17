<?php
  class AUTHORIZE
  {
        static function Authenticate()
        {
            if(empty($_SESSION["user"]) || !isset($_SESSION["user"]))
            {
              return false;
            }
            return true;
        }
  }
?>