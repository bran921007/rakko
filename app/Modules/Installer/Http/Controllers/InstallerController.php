<?php
namespace App\Modules\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
//use October\Rain\Config\Rewrite as NewConfig;
//use App\Helpers\ConfigWriter\Rewrite as Rewrite;
//use App\Helpers\ConfigWriter\FileWriter as FileWriter;

use Artisan;
use Config;
use Caffeinated\Flash\Facades\Flash as Flash;
use Input;
use File;
use Theme;

class InstallerController extends Controller
{


	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function welcome()
	{
		return Theme::View('modules.installer.installer');
//		return View('installer::installer');
	}


/*
|--------------------------------------------------------------------------
| Step: 1
| check dependancies
|--------------------------------------------------------------------------
*/


	public function getIndex()
	{

		if ( Config::get('rakko.installed') === true) {
			Flash::error(trans('installer::install.error.installed'));
			return redirect('/');
		}

		return View('installer::check');
	}


/*
|--------------------------------------------------------------------------
| Step: 3
| database install
|--------------------------------------------------------------------------
*/


	public function getArtisan()
	{

// Migrate: kagi
		try {
			Artisan::call('module:migrate', [
				'module' => 'kagi'
				]);
			$module_migrate = true;
		} catch (Exception $e) {
			$module_migrate = false;
			Log::error( trans('installer::install.error.migrate', ['table' => 'kagi']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Migrate: profiles
		try {
			Artisan::call('module:migrate', [
				'module' => 'profiles'
				]);
			$module_migrate = true;
		} catch (Exception $e) {
			$module_migrate = false;
			Log::error( trans('installer::install.error.migrate', ['table' => 'profiles']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Migrate: general
		try {
			Artisan::call('module:migrate', [
				'module' => 'general'
				]);
			$module_migrate = true;
		} catch (Exception $e) {
			$module_migrate = false;
			Log::error( trans('installer::install.error.migrate', ['table' => 'general']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Migrate: kantoku
		try {
			Artisan::call('module:migrate', [
				'module' => 'kantoku'
				]);
			$module_migrate = true;
		} catch (Exception $e) {
			$module_migrate = false;
			Log::error( trans('installer::install.error.migrate', ['table' => 'kantoku']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Migrate: origami
		try {
			Artisan::call('module:migrate', [
				'module' => 'origami'
				]);
			$module_migrate = true;
		} catch (Exception $e) {
			$module_migrate = false;
			Log::error( trans('installer::install.error.migrate', ['table' => 'origami']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Seed: kagi
		try {
			Artisan::call('module:seed', [
				'module' => 'kagi'
				]);
			$module_seed = true;
		} catch (Exception $e) {
			$module_seed = false;
			Log::error( trans('installer::install.error.seed', ['table' => 'kagi']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Seed: profiles
		try {
			Artisan::call('module:seed', [
				'module' => 'profiles'
				]);
			$module_seed = true;
		} catch (Exception $e) {
			$module_seed = false;
			Log::error( trans('installer::install.error.seed', ['table' => 'profiles']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Seed: profiles
		try {
			Artisan::call('module:seed', [
				'module' => 'general'
				]);
			$module_seed = true;
		} catch (Exception $e) {
			$module_seed = false;
			Log::error( trans('installer::install.error.seed', ['table' => 'general']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Seed: kantoku
		try {
			Artisan::call('module:seed', [
				'module' => 'kantoku'
				]);
			$module_seed = true;
		} catch (Exception $e) {
			$module_seed = false;
			Log::error( trans('installer::install.error.seed', ['table' => 'kantoku']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// Seed: manager
		try {
			Artisan::call('module:seed', [
				'module' => 'origami'
				]);
			$module_seed = true;
		} catch (Exception $e) {
			$module_seed = false;
			Log::error( trans('installer::install.error.seed', ['table' => 'origami']) . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

		return View('installer::artisan',
			compact(
				'module_migrate',
				'module_seed'
			));
	}


/*
|--------------------------------------------------------------------------
| Step: 3
| configure
|--------------------------------------------------------------------------
*/


	public function getSettings()
	{
//dd('get settings');
// 		$fileWriter = new FileWriter;
// 		$fileWriter->write('rakko.installed', true);

// $writeConfig = new Rewrite;
// $file = base_path() . '/config/' . 'rakko.php';
// $writeConfig->toFile($file, [
// 	'installed'=> '564654654'
// 	]);

// work around until config::write can be solved
		$config_installed = Config::get('rakko.installed');
		$config_timezone = Config::get('app.timezone');
//dd($config_installed);

		return View('installer::settings_show',
			compact(
				'config_installed',
				'config_timezone'
			));


/*
		$version = '1.0.0';

Artisan::call(
	'vendor:publish',
	[
		'--force'=> true,
		'--provider' => 'App\Modules\Installer\Providers\InstallerServiceProvider'
	]);

dd(Artisan::call(
	'vendor:publish',
	[
		'--force'=> true,
		'--provider' => 'App\Modules\Installer\Providers\InstallerServiceProvider'
	]));

//vendor:publish --provider="App\Modules\Installer\Providers\InstallerServiceProvider" --tag="rakko" --force


$file = base_path() . '/config/' . 'rakko.php';
$contents = "hello";

//dd(File::isWritable($file));
if (!is_writable($file)) {
	$results = chmod($file, '0777');  //this gives true
}
dd($results);

$newAppConfig = new NewConfig;
$newAppConfig->toFile($file, [
	'installed'=> '564654654'
	]);


$bytes_written = File::put($file, $contents);
if ($bytes_written === false)
{
    die("Error writing to file");
}

// rakko app information
if ( Storage::disk('local')->exists('/../../config/rakko.php') ) {
dd('get settings');


}


		try {

// 			Config::set('rakko.installed', false);
// 			Config::set('rakko.version', $version);
// 			Config::set('rakko.install_date', date('YmdHis'));

			$rakko_config = true;
		} catch (Exception $e) {
			$rakko_config = false;
			Log::error( trans('installer::install.error.rakko_config') . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

//		return View('installer::final');


		return View('installer::settings_show');
*/
	}




/*
|--------------------------------------------------------------------------
| Step: 4
|
|--------------------------------------------------------------------------
*/


	public function postSettings()
	{
/*
//dd('post settings');
		$input = Input::all();
		$timezone = Input::get('timezone');
		$version = '1.0.0';

// app.timezone
//dd($timezone);
		try {
			Config::write('app.timezone', $timezone);
			$time_zone = true;
dd(Config::write('app.timezone', $timezone));
		} catch (Exception $e) {
			$timeZone = false;
			Log::error( trans('installer::install.error.timeZone') . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

// rakko app information
		try {
			Config::set('rakko.installed', false);
			Config::set('rakko.version', $version);
			Config::set('rakko.install_date', date('YmdHis'));
			$rakko_config = true;
		} catch (Exception $e) {
			$rakko_config = false;
			Log::error( trans('installer::install.error.rakko_config') . $e->getMessage() );
			Response::make($e->getMessage(), 500);
		}

		return View('installer::final');
*/
	}




/*
|--------------------------------------------------------------------------
| Step: 5
| login
|--------------------------------------------------------------------------
*/


	public function getFinal()
	{
//dd('final');
		return View('installer::final');
	}


}
