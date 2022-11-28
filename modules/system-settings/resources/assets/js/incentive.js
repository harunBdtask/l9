// input challan closing
$(document).on('change', '.incentive-buyer', function (e) {
  e.preventDefault();
  $('.incentive-style').empty();
  $('.incentive-order').empty();
  $('.order-wise-operation-assigned').empty(); 
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
          $('.incentive-style').html(styleDropdown);
        }
      }
    });
  }
});
$(document).on('change', '.incentive-style', function (e) {
  e.preventDefault();
  $('.incentive-order').empty();
  $('.order-wise-operation-assigned').empty();
  var style_id = $(this).val();
  if(style_id){
    $.ajax({
      type: 'GET',
      url: '/get-orders/'+style_id,
      success: function (response) {
        var OrderDropdown = '<option value="">Select a Order</option>';
        if(Object.keys(response).length > 0){
          $.each(response, function (index, val) {
            OrderDropdown += '<option value="'+index+'">'+val+'</option>';
          });
          $('.incentive-order').html(OrderDropdown);                
        }
      }
    });
  }
});

$(document).on('change', '.incentive-operation', function (e) {
  e.preventDefault();  
  $('.order-wise-operation-assigned').empty();
  var buyer_id = $('.incentive-buyer').val();
  var style_id = $('.incentive-style').val();
  var order_id = $('.incentive-order').val();  
  var operation_id = $(this).val();

  if( buyer_id && style_id && order_id && operation_id )
  {
    if(order_id){
      $.ajax({
        type: 'GET',
        url: '/incentive/get-or-assigned-order-wise-operation/'+order_id+'/'+operation_id,
        success: function (response) {
          $('.order-wise-operation-assigned').html(response.view);
        }
      });
    }
  }   
});

$(document).on('click', '.employee-target-save-btn', function (e) {
  e.preventDefault();
  var current = $(this);
  var employee_id = current.val();
  var target = current.attr('data-employee-target');
  var operation_id = current.attr('data-operation-id');
  var token = $('meta[name="csrf-token"]').attr('content');

  if(confirm('Do you want to perform this action?') == true) {
    //return true;
  } else{
    return false;
  }

  if(employee_id && target && token){
    $.ajax({
      type: 'POST',
      url: '/incentive/date-wise-employee-production-target-post',
      data: { employee_id: employee_id, target: target, operation_id: operation_id, _token: token },
      success: function (response) {
        if(response == 200){
          current.removeClass('btn-info').addClass('btn-warning');
          $('.js-response-message').html(getMessage('Successfully added today target', 'success')).fadeIn().delay(2000).fadeOut(2000);               
        }else if(response == 600){
          current.removeClass('btn-warning').addClass('btn-info');
          $('.js-response-message').html(getMessage('Successfully remove today target', 'success')).fadeIn().delay(2000).fadeOut(2000);              
        }else{
          $('.js-response-message').html(getMessage(U_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
        }
      }
    });
  }
});

$(document).on('click', '.add-operation-wise-condition-value-btn', function (e) {
  var input = {};
  var operational_condition_id = $(this).val();
  input['order_id'] = $('.incentive-order').val();
  input['operational_condition_id'] = operational_condition_id;
  input['operation_id'] = $('.incentive-operation').val();
  input['smv'] = $('.smv-'+operational_condition_id).val();
  input['_token'] = $('meta[name="csrf-token"]').attr('content');  
  
  if( $('input:checkbox:checked').length > 1){
    alert('Sorry!! Maximum one operational condition allowed');
    $('.operational-condition-id-').prop( "checked", false );
    $(this).parent('td').prev('td').find('#smvv').val('');  
  } else {

    $.ajax({
      type: "POST",
      url: "/incentive/order-wise-operations-store",
      data: input,
      success: function (response) {
        if (response == 403) {
          $('.js-response-message').html(getMessage('Please form fill-up correctly', 'danger')).fadeIn().delay(2000).fadeOut(2000);
        } else if(response == 200){      
          $('.js-response-message').html(getMessage(U_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000); 
        }else if(response == 500){
          $('.js-response-message').html(getMessage(U_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
        }
      }
    });
  }
});

$(document).on('click', '.delete-operation-wise-condition-value-btn', function (e) {
  e.preventDefault();  
  var current = $(this);
  var id = current.val();
  if(id){
    if (confirm('Are you sure to delete this') == true) {
      $.ajax({
        type: 'GET',
        url: '/incentive/delete-order-wise-operations/'+id,      
        success: function (response) {
          if(response == 200){
            $('.operational-condition-id-'+id).prop( "checked", false );
            current.parent('td').prev('td').find('#smvv').val('');
            $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000); 
          }else{
            $('.js-response-message').html(getMessage(D_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
          }
        }
      });
    }  
  } else {
   alert('Please select first')
 } 
});

$(document).on('change', '.incentive-styl', function (e) {
  e.preventDefault();
  $('.inc-ordr-dropdown').empty();
  var style_id = $(this).val();
  if(style_id){
    $.ajax({
      type: 'GET',
      url: '/get-orders/'+style_id,
      success: function (response) {
        var OrderDropdown = '<option value="">Select a Order</option>';
        if(Object.keys(response).length > 0){
          $.each(response, function (index, val) {
            OrderDropdown += '<option value="'+index+'">'+val+'</option>';
          });
          $('.inc-ordr-dropdown').html(OrderDropdown);                
        }
      }
    });
  }  
});

$(document).on('change', '.inc-ordr-dropdown', function (e) {
  e.preventDefault();
  var order_id = $(this).val();
  $('.employee-wise-target').empty();
  if(order_id){    
    $.ajax({
      type: 'GET',
      url: '/incentive/employee-list/'+order_id,      
      success: function (response) {               
        if(response.status == 200){                 
          $('.employee-wise-target').html(response.view);

        }else{

        }
      }
    });     
  }
});

/*$(function() {
    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');
        console.log(url);
        getArticles(url);
        window.history.pushState("", "", url);
    });

    function getArticles(url) {
        $.ajax({
            url : url          
        }).done(function (data) {
            $('.articles').html(data);  
        }).fail(function () {
            alert('Articles could not be loaded.');
        });
    }
});
*/

