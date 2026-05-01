<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});
Route::get('/crm', function () {
    return view('app');
});
// Route::get('/admin', function () {
//     dd(1);
//     return view('app');
// }); 
Route::get('/','HomeController@home')->name('home')->middleware(checkLanguage::class);
Route::get('/sitemap.xml', 'SitemapController@index')->name('sitemap.index');
Route::get('/sitemaps.xml', 'SitemapController@index');
Route::get('/sitemaps/static.xml', 'SitemapController@staticPages')->name('sitemap.static');
Route::get('/sitemaps/blog.xml', 'SitemapController@blog')->name('sitemap.blog');
Route::get('/sitemaps/product.xml', 'SitemapController@product')->name('sitemap.product');
Route::group(['namespace'=>'Client','middleware' => ['checkLanguage']], function(){
    Route::get('get-variant.html','ProductController@getSku')->name('getSku');
    Route::get('type-product/{id}','PageController@typeproduct');
    Route::get('district/{id}','PageController@district');
    
    Route::get('dang-nhap.html','AuthController@login')->name('login')->middleware('CheckAuthLogout::class');
    Route::post('dang-nhap.html','AuthController@postLogin')->name('postlogin');
    Route::get('dang-ky.html','AuthController@register')->name('register');
    Route::post('dang-ky.html','AuthController@postRegister')->name('postRegister');
    Route::get('dang-xuat.html','AuthController@logout')->name('logout')->middleware('CheckAuthClient::class');
    Route::post('filter.html','ProductController@filterProduct')->name('filterProduct');
    Route::get('build-pc.html','BuildPcController@buildPc')->name('buildPc');
    
    Route::get('video-review.html','PageController@videoReview')->name('videoReview');
    Route::get('trang-noi-dung/{slug}.html','PageContentController@detail')->name('pagecontent');
    Route::get('dich-vu/{slug}.html','PageController@serviceList')->name('serviceList');
    Route::get('dich-vu/{danhmuc}/{slug}.html','PageController@serviceDetail')->name('serviceDetail');
    Route::get('gioi-thieu.html','PageController@aboutUs')->name('aboutUs');  
    Route::get('cong-nghe.html','PageController@technology')->name('technology');   
    Route::get('lien-he.html','PageController@contact')->name('lienHe');
    Route::post('lien-he','PageController@postcontact')->name('postcontact');
    Route::get('du-an-tieu-bieu.html','PageController@duanTieuBieu')->name('duanTieuBieu');
    Route::get('du-an-tieu-bieu/{slug}.html','PageController@duanTieuBieuDetail')->name('duanTieuBieuDetail');
    Route::get('cau-hoi-thuong-gap.html','PageController@fag')->name('fag');
    Route::get('giai-phap/{slug}.html','SolutionController@detail')->name('detailSolution');

    Route::group(['prefix'=>'cong-trinh'], function(){
        Route::get('/tat-ca.html','ConstructionController@list')->name('allListConstruction');
        Route::get('{id}.html','ConstructionController@detail')->name('detailConstruction');
    });
    Route::get('quickview/{id}','PageController@quickview')->name('quickview');
    Route::get('nhan-bao-gia.html','PageController@baogia')->name('baogia');

    Route::get('gio-hang.html', 'CartController@listCart')->name('listCart');
    Route::post('add-to-cart', 'CartController@addToCart')->name('add.to.cart');
    Route::post('update-cart', 'CartController@update')->name('update.cart');
    Route::post('remove-from-cart', 'CartController@remove')->name('remove.from.cart');
    Route::get('thanh-toan.html','CartController@checkout')->name('checkout');
    Route::post('thantoan','CartController@postBill')->name('postBill');
    Route::get('payos/return','CartController@payosReturn')->name('payos.return');
    Route::get('payos/cancel','CartController@payosCancel')->name('payos.cancel');
    Route::post('payos/webhook','CartController@payosWebhook')->name('payos.webhook');
    Route::get('dat-hang-thanh-cong.html','CartController@orderSuccess')->name('orderSuccess');
    
    Route::get('khuyen-mai.html','PageController@khuyenMai')->name('khuyenMai');
    Route::get('khuyen-mai/{slug}.html','PageController@detailKhuyenmai')->name('detailKhuyenmai');

    Route::get('dat-ban.html','PageController@orderNow')->name('orderNow');
    Route::get('menu.html','PageController@menu')->name('menu');
    Route::get('account/orders','AuthController@accoungOrder')->name('accoungOrder')->middleware('CheckAuthClient::class');
    Route::get('account/orders/{billid}','AuthController@accoungOrderDetail')->name('accoungOrderDetail')->middleware('CheckAuthClient::class');
    
    Route::post('buildpc','BuildPcController@renderProductBuild')->name('renderProductBuild');
    Route::post('addProductBuildPc','BuildPcController@addProductBuildPc')->name('addProductBuildPc');
    Route::post('removeItemBuild','BuildPcController@removeItemBuild')->name('removeItemBuild');
    Route::post('plusQtyItemBuild','BuildPcController@plusQtyItemBuild')->name('plusQtyItemBuild');
    Route::post('mineQtyItemBuild','BuildPcController@mineQtyItemBuild')->name('mineQtyItemBuild');
    Route::post('addBuildToCart','BuildPcController@addBuildToCart')->name('addBuildToCart');
    
    Route::get('auth/google', 'GoogleController@redirectToGoogle')->name('loginGoogle');
    Route::get('auth/google/callback', 'GoogleController@handleGoogleCallback');

    Route::get('auth/facebook', 'FacebookController@redirectToFacebook')->name('loginFacebook');
    Route::get('auth/facebook/callback', 'FacebookController@handleFacebookCallback'); 
    Route::group(['prefix'=>'tin-tuc'], function(){
        Route::get('/ban-tin-moi-ngay.html','BlogController@list')->name('allListBlog');
        Route::get('danh-muc/{slug}.html','BlogController@listCateBlog')->name('listCateBlog');
        Route::get('loai-danh-muc/{slug}.html','BlogController@listTypeBlog')->name('listTypeBlog');
        Route::get('chi-tiet/{slug}.html','BlogController@detailBlog')->name('detailBlog');
    });
    Route::get('send-email','PageController@sendmail')->name('sendmail');
    Route::get('tat-ca-san-pham.html','ProductController@allProduct')->name('allProduct');
    Route::get('san-pham-noi-bat.html','ProductController@flashSale')->name('flashSale');
    Route::get('tag/{tag}.html','ProductController@tag')->name('allListTags');
    Route::get('tag/danhmuc/{tagCateSlug}.html','ProductController@tagCateList')->name('allTagCateList');
    Route::get('chi-tiet/{cate}/{type}/{id}.html','ProductController@detail_product')->name('detailProduct');
    Route::get('{danhmuc}.html','ProductController@allListCate')->name('allListProCate');
    Route::get('{danhmuc}/{loaidanhmuc}.html','ProductController@allListType')->name('allListType');
    Route::get('{danhmuc}/{loaidanhmuc}/{loai2}.html','ProductController@allListTypeTwo')->name('allListTypeTwo');
    Route::post('san-pham/compare','ProductController@compare')->name('compareProduct');
    Route::post('san-pham/remove-compare','ProductController@removeCompare')->name('removeCompare');
    Route::get('san-pham/so-sanh-san-pham','ProductController@compareList')->name('compareList');
    Route::post('auto-search-product','ProductController@autosearchcomplete')->name('autosearchcomplete');
    Route::post('ket-qua-tim-kiem','ProductController@searchResult')->name('search_result');
    
});


Route::post('/languages', 'LanguageController@index')->name('languages');
