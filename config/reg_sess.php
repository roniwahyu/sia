<?php
	function reg_sess($name,$value)
	{
		require_once "new_session.php";
		
		session_register($name);
		$_SESSION[$name]	= $value;
		return $name;		
	}
?>