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

	public function Dashboard($msg = ''){
		
		$data['db'] = $this->database;

		$tables = $this->db->select('SHOW TABLES');
		$form = array();

		$i = 0;
		foreach($tables as $row){

			$check = 'File will be created.';
			$clr = '#beff9a';

			$tble = $row->Tables_in_laravel;
			
			$filename = ucfirst(str_replace('_','',$tble));
			if(substr($filename, -3) == 'ies'){
				$filename = str_replace('ies','y',$filename);
			}elseif(substr($filename, -1) == 's'){
				$filename = substr($filename, 0, -1);
			}

			if(file_exists(app_path().'/models/'.ucfirst(str_replace('_','',$filename)).'.php')){
				$check = 'File will be overwritten if checked!';
				$clr = '#ffcece';
			}

			$form[$tble] = array('text' => $check, 'color' => $clr);
			++$i;

		}

		$data['count'] = $i;
		$data['form'] = $form;
		if($msg)
			$data['msg'] = $msg;

		return $this->view->make('steamy::dash', compact('data'));

	}

	public function Process(){

		return $this->CreateModels();

	}

	private function CreateModels(){

		$folder = '/pitch/steamy/src/templates';

		if(is_dir(base_path().'/vendor'.$folder))
			$path = base_path().'/vendor'.$folder;
		else
			$path = base_path().'/workbench'.$folder;

		$template = file_get_contents($path.'/model.php');
		$many_to_many = file_get_contents($path.'/many-to-many.php');
		$one_to_many = file_get_contents($path.'/one-to-many.php');
		$one_to_one = file_get_contents($path.'/one-to-one.php');
		$modelCount = 0;
		$belCount = 0;
		$relCount = 0;
		$hasCount = 0;

		if($_POST){
			// Generate our arrays to use later
			$belongs = array();
			$hasmany = array();
			$process = array();
			
			$tbls = $_POST['tbls'];

			if(count($tbls) > 0){
				foreach($tbls as $v){
					array_push($process, $v);
				}
			}

			$tables = $this->db->select('SHOW TABLES');

			foreach($tables as $row){

				if(in_array($row->Tables_in_laravel, $process)){
					$belongs[$row->Tables_in_laravel] = array();
					$hasmany[$row->Tables_in_laravel] = array();
					
					//$rows = mysql_query("SHOW COLUMNS FROM $row[0]");
					//mysql_fetch_array($rows, MYSQL_ASSOC)
					$cols = $this->db->select("SHOW COLUMNS FROM $row->Tables_in_laravel");
					foreach($cols as $col){
						
						array_push($hasmany[$row->Tables_in_laravel], str_replace('_id','',$col->Field));
						
						if(strstr($col->Field, '_id')){
							array_push($belongs[$row->Tables_in_laravel], str_replace('_id','',$col->Field));
						}		
					}
				}
			}
			
			// Rest our pointer for the mysql_query back to 0 because we just looped through it.
			// mysql_data_seek($tables, 0);
			
			foreach($tables as $row){
				if(in_array($row->Tables_in_laravel, $process)){
			
					$filename = ucfirst(str_replace('_','',$row->Tables_in_laravel));
					if(substr($filename, -3) == 'ies'){
						$filename = str_replace('ies','y',$filename);
					}elseif(substr($filename, -1) == 's'){
						$filename = substr($filename, 0, -1);
					}
					
					// Open our new file.
					$newFile = fopen(app_path().'/models/'.$filename.'.php', 'w');
					
					// Add the class name to the model
					$contents = str_replace('{class_name}', $filename, $template);
					
					// Add the table name to the model
					$contents = str_replace('{table_name}', $row->Tables_in_laravel, $contents);

					foreach($hasmany as $key => $val){ // Loop through all tables.
						
						$hasm = $one_to_many.PHP_EOL;
						
						if($row->Tables_in_laravel != $key){ // We don't need to loop through the table we're working with currnetly.

							if(in_array($row->Tables_in_laravel, $val) 
								OR in_array(substr($row->Tables_in_laravel, 0, -1), $val) 
								OR in_array(substr($row->Tables_in_laravel, 0, -1).'ies', $val) 
								OR in_array(substr($row->Tables_in_laravel, 0, -3).'y', $val)){
									$hasm = str_replace('{functionName}', $key, $hasm);
									$hasm = str_replace('{tableName}', ucwords($key), $hasm);
									$hasCount++;
									$relCount++;
									$contents = str_replace('// {has_many}', $hasm, $contents);
							}
							
						}
						
					}
					$contents = str_replace('// {has_many}', '', $contents);

					// Check if this table references any other tables by id (*_id)
					foreach($belongs[$row->Tables_in_laravel] as $val){
						$bel = $one_to_one.PHP_EOL;
						$bel = str_replace('{functionName}', $val, $bel);
						$bel = str_replace('{tableName}', ucwords($val), $bel);
						$belCount++;
						$relCount++;
						$contents = str_replace('// {belongs_to}', $bel, $contents);
					}
					
					$contents = str_replace('// {belongs_to}', '', $contents);
					
					fwrite($newFile, $contents);
					fclose($newFile);

					++$modelCount;
				}
			}

			return true;
		}else{
			return false;
		}

	}

}