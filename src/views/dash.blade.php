<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="utf-8" />  
    <title>Steam</title>  
  
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/pitch/steamy/css/bootstrap.css') }}" />
  
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->  
</head>  
<body>  
    <h1>Steam</h1>
    <p><?=$data['db']?></p>

    <script src="{{ URL::asset('packages/pitch/steamy/js/bootstrap.js') }}"></script>
</body>  
</html>