<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// 首页
$routes->get("/", "Home::index");

// 登录
$routes->get("/user/login", "User::Login");
$routes->post("/user/login", "User::Login");

// 登出
$routes->get("/user/logout", "User::Logout");

// 注册
$routes->get("/user/register", "User::Register");
$routes->post("/user/register", "User::Register");

// 重置
$routes->get("/user/reset", "User::Reset");
$routes->post("/user/reset", "User::Reset");

// 用户后台
$routes->get("/user/home", "User::Home");

// 更新用户数据
$routes->post("/user/update", "User::Update");

// 渲染OPML数据
$routes->addPlaceholder("uuid", "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}");
$routes->get("/opml/(:uuid)", "Opml::DisplayOPML/$1");

// 更新/新增/删除OPML/RSS记录
$routes->post("/opml/update", "Opml::Update");
$routes->get("/opml/delete", "Opml::Delete");

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
