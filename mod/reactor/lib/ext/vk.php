<?php 

$_vk_idx_upload_image = 0;

function vk_uploadImage($group_id, $token, $file, $filename='image')
{
	global $_vk_idx_upload_image;
	$_vk_idx_upload_image++;
	$sVkImageId='';

	$sReq = 'https://api.vk.com/method/photos.getWallUploadServer?gid='.$group_id.'&access_token='.$token;
	$oResp = json_decode(file_get_contents($sReq));
	$sUploadUrl = $oResp->response->upload_url.'';

	$sHost = strstr( substr($sUploadUrl,7), '/', true );
	$aGetVars = explode( '&', substr(strstr($sUploadUrl,'?'),1) );
	$aGetVarsReady = array();
	foreach($aGetVars as $key => $value)
	{
		$aGetVarsReady[ strstr($value,'=',true) ] = substr(strstr($value,'='),1);
	}

	$sBoundary = md5(microtime());
	$sEntityBody = '';
	foreach($aGetVarsReady as $key => $value)
	{
		$sEntityBody.='--'.$sBoundary."\r\n".
			'Content-Disposition: form-data; name="'.$key.'"'."\r\n".
			"\r\n".$value."\r\n";
	}

	$sEntityBody.= '--'.$sBoundary."\r\n".
		'Content-Disposition: form-data; name="photo"; filename="'.$filename.$_vk_idx_upload_image.'.jpg"'."\r\n".
		'Content-Type: image/jpeg'."\r\n".
		'Content-Transfer-Encoding: binary'."\r\n".
		"\r\n".file_get_contents(FILE_DIR.$file)."\r\n".
		'--'.$sBoundary.'--'."\r\n";

	$sReq = 'POST '.strstr($sUploadUrl,'?',true).' HTTP/1.1'."\r\n".
			'Host: '.$sHost." \r\n".
			'Content-Type: multipart/form-data; boundary='.$sBoundary."\r\n".
			'Content-Length: '.strlen($sEntityBody)."\r\n".
			'Connection: close'."\r\n".
			"\r\n".$sEntityBody;

	$sResp = '';
	$hUpload = fsockopen($sHost,80);
	if($hUpload)
	{
		fwrite($hUpload, $sReq);
		while(!feof($hUpload))
		{
			$sResp.=fgets($hUpload,128);
		}
		fclose($hUpload);

		$sResp = explode("\r\n\r\n",$sResp);
		if( stripos($sResp[0],'chunked') !== false ) $sResp[1] = http_chunked_decode($sResp[1]);
		$oResp = json_decode($sResp[1]);

		$sReq = 'https://api.vk.com/method/photos.saveWallPhoto?gid='.$group_id.'&server='.$oResp->server.'&photo='.$oResp->photo.'&hash='.$oResp->hash.'&access_token='.$token;
		$oResp = json_decode(file_get_contents($sReq));

		$sVkImageId = $oResp->response[0]->id;
	}

	return $sVkImageId;
}


function vk_post($group_id, $token, $text, $images=array())
{
	$attachments = '';
	file_put_contents(SITE_DIR.'../vk_api.log', print_r($attachments,true));
	foreach ($images as $file)
	{
		$attachments .= ','.vk_uploadImage($group_id, $token, $file);
	}
	$attachments = substr($attachments, 1);

	$sReq = 'https://api.vk.com/method/wall.post?owner_id=-'.$group_id.'&message='.urlencode($text).'&attachments='.$attachments.'&access_token='.$token;
	$oResp = json_decode(file_get_contents($sReq));
	return $oResp;
}

?>