
// function tesk_list_rollUp(){
//   $('section.bg_content').css('display','none')
//   $('.section_list').css('top','189px')
// }

// tesk_list_rollUp()

// $('input.input_add_task').on('click',function(){
//   $('section.bg_content').css('display','block')
//   $('.section_list').css('top','732px')
// })

$('.nav_btn').on('click',function(){
    $(this).addClass('nav_btn_click')
    $(this).siblings().removeClass('nav_btn_click')
    $(this).children('.nav_btn_line').css('display','block')
    $(this).siblings().children('.nav_btn_line').css('display','none')
  })
  
  $('.nav_btn').first().trigger('click')
  
  
  // 選單收起來（且編輯筆變成黑色）
  function type_list_rollUp(){
    $('.bg_title').siblings().toggleClass('bg_edit')
    $('.bg_content').toggleClass('bg_content_edit')
    $('.section_list').toggleClass('list_rollUp')
    $('.pencil_edit').toggleClass('pen_click')
    
  }
  
  // 將星星標記移除
  function star_unclick(){
    $('.bg_title > .fa-star').removeClass('fas')
    $('.bg_title > .fa-star').addClass('far')
  }
  
  // 點擊選單上方 Type title 處，筆變藍色，且展開選單
  //（pen_click 是黑色筆的狀態）
  $('input.input_typing').on('click',function(){
    if($('.pencil_edit').hasClass('pen_click')){
      type_list_rollUp()
    }
  })
  
  // 點選編輯筆處，可以將選單收起
  // 當收起選單時，選單上的星星和 Tesk title會恢復原始狀態
  $('.pencil_edit').on('click',function(){
    type_list_rollUp()
    if($('.pencil_edit').hasClass('pen_click')){
      star_unclick()
      $('input.input_typing').val('')
    }
  })
  
  
  $('.js_list_icon').on('click',function(){
    $(this).toggleClass('pen_click')
  })
  // add 和 cancel 按鈕按了之後，選單收起
  $('.bg_btn_a,.bg_btn_c').on('click',function(){
    type_list_rollUp()
  
  })
  
  // 按下 cancel 按鈕後， Type title 恢復 0 文字、星星標記取消
  $('.bg_btn_c').on('click',function(){
    $('input.input_typing').val('')
    star_unclick()
  })
  
  // 星星標記的選取/取消
  $('section').on('click','.fa-star',function(){
    $(this).toggleClass('far')
    $(this).toggleClass('fas')
    if($(this).hasClass('far')){
      $(this).parents('.list_rec').css('background-color','#F2F2F2')
    }
    else{
     $(this).parents('.list_rec').css('background-color','#FFF2DC')
    }
  })
  
  // 按了 add 按鈕後，新增下方 list 
  $('.bg_btn_a').on('click',function(){
    
    var Type_title = $('input.input_typing').val()
    var date = $('input.date').val()
    var time = $('input.time').val()
    var comment = $('input.comment').val()
    var star_click
    var bgGrayYellow
    
    // 若有選取星星標記，則 list 也會有星星標記
    if($('.bg_title > .fa-star').hasClass('fas')){
      star_click = '<i class="fas fa-star js_list_icon"></i>'
      // 若有或沒有星星標記的話，背景顏色轉換
      bgGrayYellow = '<div style = "background-color: #FFF2DC" class="list_rec">'
      // $('.list_rec').css('background-color','#FFF2DC')
    }
    else{
      star_click = '<i class="far fa-star js_list_icon"></i>'
      
      bgGrayYellow = '<div style = "background-color: #F2F2F2" class="list_rec">'
    }
    
    // Tesk title 若有文字則顯示文字，若空白，則使用 placeholder 的預設文字
    if(Boolean(Type_title) === true){
      // alert(star_click)
      $('.section_list').first().prepend(bgGrayYellow + '<input class="btn_checkbox" type="checkbox"/><div class="card_title">' + Type_title +'<div class="list_details"><i class="far fa-calendar-alt list_details_icon"><div class="icon_date">5/14</div></i><i class="far fa-file list_details_icon"></i><i class="far fa-comment-dots list_details_icon"></i></div>'+ '</div>' + star_click + '<i class="fas fa-pencil-alt js_list_icon"></i></div>')
    }
    else{
      $('.section_list').first().prepend(bgGrayYellow + '<input class="btn_checkbox" type="checkbox" /><div class="card_title">Type\u00a0Something\u00a0Here…'+'<div class="list_details"><i class="far fa-calendar-alt list_details_icon"><div class="icon_date">5/14</div></i><i class="far fa-file list_details_icon"></i><i class="far fa-comment-dots list_details_icon"></i></div>'+'</div>' + star_click + '<i class="fas fa-pencil-alt js_list_icon"></i></div>')
    }
    star_unclick()
  })
  
  
  // 如果 checkbox 勾選，則變成灰階(完成狀態)
  $('.section_list').on('click','.btn_checkbox',function(){
    $(this).parent().toggleClass('finished_card')
    $(this).siblings('.card_title').toggleClass('finished_card_title')
    $(this).parents('.list_rec').css('background-color','#F2F2F2')
    if($(this).prop('checked')){
      $(this).siblings('.card_title').find('.list_details_icon').css('display','none')
      $(this).siblings('.fa-star').removeClass('fas')
      $(this).siblings('.fa-star').addClass('far')
      $(this).css({'top': '50%','transform': 'translateY(-50%) scale(1)'})
      $(this).parents('.list_rec.sample').css('background-color','#F2F2F2')
    }
    else{
      $(this).siblings('.card_title').find('.list_details_icon').css('display','inline-block')
      $(this).css({'top': '22px','transform': 'translateY(0%) scale(1)'})
      if($(this).siblings('.fa-star').hasClass('fas')){
        $(this).parents('.list_rec.sample').css('background-color','#FFF2DC')
      }
      
      
  
    }
  })
  
  if($('.list_rec.sample > i.fa-star').hasClass('far')){
    $('.list_rec.sample').css('background-color','#F2F2F2')
  }
  else{
    $('.list_rec.sample').css('background-color','#FFF2DC')
  }
  
  $('.list_rec.sample:first > i.fa-star').trigger('click')
  $('.list_rec.sample:eq(1) > i.fa-star').trigger('click')
  $('.list_rec.sample:last > input').trigger('click')
  
  
  // Tasks left 還沒完成
  var listNum = $('.list_rec.sample').length
  var listNum2 = $('.section_list').length
  $('.massage').append(listNum + '\u00a0tasks left')
  
  $('.bg_btn_a').on('click',function(){
    $('.massage').text(listNum + '\u00a0tasks left')
  })
  
  
  
  console.log(isNaN(''))
  console.log( '' == null)
  // 空字串就是空字串，不屬於 NaN ，也不是 null