<?php

Route::get('/steamy', function()
{
	return App::make('steam')->dashboard();
});

Route::post('/steamy/process', function()
{
	$process = App::make('steam')->process();

	if($process){
		//return App::make('steam')->dashboard();
		return Redirect::to('steamy');
	}

});