/* Glow Frame theme — small progressive-enhancement interactions */
document.addEventListener('DOMContentLoaded', function () {
  // Nothing required globally yet — page-specific inline scripts
  // (product gallery, checkout payment toggle, delivery charge)
  // are included directly in their templates for simplicity and
  // easy editing inside the WordPress template files.
});
jQuery(function($){

$("#gf-search").keyup(function(){

let keyword=$(this).val();

if(keyword.length<2){

$("#gf-search-result").hide();

return;

}

$.ajax({

url:gf_ajax.ajax_url,

type:"POST",

data:{

action:"gf_live_search",

keyword:keyword

},

success:function(data){

$("#gf-search-result")

.html(data)

.fadeIn();

}

});

});

});
