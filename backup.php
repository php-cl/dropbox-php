<?php

class Backup {

    private $dbxClient;
    private $projectFolder;

    /**
     * __construct pass token and project to the client method
     * @param string $token  authorization token for Dropbox API 
     * @param string $project       name of project and version
     * @param string $projectFolder name of the folder to upload into
     */
    public function __construct($token,$project,$projectFolder){
        $this->dbxClient = new Dropbox\Client($token, $project);
        $this->projectFolder = $projectFolder;
    }

    /**
     * upload set the file or directory to upload
     * @param  [type] $dirtocopy [description]
     * @return [type]            [description]
     */
    public function upload($dirtocopy){

        if(!file_exists($dirtocopy)){

            exit("File $dirtocopy does not exist");
            
        } else {

            //if dealing with a file upload it
            if(is_file($dirtocopy)){
                $this->uploadFile($dirtocopy);
                   
            } else { //otherwise collect all files and folders

                $iter = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($dirtocopy, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::SELF_FIRST,
                    \RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
                );

                //loop through all entries
                foreach($iter as $file) {

                    $words = explode('/',$file);
                    $stop = end($words);    

                    //if file is not in the ignore list pass to uploadFile method
                    if(!in_array($stop, $this->ignoreList())){
                        $this->uploadFile($file);
                    }

                }
            }
        }
    }

    /**
     * uploadFile upload file to dropbox using the Dropbox API
     * @param  string $file path to file
     */
    public function uploadFile($file){
        $f = fopen($file, "rb");
        $this->dbxClient->uploadFile("/".$this->projectFolder."/$file", Dropbox\WriteMode::add(), $f);
        fclose($f);
    }

    /**
     * ignoreList array of filenames or directories to ignore
     * @return array 
     */
    public function ignoreList(){
        return array(
            '.DS_Store',
            'cgi-bin'
        );
    }
}



# Si te Error en PHP Comenta esta linea en  \lib\Dropbox\RequestUtil.php(line.no : 19)

/*
if (strlen((string) PHP_INT_MAX) < 19) {
//    // Looks like we're running on a 32-bit build of PHP.  This could cause problems because some of the numbers
//    // we use (file sizes, quota, etc) can be larger than 32-bit ints can handle.
   throw new \Exception("The Dropbox SDK uses 64-bit integers, but it looks like we're running on a version of PHP that doesn't support 64-bit integers (PHP_INT_MAX=" . ((string) PHP_INT_MAX) . ").  Library: \"" . __FILE__ . "\"");
}
*/

?>