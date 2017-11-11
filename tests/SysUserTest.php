<?php
use Smart\Models\SysUser;
use Tests\TestCase;
use Laravel\Dusk\Browser;

class SysUserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function setUp()
    {
        parent::setUp();
        $this->be( SysUser::first(),'admin');
    }

    public function testIndex(){

        //$this->user = factory(SysUser::class )->create();
        $this->browse(function (Browser $browser){
            $browser->loginAs(\Smart\Models\SysUser::first())->visit('backend/sysuser/index')->assertSee('系统用户');
        });

    }

    public function testRead(){

        $count = SysUser::where('status','=' , 1)->count();
        $this->browse(function (Browser $browser) use ($count) {
        //    $browser->visit( 'backend/sysuser/read?status=1')->seeJson(['code'=>0,'msg'=>'查询成功' , 'total' => $count ]);
        });
    }


}
