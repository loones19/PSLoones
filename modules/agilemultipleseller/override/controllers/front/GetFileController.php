<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class GetFileController extends GetFileControllerCore
{
	public function init()
	{
		if (Tools::getValue("is_seller") && isset($this->context->customer) && $this->context->customer->id && $this->context->customer->isLogged() && Tools::getValue('key'))
		{
						$filename = str_replace("-orderdetail","",Tools::getValue('key'));
			if (!Validate::isSha1($filename))
				die(Tools::displayError());

			$file = _PS_DOWNLOAD_DIR_.strval(preg_replace('/\.{2,}/', '.', $filename));
			
			$id_product_download = ProductDownload::getIdFromFilename($filename);
			$product_download = new ProductDownload($id_product_download);
			$id_product = $product_download->id_product;
			$id_seller = AgileSellerManager::getLinkedSellerID($this->context->customer->id);

			if($id_seller != AgileSellerManager::getObjectOwnerID('product',$id_product))die(Tools::displayError('No access permission'));
	
			$filename = ProductDownload::getFilenameFromFilename($filename);
			if (empty($filename))
			{
				$newFileName = Tools::getValue('filename');
				if (!empty($newFileName))
					$filename = Tools::getValue('filename');
				else
					$filename = 'file';
			}

			if (!file_exists($file))
				Tools::redirect('index.php');
			
			
						$mimeType = false;
			if (function_exists('finfo_open'))
			{
				$finfo = @finfo_open(FILEINFO_MIME);
				$mimeType = @finfo_file($finfo, $file);
				@finfo_close($finfo);
			}
			else if (function_exists('mime_content_type'))
				$mimeType = @mime_content_type($file);
			else if (function_exists('exec'))
			{
				$mimeType = trim(@exec('file -b --mime-type '.escapeshellarg($file)));
				if (!$mimeType)
					$mimeType = trim(@exec('file --mime '.escapeshellarg($file)));
				if (!$mimeType)
					$mimeType = trim(@exec('file -bi '.escapeshellarg($file)));
			}

			if (empty($mimeType))
			{
				$bName = basename($filename);
				$bName = explode('.', $bName);
				$bName = strtolower($bName[count($bName) - 1]);

				$mimeTypes = array(
				'ez' => 'application/andrew-inset',
				'hqx' => 'application/mac-binhex40',
				'cpt' => 'application/mac-compactpro',
				'doc' => 'application/msword',
				'oda' => 'application/oda',
				'pdf' => 'application/pdf',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',
				'smi' => 'application/smil',
				'smil' => 'application/smil',
				'wbxml' => 'application/vnd.wap.wbxml',
				'wmlc' => 'application/vnd.wap.wmlc',
				'wmlsc' => 'application/vnd.wap.wmlscriptc',
				'bcpio' => 'application/x-bcpio',
				'vcd' => 'application/x-cdlink',
				'pgn' => 'application/x-chess-pgn',
				'cpio' => 'application/x-cpio',
				'csh' => 'application/x-csh',
				'dcr' => 'application/x-director',
				'dir' => 'application/x-director',
				'dxr' => 'application/x-director',
				'dvi' => 'application/x-dvi',
				'spl' => 'application/x-futuresplash',
				'gtar' => 'application/x-gtar',
				'hdf' => 'application/x-hdf',
				'js' => 'application/x-javascript',
				'skp' => 'application/x-koan',
				'skd' => 'application/x-koan',
				'skt' => 'application/x-koan',
				'skm' => 'application/x-koan',
				'latex' => 'application/x-latex',
				'nc' => 'application/x-netcdf',
				'cdf' => 'application/x-netcdf',
				'sh' => 'application/x-sh',
				'shar' => 'application/x-shar',
				'swf' => 'application/x-shockwave-flash',
				'sit' => 'application/x-stuffit',
				'sv4cpio' => 'application/x-sv4cpio',
				'sv4crc' => 'application/x-sv4crc',
				'tar' => 'application/x-tar',
				'tcl' => 'application/x-tcl',
				'tex' => 'application/x-tex',
				'texinfo' => 'application/x-texinfo',
				'texi' => 'application/x-texinfo',
				't' => 'application/x-troff',
				'tr' => 'application/x-troff',
				'roff' => 'application/x-troff',
				'man' => 'application/x-troff-man',
				'me' => 'application/x-troff-me',
				'ms' => 'application/x-troff-ms',
				'ustar' => 'application/x-ustar',
				'src' => 'application/x-wais-source',
				'xhtml' => 'application/xhtml+xml',
				'xht' => 'application/xhtml+xml',
				'zip' => 'application/zip',
				'au' => 'audio/basic',
				'snd' => 'audio/basic',
				'mid' => 'audio/midi',
				'midi' => 'audio/midi',
				'kar' => 'audio/midi',
				'mpga' => 'audio/mpeg',
				'mp2' => 'audio/mpeg',
				'mp3' => 'audio/mpeg',
				'aif' => 'audio/x-aiff',
				'aiff' => 'audio/x-aiff',
				'aifc' => 'audio/x-aiff',
				'm3u' => 'audio/x-mpegurl',
				'ram' => 'audio/x-pn-realaudio',
				'rm' => 'audio/x-pn-realaudio',
				'rpm' => 'audio/x-pn-realaudio-plugin',
				'ra' => 'audio/x-realaudio',
				'wav' => 'audio/x-wav',
				'pdb' => 'chemical/x-pdb',
				'xyz' => 'chemical/x-xyz',
				'bmp' => 'image/bmp',
				'gif' => 'image/gif',
				'ief' => 'image/ief',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'jpe' => 'image/jpeg',
				'png' => 'image/png',
				'tiff' => 'image/tiff',
				'tif' => 'image/tif',
				'djvu' => 'image/vnd.djvu',
				'djv' => 'image/vnd.djvu',
				'wbmp' => 'image/vnd.wap.wbmp',
				'ras' => 'image/x-cmu-raster',
				'pnm' => 'image/x-portable-anymap',
				'pbm' => 'image/x-portable-bitmap',
				'pgm' => 'image/x-portable-graymap',
				'ppm' => 'image/x-portable-pixmap',
				'rgb' => 'image/x-rgb',
				'xbm' => 'image/x-xbitmap',
				'xpm' => 'image/x-xpixmap',
				'xwd' => 'image/x-windowdump',
				'igs' => 'model/iges',
				'iges' => 'model/iges',
				'msh' => 'model/mesh',
				'mesh' => 'model/mesh',
				'silo' => 'model/mesh',
				'wrl' => 'model/vrml',
				'vrml' => 'model/vrml',
				'css' => 'text/css',
				'html' => 'text/html',
				'htm' => 'text/html',
				'asc' => 'text/plain',
				'txt' => 'text/plain',
				'rtx' => 'text/richtext',
				'rtf' => 'text/rtf',
				'sgml' => 'text/sgml',
				'sgm' => 'text/sgml',
				'tsv' => 'text/tab-seperated-values',
				'wml' => 'text/vnd.wap.wml',
				'wmls' => 'text/vnd.wap.wmlscript',
				'etx' => 'text/x-setext',
				'xml' => 'text/xml',
				'xsl' => 'text/xml',
				'mpeg' => 'video/mpeg',
				'mpg' => 'video/mpeg',
				'mpe' => 'video/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',
				'mxu' => 'video/vnd.mpegurl',
				'avi' => 'video/x-msvideo',
				'movie' => 'video/x-sgi-movie',
				'ice' => 'x-conference-xcooltalk');

				if (isset($mimeTypes[$bName]))
					$mimeType = $mimeTypes[$bName];
				else
					$mimeType = 'application/octet-stream';
			}

						header('Content-Transfer-Encoding: binary');
			header('Content-Type: '.$mimeType);
			header('Content-Length: '.filesize($file));
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			ob_end_flush();
			$fp = fopen($file, 'rb');
			while (!feof($fp))
				echo fgets($fp, 16384);

			exit;			
			
		}

		parent::init();
	}
}

