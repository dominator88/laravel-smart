<?php
/**
 * 代码生成 Controller
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/25
 * Time: 14:45
 */

namespace Smart\Controllers\Backend;

use Smart\Service\GenerateService;
use Illuminate\Http\Request;

class Generate extends Backend {
    /**
     * 构造函数
     * Generate constructor.
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = GenerateService::instance();
    }

    public function index() {

        $this->_init( '代码生成' );

        $this->_addData( 'tables', $this->service->getTables() );

        //uri
        $this->_addParam( 'uri', [
            'getSystemInfo'     => full_uri( 'Backend/Generate/get_system_info' ),
            'createSystem'      => full_uri( 'Backend/Generate/create_system' ),
            'createApi'         => full_uri( 'Backend/Generate/create_api' ),
            'destroySystemFile' => full_uri( 'Backend/Generate/destroy_system_file' ),
        ] );

        $this->_addParam( [
            'type'                    => $this->service->type,
            'module'                  => $this->service->module,
            'viewType'                => $this->service->viewType,
            'tableType'               => $this->service->tableType,
            'apiVer'                  => $this->service->apiVer,
            'apiParams'               => $this->service->apiParams,
            'apiAuthUser'             => $this->service->apiAuthUser,
            'systemDefault'           => [
                'module'    => 'Backend',
                'tableName' => $this->data['tables'][0]->tableName,
            ],
            'systemComponentsDefault' => [
                'tableType' => 'grid',
                'viewType'  => 'portlet',
            ],
            'apiDefault'              => [
                'apiVersion' => 'v1',
                'authUser'   => '1',
            ],
        ] );

        //需要引入的 css 和 js
        $this->_addCssLib( 'node_modules/select2/dist/css/select2.min.css' );
        $this->_addJsLib( 'node_modules/select2/dist/js/select2.min.js' );
        $this->_addJsLib( 'node_modules/select2/dist/js/i18n/zh-CN.js' );


        return $this->_displayWithLayout('backend::generate.index');
    }

    function get_system_info(Request $request) {
        $type      = $request->input( 'type' );
        $tableName = $request->input( 'tableName' );
        $module    = $request->input( 'module' );

        $Generate = GenerateService::instance();

        $result = [];
        if ( $type == 'system' ) {
            $result = $Generate->getSystemInfo( $tableName, $module, TRUE );
        }

        return json( $result );
    }


    function create_system(Request $request) {
        $data = $request->except('components._token');

        $result = $this->service->createSystem( $data );

        return json( $result );
    }

    function create_api(Request $request) {
        $data = [
            'name'       => $request->input( 'name' ),
            'directory'  => $request->input( 'directory' ),
            'params'     => $request->input( 'params/a' ),
            'desc'       => $request->input( 'desc' ),
            'apiVersion' => $request->input( 'apiVersion' ),
            'authUser'   => $request->input( 'authUser' )
        ];

        $result = $this->service->createApi( $data );

        return json( $result );
    }

    function destroy_system_file(Request $request) {
        $temp      = $request->input( 'temp' );
        $tableName = $request->input( 'tableName' );
        $module    = $request->input( 'module' );

        $result = $this->service->deleteSystemFile( $module, $tableName, $temp );

        return json( $result );
    }

}
