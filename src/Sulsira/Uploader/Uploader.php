<?php namespace Sulsira\Uploader;
/**
* 
*/
use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use Intervention\Image\ImageManagerStatic as Image;
use Config, Session, Redirect, Input;
defined('DS') ? NULL :define('DS',DIRECTORY_SEPARATOR);
class Uploader 
{

	protected static $instance;
	protected $input;
    private $rules;
    private $image;
    private $directory;
    private $dimension;
    private $renamed;
    private $data;
    private $filetype;
    private $fileextention;
    private $filename;
    private $resize;
    private $resized;
    private $obj;
    private $thumnail_location;
    private $file_size;
    private $file_original_name;
    private $folder;
    // important data for the database
        // the thumbain location
        // we want the size
        // we want the original name
        // we want the original image
        // we want the full image in a new directory
    function  __construct($data , $args, $obj){
        $this->data = $data;
        $this->obj = $obj;
        $this->processor($data);
    }
	public function validate($rules){
        $this->rules = ($rules)?: Config::get('uploader::rules');
        //validate image
        // table the rule: size, type and extension
        #v0.2
        return $this;
	}
	public function directory($dir){
        var_dump($dir);
        $this->folder =& $dir;
        $dir = ($dir)?: $this->directory;
        if( \File::exists($dir) ) {
            $this->directory = $dir;
        }else{
            $made = mkdir($dir, Config::get('uploader::chmod'), true);
            if ($made) {
                 $this->directory = $dir;
            }
        }
        
        return $this;
	}
	public function resize($x,$y, $dir =null){
        $folder = '';
        $dim = [
            'y' => $y,
            'x' => $x
        ];

        $this->dimension = $dim;
        $this->obj->resize($this->dimension['x'],$this->dimension['x']);
        $folder = $this->renamed;

        if($dir){

            $this->directory($this->directory.DS.$dir);
            $folder = $this->directory.DS.$this->file_new_name;

        }
        $this->thumnail_location &= $folder;
        $this->save($folder);
        return $this;
	}
	public function rename($rename, $dir = null, $exten = false){
        $actual_path = '';
        $file_new_name = $rename.'.'.$this->fileextention;
        #should be rename file be extened to the directory in scope or a complete new directory structure
        $this->filename =& $file_new_name;
        $folder = ($dir)?: $this->directory;
        $this->file_new_name = $file_new_name;
        if($dir){
            if($exten == true){
                $this->directory($this->directory.$dir);
               $actual_path .= $this->directory;
            }else{
                $this->directory($dir);
                $actual_path .= $this->directory;
            }
        }else{
            $this->directory($dir);
            $actual_path .= $this->directory;
        }
        $this->renamed =  $actual_path.DS.$file_new_name;
        $this->save($this->renamed);
        // need to attach a string of the file location to be moved
        return $this;
	}
	public function save($tobesaved = null){
		#move image of file to the right directory$img_obj,$tobesaved
        $directory = ($tobesaved)?: $this->directory.DS.$this->filename;
       $this->obj->save($directory); 
        return $this;
	}
	public function details(){
        $data = array();
        $data = [
            'old' => $this->data,
            'new' => [
                'dimension' => $this->dimension ,
                'file' => $this->renamed ,
                'filename' => $this->filename ,
                'thumnail_location' => $this->folder,
                'folder' => $this->folder.'/../'
            ]
        ];
        return  $data;
	}


    public static function file($args = null){
        #act like a DTO
		$data = ($args)?: Input::file(Config::get('uploader::filename'));
//        return new Uploader($data, $args);
	}
	public static function image($args = null){
        
        $data = ($args)?: Input::file(Config::get('uploader::filename'));
        

        $img_onj = Image::make( $data->getRealPath() );
        // $data =  Input::file('profilePic');
		// check to see if an array or string supplied;
		//if string get content like a link
		// if array get content like input

		// send the passed information to the global scope
        $obj = new Uploader($data, $args,$img_onj);
        return  $obj ;
	}
    public function processor($data){
        $this->fileextention = $data->getClientOriginalExtension();
        $this->file_size = $data->getSize();
        $this->real_path = $data->getRealPath();

       return $this;
    }
//	public static function __callStatic($name, $args){
//
//        $args = empty($args) ? [] : $args[0];
//
//        $instance = static::$instance;
//        if ( ! $instance) $instance = static::$instance = new static;
//
//        return $instance->shoot($name, $args);
//
//
//	}

}#end of class