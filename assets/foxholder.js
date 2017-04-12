$.fn.foxholder = function(number) {
  this.addClass("form-container").attr("id", "example-"+number.demo);

  //adding labels with placeholders content. Removing placeholders
  this.find('form').find('input,textarea').each(function() {
    var placeholderText, formItemId, inputType; 

    //wrapping form elements in their oun <div> tags
    $(this).wrap('<div class="form-item-block"></div>');
    console.log(this);
    //creating labels
    inputType = $(this).attr('type');

    if (inputType == 'hidden') {

    } else {
      placeholderText = $(this).attr('placeholder');
      formItemId = $(this).attr('id')
      $(this).after('<label for="'+ formItemId +'"><span>'+ placeholderText +'</span></label>');
      $(this).removeAttr('placeholder');
    }
  });

  //adding class on blur
  $('.form-container12 form').find('input,textarea').blur(function(){
    if ($.trim($(this).val())!="") {
      $(this).addClass("active");
    } else {
      $(this).removeClass("active");
    }
  });

  //adding line-height for block with textarea 
  $('.form-item-block').each(function() {
    if ($(this).has('textarea').length > 0) {
      $(this).css({'line-height': '0px'});
    }
  });

  //examples scripts

  if (number.demo == 2) {

    //example-2 adding top property for label
    $('#example-2 input, #example-2 textarea').focus(function() {
      var labelTop;
      labelTop = parseInt($(this).css('padding-top'));
      $(this).next('label').css({'top': 0 - (labelTop + 6)});
    });

    $('#example-2 input, #example-2 textarea').blur(function() {
      if ($(this).hasClass('active')) {
      } else {
        $(this).next('label').css({'top': 0});
      }
    });
  }
}
