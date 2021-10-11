<?php
namespace Admin;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Lang;
/**
 *  Admin Service Provider
 */
class AdminServiceProvider extends ServiceProvider
{
	public function boot() {
		$mainLang = Lang::where('default', 1)->first();
		setcookie('lang_id', $mainLang->lang, time() + 10000000, '/');

		Schema::defaultStringLength(191);

		$this->loadRoutesFrom(__DIR__ . '/routes/web.php');
		$this->loadViewsFrom(__DIR__ . '/./../resources/views', 'admin');
	}

	public function register() {
		$this->registerPublishables();
	}

	public function registerPublishables() {
		$basePath = dirname(__DIR__);

		$arrPublishable = ['migrations' => ["$basePath/publishable/database/migrations" => database_path('migrations') , ], 'config' => ["$basePath/publishable/config/admin.php" => config_path('admin.php') , ], ];

		foreach ($arrPublishable as $group => $paths) {
			$this->publishes($paths, $group);
		}

		$this->publishes([
		    __DIR__ . '/./../resources/assets' =>
		    resource_path('assets/vendor/admin'
		)], 'vue-components');
	}

	public function registerPublishablesVue() {
		$this->publishes([
		    __DIR__ . '/./../resources/assets' =>
		    	resource_path('assets/vendor/admin'
		)], 'vue-components');
	}
}
