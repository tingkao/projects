// Task base class
class Task {

    // Variables
    #title
    #comment
    #date
    #time
    #hasFile
    #hasStar
    #isDone

    // Constructor
    constructor(title, comment, date, time, hasFile, hasStar, isDone) {
        if(title && title.trim() != ''){
            this.#title = title;
        }else {
            this.#title = comment;
        }
        this.#date = date;
        this.#time = time;
        this.#hasFile = hasFile;
        this.#hasStar = hasStar;
        this.#isDone = isDone;
    }

    // Getters
    get title() {
        return this.#title;
    }
    
    get comment() {
        return this.#comment;
    }

    get date() {
        return this.#date;
    }

    get time() {
        return this.#time;
    }

    get hasFile() {
        return this.#hasFile;
    }
    get hasStar() {
        return this.#hasStar;
    }
    get isDone() {
        return this.#isDone;
    }

    // Setters

    set title(new_title) {
        this.#title = new_title;
    }

    set comment(new_comment) {
        this.#comment = new_comment;
    }

    set date(new_date) {
        this.#date = new_date;
    }
    set time(new_time) {
        this.#time = new_time;
    }

    set hasFile(new_hasFile) {
        this.#hasFile = new_hasFile;
    }

    set hasStar(new_hasStar) {
        this.#hasStar = new_hasStar;
    }

    set isDone(new_isDone) {
        this.#isDone = new_isDone;
    }


    // Methods

}









var taskArray = [];

//add todoList
  document.querySelector('.bg_btn_a').addEventListener('click', function(e){
    var title = document.querySelector('#title').value
    var date = document.querySelector('#date').value
    var time = document.querySelector('#time').value
    var comment = document.querySelector('#comment').value
    var star_tag;
    var bgColor;

    var hasFile = false;
    var hasStar = false;
    var isDone = false;

    if(!title && title.trim() == ''){
        if(!comment && comment.trim() == ''){
            alert("your task title is empty")
            return;
        }else {
            title = comment;
        }
    }

    // handle isDone---------------------------------------------------------------------------------
    // handle hasFile

    // handle hasStar
    if(document.querySelector('.bg_title > .fa-star').classList.contains('fas')){
        hasStar = true;
        star_tag = '<i class="fas fa-star js_list_icon"></i>'
        bgColor = '#FFF2DC';
    }
    else{
        star_tag = '<i class="far fa-star js_list_icon"></i>'
        bgColor = '#F2F2F2';
    }

    //create data in server----------------------------------------------------------------------------
    taskArray.push(new Task(title, comment, date, time, hasFile, hasStar, isDone));

    //create data in client side
    document.querySelector('.section_list').insertAdjacentHTML("afterbegin", 
    createTask (title, date, time, bgColor, star_tag));

    //reset
    resetPanel();
    //remove sameple
    document.querySelector('.sample').style.display = "none";

    console.log(taskArray)

  })

  document.querySelector('#star').addEventListener('click', function(e){
    document.querySelector('#star').classList.toggle('far');
    document.querySelector('#star').classList.toggle('fas');
  })

  document.querySelector('#pen').addEventListener('click', function(e){
    list_rollUp();
  })

  document.querySelector('#title').addEventListener('click', function(e){
    list_rollUp();
  })

  //task stars
  document.querySelector('.section_list').addEventListener('click', function(e){
    if(e.target.classList.contains('fa-star')){
        if(e.target.classList.contains('far')){
            toggleStar(e.target);
            e.target.parentNode.style.backgroundColor = "#FFF2DC";
        }else if(e.target.classList.contains('fas')){
            toggleStar(e.target);
            e.target.parentNode.style.backgroundColor = "#F2F2F2";
        }
    }
  })

  function resetPanel(){
    //date
    var d = new Date()
    var month = '' + (d.getMonth() + 1)
    var day = '' + d.getDate()
    var year = d.getFullYear()
  
    if(document.querySelector('#date').value.trim() == ''){
        document.querySelector('#date').value = `${year}/${month}/${day}`
    } 

    if(document.querySelector('#time').value.trim() == ''){
        document.querySelector('#time').value = `23:59`
    } 
    
    //star-mark
    if(document.querySelector('#star').classList.contains('fas')){
        document.querySelector('#star').classList.toggle('far');
        document.querySelector('#star').classList.toggle('fas');
    }

    //input
    document.querySelector('#title').value = '';
    document.querySelector('#comment').value = '';

  }


  function createTask (title, date, time, bgColor, star_tag){
    str = 
    `    
    <div style = "background-color: ${bgColor}" class="list_rec">
    <input class="btn_checkbox" type="checkbox"/>
    <div class="card_title">${title}
      <div class="list_details"><i class="far fa-calendar-alt list_details_icon">
          <div class="icon_date">${date.substr(5)}</div></i><i class="far fa-file list_details_icon"></i><i class="far fa-comment-dots list_details_icon"></i></div>
    </div><i class="fas fa-pencil-alt js_list_icon"></i>${star_tag}
    </div>`
    return str;
  }

  function toggleStar(el){
    el.classList.toggle('far');
    el.classList.toggle('fas');
  }

  function list_rollUp(){
    $('.bg_title').siblings().toggleClass('bg_edit')
    $('.bg_content').toggleClass('bg_content_edit')
    $('.section_list').toggleClass('list_rollUp')
    $('.pencil_edit').toggleClass('pen_click')
  }
  
list_rollUp();
resetPanel()    

