<?php
  session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NextSticker | Go Where You Want</title>
<link type="text/css" rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery-1.8.3.min.js"/></script>
<script type="text/javascript" src="js/script.js"/></script>
<script type="text/javascript">
window.onload=function(){
  waterfallInit({
    parent:'main',
    pin:'box',
    successFn:success,
    num:30,
    requestUrl:'cv.php',
    city:''
    });
  function success(data){
    var obj= $.parseJSON(data);
        $.each(obj,function(key,value){
        var oBox=$('<div>').addClass('box').appendTo($('#main'));
        var oPic=$('<div>').addClass('pic').appendTo($(oBox));//要的就是这个的高
        //loadImg($(value).attr('src'),callBack,$(oPic)[0]);//加载图片获取高度
        $(oPic)[0].style.height = Math.floor(parseInt($(value).attr('H')))+'px';
        //alert(Math.floor(parseInt($(value).attr('H')))+'px');
        var PHO =document.createElement('img');
        $(PHO).attr('src',$(value).attr('src')).attr('id',$(value).attr('src')).appendTo($(oPic));  
        var Picture=document.getElementById($(value).attr('src'));

        Picture.onclick=function(){ 
        var body=document.getElementsByTagName('body');
        $(body).css({'overflow-y':'hidden',});
        var documentH=$(window).height();
        var documentW=$(window).width();
        var oMask = document.createElement("div");
        oMask.id="mask";
        oMask.style.height=documentH+'px';
        oMask.style.width=documentW+'px';
        document.body.appendChild(oMask);
        var Op=document.createElement("div");
        Op.id="picture";
        document.body.appendChild(Op);
        var oImg=$('<img>').attr('src',$(value).attr('src')).appendTo($(Op));
        $('<div>').attr('id','close').appendTo($(Op));
        var W=$(oImg).width();
        Op.style.left=documentW/2-W/2+"px";
        Op.style.top=documentH/2-300+"px";
        var oClose=document.getElementById("close");
        oClose.onclick=function(){
          $(body).css({'overflow-y':'auto',});
          document.body.removeChild(Op);
          document.body.removeChild(oMask);
          };  
          };

        var oST=$('<div>').attr('id',$(value).attr('src')+'ST').addClass('something').addClass('icon-heart2').addClass('icon').addClass('love1').appendTo($(oBox)); 
        if(($(value).attr('LIKE'))!=''){
          var allUsers = $(value).attr('LIKE');
          var strs = [];
          strs=allUsers.split(",");
          for(var i=0;i<strs.length-1;i++){
            $('<div>').appendTo($(oST)).addClass('user1').text(strs[i]).attr('id',strs[i]);
          }
          $(oST).css({'display':'block',});
          
        } 

        var oCon=$('<ul>').attr('id',$(value).attr('src')).addClass('comment').appendTo($(oBox));//评论框
        if(($(value).attr('COMMENT'))!=''){
          var allCons = $(value).attr('COMMENT');
          var allConsA = $(value).attr('COMMENTA');
          var strscon = [];
          var strsconA = [];
          strscon=allCons.split(",");
          strsconA=allConsA.split(",");
          for(var i=0;i<strsconA.length-1;i++){
            var COMBOX = document.createElement('li');
                  COMBOX.className = 'commentbox';
                  COMBOX.innerHTML = '<div class="user1">'+strsconA[i]+'</div>'+
                                       '<div class="comContent">'+strscon[i]+'</div>'
                      $(COMBOX).appendTo($(oCon));
          }
          $(oCon).css({'display':'block',});  
        } 

        var oSpan=$('<span>').appendTo($(oPic));
        var oTale=$('<div>Comment</div>').addClass('tale').addClass('icon-comment-stroke').addClass('icon').addClass('CMT').attr('id',$(value).attr('src')).appendTo($(oSpan));

        $(oTale)[0].onclick=function(){ 
        if(document.getElementById('sessionHere').innerHTML.replace(/\s/g, "")==''){
          window.location.href="index.php";
        } else if ( typeof(combox) == "undefined") {

            //alert(oBox.outerHeight()+);
            var combox = document.createElement("div");
            combox.id = "mask-com";
            $(combox).appendTo($(oBox));
            var textbox = $('<textarea>').addClass('textarea').appendTo($(combox));
            $('<button>Comment</button>').addClass('comBtn').attr('id','MACom').appendTo($(combox));
            $('<button>Quit</button>').addClass('comBtn').attr('id','quit').appendTo($(combox)).css({'float':'right',});
            //waterfall();
          }
        var quitCombox = document.getElementById('quit');
        quitCombox.onclick=function(){
          $(textbox)[0].parentNode.innerHTML = "";
          combox.parentNode.removeChild(combox);
          //waterfall();
        }
        $(textbox)[0].onblur = function () {
                  var me = this;
                  var val = me.value;
                if (val == '') {
                  timer = setTimeout(function () {
                    $(textbox)[0].parentNode.innerHTML = "";
                    combox.parentNode.removeChild(combox);
          waterfall();
                    }, 200);
                          }
                           }
              var makecomment = document.getElementById('MACom');//发表评论
              makecomment.onclick = function(){
                $.ajax({
                  type:'GET',
                  url:'severComment.php',
                  data:'COMMENT='+$(textbox)[0].value+'&COMMENTA='+document.getElementById('sessionHere').innerHTML+'&ID='+$(value).attr('src'),
            datatype:'json',
                })
                var COMBOX = document.createElement('li');
                COMBOX.className = 'commentbox';
                COMBOX.innerHTML = '<div class="user1">'+document.getElementById('sessionHere').innerHTML+'</div>'+
                                       '<div class="comContent">'+$(textbox)[0].value+'</div>'
                    $(COMBOX).appendTo($(oCon));
                    oCon.css({'display':'block',});
                    $(textbox)[0].value = '';
                    $(textbox)[0].parentNode.innerHTML = "";
                    combox.parentNode.removeChild(combox);
                    startNum = indexReturn($(value).attr('src'));
                    waterfall(2);
              }

        };
        
        var oLike=$('<div>Liked</div>').addClass('like').attr('id',$(value).attr('src')).addClass('icon').addClass('love').addClass('icon-heart-stroke').appendTo($(oSpan));
          $(oLike)[0].onclick=function(){
            //alert(document.getElementById('sessionHere').innerHTML.replace(/\s/g, "")=='');//除去空格
              if(document.getElementById('sessionHere').innerHTML.replace(/\s/g, "")==''){
                  window.location.href="index.php";
              }else{  
                oLike.addClass("btn-activated");
                setTimeout(function(){
            oLike.removeClass("btn-activated");
              },500); 
              $.ajax({//AJAX动态更新数据库
              type:'GET',
              url:'severLike.php',
              data:'LIKEHERE='+document.getElementById('sessionHere').innerHTML+'&ID='+$(value).attr('src'),
              datatype:'String',
              beforeSend:function(){
                
                },
              success:function(data){
                //alert(data);
                if(data!=''){
                  var strs = [];
                  strs=data.split(",");
                  $(oST)[0].innerHTML='';
                  for(var i=0;i<strs.length-1;i++){
                    $('<div>').appendTo($(oST)).addClass('user1').text(strs[i]).attr('id',strs[i]);
                   }
                $(oST).css({'display':'block',});   
                } else{
                  $(oST)[0].innerHTML='';
                  $(oST).css({'display':'none',});
                }
                startNum = indexReturn($(value).attr('src'));
                waterfall(2);
              },
            })
            }   
        };
      })
      return true;
    }
$('#search_query').bind('keyup',function(){
    var searchtext = $('#search_query').val();
    var SEARCHTEXT = searchtext.toUpperCase();
    $.ajax({
        type:'GET',
        url:'search.php',
        data:'SEARCHTEXT='+SEARCHTEXT,
        datatype:'json',
        success:function(data){
          if (SEARCHTEXT == '') {
            $('#search-suggest').hide();
            };
          var obj= $.parseJSON(data);
          var html='';
          $.each(obj,function(key,value){
            html+='<li>'+value.CITY+'</li>';
            })
          $('#search-result').html(html);
          $('#search-suggest').show();
          },
        })    
  });
  $(document).bind('click',function(){
    $('#search-suggest').hide();
  });
  var S = document.getElementById('search-result');
  $(S).delegate('li','click',function(){
    var keywords = $(this).text().toUpperCase();
    //alert(keywords);
    $.ajax({
      type:'GET',
      url:'search.php',
      data:'searchCity='+keywords,
      datatype:'String',
    })
    location.href = 'searchCity.php';
  });
  
}
</script>
</head>
<body>
 <div id="sidebar" class="side">
    <div class="search">
      <input type="text" id="search_query" placeholder="Search NextSticker" class="search_key" autocomplete="off" required="required">
      <input type="submit" id="search_query_submit" class="search_query_submit" value="">
    </div>
    <div class="suggest" id="search-suggest">
        <ul id="search-result">
        </ul>
    </div
    <div class="bar">
        <ul class="sidemenu">
        <li id="Discovery" class="item"><a href="javascript:;">Discovery</a></li>
        <li id="Story" class="item"><a href="story.php">Story</a></li>
        <li id="World" class="item"><a href="world.php">World</a></li>
        <li id="Style" class="item"><a href="style.php">Guide</a></li>
        </ul>
    </div>
 </div>
   <div id="top">
            <div class="left">
              <div id="closeBar" class="select_btn"></div>
            </div>
      <a href="#" class="margin_t_5">NextSticker</a>     
      <ul class="topbtn" >
        <li id="sessionHere"><?PHP echo $_SESSION['nickname'] ?></li>
      </ul>   
   </div>
  <div id="outer">
      <div id="main">
        <a href="javascript:;" id="btn" title="回到顶部" ></a>
      </div>
  </div>
  <div>
</div>
</body>
</html>
