<?php
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

//change string
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //no change
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}

function unescape($str) 
{ 
	$ret = ''; 
	$len = strlen($str); 
	for ($i = 0; $i < $len; $i++) { 
		if ($str[$i] == '%' && $str[$i+1] == 'u') { 
			$val = hexdec(substr($str, $i+2, 4)); 
			if ($val < 0x7f) $ret .= chr($val); 
			else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f)); 
			else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f)); 
			$i += 5; 
		} 
		else if ($str[$i] == '%') { 
			$ret .= urldecode(substr($str, $i, 3)); 
			$i += 2; 
		} 
		else $ret .= $str[$i]; 
	} 
	return $ret; 
} 

function get_groups($user,$password) {
	$ldap_url = '';
	$ldap_domain = '';
	$M = M('Setting');
	$ldap_dn = $M->where("setting_name='BaseDN'")->getField('setting_value');
	if (!$ldap_dn) {
		$ldap_dn = "DC=viasystems,DC=pri";
	}
	$ldap_url = $M->where("setting_name='LdapServer'")->getField('setting_value');
	if (!$ldap_url) {
		$ldap_url = '10.65.8.51';
	}
	$ldap_domain = $M->where("setting_name='UserDomain'")->getField('setting_value');
	if (!$ldap_domain) {
		$ldap_domain="vspri";
	}
	$ldap = ldap_connect( $ldap_url );
	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
	$login = @ldap_bind( $ldap, "$user@$ldap_domain", $password ); 
	if(!$login){
		return '';
	}
	// Search AD
	$results = @ldap_search($ldap,$ldap_dn,"(&(objectCategory=person)(samaccountname=$user))",array("memberof","primarygroupid"));
	$entries = @ldap_get_entries($ldap, $results);
	// No information found, bad user
	if($entries['count'] == 0) return false;
	
	// Get groups and primary group token
	$output = $entries[0]['memberof'];
	$token = $entries[0]['primarygroupid'][0];
	
	// Remove extraneous first entry
	array_shift($output);
	
	// We need to look up the primary group, get list of all groups
	$results2 = @ldap_search($ldap,$ldap_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
	$entries2 = @ldap_get_entries($ldap, $results2);
	
	// Remove extraneous first entry
	array_shift($entries2);
	
	// Loop through and find group with a matching primary group token
	foreach($entries2 as $e) {
		if($e['primarygrouptoken'][0] == $token) {
			// Primary group found, add it to output array
			$output[] = $e['distinguishedname'][0];
			// Break loop
			break;
		}
	}
	return $output;
}

function getSetting ($name) {
	$M = M('Setting');
	$val = $M->where("setting_name='".$name."'")->getField('setting_value');
	return $val;
}

function TextToImage(
	  $text, 
	  $separate_line_after_chars=40,  
	  $font='./myfont.ttf', 
	  $size=24,
	  $rotate=0,
	  $padding=2,
	  $transparent=false, 
	  $color=array('red'=>0,'grn'=>0,'blu'=>0), 
	  $bg_color=array('red'=>255,'grn'=>255,'blu'=>255) 
){
    $amount_of_lines= ceil(strlen($text)/$separate_line_after_chars);
    $x=explode("\n", $text); $final='';
    foreach($x as $key=>$value){
        $returnes='';
        do{ $first_part=mb_substr($value, 0, $separate_line_after_chars, 'utf-8');
            $value= "\n".mb_substr($value, $separate_line_after_chars, null, 'utf-8');
            $returnes .=$first_part;
        }  while( mb_strlen($value,'utf-8')>$separate_line_after_chars);
        $final .= $returnes."\n";
    }
    $text=$final;
    $width=$height=$offset_x=$offset_y = 0;
    // get the font height.
    $bounds = ImageTTFBBox($size, $rotate, $font, "W");
    if ($rotate < 0)        {$font_height = abs($bounds[7]-$bounds[1]); } 
    elseif ($rotate > 0)    {$font_height = abs($bounds[1]-$bounds[7]); } 
    else { $font_height = abs($bounds[7]-$bounds[1]);}
    // determine bounding box.
    $bounds = ImageTTFBBox($size, $rotate, $font, $text);
    if ($rotate < 0){       $width = abs($bounds[4]-$bounds[0]);                    
							$height = abs($bounds[3]-$bounds[7]);
                            $offset_y = $font_height;                               
							$offset_x = 0;
    } 
    elseif ($rotate > 0) {  $width = abs($bounds[2]-$bounds[6]);                    
							$height = abs($bounds[1]-$bounds[5]);
                            $offset_y = abs($bounds[7]-$bounds[5])+$font_height;    
							$offset_x = abs($bounds[0]-$bounds[6]);
    } 
    else{                   $width = abs($bounds[4]-$bounds[6]);                    
							$height = abs($bounds[7]-$bounds[1]);
                            $offset_y = $font_height;                               
							$offset_x = 0;
    }

    $image = imagecreate($width+($padding*2)+1,$height+($padding*2)+1);

    $background = ImageColorAllocate($image, $bg_color['red'], $bg_color['grn'], $bg_color['blu']);
    $foreground = ImageColorAllocate($image, $color['red'], $color['grn'], $color['blu']);

    if ($transparent) ImageColorTransparent($image, $background);
    ImageInterlace($image, true);
	// render the image
    ImageTTFText($image, $size, $rotate, $offset_x+$padding, $offset_y+$padding, $foreground, $font, $text);
    imagealphablending($image, true);
    imagesavealpha($image, true);
	return $image;
}



function isApprover () {
	$approvers = getSetting('TqApprovers');
	if (strstr(strtoupper($approvers),strtoupper($_SESSION['tqLoginUserName']))) {
		return 1;
	} else {
		return 0;
	}
}

function getADUsersData ($user,$pass,$for_user = '') {
	//$M = M('Setting');
	// do ldap check here:
	//$ldap_server = $M->where("setting_name='LdapServer'")->getField('setting_value');
	//if (!$ldap_server) {
		$ldap_server = '10.65.8.51';
	//}
//	$domain = $M->where("setting_name='UserDomain'")->getField('setting_value');
	//if (!$domain) {
		$domain="vspri";
	//}
	
	//$base_dn = $M->where("setting_name='BaseDN'")->getField('setting_value');
	//if (!$base_dn) {
		$base_dn = 'DC=viasystems,DC=pri';
	//}
	
	$conn_ = ldap_connect($ldap_server);
	if(!$conn_){
			$this->error('Connect to LDAP server error!');
	}	
	ldap_set_option($conn_, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($conn_, LDAP_OPT_REFERRALS, 0);
	ldap_set_option($conn_, LDAP_OPT_SIZELIMIT , 10);
	$bind = ldap_bind($conn_, "$domain\\".$user, $pass);
	if ($for_user != '') {
		$user = $for_user;
	}
	$attributes = array("displayname", "mail","telephonenumber","manager", "department",'memberof');
	$results = ldap_search($conn_,$base_dn,"(&(objectCategory=person)(samaccountname=$user))",$attributes);
	$entries = ldap_get_entries($conn_, $results);
	$vo = '';
	if($entries["count"] > 0){
		$vo['displayname'] =$entries[0]['displayname'][0]; 
        $vo['department'] =$entries[0]['department'][0];
		$vo['email'] =$entries[0]['mail'][0];
		$vo['telephonenumber'] =$entries[0]['telephonenumber'][0];
		$groups=array();
        foreach( $entries[0]['memberof'] as $e){     
	     $groups[]=  str_replace('CN=','',explode(',',$e)[0]);
         };
		$vo['memberof'] =implode(',',$groups);
		$manager_result = ldap_search($conn_,$entries[0]['manager'][0],'(objectCategory=person)',array("displayname", "mail","telephonenumber"));
		$manager_entries = ldap_get_entries($conn_, $manager_result);
		if($manager_entries["count"] > 0){
			$vo['manager_email'] = $manager_entries[0]['mail'][0];
			$vo['manager_phone'] = $manager_entries[0]['telephonenumber'][0];
			$vo['manager_name'] = $manager_entries[0]['displayname'][0];
		}
	}
	ldap_unbind($conn_);
	return $vo;
}

function getCurrentSkin () {
	$skin = 'background:#53a93f;color:white;';
	$ck = $_COOKIE['current-skin'];
	if (strstr($ck,'gray')) {
		$skin = 'background:#585858;color:white;';
	} elseif (strstr($ck,'black')) {
		$skin = 'background:#474544;color:white;';
	}
	return $skin;
}

function msubstr1($str,$length)   
{   
	$start=0;
	$charset="utf-8"; 
	$suffix=true;
    if(function_exists("mb_substr")){    
		if($suffix)
		{
			if (strlen($str)>$length)
			{
			return mb_substr($str, $start, $length, $charset).'...'; 
			}
			else
			{
			return mb_substr($str, $start, $length, $charset);
			}
		}
      else  
		return mb_substr($str, $start, $length, $charset);    
       }   
    elseif(function_exists('iconv_substr')) {   
        if($suffix)
		{
			if (strlen($str)>$length) 
			{
				return iconv_substr($str,$start,$length,$charset);
			}
			else 
			{
				return iconv_substr($str,$start,$length,$charset);
			}
		}
       else  
       return iconv_substr($str,$start,$length,$charset);   
    }   
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";   
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";   
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";   
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";   
    preg_match_all($re[$charset], $str, $match);   
    $slice = join("",array_slice($match[0], $start, $length));   
	if($suffix) 
	{
		if (strlen($str)>$length) 
			return $slice;
		else 
			return $slice;
	}
	  
    return $slice;   
}

function s_mail($fromAddress,$from_nickname,$sendto,$ccAddress, $title, $response) {
	$Email = M("Emails");
	$vo["date"] = date('Y-m-d H:i:m',time());
	$vo["type"] = "project_status";
	$vo["sender"] = $fromAddress;
	$vo["send_name"] = $from_nickname;
	$vo["to"] = $sendto;
	$vo["cc"] = $ccAddress;
	$vo["subject"] = $title;
	$vo["content"] = $response;
	$vo["complete"] = 0;
	$Email->data($vo)->add();
}

function generalIssueColor ($status,$created_by) {
	if ($status ==0 and $created_by == 'System') {
		return 1;
	} else {
		return 0;
	}
}

function blankDate($date) {
	if ($date == '0000-00-00 00:00:00') {
		return '';
	} else {
		return $date;
	}
}
function random($length, $chars = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
	$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
?>