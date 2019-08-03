<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileZipperCore extends ZipArchive
{
		public function zipFolderRecusive($zipfile, $folder)
	{
		if(empty($zipfile))return;
		if(empty($folder) || !file_exists($folder))return;

		$basename = basename($folder);
		if(file_exists($zipfile))unlink($zipfile);
		if ($this->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE) {
			echo("cannot open <$zipfile>\n");
			return;
		}
		else
		{
			$this->addFoler($folder, $basename);
			$this->close();
		}
		
	} 
	
	
                public function addFoler($filename, $localname)
    {
        $this->addEmptyDir($localname);
        $iter = new RecursiveDirectoryIterator($filename, FilesystemIterator::SKIP_DOTS);

        foreach ($iter as $fileinfo) {
            if (! $fileinfo->isFile() && !$fileinfo->isDir()) {
                continue;
            }

            $method = $fileinfo->isFile() ? 'addFile' : 'addFoler';
            $this->$method($fileinfo->getPathname(), $localname . '/' .
                $fileinfo->getFilename());
        }
    }
} 

