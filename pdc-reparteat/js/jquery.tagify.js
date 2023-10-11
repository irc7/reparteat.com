var $tag = jQuery.noConflict();
(function($tag) {
 $tag.fn.tagify = function() {
   $tag('body').on('click', '.ui-tagify-remove', function() {
	   event.preventDefault();
     $tag(this).parent().remove();
   });
   
   var wrap = document.createElement('div'),
     delimeters = [44], // comma
     length = delimeters.length,
     i = 0;
   $tag(wrap).addClass('ui-tagify-wrap').click(function() {
     this.focus();
   });
   this.css('display', 'inline-block')
     .css('width', '130px')
     .wrap(wrap)
     .addClass('ui-tagify-input')
     .bind('keypress', function(event) {
       var charCode = event.which || event.keyCode,
         charStr, tagContent;
       for (i = 0; i < length; i++) {
         if (delimeters[i] === charCode) {
           charStr = String.fromCharCode(charCode);
           tagContent = $tag(this).val().split(charStr)[0].trim();
           if (0 < tagContent.length) {
             $tag(this).before('<div class="ui-tagify-tag">' + tagContent + '<a href="#" class="ui-tagify-remove">⨉</a></div>').val('');
           }
           event.preventDefault();
           break;
         }
       }
     })
     .bind('keydown', function(event) {
       var charCode = event.which || event.keyCode;
       if(charCode === 8 && 0 === $tag(this).val().length) {
         if ($tag('.ui-tagify-selected').length) {
           $tag('.ui-tagify-selected').remove();
         }
         else if ($tag(this).prev().length) {
           $tag(this).prev().addClass('ui-tagify-selected');
         }
       }
     });
   // returns the div wrapper, does this make the most sense?
  return this.parent();
 };
})(jQuery);

$tag('.tagify').tagify();