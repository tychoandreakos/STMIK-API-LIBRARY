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
 * Router untuk Membership
 */
$router->group(['namespace' => 'Member'], function () use ($router) {
  $router->group(['name' => 'membership'], function () use ($router) {
    $router->get('member-type', 'MemberTypeController@index'); // untuk mengambil keseluruhan data
    $router->get(
      'member-type/collections',
      'MemberTypeController@getNameCollection'
    );
    $router->get(
      'member-type/delete',
      'MemberTypeController@retrieveDeleteHistoryData'
    );
    $router->post('member-type/details', 'MemberTypeController@multipleDetail'); // untuk mendapatkan multipe data collections
    $router->post('member-type', 'MemberTypeController@store'); // untuk menyimpan data
    $router->put('member-type/{id}/edit', 'MemberTypeController@update'); // untuk update data
    $router->post(
      'member-type/destroy-collections',
      'MemberTypeController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post(
      'member-type/restore',
      'MemberTypeController@restoreCollectionData'
    ); //Untuk melakukan restorasi data
    $router->delete(
      'member-type/delete-all',
      'MemberTypeController@destroyAll'
    ); // Untuk menghapus seluruh data yang ada di database
    $router->delete('member-type/{id}/delete', 'MemberTypeController@destroy'); // untuk delete data
    $router->delete(
      'member-type/destroy',
      'MemberTypeController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'member-type/{id}/destroy',
      'MemberTypeController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('member-type/search', 'MemberTypeController@search'); // Untuk query pencarian
    $router->get('member-type/{id}/detail', 'MemberTypeController@detail'); //Untuk mendapatkan detail item
    $router->post('member-type/delete', 'MemberTypeController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('member-type/update', 'MemberTypeController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'member-type/restore',
      'MemberTypeController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'member-type/{id}/restore',
      'MemberTypeController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'member'], function () use ($router) {
    $router->get('member', 'MemberController@index'); // untuk mengambil keseluruhan data
    $router->get('member/delete', 'MemberController@retrieveDeleteHistoryData');
    $router->post('member/details', 'MemberController@multipleDetail'); // untuk mendapatkan multipe data collections
    $router->post('member', 'MemberController@store'); // untuk menyimpan data
    $router->put('member/{id}/edit', 'MemberController@update'); // untuk update data
    $router->post(
      'member/destroy-collections',
      'MemberController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('member/restore', 'MemberController@restoreCollectionData'); //Untuk melakukan restorasi data
    $router->delete('member/delete-all', 'MemberController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('member/{id}/delete', 'MemberController@destroy'); // untuk delete data
    $router->delete('member/destroy', 'MemberController@deleteAllHistoryData'); // Menghapus seluruh data.
    $router->delete(
      'member/{id}/destroy',
      'MemberController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('member/search', 'MemberController@search'); // Untuk query pencarian
    $router->get('member/{id}/detail', 'MemberController@detail'); //Untuk mendapatkan detail item
    $router->post('member/delete', 'MemberController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('member/update', 'MemberController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'member/restore',
      'MemberController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'member/{id}/restore',
      'MemberController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });
});

$router->group(['namespace' => 'Classifications'], function () use ($router) {
  /**
   * Router untuk classification.
   */
  $router->group(['name' => 'classification'], function () use ($router) {
    $router->get('classification', 'ClassificationListController@index'); // untuk mengambil keseluruhan data
    $router->post('classification', 'ClassificationListController@store'); // untuk menyimpan data
    $router->put(
      'classification/{id}/edit',
      'ClassificationListController@update'
    ); // untuk update data
    $router->delete(
      'classification/{id}/delete',
      'ClassificationListController@destroy'
    ); // untuk delete data});
    $router->post(
      'classification/search',
      'ClassificationListController@search'
    ); // Untuk query pencarian
  });
});

$router->group(['name' => 'pattern'], function () use ($router) {
  /**
   * Router untuk pattern.
   */
  $router->get('pattern', 'PatternController@index'); // untuk mengambil keseluruhan data
  $router->post('pattern', 'PatternController@store'); // untuk menyimpan data
  $router->put('pattern/{id}/edit', 'PatternController@update'); // untuk update data
  $router->delete('pattern/{id}/delete', 'PatternController@destroy'); // untuk delete data});
  $router->post('pattern/search', 'PatternController@search'); // Untuk query pencarian
});

$router->group(['namespace' => 'Book'], function () use ($router) {
  /**
   * Router untuk book.
   */
  $router->group(['name' => 'book'], function () use ($router) {
    $router->get('book', 'BookController@index'); // untuk mengambil keseluruhan data
    $router->get('book/delete', 'BookController@retrieveDeleteHistoryData');
    $router->post('book', 'BookController@store'); // untuk menyimpan data
    $router->put('book/{id}/edit', 'BookController@update'); // untuk update data
    $router->delete('book/delete-all', 'BookController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('book/{id}/delete', 'BookController@destroy'); // untuk delete data
    $router->delete('book/destroy', 'BookController@deleteAllHistoryData'); // Menghapus seluruh data.
    $router->delete('book/{id}/destroy', 'BookController@deleteHistoryData'); // Menghapus seluruh data sesuai dengan ID.
    $router->post('book/search', 'BookController@search'); // Untuk query pencarian
    $router->get('book/{id}/detail', 'BookController@detail'); //Untuk mendapatkan detail item
    $router->post('book/delete', 'BookController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('book/update', 'BookController@updateSome'); // Untuk melakukan update beberapa data
    $router->put('book/restore', 'BookController@returnAllDeleteHistoryData'); // Mengembalikan seluruh data yang sudah terhapus
    $router->put('book/{id}/restore', 'BookController@returnDeleteHistoryData'); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  /**
   * Router untuk Book Transaction.
   */
  $router->group(['name' => 'book_transaction'], function () use ($router) {
    $router->get('book-list', 'BookTransactionController@index'); // untuk mengambil keseluruhan data
    $router->get(
      'book-list/delete',
      'BookTransactionController@retrieveDeleteHistoryData'
    );
    $router->post('book-list', 'BookTransactionController@store'); // untuk menyimpan data
    $router->put('book-list/{id}/edit', 'BookTransactionController@update'); // untuk update data
    $router->delete(
      'book-list/delete-all',
      'BookTransactionController@destroyAll'
    ); // Untuk menghapus seluruh data yang ada di database
    $router->delete(
      'book-list/{id}/delete',
      'BookTransactionController@destroy'
    ); // untuk delete data
    $router->delete(
      'book-list/destroy',
      'BookTransactionController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'book-list/{id}/destroy',
      'BookTransactionController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('book-list/search', 'BookTransactionController@search'); // Untuk query pencarian
    $router->get('book-list/{id}/detail', 'BookTransactionController@detail'); //Untuk mendapatkan detail item
    $router->post('book-list/delete', 'BookTransactionController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('book-list/update', 'BookTransactionController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'book-list/restore',
      'BookTransactionController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'book-list/{id}/restore',
      'BookTransactionController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  /**
   * Router untuk Book Biblio.
   */
  $router->group(['name' => 'biblio'], function () use ($router) {
    $router->get('bibliobigrafi', 'BiblioController@index'); // untuk mengambil keseluruhan data
    $router->get(
      'bibliobigrafi/delete',
      'BiblioController@retrieveDeleteHistoryData'
    );
    $router->post('bibliobigrafi', 'BiblioController@store'); // untuk menyimpan data
    $router->put('bibliobigrafi/{id}/edit', 'BiblioController@update'); // untuk update data
    $router->delete('bibliobigrafi/delete-all', 'BiblioController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('bibliobigrafi/{id}/delete', 'BiblioController@destroy'); // untuk delete data
    $router->delete(
      'bibliobigrafi/destroy',
      'BiblioController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'bibliobigrafi/{id}/destroy',
      'BiblioController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('bibliobigrafi/search', 'BiblioController@search'); // Untuk query pencarian
    $router->get('bibliobigrafi/{id}/detail', 'BiblioController@detail'); //Untuk mendapatkan detail item
    $router->post('bibliobigrafi/delete', 'BiblioController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('bibliobigrafi/update', 'BiblioController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'bibliobigrafi/restore',
      'BiblioController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'bibliobigrafi/{id}/restore',
      'BiblioController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });
});

$router->group(['namespace' => 'Master'], function () use ($router) {
  /**
   * Router untuk gmd.
   */
  $router->group(['name' => 'gmd'], function () use ($router) {
    $router->get('gmd', 'GmdController@index'); // untuk mengambil keseluruhan data
    $router->get('gmd/delete', 'GmdController@retrieveDeleteHistoryData');
    $router->get('gmd/simple', 'GmdController@getDataForDropdown'); // untuk mendapatkan data yang akan disimpan didalam dropdown
    $router->post('gmd/details', 'GmdController@multipleDetail'); // untuk mendapatkan multipe data collections
    $router->post('gmd', 'GmdController@store'); // untuk menyimpan data
    $router->put('gmd/{id}/edit', 'GmdController@update'); // untuk update data
    $router->post('gmd/find', 'GmdController@searchDataForDropdown'); // untuk mencari data dropdown
    $router->post(
      'gmd/destroy-collections',
      'GmdController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('gmd/restore', 'GmdController@restoreCollectionData'); //Untuk melakukan restorasi data
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

  $router->group(['name' => 'publisher'], function () use ($router) {
    $router->get('publisher', 'PublisherController@index'); // Untuk mengambil seluruh dat
    $router->get(
      'publisher/delete',
      'PublisherController@retrieveDeleteHistoryData'
    );
    $router->post('publisher', 'PublisherController@store'); // untuk menyimpan data
    $router->put('publisher/{id}/edit', 'PublisherController@update'); // untuk update data
    $router->post(
      'publisher/destroy-collections',
      'PublisherController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post(
      'publisher/restore',
      'PublisherController@restoreCollectionData'
    ); //Untuk melakukan restorasi data
    $router->delete('publisher/delete-all', 'PublisherController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('publisher/{id}/delete', 'PublisherController@destroy'); // untuk delete data
    $router->delete(
      'publisher/destroy',
      'PublisherController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'publisher/{id}/destroy',
      'PublisherController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('publisher/search', 'PublisherController@search'); // Untuk query pencarian
    $router->get('publisher/{id}/detail', 'PublisherController@detail'); //Untuk mendapatkan detail item
    $router->post('publisher/delete', 'PublisherController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('publisher/update', 'PublisherController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'publisher/restore',
      'PublisherController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'publisher/{id}/restore',
      'PublisherController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'author'], function () use ($router) {
    $router->get('author', 'AuthorController@index'); // Untuk mengambil seluruh dat
    $router->get('author/delete', 'AuthorController@retrieveDeleteHistoryData');
    $router->post('author', 'AuthorController@store'); // untuk menyimpan data
    $router->put('author/{id}/edit', 'AuthorController@update'); // untuk update data
    $router->post(
      'author/destroy-collections',
      'AuthorController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('author/restore', 'AuthorController@restoreCollectionData'); //Untuk melakukan restorasi data
    $router->delete('author/delete-all', 'AuthorController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('author/{id}/delete', 'AuthorController@destroy'); // untuk delete data
    $router->delete('author/destroy', 'AuthorController@deleteAllHistoryData'); // Menghapus seluruh data.
    $router->delete(
      'author/{id}/destroy',
      'AuthorController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('author/search', 'AuthorController@search'); // Untuk query pencarian
    $router->get('author/{id}/detail', 'AuthorController@detail'); //Untuk mendapatkan detail item
    $router->post('author/delete', 'AuthorController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('author/update', 'AuthorController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'author/restore',
      'AuthorController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'author/{id}/restore',
      'AuthorController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'subject'], function () use ($router) {
    $router->get('subject', 'SubjectController@index'); // Untuk mengambil seluruh dat
    $router->get(
      'subject/delete',
      'SubjectController@retrieveDeleteHistoryData'
    );
    $router->post('subject', 'SubjectController@store'); // untuk menyimpan data
    $router->put('subject/{id}/edit', 'SubjectController@update'); // untuk update data
    $router->post(
      'subject/destroy-collections',
      'SubjectController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('subject/restore', 'SubjectController@restoreCollectionData'); //Untuk melakukan restorasi data
    $router->delete('subject/delete-all', 'SubjectController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('subject/{id}/delete', 'SubjectController@destroy'); // untuk delete data
    $router->delete(
      'subject/destroy',
      'SubjectController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'subject/{id}/destroy',
      'SubjectController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('subject/search', 'SubjectController@search'); // Untuk query pencarian
    $router->get('subject/{id}/detail', 'SubjectController@detail'); //Untuk mendapatkan detail item
    $router->post('subject/delete', 'SubjectController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('subject/update', 'SubjectController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'subject/restore',
      'SubjectController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'subject/{id}/restore',
      'SubjectController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'location'], function () use ($router) {
    $router->get('location', 'LocationController@index'); // Untuk mengambil seluruh dat
    $router->get(
      'location/delete',
      'LocationController@retrieveDeleteHistoryData'
    );
    $router->post('location', 'LocationController@store'); // untuk menyimpan data
    $router->put('location/{id}/edit', 'LocationController@update'); // untuk update data
    $router->post(
      'location/destroy-collections',
      'LocationController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post(
      'location/restore',
      'LocationController@restoreCollectionData'
    ); //Untuk melakukan restorasi data
    $router->delete('location/delete-all', 'LocationController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('location/{id}/delete', 'LocationController@destroy'); // untuk delete data
    $router->delete(
      'location/destroy',
      'LocationController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'location/{id}/destroy',
      'LocationController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('location/search', 'LocationController@search'); // Untuk query pencarian
    $router->get('location/{id}/detail', 'LocationController@detail'); //Untuk mendapatkan detail item
    $router->post('location/delete', 'LocationController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('location/update', 'LocationController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'location/restore',
      'LocationController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'location/{id}/restore',
      'LocationController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'koleksi'], function () use ($router) {
    $router->get('koleksi', 'KoleksiController@index'); // Untuk mengambil seluruh dat
    $router->get(
      'koleksi/delete',
      'KoleksiController@retrieveDeleteHistoryData'
    );
    $router->post('koleksi', 'KoleksiController@store'); // untuk menyimpan data
    $router->put('koleksi/{id}/edit', 'KoleksiController@update'); // untuk update data
    $router->post(
      'koleksi/destroy-collections',
      'KoleksiController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('koleksi/restore', 'KoleksiController@restoreCollectionData'); //Untuk melakukan restorasi data
    $router->delete('koleksi/delete-all', 'KoleksiController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('koleksi/{id}/delete', 'KoleksiController@destroy'); // untuk delete data
    $router->delete(
      'koleksi/destroy',
      'KoleksiController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'koleksi/{id}/destroy',
      'KoleksiController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('koleksi/search', 'KoleksiController@search'); // Untuk query pencarian
    $router->get('koleksi/{id}/detail', 'KoleksiController@detail'); //Untuk mendapatkan detail koleksi
    $router->post('koleksi/delete', 'KoleksiController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('koleksi/update', 'KoleksiController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'koleksi/restore',
      'KoleksiController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'koleksi/{id}/restore',
      'KoleksiController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'item'], function () use ($router) {
    $router->get('item', 'ItemStatusController@index'); // Untuk mengambil seluruh dat
    $router->get(
      'item/delete',
      'ItemStatusController@retrieveDeleteHistoryData'
    );
    $router->post('item', 'ItemStatusController@store'); // untuk menyimpan data
    $router->put('item/{id}/edit', 'ItemStatusController@update'); // untuk update data
    $router->post(
      'item/destroy-collections',
      'ItemController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('item/restore', 'ItemController@restoreCollectionData'); //Untuk melakukan restorasi data
    $router->delete('item/delete-all', 'ItemStatusController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('item/{id}/delete', 'ItemStatusController@destroy'); // untuk delete data
    $router->delete(
      'item/destroy',
      'ItemStatusController@deleteAllHistoryData'
    ); // Menghapus seluruh data.
    $router->delete(
      'item/{id}/destroy',
      'ItemStatusController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('item/search', 'ItemStatusController@search'); // Untuk query pencarian
    $router->get('item/{id}/detail', 'ItemStatusController@detail'); //Untuk mendapatkan detail item
    $router->post('item/delete', 'ItemStatusController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('item/update', 'ItemStatusController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'item/restore',
      'ItemStatusController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'item/{id}/restore',
      'ItemStatusController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'bahasa'], function () use ($router) {
    $router->get('bahasa', 'BahasaController@index'); // Untuk mengambil seluruh dat
    $router->get('bahasa/delete', 'BahasaController@retrieveDeleteHistoryData');
    $router->post('bahasa', 'BahasaController@store'); // untuk menyimpan data
    $router->put('bahasa/{id}/edit', 'BahasaController@update'); // untuk update data
    $router->post(
      'bahasa/destroy-collections',
      'BahasaController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('bahasa/restore', 'BahasaController@restoreCollectionData'); //Untuk melakukan restorasi data
    $router->delete('bahasa/delete-all', 'BahasaController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('bahasa/{id}/delete', 'BahasaController@destroy'); // untuk delete data
    $router->delete('bahasa/destroy', 'BahasaController@deleteAllHistoryData'); // Menghapus seluruh data.
    $router->delete(
      'bahasa/{id}/destroy',
      'BahasaController@deleteHistoryData'
    ); // Menghapus seluruh data sesuai dengan ID.
    $router->post('bahasa/search', 'BahasaController@search'); // Untuk query pencarian
    $router->get('bahasa/{id}/detail', 'BahasaController@detail'); //Untuk mendapatkan detail bahasa
    $router->post('bahasa/delete', 'BahasaController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('bahasa/update', 'BahasaController@updateSome'); // Untuk melakukan update beberapa data
    $router->put(
      'bahasa/restore',
      'BahasaController@returnAllDeleteHistoryData'
    ); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'bahasa/{id}/restore',
      'BahasaController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });

  $router->group(['name' => 'place'], function () use ($router) {
    $router->get('place', 'PlaceController@index'); // Untuk mengambil seluruh dat
    $router->get('place/delete', 'PlaceController@retrieveDeleteHistoryData');
    $router->post('place', 'PlaceController@store'); // untuk menyimpan data
    $router->put('place/{id}/edit', 'PlaceController@update'); // untuk update data
    $router->post(
      'place/destroy-collections',
      'PlaceController@deleteHistoryCollectionData'
    ); // Untuk menghapus data berupa collections
    $router->post('place/restore', 'PlaceController@restoreCollectionData'); //Untuk melakukan restorasi data
    $router->delete('place/delete-all', 'PlaceController@destroyAll'); // Untuk menghapus seluruh data yang ada di database
    $router->delete('place/{id}/delete', 'PlaceController@destroy'); // untuk delete data
    $router->delete('place/destroy', 'PlaceController@deleteAllHistoryData'); // Menghapus seluruh data.
    $router->delete('place/{id}/destroy', 'PlaceController@deleteHistoryData'); // Menghapus seluruh data sesuai dengan ID.
    $router->post('place/search', 'PlaceController@search'); // Untuk query pencarian
    $router->get('place/{id}/detail', 'PlaceController@detail'); //Untuk mendapatkan detail item
    $router->post('place/delete', 'PlaceController@destroySome'); // Untuk menghapus data yang dipilih
    $router->post('place/update', 'PlaceController@updateSome'); // Untuk melakukan update beberapa data
    $router->put('place/restore', 'PlaceController@returnAllDeleteHistoryData'); // Mengembalikan seluruh data yang sudah terhapus
    $router->put(
      'place/{id}/restore',
      'PlaceController@returnDeleteHistoryData'
    ); // Mengembalikan data yang sudah terhapus sesuai ID
  });
});
