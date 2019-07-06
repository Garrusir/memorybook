		$(document).ready(function(){

			// mobile-menu
			window.onresize = function(){
				if(document.body.clientWidth > 480) {
					menu = document.getElementById("menu");
					menu.style.display = 'block';
				menu.style.height = '100%';
				}
			}
			bt = document.getElementById("menu-btn");

		bt.onclick = function(){
				menu = document.getElementById("menu");
				if (menu.style.display != 'block') {
				menu.style.display = 'block';
				menu.style.height = '100%';
				} else {
					menu.style.height = '0';
					menu.style.display = 'none';
				}
			}

			// ajax-search
			$('.search-form form').submit(function(){
				return false;
			})

			$('#send').click(function(){
				var serachText = $('#name').val().replace( /[^a-zа-я0-9]/ig, " ");
				if (serachText == '') {
				
					return false;
			
				}
				$.ajax({
					url: 'search.php',
					type: 'GET',
					data: { 
						words 	: $('#name').val(),
						search 	: true,
						ajax 	: true 
					},
					beforeSend: function(){
						$('.ajax-content').append('<div class="w-square"></div>');
						$('.ajax-content').append('<i class="fa-none fa fa-spinner fa-pulse fa-4x" aria-hidden="true"></i>');
					},
					success: function(data){
						$('.ajax-content > .fa').remove();
						$('.w-square').remove();
						window.history.pushState(null, null, "search.php?words=" + serachText + "&search=true");
						$('.ajax-content').html(data);
					}
				});
			
			});

				// ajax-pagination
				$('body').on('click','.pagin-wrap a', function(event){
   

				// $('.pagin-wrap a').click(function (event){
				
				event.preventDefault();
				console.log("ahref - "  + $(this).attr('href'));
				console.log("location - "  + location.href);
				var link =  $(this).attr('href');
				// console.log(location.pathname.hostname);
				var page =  $(this).attr('data-page');
				$.ajax({
					url: location.href,
					type: 'GET',
					data: { 
						'page'	: page,
						ajax 	: true 
					},
					beforeSend: function(){
						console.log("test1");
						$('.ajax-content').append('<div style="display:none;" class="w-square"></div>');
						$('.w-square').fadeIn(300);
						$('.ajax-content').append('<i class="fa-none fa fa-spinner fa-pulse fa-4x" aria-hidden="true"></i>');
					},
					success: function(data){
						console.log("test2");
						$('.ajax-content > .fa').remove();
						$('.w-square').fadeOut(300);
						$('.w-square').remove();

						window.history.pushState(null, null, link );
						console.log('link - ' + link );
						//location.search = "page=" + page ;
						$('.ajax-content').html(data);
					}
				});
				
				
			
				});

		})
