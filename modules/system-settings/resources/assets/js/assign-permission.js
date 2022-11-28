$(document).on('change', '.factory-dropdown', function () {     
  var factory_id = $(this).val();      
  if(factory_id){
    $.ajax({
        type: 'GET',
        url: '/get-users/'+factory_id,       
        success: function (response) {              
          
          if(Object.keys(response).length > 0){
            var userDropdown = '<option value="">Select a User</option>';
            $.each(response, function (index, val) {
                userDropdown += '<option value="'+val.id+'">'+val.email+'</option>';
            });
          }else{
            var userDropdown = '<option value="">User not found</option>';
          }
          $('.user-dropdown').html(userDropdown);
        }
    });
  }  
});

$(document).on('change', '.module-dropdown', function () {      
  var module_id = $(this).val();      
  if(module_id){
    $.ajax({
        type: 'GET',
        url: '/get-menus/'+module_id,
        success: function (response) {              
          if(Object.keys(response).length > 0){
            var menuDropdown = '<option value="">Select a menu</option>';
            $.each(response, function (index, val) {
                menuDropdown += '<option value="'+index+'">'+val+'</option>';
            });                                
          }else{
            var menuDropdown = '<option value="">Menu not found</option>';
          }
          $('.menu-dropdown').html(menuDropdown);
        }
    });
  }  
});

$(document).on('change', '.menu-dropdown', function () {
   var menu_id = $(this).val();
   var module_id = $('.module-dropdown').val();
   var user_id = $('.user-dropdown').val();      
   if (menu_id && module_id && user_id) {
       $.ajax({
           type: "GET",
           url: '/get-all-permission?user_id=' + user_id + '&module_id=' + module_id+ '&menu_id=' + menu_id,
           success: function (response) {
               $('.permission-div').html(response);
           }
       });
   }
});
  