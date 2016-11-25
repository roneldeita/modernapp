@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Post Something</div>

                <div class="panel-body">
                    <form name="create-post">
                        {{ csrf_field() }}
                        <div class="form-group">
                           <div class="input-group input-group-sm">
                                <span class="input-group-addon text-primary" id="category">About</span>
                                <select class="form-control" name="postcategory_id" style="width:135px" aria-describeby="category">
                                    <option value="">Select an option</option>
                                    @foreach($postcategories as $id=>$postcategory)
                                        <option value="{{ $id }}">{{ $postcategory }}</option>
                                    @endforeach
                                </select>
                           </div> 
                        </div>
                        <div class="form-group">
                             <textarea class="form-control" rows="4" name="body"></textarea>
                        </div>
                        <div class="form-group">
                             <button class="btn btn-sm btn-primary pull-right" name="post">Post</button>
                        </div>
                        <div class="notif-msg"></div>
                    </form>
                </div>

                <div class="panel-footer">
                    <div id="post-alert-notif"></div>
                </div>
            </div>

            <div id="posts"></div>

            <div class="form-group text-center">
                <a href="javascript:;" class="btn btn-sm btn-primary" name="load-more-btn" data-page="" style="display:none">Show More Posts</a>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    
    <script src="//js.pusher.com/3.0/pusher.min.js"></script>

    <script type="text/javascript">
        var postcategory    = $('select[name=post-category]');
        var body            = $('textarea[name=body]');
        var postBtn         = $('button[name=post]');
        var form            = $('form[name=create-post]');
        var notif           = $('div.notif-msg');
        //posts
        var frmFooter       = $('div#post-alert-notif');
        var panel           = $('div#posts');
        var loadBtn         = $('a[name=load-more-btn]');
        var ctr             = 0;

        form.on('submit', function(e){
            e.preventDefault();
        })

        $(function(){
            //load posts
            loadPosts();

            //create post
            postBtn.on('click', function(){

                $.ajax({
                    type: "POST",
                    url: "{{ url('/createpost') }}",
                    data : form.serializeArray(),
                    dataType: "JSON",
                    beforeSend: function(){

                        cleanNotif();
                        
                    },
                    success: function(data){

                        cleanForm();
                        prependPost(data['id']);

                    },
                    error: function(xhr, status, error){

                        var errors = $.parseJSON(xhr.responseText);

                        notif.append($('<ul></ul>', {name:"error-msgs", class:"text-danger"}));

                        $.each(errors, function(key, value){

                            $('ul[name=error-msgs]').append($('<li></li>',{text: value}));

                        });

                
                    }

                });

            });
            
            //load more post
            loadBtn.on('click', function(){
                var nPage = $(this).attr('data-page');
                loadPosts(nPage);
            });

            //prepend new post
            function prependPost(id){
                $.ajax({
                    type:"GET",
                    url: "{{ url('/insertedpost') }}",
                    data:{
                        "_token":"{{ csrf_token() }}",
                        "id":id
                    },
                    dataType:"JSON",
                    success: function(data){

                        var profilePic = $('<img/>',{ class:"img-rounded", style:"width:30px;height:30px;margin:0 5px 0 0", src:data['user']['profile_picture']});

                        panel.prepend(
                            $('<div></div>', { id:data['id'], class:"panel panel-default post-container"}).append(
                                $('<div></div>', {class:"panel-body"}).append(
                                    $('<div></div>').append(
                                        profilePic,
                                        $('<a></a>',{ text:data['user']['name'], href:"javascript:;" }),
                                        $('<span></span>',{ text:data['created'], style:"font-size:12px", class:"text-muted pull-right"})
                                    ),
                                    $('<div></div>', {text:data['body'], style:"margin-top:10px"})
                                ),
                               $('<div></div>', {class:"panel-footer",style:"padding:2px 5px"}).append(
                                    $('<ul></ul>',{class:"nav nav-pills"}).append(
                                        $('<li></li>').append(
                                            $('<a></a>',{href:"javascript:;", id: data['id'], text:" Comment", name:"comment-lnk", style:"font-size:13px; padding:2px;"}).prepend($('<span></span>', {class:"fa fa-chevron-circle-down text-muted"}))
                                        )
                                    )
                                ),
                                $('<ul></ul>',{class:"list-group"})
                            )
                        );

                    }

                });
            }

            //prepend new posts
            function prependNewPosts(ids){
                $.ajax({
                    type:"GET",
                    url: "{{ url('/loadnewposts') }}",
                    data:{
                        "_token":"{{ csrf_token() }}",
                        "ids":ids
                    },
                    dataType:"JSON",
                    success: function(data){

                        $.each(data, function(key, value){

                            var profilePic = $('<img/>',{ class:"img-rounded", style:"width:30px;height:30px;margin:0 5px 0 0", src:value['user']['profile_picture']});

                            panel.prepend(
                                $('<div></div>', { id:value['id'], class:"panel panel-default post-container"}).append(
                                    $('<div></div>', {class:"panel-body"}).append(
                                        $('<div></div>').append(
                                            profilePic,
                                            $('<a></a>',{ text:value['user']['name'], href:"javascript:;" }),
                                            $('<span></span>',{ text:value['created'], style:"font-size:12px", class:"text-muted pull-right"})
                                        ),
                                        $('<div></div>', {text:value['body'], style:"margin-top:10px"})
                                    ),
                                   $('<div></div>', {class:"panel-footer",style:"padding:2px 5px"}).append(
                                        $('<ul></ul>',{class:"nav nav-pills"}).append(
                                            $('<li></li>').append(
                                                $('<a></a>',{href:"javascript:;", id: value['id'], text:" Comment", name:"comment-lnk", style:"font-size:13px; padding:2px;"}).prepend($('<span></span>', {class:"fa fa-chevron-circle-down text-muted"}))
                                            )
                                        )
                                    ),
                                    $('<ul></ul>',{class:"list-group"})
                                )
                            );

                        });

                    }

                });
            }

            //load posts
            function loadPosts(page){

                $.ajax({
                    type:"GET",
                    url: "{{ url('/posts?page=') }}"+page,
                    data:{
                        "_token":"{{ csrf_token() }}"
                    },
                    dataType:"JSON",
                    success: function(data){
                        
                        $.each(data['data'], function(key, value){

                            var LastPanelId =  panel.find('div.panel:last-child').attr('id');
                            var profilePic = $('<img/>',{ class:"img-rounded", style:"width:30px;height:30px;margin:0 5px 0 0", src:"{{ url('/') }}"+value['user']['profile_picture']});

                            if(LastPanelId != value['id']){

                                panel.append(
                                    $('<div></div>', { id:value['id'], class:"panel panel-default post-container"}).append(
                                        $('<div></div>', {class:"panel-body"}).append(
                                            $('<div></div>').append(
                                                profilePic,
                                                $('<a></a>',{ text:value['user']['name'], href:"javascript:;", style:"font-weight:bold" }),
                                                $('<span></span>',{ text:value['created'], style:"font-size:12px", class:"text-muted pull-right"})
                                            ),
                                            $('<div></div>', {text:value['body'], style:"margin-top:10px; font-size:16px"})
                                        ),
                                        $('<div></div>', {class:"panel-footer",style:"padding:2px 5px"}).append(
                                            $('<ul></ul>',{class:"nav nav-pills"}).append(
                                                $('<li></li>').append(
                                                    $('<a></a>',{href:"javascript:;", id: value['id'], text:" Comment", name:"comment-lnk", style:"font-size:13px; padding:2px;"}).prepend($('<span></span>', {class:"fa fa-chevron-circle-down text-muted"}))
                                                )
                                            ),
                                            $('<ul></ul>',{class:"list-group", style:"margin:1px"})
                                        )
                                        
                                    )
                                );

                            }

                        });

                        var nextPage = data['next_page_url'];
                        var lastPage = data['last_page'];

                        if(nextPage===null){

                            //hide load button
                            loadBtn.hide();

                        }else{

                            //show load button
                            var page = nextPage.split('page=')[1];

                            loadBtn.attr('data-page', page).show();

                        }

                    }

                });

            }

            //toggle comments
            $(document).on('click', 'a[name=comment-lnk]', function(){
                showComments($(this).attr('id'));
            });

            //show comments
            function showComments(postID){
                $.ajax({
                    type:"GET",
                    url:"{{ url('/showcomments') }}",
                    data:{
                        "_token":"{{ csrf_token() }}",
                        "id":postID
                    },
                    dataType:"JSON",
                    success:function(data){

                        var comment_list = $('div#'+postID).find('ul.list-group');

                        if(comment_list.children().length == 0){

                            $('div#'+postID).find('a[name=comment-lnk]').find('span').removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');

                            $.each(data, function(key, value){

                                comment_list.append(
                                    $('<li></li>',{style:"border:0px; font-size:14px; line-height:6px",class:"list-group-item"}).append(
                                        $('<p></p>', {text:" "+value['body']}).prepend($('<a></a>',{href:"javascript:;", text:value['owner']})),
                                        $('<p></p>', {text:value['created'], style:"font-size:11px;"})
                                    )
                                );
                                
                            });
 
                            comment_list.append(
                                    $('<li></li>', { style:" border:0px; margin:0; padding:4px 2px;", class:"list-group-item" }).append(
                                        $('<textarea></textarea>',{name:"comment-box",class:"form-control", rows:1, style:" height:32px; line-height:18px", placeholder:"Write a comment..."})
                                    )
                                );

                        }else{

                            $('div#'+postID).find('a[name=comment-lnk]').find('span').removeClass('fa-chevron-circle-up').addClass('fa-chevron-circle-down');

                            comment_list.empty();

                        }

                    }
                });
            }

            //append a comment
            function appendComment(commentID, postID){

                $.ajax({
                    type:"GET",
                    url:"{{ url('/insertedcomment') }}",
                    data:{
                        "_token":"{{ csrf_token() }}",
                        comment_id:commentID
                    },
                    dataType:"JSON",
                    success:function(data){

                        var comment_list = $('div#'+postID).find('ul.list-group').find('li:last').prev();

                        comment_list.after(
                            $('<li></li>',{style:"border:0px; font-size:14px; line-height:6px",class:"list-group-item"}).append(
                                $('<p></p>', {text:" "+data['body']}).prepend($('<a></a>',{href:"javascript:;", text:data['owner']})),
                                $('<p></p>', {text:data['created'], style:"font-size:11px;"})
                            )
                        );

                    }
                });

            }


            //create a comment
            $(document).on('keypress', 'textarea[name=comment-box]', function(e){

                //get the comment
                var comment = $(this).val();
                //get the user_id
                var userID = {{ Auth::user()->id }};
                //get the post_id
                var postID = $(this).parents('.post-container').attr('id');


                if(e.which === 13){
                    
                    $.ajax({
                        type:"POST",
                        url:"{{ url('/createcomment') }}",
                        data:{
                            "_token":"{{ csrf_token() }}",
                            "body":comment,
                            "user_id":userID,
                            "post_id":postID
                        },
                        dataType:"JSON",
                        success:function(data){

                            appendComment(data['id'], postID);
                            
                        }
                    });

                    //clear the comment box
                    $(this).val('').focus();
                    return false;

                }

            });

            function cleanForm(){
                form.trigger('reset');

            }

            function cleanNotif(){
                notif.empty();
            }

            function newPost(count, post_id){

                var sum = Number($('span#post-ctr').text()) + Number(count);
                var input = $('input#post-ids').val();

                if(input != undefined){
                    post_id = input + ',' + post_id;
                }

                //naming convention
                var post = "new post ";

                if( sum > 1 ){

                    post = "new posts ";
                }

                frmFooter.empty().append(
                    $('<div></div>', { class:"alert alert-success text-center", style:"margin:0px"}).append(

                        $('<span></span>', {id:"post-ctr", text: sum}),
                        $('<span></span>', {text:" "+post}),
                        $('<a></a>', {href:"javascript:;", class:"text-success load-nw-posts"}).append(
                            $('<span></span>', {class:"fa fa-refresh"})),
                            $('<input></input>',{ type:"hidden", id:"post-ids", value:post_id})
                    )
                );

            }

            $(document).on('click', 'a.load-nw-posts', function(){

                var postIds = $('input#post-ids').val();
                var toArrPostIds = new Array();

                toArrPostIds = postIds.split(",");

                //call ajax to load new posts
                prependNewPosts(toArrPostIds);

                //clear the alert
                frmFooter.empty();

            });

            var pusher = new Pusher("{{env("PUSHER_KEY")}}")
            var channel = pusher.subscribe('post');
            var channel = pusher.subscribe('comment');

            channel.bind('App\\Events\\PostEvent', function(data) {
            
                var sender = {{ Auth::user()->id }}; 

                if(data.post.user_id != sender ){

                    newPost(1, data.post.id);

                }

            });

            channel.bind('App\\Events\\CommentEvent', function(data) {
            
                var sender = {{ Auth::user()->id }}; 

                if(data.comment.user_id != sender ){

                    appendComment(data.comment.id, data.comment.commentable_id);

                }

            });
            
        });
        
    </script>

@endsection
