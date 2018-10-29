//******************************
// data grid format function
//******************************

//edit button
var optEdit = function ( value , row ) {
  return '<a class="btn btn-sm grey-cascade editBtn" data-id="' + row.id + '" href="javascript:;">' +
         '<i class="fa fa-edit"></i> 编辑</a>';
};

//delete button
var optDelete = function ( value , row ) {
  return '<a class="btn btn-sm red destroyBtn" data-id="' + row.id + '" href="javascript:;">' +
         '<i class="fa fa-trash"></i> 删除</a>';
};

var optIncome = function ( value , row ) {
  return '<a class="btn btn-sm red destroyBtn" data-id="' + row.id + '" href="javascript:;">' +
         '<i class="fa fa-trash"></i> 进入</a>';
};

//status
var statusColor = [ 'default' , 'primary' , 'success' , 'info' ];
var formatStatus = function ( value ) {
  return '<span class="label label-sm label-' + statusColor[ value ] + '">' + Param.status[ value ] + '</span>';
};

//type
var typeColor = [ 'default' , 'primary' , 'success' , 'info' ];
var formatType = function ( value ) {
  return '<span class="label label-sm label-' + typeColor[ value ] + '">' + Param.type[ value ] + '</span>';
};

//catalog
var catalogColor = [ 'default' , 'primary' , 'success' , 'info' ];
var formatCatalog = function ( value ) {
  return '<span class="label label-sm label-' + catalogColor[ value ] + '">' + Param.catalog[ value ] + '</span>';
};

//icon or img
var formatIcon = function ( value ) {
  var html = '';
  //console.log( 'value is ',value );
  if ( value && ! empty( value ) ) {
    var src = Param.uri.img + value;
    html = '<a href="${src}" target="_blank"><img src="${src}" style="max-width: 60px"></a>'.replace( /\${src}/g , src );
  }
  return html;
};


var formatDatetime = function ( value ) {
  var html = '';
  if ( ! empty( value ) ) {
    html = value.substr( 0 , 16 );
  }
  return html
};

var formatDate = function ( value ) {
  var html = '';
  if ( ! empty( value ) ) {
    html = value.substr( 0 , 10 );
  }
  return html
};

var yesColor = [ 'default' , 'primary' ];
var formatYes = function ( value ) {
  var data = [ '否' , '是' ];
  return '<span class="label label-sm label-' + yesColor[ value ] + '">' + data[ value ] + '</span>';
};