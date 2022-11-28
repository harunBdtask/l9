(function ($, window, document) {
   $(function () {

      $('#buyer_id').select2();
      /*---Events Handler---*/
      const addTRHandler = function () {
         const clonedRow = $(this).parents('tr').clone();
         parentEl(this, 'tr').after(clonedRow);
         toastr.success('A new row is added!')
      };

      const removeTRHandler = function () {
         const rowCounts = $('.table-row').length;

         if (rowCounts <= 1) {
            toastr.info('You cannot remove the last row!', 'Sorry!');
            return false;
         }

         withConfirm(() => {
            parentEl(this, '.table-row').remove();
            toastr.warning('The row is removed!');
         }, 'Are you sure you want to remove this row?');
      };

      const calculateTotalHandler = function () {
         const $parentRow = parentEl(this, 'tr');
         const $totalInput = $parentRow.find('.total');
         const qty = $parentRow.find('.qty').val();
         const unitPrice = $parentRow.find('.unit_price').val();
         $totalInput.val(qty * unitPrice, 2);
      };

      const handleSubmit = function (e) {
         e.preventDefault();
         const $loader = $('#loader');
         $('.text-danger').html('');
         $loader.show();

         const $form = $('#pre-budget-form');

         const formData = new FormData(document.querySelector('#pre-budget-form'));

         axios({
            method: 'POST',
            headers: {requestFor: 'pre-budget', 'Content-Type': 'multipart/form-data'},
            data: formData,
            url: $form.attr('action')
         }).then(res => {

            const {success, update} = res.data;

            if (success && update) {
               toastr.success('Successfully Update', 'Pre-Budget')
            } else if (success) {
               toastr.success('Successfully Created', 'Pre-Budget')
            }

            if (success) {
               setTimeout(() => {
                  window.location.href = '/pre-budgets';
               }, 2000)
            }

         }).catch(e => {
            if (e.response.status === UNPROCESSABLE_ENTITY) {
               const errors = e.response.data.errors;
               $.each(errors, validationErrorsHandler);
            }
         }).finally(() => {
            $loader.hide();
         })
      };

      /*---Events---*/
      $(document).on('click', '.add-table-row', addTRHandler);
      $(document).on('click', '.remove-table-row', removeTRHandler);
      $(document).on('change, keyup', '.qty, .unit_price', calculateTotalHandler);
      $(document).on('submit', '#pre-budget-form', handleSubmit);
   })

})(window.jQuery, window, document);
