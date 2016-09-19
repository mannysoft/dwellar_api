<?php

class PhotoUploader {
	
    public $path = 'contacts';
	
    public function upload()
    {
        $fileContent = new StdClass;

        if (Input::hasFile('photo'))
        {
            $file = Request::file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = $file->getFilename().'.'.$extension;
            Storage::put($this->path.'/'.$filename,  File::get($file));

            $file->getClientMimeType();

            $fileContent->filename  = $filename;
            $fileContent->mime      = $file->getClientMimeType();

            return $fileContent;

            //return $filename;
            // $destinationPath    = $this->path.'/'.Auth::user()->id;
                
            // $filename           = $photo->getClientOriginalName();

            // $ext                = $photo->getClientOriginalExtension();

            // $filename           = str_random(12).time().'.'.$ext;
            
            // $photo->move($destinationPath, $filename);

            // return $filename;
        }
    }

    public function setPath($path = 'contacts')
    {
        $this->path = $path;
    }
}
