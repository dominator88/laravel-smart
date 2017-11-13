<?php
use Smart\Models\SysUser;
use Tests\TestCase;
use Laravel\Dusk\Browser;

class SimulatorTest extends TestCase
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
            $browser->loginAs(\Smart\Models\SysUser::first())->visit('backend/simulator/index')->assertSee('接口模拟器')
                ->press('#selectActionBtn')
                ->pause(1000)
                ->press('#submitBtn')
                ->pause(1000)
                ->assertSee('"msg":"查询成功"');

        });

    }




}
