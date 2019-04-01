<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/16
 * Time: 13:31
 */

namespace Smart\Controllers\Backend;

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Smart\Service\SysUserService;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;

class AuthController extends Backend {

	use ResetsPasswords;

	protected $autoload_service = 0;

	public function __construct(Request $request){
		parent::__construct($request);
		$this->service = ServiceManager::make( SysUserService::class );

	}

	public function changePassword(Request $request){
		$password = $request->password;
		$id = Auth::id();
		$result = $this->service->resetPwd($id, $password);

		return json($result);
	}
}
