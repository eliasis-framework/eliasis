# CHANGELOG

## 1.0.5 - 2017-04-29

* Added `Eliasis\Module\Module::getModulesInfo()` method.
A new method was added to obtain basic information for all loaded modules.


## 1.0.4 - 2017-04-26

* The method of accessing the App object for multiapplications has been changed, the identifier is indicated directly when instantiating it. For example: 

App::Identifier('namespace', 'controller'); 

Instead of 

App::id('Identifier'); 
App::('namespace', 'controller');

## 1.0.3 - 2017-04-14

* Deleted `Eliasis\Module\Module->getNamespace()` method.
* Deleted `Eliasis\Module\Module->getUrl()` method.
* Deleted `Eliasis\Module\Module->getPath()` method.
* Deleted `Eliasis\Module\Module->getFolder()` method.

## 1.0.2 - 2017-04-06

* Modified the startup mode of the framework to allow the creation and operation of several applications at once.

* Added   `Eliasis\App\App::id()` method.
* Added   `Eliasis\App\App::run()` method.
* Added   `Eliasis\App\App::getInstance()` method.
* Deleted `Eliasis\App\App->__construct()` method.

* Added `Eliasis\Model\Model` abstract class.
* Added `Eliasis\Model\Model->__construct()` method.
* Added `Eliasis\Model\Model::getInstance()` method.
* Added `Eliasis\Model\Model->__wakeup()` method.
* Added `Eliasis\Model\Model->__clone()` method.

* Added `Eliasis\Model\Exception\ModelException` class.
* Added `Eliasis\Model\Exception\ModelException->__construct()` method.

* Added `Eliasis\Controller\Controller::getModelInstance()` method.

## 1.0.1 - 2017-03-15

* Deleted `Eliasis\Hook\Hook` class.
* Deleted `Eliasis\Hook\Hook::getInstance()` method.
* Deleted `Eliasis\Hook\Hook::setHook()` method.
* Deleted `Eliasis\Hook\Hook::setHooks()` method.
* Deleted `Eliasis\Hook\Hook::addHook()` method.
* Deleted `Eliasis\Hook\Hook::run()` method.
* Deleted `Eliasis\Hook\Hook::loadModules()` method.
* Deleted `Eliasis\Hook\Hook->collectHook()` method.

* Deleted `Eliasis\Hook\Exception\HookException` class.
* Deleted `Eliasis\Hook\Exception\HookException->__construct()` method.

* Deleted `Eliasis\Route\Route` class.
* Deleted `Eliasis\Route\Route::addRoute()` method.
* Deleted `Eliasis\Route\Route::getRoute()` method.
* Deleted `Eliasis\Route\Route::loadRegexRoutes()` method.

* Deleted `Eliasis\Router\Router` class.
* Deleted `Eliasis\Router\Router::__callstatic()` method.
* Deleted `Eliasis\Router\Router::error()` method.
* Deleted `Eliasis\Router\Router::haltOnMatch()` method.
* Deleted `Eliasis\Router\Router::invokeObject()` method.
* Deleted `Eliasis\Router\Router::_parseUrl()` method.
* Deleted `Eliasis\Router\Router::dispatch()` method.
* Deleted `Eliasis\Router\Router::_checkRoutes()` method.
* Deleted `Eliasis\Router\Router::_getUri()` method.
* Deleted `Eliasis\Router\Router::_checkRegexRoutes()` method.
* Deleted `Eliasis\Router\Router::_verifyPath()` method.

* Deleted `Eliasis\Data\Data` class.

* Deleted `Eliasis\App\App::getRoutes()` method.
* Deleted `Eliasis\App\App::_setConstants()` method.
* Added   `Eliasis\App\App->_runErrorHandler()` method.
* Added   `Eliasis\App\App->_runCleaner()` method.
* Added   `Eliasis\App\App->_runHooks()` method.
* Added   `Eliasis\App\App->_runModules()` method.
* Added   `Eliasis\App\App->_runRoutes()` method.
* Added   `Eliasis\App\App->_setPaths()` method.
* Added   `Eliasis\App\App->_setUrls()` method.

* Deleted `Eliasis\App\Exception\AppException` class.
* Deleted `Eliasis\App\Exception\AppException->__construct()` method.

* Deleted `Eliasis\Module\Module->addResource()` method.
* Added   `Eliasis\Module\Module->addResources()` method.

* Deleted `Josantonius/Cleaner` library.
* Deleted `Josantonius/Asset` library.
* Deleted `Josantonius/ErrorHandler` library.

## 1.0.0 - 2017-03-09

* Added `Eliasis\App\App` class.
* Added `Eliasis\App\App::_setConstants()` method.
* Added `Eliasis\App\App::getSettings()` method.
* Added `Eliasis\App\App::addOption()` method.
* Added `Eliasis\App\App::_setRoutes()` method.
* Added `Eliasis\App\App::__callstatic()` method.

* Added `Eliasis\App\Exception\AppException` class.
* Added `Eliasis\App\Exception\AppException->__construct()` method.

* Added `Eliasis\Controller\Controller` abstract class.
* Added `Eliasis\Controller\Controller->__construct()` method.
* Added `Eliasis\Controller\Controller::getInstance()` method.
* Added `Eliasis\Controller\Controller::getViewInstance()` method.
* Added `Eliasis\Controller\Controller->__wakeup()` method.
* Added `Eliasis\Controller\Controller->__clone()` method.

* Added `Eliasis\Controller\Exception\ControllerException` class.
* Added `Eliasis\Controller\Exception\ControllerException->__construct()` method.

* Added `Eliasis\Data\Data` class.

* Added `Eliasis\Hook\Hook` class.
* Added `Eliasis\Hook\Hook::getInstance()` method.
* Added `Eliasis\Hook\Hook::setHook()` method.
* Added `Eliasis\Hook\Hook::setHooks()` method.
* Added `Eliasis\Hook\Hook::addHook()` method.
* Added `Eliasis\Hook\Hook::run()` method.
* Added `Eliasis\Hook\Hook::loadModules()` method.
* Added `Eliasis\Hook\Hook->collectHook()` method.

* Added `Eliasis\Hook\Exception\HookException` class.
* Added `Eliasis\Hook\Exception\HookException->__construct()` method.

* Added `Eliasis\Module\Module` class.
* Added `Eliasis\Module\Module::getInstance()` method.
* Added `Eliasis\Module\Module->_setInfo()` method.
* Added `Eliasis\Module\Module->addResource()` method.
* Added `Eliasis\Module\Module->getNamespace()` method.
* Added `Eliasis\Module\Module->getUrl()` method.
* Added `Eliasis\Module\Module->getPath()` method.
* Added `Eliasis\Module\Module->getFolder()` method.
* Added `Eliasis\Module\Module::__callstatic()` method.

* Added `Eliasis\Module\Exception\ModuleException` class.
* Added `Eliasis\Module\Exception\ModuleException->__construct()` method.

* Added `Eliasis\Model\Model` abstract class.

* Added `Eliasis\Route\Route` class.
* Added `Eliasis\Route\Route::addRoute()` method.
* Added `Eliasis\Route\Route::getRoute()` method.
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

* Added `Josantonius/Cleaner` library.
* Added `Josantonius/Url` library.
* Added `Josantonius/Asset` library.
* Added `Josantonius/ErrorHandler` library.
