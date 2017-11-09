<?php
use Smart\Models\SysUser;
use Tests\TestCase;

class SysUserTest extends TestCase
{
    public $user ;
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

       $this->visit('backend/sysuser/index')->assertResponseStatus(200)->see('系统用户');


    }

    public function testRead(){

        $count = SysUser::where('status','=' , 1)->count();

       $this->visit( 'backend/sysuser/read?status=1')->assertResponseOk()->seeJson(['code'=>0,'msg'=>'查询成功' , 'total' => $count ]);

    }


}
