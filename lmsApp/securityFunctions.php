<?php

    $sitekey = 'KLJKjlH988989h89Hp98hpjhgFG786GF6gKJBB7878GLGjbLJ';

	function encrypt_string($str,$key,$base64_encode_result=true)
    {
        if ($str == '')
            return '';
        $td = mcrypt_module_open('blowfish','','ecb','');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        if ($base64_encode_result)
            $encrypted_data = base64_encode($encrypted_data);
        return $encrypted_data;
    }
    
    
    function decrypt_string($str,$key,$string_is_base64_encoded=true)
    {
        if ($str == '')
            return '';
        if ($string_is_base64_encoded)
            $str = base64_decode($str);
        $td = mcrypt_module_open('blowfish','','ecb','');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $decrypted_data = mdecrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);        
        return $decrypted_data;
    }
    
    
    function get_encrypted_password($password,$rawhash)
    {
        // Select Blowfish Algorithm
        $hashtype = '$2y$';
        
        // Blowfish Cost parameter
        $hashcost = '11';
        
        // MD% the hash so it won't be so recognisable
        $hash = md5($rawhash);
        
        // Generate salt
        $salt = $hashtype . $hashcost . '$' . $hash;
        
        // Entrypt the password                        
        $encryptedpassword = crypt($password, $salt);
        
        return $encryptedpassword;
    }
    
    
    function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
    	$sets = array();
    	if(strpos($available_sets, 'l') !== false)
    		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
    	if(strpos($available_sets, 'u') !== false)
    		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    	if(strpos($available_sets, 'd') !== false)
    		$sets[] = '23456789';
    	if(strpos($available_sets, 's') !== false)
    		$sets[] = '!@#$%&*?';
    
    	$all = '';
    	$password = '';
    	foreach($sets as $set)
    	{
    		$password .= $set[array_rand(str_split($set))];
    		$all .= $set;
    	}
    
    	$all = str_split($all);
    	for($i = 0; $i < $length - count($sets); $i++)
    		$password .= $all[array_rand($all)];
    
    	$password = str_shuffle($password);
    
    	if(!$add_dashes)
    		return $password;
    
    	$dash_len = floor(sqrt($length));
    	$dash_str = '';
    	while(strlen($password) > $dash_len)
    	{
    		$dash_str .= substr($password, 0, $dash_len) . '-';
    		$password = substr($password, $dash_len);
    	}
    	$dash_str .= $password;
    	return $dash_str;
    }
    
    
	  
?>