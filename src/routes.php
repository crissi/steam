<?php

Route::get('/steamy', function()
{
	return App::make('steam')->dashboard();
});

Route::post('/steamy/process', function()
{
	return App::make('steam')->process();
});