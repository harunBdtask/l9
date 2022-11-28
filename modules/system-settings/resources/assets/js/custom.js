//$(document).ready(function(){

    var D_SUCCESS = "Successfully deleted";
    var D_FAIL = "Not successfully deleted";
    var U_SUCCESS = "Successfully updated";
    var U_FAIL = "Not successfully updated";
   
   	
    // common loader that use all loading functionalities
    var loader = '<div class="text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>';
    
    // for page load message hide after transition period like create, update 
   	$('.response-message').fadeIn().delay(2000).fadeOut(2000);


    // common message area for ajax
    function getMessage(message, type) {
       return '<div class="alert alert-'+type+'">'+ message+'</div>';
    }

    // common confirmation message for delete
    $('.del-confirm').on('click', function(){
        if(confirm('are you sure to delete this?') == true) {
           return true;
        }else{
          return false;
        }
    }); 

  // size wise report
  $(document).on('change', '.administration-module', function () {
    $('.administration-submodule').empty();   
    var module_id = $(this).val();      
    if(module_id){
      $.ajax({
          type: 'GET',
          url: '/get-submodules/'+module_id,       
          success: function (response) {
            var OrderDropdown = '<option value="">Select a Order</option>';
            if(Object.keys(response).length > 0){
              $.each(response, function (index, val) {
                  OrderDropdown += '<option value="'+index+'">'+val+'</option>';
              });
              $('.administration-submodule').html(OrderDropdown);                
            }   
          }
      });
    }  
  });

