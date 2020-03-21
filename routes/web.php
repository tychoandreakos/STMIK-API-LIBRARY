<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
  return $router->app->version();
});

/**
 * Router untuk gmd.
 */
$router->group(['name' => 'gmd'], function () use ($router) {
  $router->get('gmd', 'GmdController@index'); // untuk mengambil keseluruhan data
  $router->get('gmd/delete', 'GmdController@retrieveDeleteHistoryData');
  $router->post('gmd', 'GmdController@store'); // untuk menyimpan data
  $router->put('gmd/{id}/edit', 'GmdController@update'); // untuk update data
  $router->delete('gmd/delete-all', 'GmdController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
  $router->delete('gmd/{id}/delete', 'GmdController@destroy'); // untuk delete data
  $router->delete('gmd/destroy', 'GmdController@deleteAllHistoryData'); // Menghapus seluruh data.
  $router->delete('gmd/{id}/destroy', 'GmdController@deleteHistoryData'); // Menghapus seluruh data sesuai dengan ID.
  $router->post('gmd/search', 'GmdController@search'); // Untuk query pencarian
  $router->get('gmd/{id}/detail', 'GmdController@detail'); //Untuk mendapatkan detail item
  $router->post('gmd/delete', 'GmdController@destroySome'); // Untuk menghapus data yang dipilih
  $router->post('gmd/update', 'GmdController@updateSome'); // Untuk melakukan update beberapa data
  $router->put('gmd/restore', 'GmdController@returnAllDeleteHistoryData'); // Mengembalikan seluruh data yang sudah terhapus
  $router->put('gmd/{id}/restore', 'GmdController@returnDeleteHistoryData'); // Mengembalikan data yang sudah terhapus sesuai ID
});
