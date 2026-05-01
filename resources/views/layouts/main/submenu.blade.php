<div class="mainmenu">
    <div class="nav-cate">
       <ul id="menu2017" class="ul_menu">
         @foreach ($categoryhome as $item)
            @if (count($item->typeCate) > 0 || count($item->tagCate) > 0)
            <li class="dropdown menu-item-count">
               <a class="nd-categories-a" href="{{route('allListProCate',['danhmuc'=>$item->slug])}}" title="{{languageName($item->name)}}">
                  <img width="21" height="21" src="{{$item->avatar}}" alt="{{languageName($item->name)}}" style="margin-right: 10px;">{{languageName($item->name)}} <svg width="10" height="10" class="position-absolute rounded-circle">
                   <use href="#svg-right"></use>
               </svg></a>
               <div class="subcate gd-menu">
                  <div class="nd-cate-list ">
                     @foreach ($item->typeCate as $type)
                     <aside>
                        <a class="nd-categories-main-sub" href="{{route('allListType',['danhmuc'=>$item->slug,'loaidanhmuc'=>$type->slug])}}" title="{{languageName($type->name)}}">{{languageName($type->name)}}</a>
                        @if (count($type->typetwo) > 0)
                        <ul class="submenu__list">
                           @foreach ($type->typetwo as $ttwo)
                           @if ($ttwo->status == 1)
                           <li class="submenu__col">
                              <span class="submenu__item submenu__item--main">
                                  <a class="link" href="{{route('allListTypeTwo',['danhmuc'=>$item->slug,'loaidanhmuc'=>$type->slug,'loai2'=>$ttwo->slug])}}" title="{{languageName($ttwo->name)}}">{{languageName($ttwo->name)}}</a>
                              </span>
                          </li>
                           @endif
                           
                           @endforeach
                       </ul>
                        @endif
                     </aside>
                     @endforeach
                     @foreach ($item->tagCate as $tagcate)
                        @if ($tagcate->status == 1)
                        <aside>
                           <a class="nd-categories-main-sub" href="{{route('allTagCateList',['tagCateSlug'=>$tagcate->slug])}}" title="{{$tagcate->name}}">{{$tagcate->name}}</a>
                           @if (count($tagcate->tags) > 0)
                           <ul class="submenu__list">
                              @foreach ($tagcate->tags as $tag)
                              <li class="submenu__col">
                                 <span class="submenu__item submenu__item--main">
                                     <a class="link" href="{{route('allListTags',['tag'=>$tag->slug])}}" title="{{($tag->name)}}">{{($tag->name)}}</a>
                                 </span>
                             </li>
                              @endforeach
                          </ul>
                           @endif
                        </aside>
                        @endif
                     
                     @endforeach
                  </div>
               </div>
            </li>
            @else 
            <li class="menu-item-count">
               <a class="nd-categories-a" href="{{route('allListProCate',['danhmuc'=>$item->slug])}}" title="{{languageName($item->name)}}"><img style="margin-right: 10px;" width="21" height="21" src="{{$item->avatar}}" alt="{{languageName($item->name)}}"> {{languageName($item->name)}}</a>
            </li>
            @endif
          
          @endforeach
          @if (count($categoryhome) > 7)
          <li id="more" class="moremenu menu-item-count clearfix ">
            <a class="nd-categories-a" href="javascript:;" title="Xem thêm"><img style="margin-right: 10px;" width="21" height="21" src="{{url('frontend/images/plus.png')}}" alt="Xem thêm"> Xem thêm</a>
         </li>
         <li id="myDIV" class="hidemenu menu-item-count clearfix ">
            <a class="nd-categories-a" href="javascript:;" title="Xem thêm"><img style="margin-right: 10px;" width="21" height="21" src="{{url('frontend/images/mine.png')}}" alt="Thu gọn"> Thu gọn</a>
         </li>
          @endif
          
       </ul>
    </div>
 </div>
<script>
   var more = document.getElementsByClassName('.moremenu'),
		less = document.getElementsByClassName('.hidemenu');
	// more.forEach(function(el){
      $(".moremenu").click(function(){
         var ulli = document.querySelectorAll('ul.ul_menu > li');
			ulli.forEach(function(ul) {
            console.log(ul);
				ul.style.display = "block";
			});
			this.style.display = "none";
			document.querySelector('.hidemenu').style.display = "block";
         $( ".moremenu" ).removeClass( "mystyles" );
      });
      $(".hidemenu").click(function(){
         var ulli = document.querySelectorAll('ul.ul_menu > li');
			ulli.forEach(function(ul) {
				ul.style.display = "none";
			});
			this.style.display = "none";
			$( ".moremenu" ).addClass( "mystyles" );
      });
	// });
	// less.forEach(function(el){
		
	// });

</script>