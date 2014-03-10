<?php

class File 
{
	public function move($destination, $fileName = NULL)
	{
		if ($fileName == NULL)
		{
			$fileName = $this->name;
		}
		move_uploaded_file($fileName, $destination);
	}
}

