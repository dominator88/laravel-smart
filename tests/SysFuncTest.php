<?php
use Smart\Models\SysFunc;
use Tests\TestCase;
use Laravel\Dusk\Browser;

class SysFuncTest extends TestCase
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
        $this->be( \Smart\Models\SysUser::first(),'admin');
    }

    public function testIndex(){

        //$this->user = factory(SysUser::class )->create();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(\Smart\Models\SysUser::first())->visit('backend/sysfunc/index')->assertSee('系统功能');


       //     $browser->visit('backend/sysfunc/read?status=1')->seeJson(['code' => 0 , 'msg' => '查询成功' ]);

        });

    }

    public function testCreate(){


        $this->browse(function (Browser $browser) {
            $browser->loginAs(\Smart\Models\SysUser::first())->visit('backend/sysfunc/index')->press('#addNewBtn')
                ->pause(1000)->whenAvailable('.modal', function ($modal) {
                $modal->assertSee('新建系统功能');

            });
            $browser->with('.modal', function ($modal) {
                $modal->type('name','test1')
                    ->type( 'uri' ,'backend/test1/index')
                    ->select('pid' ,0)
                    ->type('sort' ,1 )
                    ->radio('status' ,1 )
                    ->radio('is_menu' ,1 )
                    ->press('保存');
            })->waitForText('创建成功')->assertSee('创建成功');

        });
    }

    public function testDelete(){
        $this->browse(function( Browser $browser){
            $browser->loginAs(\Smart\Models\SysUser::first())->visit('backend/sysfunc/index')->waitForText('test1')
                    ->click('.destroyBtn')->whenAvailable('.fit-confirm' , function ($modal){
                        $modal->assertSee('是否删除');
                });

            $browser->with('.fit-confirm' , function($modal){
                $modal->press('#fit-confirm-ok_btn');
            })->waitForText('成功删除')->assertSee('成功删除');

        });
    }

    public function testPermission(){
        $this->browse(function( Browser $browser){
            $browser->loginAs(\Smart\Models\SysUser::first())->visit('backend/sysfunc/index')->assertSee('系统功能')->pause(1000)
            ->click(".privilegeBtn")
            ->whenAvailable( '#privilegeModal' , function( $modal){
                $modal->assertSee('权限');
            })->with( '.modal' , function ($modal){
                $modal->check('name[]')->press('#submitPrivilegeFormBtn');
                })->waitForText('成功')->assertSee('成功');

        });
    }




    


}
