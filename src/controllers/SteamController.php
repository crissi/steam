<?php 

class SteamController extends BaseController {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function Dashboard()
	{
		$tables = DB::select('SHOW TABLES');

		$form = '<form method="post"><table>';
		$i=0;
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
			
			$form.= '<tr><td><label><input type="checkbox" name="tbl['.$i.']" value="'.$tble.'" /> '.$tble.'</label></td><td style="background:'.$clr.';">'.$check.'</td></tr>';
			++$i;
		}
		$form.= '<tr><td colspan="2"><label><input type="checkbox" name="all" class="all" />Select All</label></td></tr>';
		$form.= '<tr><td colspan="2"><input type="submit" value="Generate Files" /></td></tr></table></form>';

		echo $form;
		//return App::make('steam')->dashboard();
	}

	protected function Test()
	{
		echo 'test';
	}

}