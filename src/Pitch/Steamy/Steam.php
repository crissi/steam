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

		return $this->view->make('steamy::dash', compact('data'));

	}

	public function Process(){

		$data= '';
		$this->CreateModels($data);

	}

	private function CreateModels($data){

		//echo 'processing';
		//

		if($_POST){
			// Generate our arrays to use later
			$belongs = array();
			$hasmany = array();
			$process = array();
			
			if($_POST['tbl']){
				foreach($_POST['tbl'] as $v){
					array_push($process, $v);
				}
			}
			
			while($row = mysql_fetch_array($tables)){
				if(in_array($row[0], $process)){
					$belongs[$row[0]] = array();
					$hasmany[$row[0]] = array();
					
					$rows = mysql_query("SHOW COLUMNS FROM $row[0]");
					while($col = mysql_fetch_array($rows, MYSQL_ASSOC)){
						
						array_push($hasmany[$row[0]], str_replace('_id','',$col[Field]));
						
						if(strstr($col[Field], '_id')){
							array_push($belongs[$row[0]], str_replace('_id','',$col[Field]));
						}		
					}
				}
			}
			
			// Rest our pointer for the mysql_query back to 0 because we just looped through it.
			mysql_data_seek($tables, 0);
			
			while($row = mysql_fetch_array($tables)){
				if(in_array($row[0], $process)){
			
					$filename = ucfirst(str_replace('_','',$row[0]));
					if(substr($filename, -3) == 'ies'){
						$filename = str_replace('ies','y',$filename);
					}elseif(substr($filename, -1) == 's'){
						$filename = substr($filename, 0, -1);
					}
					
					$newFile = fopen($modelPath.$filename.'.php', 'w');
					
					$contents = str_replace('{class_name}', $filename, $template);
					
					// Check if we need to specify a table name and do the damn thang.
					if(strstr($row[0], '_')){
						$contents = str_replace('// {table_name}', PHP_EOL.'    static $table_name = "'.$row[0].'";'.PHP_EOL, $contents);
					}else{
						$contents = str_replace('// {table_name}', '', $contents);
					}
					
					
					// Check if this table has any others that reference it's id
					$hasm = 'static $has_many = array('.PHP_EOL.'{rep}    );'.PHP_EOL;
					foreach($hasmany as $key => $val){ // Loop through all tables.
						
						if($row[0] != $key){ // We don't need to loop through the table we're working with currnetly.
				
							if(in_array($row[0], $val) OR in_array(substr($row[0], 0, -1), $val) OR in_array(substr($row[0], 0, -1).'ies', $val) OR in_array(substr($row[0], 0, -3).'y', $val)){
								$hasm = str_replace('{rep}', "        array('$key'), ".PHP_EOL."{rep}", $hasm);
								$hasCount++;
								$relCount++;
							}
							
						}
						
					}
				
					$hasm = str_replace(array('{rep}',', {rep}'), "", $hasm);
					$contents = str_replace('// {has_many}', '// {has_many}'.PHP_EOL.'    '.$hasm, $contents);
						
					
					// Check if this table references any other tables by id (*_id)
					$bel = 'static $belongs_to = array('.PHP_EOL.'{rep}    );'.PHP_EOL;
					foreach($belongs[$row[0]] as $val){
						$bel = str_replace('{rep}', "        array('$val'), ".PHP_EOL."{rep}", $bel);
						$belCount++;
						$relCount++;
					}
					$bel = str_replace(array('{rep}',', {rep}'), "", $bel);
					$contents = str_replace('// {belongs_to}', '// {belongs_to}'.PHP_EOL.'    '.$bel, $contents);
					
					fwrite($newFile, $contents);
					fclose($newFile);
					++$modelCount;
				}
			}

		// We're just going to print out the table list and give the user the chance to select the ones to be generated.
		}		

	}

}