/*  $(document).on('change', '.smv-buyer-select', function () {
    $('.smv-update-order-list').empty();   
    var buyer_id = $(this).val();      
    if(buyer_id){
      $.ajax({
          type: 'GET',
          url: '/get-smv-orders/'+buyer_id,       
          success: function (response) {            
              if(response){
                  $('.smv-update-order-list').html(response.view);
              }
          }
      });
    }  
  });*/

  $(document).on('change', '.smv-buyer-select', function () {
    $('.smv-style-select').empty();
    $('.smv-update-order-list').empty();
    var buyer_id = $(this).val();      
    if(buyer_id){
      $.ajax({
          type: 'GET',
          url: '/get-styles/'+buyer_id,       
          success: function (response) {            
            var styleDropdown = '<option value="">Select a Style</option>';
            if(Object.keys(response).length > 0){
              $.each(response, function (index, val) {
                  styleDropdown += '<option value="'+index+'">'+val+'</option>';
              });
              $('.smv-style-select').html(styleDropdown);                
            }
          }
      });
    }
  });

  $(document).on('change', '.smv-style-select', function () {
    $('.smv-update-order-list').empty();   
    var style_id = $(this).val();      
    if(style_id){
      $.ajax({
          type: 'GET',
          url: '/get-smv-orders/'+style_id,       
          success: function (response) {         
            if(response){
              var tr;
              $.each(response, function(index, data){
                tr += '<tr class="odd gradeX">'+
                  '<td>'+data.po_no+'</td>'+
                  '<td>'+data.po_quantity+'</td>'+
                  '<td><input type="number" name="smv" class="order-smv" value="'+data.smv+'"></td>'+
                  '<td><button type="button" value="'+data.id+'" class="btn white smv-update-btn">'+
                  'Update</button></td></tr>';
              });
              $('.smv-update-order-list').html(tr);
            }
          }
      });
    }  
  });

  $(document).on('click', '.smv-update-btn', function () {   
    var order_id = $(this).val();
    var smv = $(this).parent('td').prev('td').find('.order-smv').val();
    var token = $('meta[name="csrf-token"]').attr('content');   
    if(order_id){
      $.ajax({
          type: 'post',
          url: '/update-order-smv',
          data: {order_id: order_id, smv:smv, _token: token},    
          success: function (response) {            
            if(response == 200){            
              $('.js-response-message').html(getMessage(U_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);              
            }else{
              $('.js-response-message').html(getMessage(U_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
            }
          }  
      });
    }  
  });
  
  // line wise npt
  $(document).on('change', '.npt-floor-select', function () {
    $('.npt-floor-select-update-row').empty();
    $('.sewing-target-btn').hide();   
    var floor_id = $(this).val();      
    if(floor_id){
      $.ajax({
          type: 'GET',
          url: '/get-line-wise-npt-update-form/'+floor_id,      
          success: function (response) {            
              if(response){
                  $('.npt-floor-select-update-row').html(response.view);
                 // $('.npt-floor-select-update-row').append(response.view);
                  $('.sewing-target-btn').show();
              }
          }
      });
    }  
  });

  // getup list order
  $(document).on('change', '.getup-buyer-select', function () {     
    $('.getup-style-select').empty();
    $('.getup-order-select').empty();
    $('.getup-color-select').empty();
    $('.getup-update-generate-form').empty();
    $('.update-getup-production-btn').hide();
    var buyer_id = $(this).val();
    if(buyer_id){
        $.ajax({
            type: 'GET',
            url: '/get-styles/'+buyer_id,       
            success: function (response) {
              var styleDropdown = '<option value="">Select a Style</option>';
              if(Object.keys(response).length > 0){
                $.each(response, function (index, val) {
                    styleDropdown += '<option value="'+index+'">'+val+'</option>';
                });
                $('.getup-style-select').html(styleDropdown);                
              }    
            }
        });
      }  
  });
   
  // print gate challan bundle delete
  $(document).on('click', '.getpass-bundle-dtn', function () {
    if (confirm('Are you sure to delete this') == true) {
      var id = $(this).val();
      var current = $(this);
      if(id){
        $.ajax({
            type: 'get',
            url: '/delete-print-invntory-bundle/'+id,        
            success: function (response) {            
              if(response == 200){
                $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);              
                current.parent('td').parent('tr').remove();
              }else{
                $('.js-response-message').html(getMessage(D_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            }  
        });
      }
    }  
  });

  // print gate challan bundle delete
  $(document).on('click', '.input-bundle-btn', function () {
    if (confirm('Are you sure to delete this') == true) {
      var id = $(this).val();     
      var current = $(this);
      if(id){
        $.ajax({
            type: 'get',
            url: '/delete-input-bundle/'+id,        
            success: function (response) {            
              if(response == 200){
                $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);              
                current.parent('td').parent('tr').remove();
              }else{
                $('.js-response-message').html(getMessage(D_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            }  
        });
      }
    }  
  });

  $(document).on('click', '.user-assigned-menu-del-btn', function () {
    if (confirm('Are you sure to delete this') == true) {
      var id = $(this).val();     
      var current = $(this);
      if(id){
        $.ajax({
            type: 'get',
            url: '/user-assigned-menu-delete/'+id,        
            success: function (response) {            
              if(response == 200){
                $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);              
                current.parent('td').parent('tr').remove();
              }else{
                $('.js-response-message').html(getMessage(D_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            }  
        });
      }
    }  
  });

// sewing input get line after floor select
$(document).on('change', '.input-floor-select', function (e) {  
  e.preventDefault();      
  var floor_id = $(this).val(); 
  $('.lines-dropdown').empty();     
  if(floor_id){
    $.ajax({
        type: 'GET',
        url: '/get-lines/'+floor_id,       
        success: function (response) {
          var linesDropdown = '<option value="">Select a Lines</option>';
          if(Object.keys(response).length > 0){
            $.each(response, function (index, val) {
                linesDropdown += '<option value="'+index+'">'+val+'</option>';
            });
            $('.lines-dropdown').html(linesDropdown);                
          }
        }
    });
  }  
});

// get cutting tables
$(document).on('change', '.bundlecard-floor-select', function () {
    $('.bundlecard-table-select').empty(); 
    var cutting_floor_id = $(this).val();     
    if(cutting_floor_id){
      $.ajax({
          type: 'GET',
          url: '/get-cutting-tables/'+cutting_floor_id,       
          success: function (response) {            
            var tableDropdown = '<option value="">Select a Tables</option>';
            if(Object.keys(response).length > 0){
              $.each(response, function (index, val) {
                  tableDropdown += '<option value="'+index+'">'+val+'</option>';
              });
              $('.bundlecard-table-select').html(tableDropdown);                
            }
          }        
      });
    }  
  });

// get style dropdown after selecting buyer dropdewn
$(document).on('change', '.buyer-dropdown', function (e) {  
  e.preventDefault();      
  var buyer_id = $(this).val(); 
  $('.style-dropdown').empty();     
  if(buyer_id){
    $.ajax({
        type: 'GET',
        url: '/get-styles/'+buyer_id,       
        success: function (response) {
          var stylesDropdown = '<option value="">Select a Style</option>';
          if(Object.keys(response).length > 0){
            $.each(response, function (index, val) {
                stylesDropdown += '<option value="'+index+'">'+val+'</option>';
            });
            $('.style-dropdown').html(stylesDropdown);
          }
        }
    });
  }  
});
