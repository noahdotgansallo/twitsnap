<?php

class HomeController extends MainController
{
	public function register()
	{
		return $this->renderView("register");
	}
}