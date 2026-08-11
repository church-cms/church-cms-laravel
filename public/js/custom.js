

$(document).ready(function(){
  $(".profile-click").click(function(){
    $(".user-dtl").toggle();
  });
});


  function show() {
    if($('.create_event').hasclass('hidden'))
    {
      $('.create_event').removeclass('hidden').addclass('block');
    }
    else 
    {
      $('.create_event').removeclass('block').addclass('hidden');
    }
  }


   function showsidebar(id){
    if($('#'+id).hasClass('hidden')){
      $('#'+id).removeClass('hidden').addClass('block');
    }
      else
      {
      $('#'+id).removeClass('block').addClass('hidden');
    }
  }
