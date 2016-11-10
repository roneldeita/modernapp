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

    <script type="text/javascript">
        var postcategory    = $('select[name=post-category]');
        var body            = $('textarea[name=body]');
        var postBtn         = $('button[name=post]');
        var form            = $('form[name=create-post]');
        var notif           = $('div.notif-msg');
        //posts
        var panel           = $('div#posts');
        var loadBtn         = $('a[name=load-more-btn]');

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
                    url: "{{ url('/create_post') }}",
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
                    url: "{{ url('/inserted') }}",
                    data:{
                        "_token":"{{ csrf_token() }}",
                        "id":id
                    },
                    dataType:"JSON",
                    success: function(data){

                        var profilePic = $('<img/>',{ class:"img-rounded", style:"width:30px;margin:0 5px 0 0", src:data['user']['profile_picture']});

                        panel.prepend(
                            $('<div></div>', { id:data['id'], class:"panel panel-default"}).append(
                                $('<div></div>', {class:"panel-body"}).append(
                                    $('<div></div>').append(
                                        profilePic,
                                        $('<a></a>',{ text:data['user']['name'], href:"javascript:;" }),
                                        $('<span></span>',{ text:data['created'], style:"font-size:12px", class:"text-muted pull-right"})
                                    ),
                                    $('<div></div>', {text:data['body'], style:"margin-top:10px"})
                                )
                            )
                        );

                    }

                });
            }

            //loading posts
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
                            var profilePic = $('<img/>',{ class:"img-rounded", style:"width:30px;margin:0 5px 0 0", src:value['user']['profile_picture']});

                            if(LastPanelId != value['id']){

                                 panel.append(
                                    $('<div></div>', { id:value['id'], class:"panel panel-default"}).append(
                                        $('<div></div>', {class:"panel-body"}).append(
                                            $('<div></div>').append(
                                                profilePic,
                                                $('<a></a>',{ text:value['user']['name'], href:"javascript:;", style:"font-weight:bold" }),
                                                $('<span></span>',{ text:value['created'], style:"font-size:12px", class:"text-muted pull-right"})
                                            ),
                                            $('<div></div>', {text:value['body'], style:"margin-top:10px"})
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

            function cleanForm(){
                form.trigger('reset');

            }

            function cleanNotif(){
                notif.empty();
            }
            
        });
        
    </script>

@endsection
