<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/11/10
 * Time: 15:12
 */
use Tests\TestCase;
use Laravel\Dusk\Browser;

class IndexCase extends TestCase{

    public function setUp(){
        parent::setUp();
        $this->be( \Smart\Models\SysUser::first(),'admin');
    }

    public function testIndex(){

        $this->browse(function (Browser $browser) {
            $browser->loginAs(\Smart\Models\SysUser::first())->visit('/')
                ->assertSee('Laravel');
        });
    //    $this->visit('backend/index/index')->assertResponseOk()->see("首页");
    }
}