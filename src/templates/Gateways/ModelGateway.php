<?php 

namespace %path%\gateways;

use %path%\Gateways\SettingGateway;
use %path%\Repositories\%model%Repository\%model%RepositoryInterface;

class %model%Gateway{

	public function __construct(%model%RepositoryInterface $%model%Repository) {
		$this->%model%Repository = $%model%Repository;
	}
	
}
?>