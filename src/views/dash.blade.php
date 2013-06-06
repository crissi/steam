<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="utf-8" />  
    <title>Steam</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/pitch/steamy/css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/pitch/steamy/css/bootstrap-responsive.css') }}" />
	
	<style>
		body{
			margin-top: 60px;
		}
		table{
			border: 1px solid #ddd;
		}
		table tr td{
			line-height: 35px;
			margin: 0;
			padding: 0;
		}
		table tr td input{
			margin-top: -2px !important;
		}
	</style>
  
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->  
</head>  
<body>  

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Steam</a>
          <!--
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

		<div class="alert alert-info">
			<span class="label label-info">Important</span> Running this script will overwrite any of the models that you have checked below if they already exist in your app/models directory!
		</div>

		<p>The below tables were found in your database. Please select the ones that you would like to automatically generate a model for.</p>

		<hr />
		<?  //$data['form'] ?>
		<?php echo Form::open(array('url' => '/steamy/process')); ?>
			<table style="width:100%;">
				<?php foreach($data['form'] as $key => $val){ ?>
					<tr style="background:<?=$val['color']?>">
						<td align="center" style="width:50px;"><input type="checkbox" value="<?=$key?>"></td>
						<td style="width:150px;"><?=ucwords(str_replace('_',' ',$key))?></td>
						<td><label class="checkbox"><?=$val['text']?></label></td>
					</tr>
				<?php } ?>
			</table>
			<hr />
			<button class="btn btn-info submit" type="button">Process Selected Tables</button>&nbsp;
			<button class="btn btn-info" type="button">Check All</button>
		<?php echo Form::close(); ?>

    </div> <!-- /container -->

    <script src="{{ URL::asset('packages/pitch/steamy/js/bootstrap.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script>
    	$('.submit').click(function(){
    		$('form').submit();
    	});
    </script>
</body>  
</html>