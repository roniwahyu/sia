<?php
	function password_user($pass)
	{
        $passmd5     = md5($pass);
        $create_protect = "494d175d9245bfb81cffea61f54aca0b";
        $password = md5($create_protect.md5($passmd5).$create_protect);
		$password_final = md5($password.$create_protect.md5($create_protect).md5($password).$passmd5);
		return $password_final;
	}
?>