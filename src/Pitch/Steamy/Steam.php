<?php namespace Pitch\Steamy;

use Illuminate\View\Environment;
use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseManager;

class Steam {

	private $database;
	private $username;
	private $password;

    /**
     * Illuminate view environment.
     *
     * @var Illuminate\View\Environment
     */
    protected $view;

    protected $db;

	public function __construct(Environment $view, Repository $config, DatabaseManager $db){


		$this->database = $config->get('steamy::steam.database');
		$this->username = $config->get('steamy::steam.username');
		$this->password = $config->get('steamy::steam.password');

		$this->view = $view;
		$this->db = $db;

	}

	public function Dashboard(){
		
		$data['db'] = $this->database;

		$results = $this->db->select('SHOW TABLES');
		var_dump($results);

		return $this->view->make('steamy::dash', compact('data'));
	}

}