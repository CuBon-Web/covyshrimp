<?php

use Illuminate\Http\Request;

use Spatie\Analytics\Period;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('store_file','Api\AllController@fileStore');
Route::group(['namespace'=>'Api','middleware' => 'api'],function(){
	Route::post('login','AuthController@login');
	Route::post('upload-image','AllController@uploadImage');
	Route::post('upload-image-multi','AllController@uploadImageMulti');
	
});
Route::group(['namespace'=>'Api','middleware'=>'auth:api'],function(){

	Route::get('/rolee', function () {
		$user = request()->user();
		dd($user->can('view-users'));
		return view('welcome');
	})->middleware('auth');

	Route::post('logout','AuthController@logout'); 
	Route::get('getNotification','NotificationController@get');
	Route::get('profile','AuthController@authentication');
	Route::post('profile/update','AuthController@updateProfile');
	Route::post('profile/change-password','AuthController@changePassword');
	Route::group(['prefix' => 'admin-user'], function(){
		Route::get('list','AdminUserController@list')->middleware('rbac.permission:rbac.view');
		Route::post('create','AdminUserController@create')->middleware('rbac.permission:rbac.create');
		Route::post('update-role/{id}','AdminUserController@updateRole')->middleware('rbac.permission:rbac.update');
		Route::post('reset-password/{id}','AdminUserController@resetPassword')->middleware('rbac.permission:rbac.update');
	});
	Route::group(['prefix' => 'rbac'], function(){
		Route::get('permissions', 'RbacController@permissions')->middleware('rbac.permission:rbac.view');
		Route::post('permission/create', 'RbacController@createPermission')->middleware('rbac.permission:rbac.create');
		Route::get('roles', 'RbacController@roles')->middleware('rbac.permission:rbac.view');
		Route::post('role/create', 'RbacController@createRole')->middleware('rbac.permission:rbac.create');
		Route::post('role/update-permissions/{id}', 'RbacController@updateRolePermissions')->middleware('rbac.permission:rbac.update');
		Route::get('admin-users', 'RbacController@adminUsers')->middleware('rbac.permission:rbac.view');
		Route::post('admin-users/assign-roles/{id}', 'RbacController@assignRolesToUser')->middleware('rbac.permission:rbac.update');
	});
	Route::group(['prefix'=>'menu'],function(){
		Route::get('listMenu','MenuController@listMenu')->middleware('rbac.permission:menu.view');
		Route::get('getAllMenu','MenuController@getAllMenu')->middleware('rbac.permission:menu.view');
		Route::post('saveChangeMenu','MenuController@saveChangemenu')->middleware('rbac.permission:menu.update');
		Route::post('addNewMenu','MenuController@addNewMenu')->middleware('rbac.permission:menu.create');
		Route::get('getEditMenu/{id}','MenuController@getEditMenu')->middleware('rbac.permission:menu.view');
		Route::post('saveEditMenuById/{id}','MenuController@saveEditMenuById')->middleware('rbac.permission:menu.update');
		Route::get('deleteMenuById/{id}','MenuController@deleteMenuById')->middleware('rbac.permission:menu.delete');
	});
	Route::group(['prefix' => 'language'], function () {
		Route::post('detailLanguage', 'LanguageController@detailLanguage')->middleware('rbac.permission:language.view')->name('language.detail');
		Route::post('saveLanguage', 'LanguageController@saveLanguage')->middleware('rbac.permission:language.update')->name('language.save');
		Route::post('searchLanguage', 'LanguageController@searchLanguage')->middleware('rbac.permission:language.view')->name('language.search');
		Route::post('activeLanguage', 'LanguageController@getActiveLanguage')->middleware('rbac.permission:language.view')->name('language.active');
		Route::post('saveLanguageStatic', 'LanguageController@saveLanguageStatic')->middleware('rbac.permission:language.update')->name('language.saveLanguageStatic');
		Route::post('searchLanguageStatic', 'LanguageController@searchLanguageStatic')->middleware('rbac.permission:language.view')->name('language.searchLanguageStatic');
		Route::post('saveLanguageStaticByLang', 'LanguageController@saveLanguageStaticByLang')->middleware('rbac.permission:language.update')->name('language.saveLanguageStaticByLang');
		Route::get('deleteLanguage/{code}', 'LanguageController@deleteLanguage')->middleware('rbac.permission:language.delete')->name('language.delete');
	}); 
	Route::group(['prefix'=>'bill','namespace'=>'Bill'], function(){
		Route::get('list','BillController@list')->middleware('rbac.permission:bill.view');
		Route::post('add','BillController@add')->middleware('rbac.permission:bill.create');
		Route::post('draft','BillController@draft')->middleware('rbac.permission:bill.update');
		Route::get('detail/{code}','BillController@detail')->middleware('rbac.permission:bill.view');
		Route::post('change-status','BillController@changeStatus')->middleware('rbac.permission:bill.update');
	});
	Route::group(['prefix'=>'pagecontent'], function(){
		Route::post('add','PageContentController@add')->middleware('rbac.permission:pagecontent.create');
	});
	Route::group(['prefix'=>'messcontact'], function(){
		Route::post('list','AllController@listMesscontact')->middleware('rbac.permission:message.view');
	});
	Route::post('addLibrary','AllController@addLibrary')->middleware('rbac.permission:library.create');
	Route::group(['prefix'=>'product', 'namespace'=>'Product'], function(){
		Route::post('create','ProductController@create')->middleware('rbac.permission:product.create');
		Route::post('list','ProductController@list')->middleware('rbac.permission:product.view');
		Route::post('bulk-delete','ProductController@bulkDelete')->middleware('rbac.permission:product.delete');
		Route::post('bulk-status','ProductController@bulkStatus')->middleware('rbac.permission:product.update');
		Route::post('bulk-duplicate','ProductController@bulkDuplicate')->middleware('rbac.permission:product.create');
		Route::get('edit/{id}','ProductController@edit')->middleware('rbac.permission:product.view');
		Route::get('delete/{id}','ProductController@delete')->middleware('rbac.permission:product.delete');
		Route::get('duplicate/{id}','ProductController@duplicate')->middleware('rbac.permission:product.create');
		Route::get('listtags/{id}','ProductController@listTags')->middleware('rbac.permission:product.view');
		Route::get('list_variant_sku/{id}','ProductController@listVariantSku')->middleware('rbac.permission:product.view');
		Route::group(['prefix'=>'category'], function(){
			Route::post('add','CategoryController@add')->middleware('rbac.permission:product.create');
			Route::post('list','CategoryController@list')->middleware('rbac.permission:product.view');
			Route::get('delete/{id}','CategoryController@delete')->middleware('rbac.permission:product.delete');
			Route::get('edit/{id}','CategoryController@edit')->middleware('rbac.permission:product.view');
		});
		Route::group(['prefix'=>'product_type'], function(){
			Route::post('add','TypeProductController@add')->middleware('rbac.permission:product.create');
			Route::post('list','TypeProductController@list')->middleware('rbac.permission:product.view');
			Route::get('delete/{id}','TypeProductController@delete')->middleware('rbac.permission:product.delete');
			Route::get('edit/{id}','TypeProductController@edit')->middleware('rbac.permission:product.view');
			Route::get('findCateType/{cate_id}','TypeProductController@findType')->middleware('rbac.permission:product.view');
		});
		Route::group(['prefix'=>'type_two'], function(){
			Route::post('add','TypeTwoProductController@add')->middleware('rbac.permission:product.create');
			Route::post('list','TypeTwoProductController@list')->middleware('rbac.permission:product.view');
			Route::get('delete/{id}','TypeTwoProductController@delete')->middleware('rbac.permission:product.delete');
			Route::get('edit/{id}','TypeTwoProductController@edit')->middleware('rbac.permission:product.view');
			Route::get('findCateType/{cate_id}','TypeTwoProductController@findType')->middleware('rbac.permission:product.view');
		});
	});
	Route::group(['prefix'=>'construction','namspace'=>"Construction"], function(){
		Route::post('list','Construction\ConstructionController@list')->middleware('rbac.permission:construction.view');
		Route::post('create','Construction\ConstructionController@create')->middleware('rbac.permission:construction.create');
		Route::get('edit/{id}','Construction\ConstructionController@edit')->middleware('rbac.permission:construction.view');
		Route::get('listPro','Construction\ConstructionController@listProduct')->middleware('rbac.permission:construction.view');
	});
	Route::group(['prefix'=>'solution','namspace'=>"Solution"], function(){
		Route::post('list','SolutionController@list')->middleware('rbac.permission:solution.view');
		Route::post('create','SolutionController@create')->middleware('rbac.permission:solution.create');
		Route::get('edit/{id}','SolutionController@edit')->middleware('rbac.permission:solution.view');
		Route::get('delete/{id}','SolutionController@delete')->middleware('rbac.permission:solution.delete');
	});
	Route::group(['prefix'=>'project','namspace'=>"Project"], function(){
		Route::post('list','ProjectController@list')->middleware('rbac.permission:project.view');
		Route::post('create','ProjectController@create')->middleware('rbac.permission:project.create');
		Route::get('edit/{id}','ProjectController@edit')->middleware('rbac.permission:project.view');
		Route::get('delete/{id}','ProjectController@delete')->middleware('rbac.permission:project.delete');
	});
	Route::group(['prefix'=>'variant','namspace'=>"Variant"], function(){
		Route::post('list','VariantController@list')->middleware('rbac.permission:variant.view');
		Route::post('create','VariantController@create')->middleware('rbac.permission:variant.create');
		Route::get('edit/{id}','VariantController@edit')->middleware('rbac.permission:variant.view');
		Route::get('delete/{id}','VariantController@delete')->middleware('rbac.permission:variant.delete');
		Route::get('get-value/{id}','VariantController@getValue')->middleware('rbac.permission:variant.view');
	});
	Route::group(['prefix'=>'promotion','namspace'=>"Promotion"], function(){
		Route::post('list','PromotionController@list')->middleware('rbac.permission:promotion.view');
		Route::post('create','PromotionController@create')->middleware('rbac.permission:promotion.create');
		Route::get('edit/{id}','PromotionController@edit')->middleware('rbac.permission:promotion.view');
		Route::get('delete/{id}','PromotionController@delete')->middleware('rbac.permission:promotion.delete');
	});
	Route::group(['prefix'=>'service','namspace'=>"service"], function(){
		Route::post('list','ServiceController@list')->middleware('rbac.permission:service.view');
		Route::post('create','ServiceController@create')->middleware('rbac.permission:service.create');
		Route::get('edit/{id}','ServiceController@edit')->middleware('rbac.permission:service.view');
		Route::get('delete/{id}','ServiceController@delete')->middleware('rbac.permission:service.delete');
		Route::group(['prefix'=>'category'], function(){
			Route::post('add','ServiceCateController@add')->middleware('rbac.permission:service.create');
			Route::post('list','ServiceCateController@list')->middleware('rbac.permission:service.view');
			Route::get('edit/{id}','ServiceCateController@edit')->middleware('rbac.permission:service.view');
			Route::get('delete/{id}','ServiceCateController@delete')->middleware('rbac.permission:service.delete');
		});
	});
	Route::group(['prefix'=>'tag', 'namespace'=>'Tag'], function(){
		Route::post('add','TagController@add')->middleware('rbac.permission:tag.create');
		Route::post('list','TagController@list')->middleware('rbac.permission:tag.view');
		Route::get('edit/{id}','TagController@edit')->middleware('rbac.permission:tag.view');
		Route::get('delete/{id}','TagController@delete')->middleware('rbac.permission:tag.delete');
		Route::group(['prefix'=>'category'], function(){
			Route::post('add','TagCateController@add')->middleware('rbac.permission:tag.create');
			Route::post('list','TagCateController@list')->middleware('rbac.permission:tag.view');
			Route::get('delete/{id}','TagCateController@delete')->middleware('rbac.permission:tag.delete');
			Route::get('edit/{id}','TagCateController@edit')->middleware('rbac.permission:tag.view');
		});
	});
	Route::post('listSloganbanner','BannerAdsController@listSlogan')->middleware('rbac.permission:bannerads.view');
	Route::post('listAdsbanner','BannerAdsController@list')->middleware('rbac.permission:bannerads.view');
	Route::post('createAdsbanner','BannerAdsController@create')->middleware('rbac.permission:bannerads.create');
	Route::get('editAdsbanner/{id}','BannerAdsController@edit')->middleware('rbac.permission:bannerads.view');
	Route::get('deleteAdsbanner/{id}','BannerAdsController@delete')->middleware('rbac.permission:bannerads.delete');

	Route::group(['prefix'=>'reviewCus','namspace'=>"reviewCus"], function(){
		Route::post('list','ReviewCusController@list')->middleware('rbac.permission:review.view');
		Route::post('create','ReviewCusController@create')->middleware('rbac.permission:review.create');
		Route::get('edit/{id}','ReviewCusController@edit')->middleware('rbac.permission:review.view');
		Route::get('delete/{id}','ReviewCusController@delete')->middleware('rbac.permission:review.delete');
	});
	Route::group(['prefix'=>'blog', 'namespace'=>'Blog'], function(){
		Route::post('create','BlogController@create')->middleware('rbac.permission:blog.create');
		Route::post('list','BlogController@list')->middleware('rbac.permission:blog.view');
		Route::get('edit/{id}','BlogController@edit')->middleware('rbac.permission:blog.view');
		Route::get('delete/{id}','BlogController@delete')->middleware('rbac.permission:blog.delete');
		Route::group(['prefix'=>'category'], function(){
			Route::post('add','BlogCategoryController@add')->middleware('rbac.permission:blog.create');
			Route::post('list','BlogCategoryController@list')->middleware('rbac.permission:blog.view');
			Route::get('edit/{id}','BlogCategoryController@edit')->middleware('rbac.permission:blog.view');
			Route::get('delete/{id}','BlogCategoryController@deleteCateBlog')->middleware('rbac.permission:blog.delete');
		});
		Route::group(['prefix'=>'type'], function(){
			Route::post('add','BlogTypegoryController@add')->middleware('rbac.permission:blog.create');
			Route::post('list','BlogTypegoryController@list')->middleware('rbac.permission:blog.view');
			Route::get('edit/{id}','BlogTypegoryController@edit')->middleware('rbac.permission:blog.view');
			Route::get('delete/{id}','BlogTypegoryController@deleteTypeBlog')->middleware('rbac.permission:blog.delete');
			Route::get('findtype/{id}','BlogTypegoryController@findTypeBlog')->middleware('rbac.permission:blog.view');
		});
	});
	Route::group(['prefix'=>'page_content'], function(){
		Route::post('create','PageContentController@create')->middleware('rbac.permission:pagecontent.create');
		Route::post('list','PageContentController@list')->middleware('rbac.permission:pagecontent.view');
		Route::get('edit/{quiz_id}/{language}','PageContentController@edit')->middleware('rbac.permission:pagecontent.view');
		Route::get('delete/{quiz_id}','PageContentController@deletePageContent')->middleware('rbac.permission:pagecontent.delete');
	});
	Route::group(['prefix'=>'website','namespace'=>'Website'], function(){
		Route::post('banner','BannerController@createOrUpdate')->middleware('rbac.permission:website.update');
		Route::get('list-banner','BannerController@list')->middleware('rbac.permission:website.view');
		Route::post('partner','PartnerController@createOrUpdate')->middleware('rbac.permission:website.update');
		Route::post('prize','PartnerController@createOrUpdatePrize')->middleware('rbac.permission:website.update');
		Route::get('list-partner','PartnerController@listPartner')->middleware('rbac.permission:website.view');
		Route::get('list-prize','PartnerController@listPrize')->middleware('rbac.permission:website.view');
		Route::get('setting','SettingController@setting')->middleware('rbac.permission:website.view');
		Route::post('save-setting','SettingController@postsetting')->middleware('rbac.permission:website.update');
		Route::post('founder','FounderController@createOrUpdate')->middleware('rbac.permission:website.update');
		Route::get('list-founder','FounderController@list')->middleware('rbac.permission:website.view');
		Route::post('video','VideoController@createOrUpdateVideo')->middleware('rbac.permission:website.update');
		Route::get('list-video','VideoController@listVideo')->middleware('rbac.permission:website.view');
		Route::post('albumaffter','AlbumAffterController@createOrUpdateAlbumAffter')->middleware('rbac.permission:website.update');
		Route::get('list-albumaffter','AlbumAffterController@listAlbumAfftero')->middleware('rbac.permission:website.view');
	});
	Route::group(['prefix'=>'customer','namespace'=>'Customer'], function(){
		Route::post('list','CustomerController@list')->middleware('rbac.permission:customer.view');
		Route::post('add','CustomerController@create')->middleware('rbac.permission:customer.create');
		Route::get('edit/{id}','CustomerController@getEdit')->middleware('rbac.permission:customer.view');
		Route::post('active','CustomerController@activeCustomer')->middleware('rbac.permission:customer.update');
		Route::post('changeStatus','CustomerController@changeStatus')->middleware('rbac.permission:customer.update');
		Route::post('edit-profile','CustomerController@postEdit')->middleware('rbac.permission:customer.update');
	});
	
	Route::group(['prefix'=>'home', 'middleware' => 'rbac.permission:dashboard.view'], function(){
		Route::post('chart','HomeController@chart');
		Route::get('revenue','HomeController@revenue');
		Route::get('statistical-bill','HomeController@statisticalBill');
		Route::get('overview','HomeController@overview');
		Route::get('analytics',function(){
			$analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));
			$data = [];
			if(count($analyticsData) > 0 ){
				foreach($analyticsData as $item){
					$obj = new \stdClass;
					$obj->label = $item['date']->date;
					$obj->value = $item['pageViews'];
					$data[] = $obj;
				}
			}
			return response()->json([
				'data'=> $data,
				'message' => 'success'
			]);
		});
		Route::post('search_navbar','HomeController@searchNavbar');
	});
});
