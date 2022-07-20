<?php

namespace YoutubeDownloader\Application;

use Exception;

/**
 * The download controller
 */
class DownloadController extends ControllerAbstract
{
	/**
	 * Excute the Controller
	 *
	 * @param string $route
	 * @param YoutubeDownloader\Application\App $app
	 *
	 * @return void
	 */
	public function execute()
	{
		$config = $this->get('config');
		$toolkit = $this->get('toolkit');

		// Check download token
		if (empty($_GET['mime']) OR empty($_GET['token']))
		{
			$this->responseWithErrorMessage('Invalid download token 8{');
		}

		// Set operation params
		$mime = filter_var($_GET['mime']);
		$ext = str_replace(['/', 'x-'], '', strstr($mime, '/'));
		$url = base64_decode(filter_var($_GET['token']));
		$name = urldecode($_GET['title']) . '.' . $ext;
		//echo $ext;
		

		// Fetch and serve
		if ($url)
		{
			
			$my_ch = curl_init($url);


		curl_setopt($my_ch, \CURLOPT_HEADER, true);
		curl_setopt($my_ch, \CURLOPT_NOBODY, true);
		curl_setopt($my_ch, \CURLOPT_RETURNTRANSFER, true);
		curl_setopt($my_ch, \CURLOPT_TIMEOUT, 10);
		curl_setopt($my_ch, \CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($my_ch, \CURLOPT_SSL_VERIFYPEER, 0);
		$r = curl_exec($my_ch);

		foreach (explode("\n", $r) as $header)
		{
			if (strpos($header, 'Content-Length:') === 0)
			{
				$size = trim(substr($header, 16));
			}
		}
		
		header('Access-Control-Allow-Credentials: true');  
		header('Access-Control-Allow-Headers: Accept, X-Access-Token, X-Application-Name, X-Request-Sent-Time');  
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS');  
		
		header("Content-Type: video/mp4");
		header("Content-Length: ".$size);
		

		readfile($url);
			/*
			//echo $mime;
			//exit;
			// Generate the server headers
			header('Content-Type: "' . $mime . '"');
			//header('Content-Disposition: attachment; filename="' . $name . '"');
			//header("Content-Transfer-Encoding: binary");
			//header('Expires: 0');
			//header('Content-Length: '.$size);
			header('Pragma: no-cache');
			header('Access-Control-Allow-Origin: *');  

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
			{
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
			}
			*/
			
			//readfile($url);
			exit;
		}
		// Not found
		$this->responseWithErrorMessage('File not found 8{');
	}
}
