<?php
class Form
{
	public static function required($variable)
	{
		if (is_array($variable))
		{
			foreach($variable as $variable)
			{
				$variable = Input::get($variable);
				if (empty($variable))
				{
					header('Location: '.$_SERVER['HTTP_REFERER']);
				}
			}
		}
		else
		{
			$variable = Input::get($variable);
			if (empty($variable))
			{
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}

	}
}

?>