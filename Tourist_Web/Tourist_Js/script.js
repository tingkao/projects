$(document).ready(function(){
$('.page_1').trigger('click')
})
$('.page_border').on('click',function(){
$(this).css({'background-color':'#9013FE','color':'#fff'})
$(this).siblings().css({'background-color':'#fff','color':'#000'})
})
var num = Number($('.page_1').text())
var clicktimes = 0
$('.page_00').on('click',function(){
// alert('initialnum:' + num)
for(var i=num; i<(num + 5); i++){
$('.page_'+ (Number(i) - Number(clicktimes*5)) ).text((Number(i) + 5))
}
num = num + 5
clicktimes = clicktimes + 1
// alert('num:'+num)
// alert('clicktimes:'+clicktimes)
})

var clicktimesMinus = 0
$('.page_0').on('click',function(){
// alert('initialnum:' + num)
if(Number($('.page_1').text())>1){
for(var i=num ; i < (num + 5); i++){
$('.page_'+ (Number(i) - Number(clicktimes*5)) ).text((Number(i) - 5))
}
num = num - 5
clicktimes = clicktimes - 1
// alert('num:'+num)
// alert('clicktimes:'+clicktimes) 
}
})


$('.part > input').on('click',function(){
var CategoriesType = $(this).siblings().text()
if($(this).prop('checked')){
$('.keyWord').append('<div class="key_btn keyWord_'+CategoriesType+'">'+ CategoriesType + '<i class="far fa-times-circle"></i></div>')
}
else {
$('.keyWord_'+CategoriesType).attr('style','display: none;')

}
})

$('#electriccars').data('columns')

$('.keyWord').on('click','.key_btn',function(){
var CategoriesType = $(this).text()
$(this).attr('style','display: none;')
$('p.p_'+CategoriesType).siblings().trigger('click')
})

var explore_html = '<section class="body_explore"><div class="explore_info"><h3 class="explore_info_title">Explore</h3><h3 class="explore_info_text1">/</h3><h3 class="explore_info_text2">{{explore_title}}</h3></div><article><div class="explore_imgborder"><img class="explore_img" src="{{bg_img}}" alt=""/></div><div class="explore_title">{{explore_title}}</div><div class="article_place"><div class="article_place_text">{{article_place_text}}</div><div class="article_place_tag">{{article_place_tag}}</div></div><div class="article_info"><i class="fas fa-map-marker"></i><p>{{article_info_place}}</p><i class="far fa-calendar-alt"></i><p>{{article_info_date}}</p></div><div class="explore_text">{{article_p}}</div></article></section></section></section>'



$('section.body article').on('click',function(){

var article_title = $(this).find('.article_title').text()
var article_text = $(this).find('.article_p').text()
var article_place_text = $(this).find('.article_place_text').text()
var article_place_tag = $(this).find('.article_place_tag').text()
var article_info_place = $(this).find('p.place').text()
var article_info_date = $(this).find('p.date').text()
var article_p = $(this).find('.article_p').html()

var img
for(var i = 1 ; i<= $('.img_border').length ; i++){
if($(this).find('.img_border').hasClass('img_border'+ i)){
img = '.img_border' + i 
}
}
var bg_img = $(this).find(img).css('background-image')
var off_url_bg_img = 
bg_img.replace('url("','')
      .replace('")','')
// $('.explore_text').empty()
$('section.body_explore').remove()

var current_explore_html = 
explore_html.replace(/{{explore_title}}/ig,article_title)
            .replace('{{article_place_text}}',article_place_text)
            .replace('{{article_place_tag}}',article_place_tag)
            .replace('{{article_info_place}}',article_info_place)
            .replace('{{article_info_date}}',article_info_date)
            .replace('{{article_p}}',article_p)
            .replace('{{bg_img}}',off_url_bg_img)
            // .replace('{{article_p}}',article_p)


$('.screan_desktop').append(current_explore_html)
$('section.search').children('div.topic_search').siblings().addClass('hidden')
$('section.screan_desktop').css('width','1024px')
$('nav').css('margin-left','0px')
$('section.body_explore').css('display','block')
$('section.body_explore').replace('{{Explore}}', 'article_place_text' )
})

$('header > h1').on('click',function(){
$('section.body_explore').css('display','none')
$('section.search').children('div.topic_search').siblings().removeClass('hidden')
$('section.screan_desktop').css('width','1200px')
$('nav').css('margin-left','38px')
})