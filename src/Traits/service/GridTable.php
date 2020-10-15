<?php
namespace Smart\Traits\Service;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait GridTable {
  /**
   * 根据id 查询
   *
   * @param $id
   *
   * @return mixed
   */
  public function getById( $id,$with = [] ) {

    if(empty($with)){
      return $this->getModel()->find( $id );
    }else{
      return $this->getModel()->with($with)->find($id);
    }
    

  }
  
  /**
   * 添加数据
   *
   * @param $data
   *
   * @return array
   */
  public function insert( $data ) {
    try {
      if ( empty( $data ) ) {
        throw new \Exception( '数据不能为空' );
      }
      
      $id = $this->getModel()->insertGetId( $data );

      return ajax_arr( '创建成功' , 0 , [ 'id' => $id ] );
    } catch ( \Exception $e ) {
      return ajax_arr( $e->getMessage() , 500 );
    }
  }
  
  /**
   * 根据ID 更新数据
   *
   * @param $id
   * @param $data
   *
   * @return array
   */
  public function update( $id , $data ) {
    try {
      $rows = $this->getModel()->where( 'id' , $id )->update( $data );
      if ( $rows == 0 ) {
        return ajax_arr( "未更新任何数据" , 0 );
      }
      
      return ajax_arr( "更新成功" , 0 );
    } catch ( \Exception $e ) {
      return ajax_arr( $e->getMessage() , 500 );
    }
  }
  
  /**
   * 根据ID 删除数据
   *
   * @param $ids //string | array
   *
   * @return array
   */
  public function destroy( $ids ) {
    try {
      $rows = $this->getModel()->destroy( $ids );
      if ( $rows == 0 ) {
        return ajax_arr( "未删除任何数据" , 0 );
      }
      
      return ajax_arr( "成功删除{$rows}行数据" , 0 );
    } catch ( \Exception $e ) {
      return ajax_arr( $e->getMessage() , 500 );
    }
  }

  public function save($param){
    try{
      $this->validator($param);
      if(isset($param['id']) && $param['id']){
        $model = $this->getModel()->findOrFail($param['id']);
        $model->update($param);
        return $model;

      }else{
        $result = $this->getModel()->create($param);
      }
      return $result;
    }catch(\Exception $e){
      if($e instanceof ModelNotFoundException ){
        throw new \Exception('当前id对应的模型不存在');
      }else{
        throw $e;
      }
      
    }
  
  }

  protected function validator($param){

  }
  
}