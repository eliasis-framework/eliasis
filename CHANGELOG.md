# CHANGELOG

## 1.0-0 - 2017-02-24

* Added `Eliasis\App\App` class.
* Added `Eliasis\App\App::setConstants()` method.
* Added `Eliasis\App\App::getSettings()` method.
* Added `Eliasis\App\App::addOption()` method.
* Added `Eliasis\App\App::__callstatic()` method.

* Added `Eliasis\App\Exception\AppException` class.
* Added `Eliasis\App\Exception\AppException->__construct()` method.

* Added `Eliasis\Controller\Controller` abstract class.
* Added `Eliasis\Controller\Controller->__construct()` method.
* Added `Eliasis\Controller\Controller::getInstance()` method.
* Added `Eliasis\Controller\Controller::getModel()` method.
* Added `Eliasis\Controller\Controller::getView()` method.
* Added `Eliasis\Controller\Controller->__clone()` method.

* Added `Eliasis\Controller\Exception\ControllerException` class.
* Added `Eliasis\Controller\Exception\ControllerException->__construct()` method.

* Added `Eliasis\Data\Data` class.

* Added `Eliasis\Hook\Hook` class.
* Added `Eliasis\Hook\Hook::get()` method.
* Added `Eliasis\Hook\Hook::setHook()` method.
* Added `Eliasis\Hook\Hook::setHooks()` method.
* Added `Eliasis\Hook\Hook::addHook()` method.
* Added `Eliasis\Hook\Hook::run()` method.
* Added `Eliasis\Hook\Hook::loadModules()` method.
* Added `Eliasis\Hook\Hook->collectHook()` method.

* Added `Eliasis\Hook\Exception\HookException` class.
* Added `Eliasis\Hook\Exception\HookException->__construct()` method.

* Added `Eliasis\Model\Model` abstract class.

* Added `Eliasis\Route\Route` class.
* Added `Eliasis\Route\Route::set()` method.
* Added `Eliasis\Route\Route::get()` method.
* Added `Eliasis\Route\Route::loadRegexRoutes()` method.

* Added `Eliasis\Router\Router` class.
* Added `Eliasis\Router\Router::__callstatic()` method.
* Added `Eliasis\Router\Router::error()` method.
* Added `Eliasis\Router\Router::haltOnMatch()` method.
* Added `Eliasis\Router\Router::invokeObject()` method.
* Added `Eliasis\Router\Router::_parseUrl()` method.
* Added `Eliasis\Router\Router::dispatch()` method.
* Added `Eliasis\Router\Router::_checkRoutes()` method.
* Added `Eliasis\Router\Router::_getUri()` method.
* Added `Eliasis\Router\Router::_checkRegexRoutes()` method.
* Added `Eliasis\Router\Router::_verifyPath()` method.

* Added `Eliasis\View\View` abstract class.
* Added `Eliasis\View\View->renderizate()` method.
* Added `Eliasis\View\View->addHeader()` method.
* Added `Eliasis\View\View->addHeaders()` method.
* Added `Eliasis\View\View->sendHeaders()` method.

* Added `config/info.php` settings file.